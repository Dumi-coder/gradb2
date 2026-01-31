<?php
class DiscussionForum extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and is a student
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Get student's faculty_id from database
        $student = new Student();
        $studentData = $student->first(['user_id' => $_SESSION['user_id']]);
        $studentFacultyId = $studentData->faculty_id ?? null;

        // Get faculties for dropdown
        $faculty = new Faculty();
        $faculties = $faculty->findAll();

        // Create model instance
        $forumPost = new ForumPost();
        $forumReply = new ForumReply();
        
        // Get posts visible to student's faculty (includes posts with visiblefaculties = 999 or student's faculty_id)
        $faculty_posts = [];
        if ($studentFacultyId) {
            $faculty_posts = $forumPost->getPostsByFaculty($studentFacultyId);
        }
        
        // Get user's own posts
        $my_posts = $forumPost->getPostsByUser($_SESSION['user_id']);
        
        // Get user's replies
        $my_replies = $forumReply->getRepliesByUser($_SESSION['user_id']);
        
        // Convert false to empty array (when no results)
        $data = [
            'title' => 'Discussion Forum - GradBridge',
            'user' => $_SESSION,
            'faculty_posts' => is_array($faculty_posts) ? $faculty_posts : [],
            'my_posts' => is_array($my_posts) ? $my_posts : [],
            'my_replies' => is_array($my_replies) ? $my_replies : [],
            'faculties' => is_array($faculties) ? $faculties : []
        ];

        $this->view('student/discussion-forum', $data);
    }

    // AJAX endpoint: Create new post
    public function create()
    {
        // Start session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Tell browser we're sending JSON
        header('Content-Type: application/json');
        
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        // Security check
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Collect form data
        $data = [
            'user_id' => $_SESSION['user_id'],
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'visiblefaculties' => $_POST['faculty_id'] ?? '',
            'views' => 0,
            'replies' => 0
        ];

        // Validation
        if (empty($data['title']) || strlen($data['title']) < 10) {
            echo json_encode(['success' => false, 'message' => 'Title must be at least 10 characters']);
            exit;
        }

        if (empty($data['content']) || strlen($data['content']) < 50) {
            echo json_encode(['success' => false, 'message' => 'Content must be at least 50 characters']);
            exit;
        }

        // Add validation for faculty_id
        if (empty($data['visiblefaculties'])) {
            echo json_encode(['success' => false, 'message' => 'Please select a faculty']);
            exit;
        }

        // Save to database
        $forumPost = new ForumPost();
        
        try {
            $result = $forumPost->insert($data);
            
            // Send response back to JavaScript
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Post created successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create post. Please try again.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    // AJAX endpoint: Update post
    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        // Security check
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_POST['post_id'] ?? 0;
        
        // Security: Make sure user owns this post
        $existing = $forumPost->getPostForOwnership($post_id);
        if (!$existing || $existing->user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot edit this post']);
            exit;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'visiblefaculties' => $_POST['faculty_id'] ?? ''
        ];

        $result = $forumPost->update($post_id, $data, 'post_id');
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post updated!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
        exit;
    }

    // AJAX endpoint: Delete post
    public function delete()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        // Security check
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $forumReply = new ForumReply();
        $post_id = $_POST['post_id'] ?? 0;
        
        // Security check
        $existing = $forumPost->getPostForOwnership($post_id);
        
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
            exit;
        }
        
        // Check if user owns this post
        if ($existing->user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete this post']);
            exit;
        }

        // CASCADE DELETE: First delete all likes for replies of this post
        try {
            $db = new Database();
            // Delete all likes for replies of this post in one query
            $query = "DELETE FROM form_reply_likes 
                      WHERE replyid IN (SELECT replyid FROM form_replies WHERE postid = :post_id)";
            $db->query($query, ['post_id' => $post_id]);
        } catch (Throwable $e) {
            // Continue even if likes deletion fails (table might not exist or be empty)
        }
        
        // Delete all replies for this post
        $forumReply->deleteRepliesByPostId($post_id);
        
        // Finally, delete the post itself
        $result = $forumPost->delete($post_id, 'post_id');
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post deleted!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
        exit;
    }

    // AJAX endpoint: Get single post for editing
    public function get()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        // Security check
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_GET['post_id'] ?? 0;
        
        $post = $forumPost->getPost($post_id);
        
        if ($post) {
            echo json_encode(['success' => true, 'post' => $post]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
        }
        exit;
    }

    // AJAX endpoint: Increment view count
    public function incrementview()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        // Security check
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_POST['post_id'] ?? 0;
        
        $result = $forumPost->incrementViews($post_id);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to increment view']);
        }
        exit;
    }

    // AJAX endpoint: Get post with replies
    public function getpostwithreplies()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in as student']);
            exit;
        }

        $post_id = $_GET['post_id'] ?? 0;
        
        if (empty($post_id)) {
            echo json_encode(['success' => false, 'message' => 'Post ID is required']);
            exit;
        }
        
        // Get post details
        $forumPost = new ForumPost();
        $post = $forumPost->getPost($post_id);
        
        if (!$post) {
            echo json_encode(['success' => false, 'message' => 'Post not found with ID: ' . $post_id]);
            exit;
        }

        // Get replies
        $forumReply = new ForumReply();
        $replies = $forumReply->getRepliesByPost($post_id);
        
        // Get liked reply IDs for current user
        $likedReplyIds = [];
        try {
            $forumReplyLike = new ForumReplyLike();
            $result = $forumReplyLike->getLikedRepliesByUser($post_id, $_SESSION['user_id']);
            $likedReplyIds = $result ? $result : [];
        } catch (Throwable $e) {
            // Continue without liked data if there's any error
            $likedReplyIds = [];
        }
        
        echo json_encode([
            'success' => true, 
            'post' => $post,
            'replies' => $replies ? $replies : [],
            'likedReplyIds' => $likedReplyIds
        ]);
        exit;
    }

    // AJAX endpoint: Add reply
    public function addreply()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        $data = [
            'postid' => $input['post_id'] ?? 0,
            'userid' => $_SESSION['user_id'],
            'reply' => $input['reply'] ?? '',
            'likes' => 0
        ];

        // Validation
        if (empty($data['postid'])) {
            echo json_encode(['success' => false, 'message' => 'Post ID is required']);
            exit;
        }

        if (empty($data['reply'])) {
            echo json_encode(['success' => false, 'message' => 'Reply cannot be empty']);
            exit;
        }

        $forumReply = new ForumReply();
        $result = $forumReply->insert($data);
        
        if ($result) {
            // Update reply count
            $forumPost = new ForumPost();
            $forumPost->query("UPDATE forum_posts SET replies = replies + 1, updated_at = NOW() WHERE post_id = :post_id", ['post_id' => $data['postid']]);
            
            echo json_encode(['success' => true, 'message' => 'Reply added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add reply']);
        }
        exit;
    }

    // AJAX endpoint: Update reply
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
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

    // AJAX endpoint: Delete reply
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        $reply_id = $input['reply_id'] ?? 0;

        // Check ownership
        $forumReply = new ForumReply();
        $existing = $forumReply->getReplyForOwnership($reply_id);
        
        if (!$existing || $existing->userid != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete this reply']);
            exit;
        }

        $post_id = $existing->postid;
        $result = $forumReply->delete($reply_id, 'replyid');
        
        if ($result) {
            // Update reply count
            $forumPost = new ForumPost();
            $forumPost->query("UPDATE forum_posts SET replies = replies - 1, updated_at = NOW() WHERE post_id = :post_id", ['post_id' => $post_id]);
            
            echo json_encode(['success' => true, 'message' => 'Reply deleted!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
        exit;
    }

    // AJAX endpoint: Toggle like on reply
    public function likereply()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $reply_id = $input['reply_id'] ?? 0;
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
        exit;
    }
}
