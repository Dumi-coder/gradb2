<?php


class DiscussionForum extends Controller
{
    // This runs when user visits the page
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Security: Make sure user is logged in (FIXED - matches other controllers)
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Prevent caching
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

               // Create model instance
               $forumPost = new ForumPost();
        
               // Get data from database
               $all_posts = $forumPost->getAllPosts();
               $my_posts = $forumPost->getPostsByUser($_SESSION['user_id']);
               
               // Convert false to empty array (when no results)
               $data['all_posts'] = is_array($all_posts) ? $all_posts : [];
               $data['my_posts'] = is_array($my_posts) ? $my_posts : [];
        
        // Send data to view
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

        // Security check (FIXED)
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        // Collect form data (FIXED - use user_id)
        $data = [
            'user_id' => $_SESSION['user_id'],
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'category' => $_POST['category'] ?? 'General',
            'priority' => $_POST['priority'] ?? 'normal',
            'tags' => $_POST['tags'] ?? ''
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

        // Save to database
        $forumPost = new ForumPost();
        $result = $forumPost->insert($data);
        
        // Send response back to JavaScript
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post created successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create post']);
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

        // Security check (FIXED)
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_POST['post_id'] ?? 0;
        
        // Security: Make sure user owns this post (FIXED)
        $existing = $forumPost->getPost($post_id);
        if (!$existing || $existing->user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot edit this post']);
            exit;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'category' => $_POST['category'] ?? 'General',
            'priority' => $_POST['priority'] ?? 'normal',
            'tags' => $_POST['tags'] ?? ''
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

        // Security check (FIXED)
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_POST['post_id'] ?? 0;
        
        // Security check (FIXED)
        $existing = $forumPost->getPost($post_id);
        if (!$existing || $existing->user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete this post']);
            exit;
        }

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
        
        // Security check (FIXED)
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
}