<?php

class ForumModeration extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Load models
        $forumModel = new ForumPost();
        $replyModel = new ForumReply();
        $facultyAdminModel = new FacultyAdmin();
        $faculty = new Faculty();
        
        $user_id = $_SESSION['user_id'];
        
        // Get faculty admin's faculty_id
        $facultyAdmin = $facultyAdminModel->first(['user_id' => $user_id]);
        $faculty_id = $facultyAdmin->faculty_id ?? null;
        
        // Get all faculties for dropdown
        $faculties = $faculty->findAll();
        
        // Get my published posts from database
        $my_posts = $forumModel->getPostsByUser($user_id);
        
        // Get faculty posts (posts visible to this faculty)
        if ($faculty_id) {
            $faculty_posts = $forumModel->getPostsByFaculty($faculty_id);
        } else {
            $faculty_posts = [];
        }
        
        // Get my replies from database
        $my_replies = $replyModel->getRepliesByUser($user_id);
        
        // Get reported posts - skip for now (needs DB adjustment)
        $reported_posts = [];

        $data = [
            'title' => 'Forum Moderation - GradBridge',
            'page_title' => 'Forum Moderation',
            'page_subtitle' => 'Moderate forum posts and manage community discussions.',
            'user' => $_SESSION,
            'my_posts' => $my_posts,
            'faculty_posts' => $faculty_posts,
            'my_replies' => $my_replies,
            'reported_posts' => $reported_posts,
            'faculties' => is_array($faculties) ? $faculties : []
        ];

        $this->view('admin/forum-moderation', $data);
    }



    public function createPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $forumModel = new ForumPost();
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'visiblefaculties' => $_POST['visiblefaculties'] ?? '999', // 999 = all faculties
        ];

        // Validate required fields
        if (empty($data['title']) || empty($data['content'])) {
            echo json_encode(['success' => false, 'message' => 'Title and content are required']);
            return;
        }

        $result = $forumModel->insert($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create post']);
        }
    }

    public function getPost($post_id)
    {
        $forumModel = new ForumPost();
        $post = $forumModel->getPost($post_id);
        
        if ($post) {
            echo json_encode(['success' => true, 'post' => $post]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
        }
    }

    public function updatePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $forumModel = new ForumPost();
        $post_id = $_POST['post_id'] ?? null;
        
        if (!$post_id) {
            echo json_encode(['success' => false, 'message' => 'Post ID is required']);
            return;
        }

        // Check ownership
        $existingPost = $forumModel->getPostForOwnership($post_id);
        if (!$existingPost || $existingPost->user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'visiblefaculties' => $_POST['faculty_id'] ?? '999',
        ];

        // Validate required fields
        if (empty($data['title']) || empty($data['content'])) {
            echo json_encode(['success' => false, 'message' => 'Title and content are required']);
            return;
        }

        $result = $forumModel->update($post_id, $data, 'post_id');
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update post']);
        }
    }

    public function deletePost($post_id)
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $forumModel = new ForumPost();
        $replyModel = new ForumReply();
        
        // Check ownership (admin can delete any post in their faculty)
        $existingPost = $forumModel->getPostForOwnership($post_id);
        if (!$existingPost) {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
            return;
        }

        // Delete all replies first
        $replyModel->deleteRepliesByPostId($post_id);
        
        // Delete the post
        $result = $forumModel->delete($post_id, 'post_id');
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete post']);
        }
    }

    public function incrementView($post_id)
    {
        $forumModel = new ForumPost();
        $result = $forumModel->incrementViews($post_id);
        echo json_encode(['success' => true]);
    }

    public function getPostDetail($post_id)
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $forumModel = new ForumPost();
        $replyModel = new ForumReply();
        
        $post = $forumModel->getPost($post_id);
        
        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
            return;
        }
        
        $replies = $replyModel->getRepliesByPost($post_id);
        
        // Get liked reply IDs for current user
        $likedReplyIds = [];
        if (!empty($_SESSION['user_id'])) {
            try {
                $forumReplyLike = new ForumReplyLike();
                $result = $forumReplyLike->getLikedRepliesByUser($post_id, $_SESSION['user_id']);
                $likedReplyIds = $result ? $result : [];
            } catch (Throwable $e) {
                // Continue without liked data if there's any error
                $likedReplyIds = [];
            }
        }
        
        echo json_encode([
            'success' => true, 
            'post' => $post,
            'replies' => $replies ?: [],
            'likedReplyIds' => $likedReplyIds
        ]);
    }

    public function addReply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $replyModel = new ForumReply();
        
        $data = [
            'postid' => $_POST['post_id'] ?? null,
            'userid' => $_SESSION['user_id'],
            'reply' => $_POST['reply'] ?? '',
        ];

        // Validate required fields
        if (empty($data['postid']) || empty($data['reply'])) {
            echo json_encode(['success' => false, 'message' => 'Post ID and reply content are required']);
            return;
        }

        $result = $replyModel->insert($data);
        
        if ($result) {
            // Update reply count in forum_posts table
            $forumModel = new ForumPost();
            $forumModel->query("UPDATE forum_posts SET replies = replies + 1 WHERE post_id = :post_id", ['post_id' => $data['postid']]);
            
            echo json_encode(['success' => true, 'message' => 'Reply posted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to post reply']);
        }
    }

    public function updatereply()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        $reply_id = $input['reply_id'] ?? 0;
        $reply_text = $input['reply'] ?? '';

        // Check ownership
        $forumReply = new ForumReply();
        $existing = $forumReply->getReplyForOwnership($reply_id);
        
        if (!$existing || $existing->userid != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot edit this reply']);
            exit;
        }

        if (empty($reply_text)) {
            echo json_encode(['success' => false, 'message' => 'Reply cannot be empty']);
            exit;
        }

        $result = $forumReply->update($reply_id, ['reply' => $reply_text], 'replyid');
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Reply updated!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
        exit;
    }

    public function deletereply()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        $reply_id = $input['reply_id'] ?? 0;
        
        // Validation
        if (empty($reply_id)) {
            echo json_encode(['success' => false, 'message' => 'Reply ID is required']);
            exit;
        }

        // Check ownership
        $forumReply = new ForumReply();
        $existing = $forumReply->getReplyForOwnership($reply_id);
        
        if (!$existing || $existing->userid != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete this reply']);
            exit;
        }

        $post_id = $existing->postid;
        
        // Delete likes for this reply first - MUST happen before deleting the reply
        try {
            $forumPost = new ForumPost();
            $deleteLikesQuery = "DELETE FROM form_reply_likes WHERE replyid = :reply_id";
            $forumPost->query($deleteLikesQuery, ['reply_id' => $reply_id]);
        } catch (Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to delete reply likes: ' . $e->getMessage()]);
            exit;
        }
        
        // Now delete the reply itself
        $result = $forumReply->delete($reply_id, 'replyid');
        
        if ($result) {
            // Update reply count
            $forumPost = new ForumPost();
            $forumPost->query("UPDATE forum_posts SET replies = GREATEST(replies - 1, 0) WHERE post_id = :post_id", ['post_id' => $post_id]);
            
            echo json_encode(['success' => true, 'message' => 'Reply deleted!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
        exit;
    }

    public function likeReply($reply_id)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            echo json_encode(['success' => false, 'message' => 'Not authorized']);
            exit;
        }

        $user_id = $_SESSION['user_id'];
        
        if (empty($reply_id)) {
            echo json_encode(['success' => false, 'message' => 'Reply ID is required']);
            exit;
        }
        
        $forumReply = new ForumReply();
        $forumReplyLike = new ForumReplyLike();
        
        // Check if user has already liked this reply
        $existingLike = $forumReplyLike->hasUserLiked($reply_id, $user_id);
        
        if ($existingLike) {
            // Unlike: Remove like record and decrement count
            $removeLike = $forumReplyLike->removeLike($reply_id, $user_id);
            $decrementResult = $forumReply->decrementLikes($reply_id);
            
            if ($removeLike && $decrementResult) {
                $reply = $forumReply->getReplyForOwnership($reply_id);
                echo json_encode([
                    'success' => true, 
                    'liked' => false,
                    'likes' => $reply->likes ?? 0
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to unlike reply']);
            }
        } else {
            // Like: Add like record and increment count
            $addLike = $forumReplyLike->addLike($reply_id, $user_id);
            $incrementResult = $forumReply->incrementLikes($reply_id);
            
            if ($addLike && $incrementResult) {
                $reply = $forumReply->getReplyForOwnership($reply_id);
                echo json_encode([
                    'success' => true, 
                    'liked' => true,
                    'likes' => $reply->likes ?? 0
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to like reply']);
            }
        }
    }

    // Moderation actions
    public function approvePost($post_id)
    {
        // TODO: Implement approve post logic
        echo json_encode(['success' => true, 'message' => 'Post approved']);
    }

    public function hidePost($post_id)
    {
        // TODO: Implement hide post logic
        echo json_encode(['success' => true, 'message' => 'Post hidden successfully']);
    }

    public function warnUser($user_id)
    {
        // TODO: Implement warn user logic
        echo json_encode(['success' => true, 'message' => 'Warning sent to user']);
    }

    public function viewall()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Load models
        $forumModel = new ForumPost();
        $facultyAdminModel = new FacultyAdmin();
        $faculty = new Faculty();
        
        $user_id = $_SESSION['user_id'];
        
        // Get faculty admin's faculty_id
        $facultyAdmin = $facultyAdminModel->first(['user_id' => $user_id]);
        $faculty_id = $facultyAdmin->faculty_id ?? null;
        
        // Get all faculties for dropdown
        $faculties = $faculty->findAll();
        
        // Get all posts visible to this faculty (no limit)
        if ($faculty_id) {
            $all_posts = $forumModel->getPostsByFaculty($faculty_id);
        } else {
            $all_posts = [];
        }

        $data = [
            'title' => 'All Forum Topics - GradBridge',
            'page_title' => 'All Forum Topics',
            'page_subtitle' => 'Browse all forum discussions for your faculty.',
            'user' => $_SESSION,
            'all_posts' => $all_posts,
            'faculties' => is_array($faculties) ? $faculties : []
        ];

        $this->view('admin/forum-viewall', $data);
    }






}
