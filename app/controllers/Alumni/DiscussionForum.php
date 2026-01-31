<?php
class DiscussionForum extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and is an alumni
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Get alumni's faculty_id from database
        $alumni = new Alumni();
        $alumniData = $alumni->first(['user_id' => $_SESSION['user_id']]);
        $alumniFacultyId = $alumniData->faculty_id ?? null;

        // Get faculties for dropdown
        $faculty = new Faculty();
        $faculties = $faculty->findAll();

        // Create model instance
        $forumPost = new ForumPost();
        $forumReply = new ForumReply();
        
        // Get posts visible to alumni's faculty (includes posts with visiblefaculties = 999 or alumni's faculty_id)
        $faculty_posts = [];
        if ($alumniFacultyId) {
            $faculty_posts = $forumPost->getPostsByFaculty($alumniFacultyId);
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

        $this->view('alumni/discussion-forum', $data);
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
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
        
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Not logged in as alumni']);
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
    
    public function viewall()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and is an alumni
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }
        
        // Prevent caching
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Get all forum data (same as index but will show all topics)
        $forumData = [
            'topics' => [
                [
                    'id' => 1,
                    'title' => 'Mentoring New Graduates: Effective Strategies',
                    'creator' => 'Nimal Perera',
                    'status' => 'active',
                    'description' => 'Share your experiences and tips for mentoring recent graduates in your field. What approaches have worked best for you in guiding new professionals?',
                    'category' => 'Mentorship',
                    'tags' => ['Mentorship', 'Career', 'Experience'],
                    'views' => 134,
                    'replies' => 23,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 2,
                    'title' => 'Industry Trends: AI and Machine Learning Impact',
                    'creator' => 'Sanduni Jayawardena',
                    'status' => 'trending',
                    'description' => 'Discussion about how AI/ML is reshaping different industries and career paths. How are you adapting to these technological changes in your profession?',
                    'category' => 'General',
                    'tags' => ['General', 'Trending', 'AI', 'Tech'],
                    'views' => 198,
                    'replies' => 45,
                    'last_activity' => '2 days ago'
                ],
                [
                    'id' => 3,
                    'title' => 'Work-Life Balance in Tech: Your Strategies',
                    'creator' => 'Chaminda Silva',
                    'status' => 'active',
                    'description' => 'How do you maintain a healthy work-life balance in demanding tech roles? Share your strategies and tips for managing stress and personal time.',
                    'category' => 'Career',
                    'tags' => ['Career', 'Tech', 'Experience'],
                    'views' => 92,
                    'replies' => 18,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 4,
                    'title' => 'Remote Work Best Practices',
                    'creator' => 'Dilani Fernando',
                    'status' => 'active',
                    'description' => 'What are your best practices for remote work productivity? Looking for tips on home office setup, communication tools, and maintaining team collaboration.',
                    'category' => 'Career',
                    'tags' => ['Career', 'General', 'Networking'],
                    'views' => 67,
                    'replies' => 12,
                    'last_activity' => '4 days ago'
                ],
                [
                    'id' => 5,
                    'title' => 'Building a Strong Professional Network',
                    'creator' => 'Kasun Rajapaksha',
                    'status' => 'active',
                    'description' => 'How do you expand your professional network effectively? Share your networking strategies, tips for LinkedIn, and experiences from industry events.',
                    'category' => 'Networking',
                    'tags' => ['Networking', 'Career', 'Experience'],
                    'views' => 156,
                    'replies' => 31,
                    'last_activity' => '5 days ago'
                ],
                [
                    'id' => 6,
                    'title' => 'Transitioning to Leadership Roles',
                    'creator' => 'Tharindi Wickramasinghe',
                    'status' => 'active',
                    'description' => 'Advice and experiences for alumni transitioning from technical roles to leadership and management positions. What challenges did you face and how did you overcome them?',
                    'category' => 'Leadership',
                    'tags' => ['Leadership', 'Career', 'Mentorship'],
                    'views' => 89,
                    'replies' => 20,
                    'last_activity' => '1 week ago'
                ],
                [
                    'id' => 7,
                    'title' => 'Startup Funding: From Idea to Investment',
                    'creator' => 'Rukmal Wijemanne',
                    'status' => 'active',
                    'description' => 'Discussing strategies for securing startup funding, pitching to investors, and navigating the venture capital landscape. Share your fundraising stories and lessons learned.',
                    'category' => 'Entrepreneurship',
                    'tags' => ['Startup', 'Entrepreneurship', 'Networking'],
                    'views' => 142,
                    'replies' => 28,
                    'last_activity' => '2 weeks ago'
                ],
                [
                    'id' => 8,
                    'title' => 'Tech Certifications Worth Pursuing in 2026',
                    'creator' => 'Amila Jayasinghe',
                    'status' => 'trending',
                    'description' => 'Which tech certifications are most valuable in today\'s market? AWS, Azure, GCP, or specialized certifications? Share your experiences and recommendations.',
                    'category' => 'Career',
                    'tags' => ['Tech', 'Career', 'Skills', 'Learning'],
                    'views' => 215,
                    'replies' => 42,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 9,
                    'title' => 'Mental Health in High-Pressure Careers',
                    'creator' => 'Shalini Perera',
                    'status' => 'active',
                    'description' => 'Let\'s discuss mental health awareness and strategies for managing stress in demanding career paths. How do you maintain your well-being while pursuing professional goals?',
                    'category' => 'General',
                    'tags' => ['Experience', 'General', 'Advice'],
                    'views' => 178,
                    'replies' => 35,
                    'last_activity' => '4 days ago'
                ],
                [
                    'id' => 10,
                    'title' => 'International Career Opportunities: Tips for Relocation',
                    'creator' => 'Dinesh Fernando',
                    'status' => 'active',
                    'description' => 'Exploring international job opportunities? Share your experiences with visa processes, cultural adaptation, and building a career abroad.',
                    'category' => 'Career',
                    'tags' => ['Career', 'Networking', 'Experience'],
                    'views' => 134,
                    'replies' => 25,
                    'last_activity' => '5 days ago'
                ],
                [
                    'id' => 11,
                    'title' => 'Data Science and Analytics Career Paths',
                    'creator' => 'Hasini Wickramaratne',
                    'status' => 'active',
                    'description' => 'Discussion about breaking into data science, essential skills, tools, and career progression. From data analyst to data scientist - what\'s your journey been like?',
                    'category' => 'Tech',
                    'tags' => ['Tech', 'AI', 'Career', 'Skills'],
                    'views' => 198,
                    'replies' => 37,
                    'last_activity' => '6 days ago'
                ],
                [
                    'id' => 12,
                    'title' => 'Effective Communication Skills for Leaders',
                    'creator' => 'Mahesh Silva',
                    'status' => 'active',
                    'description' => 'What communication strategies have helped you become a better leader? Share tips on public speaking, team communication, and stakeholder management.',
                    'category' => 'Leadership',
                    'tags' => ['Leadership', 'Mentorship', 'Experience'],
                    'views' => 112,
                    'replies' => 22,
                    'last_activity' => '1 week ago'
                ]
            ],
            'statistics' => [
                'active_discussions' => 47,
                'total_posts' => 324
            ]
        ];

        $data = [
            'title' => 'All Forums - GradBridge',
            'user' => $_SESSION,
            'forumData' => $forumData
        ];

        $this->view('alumni/all-forums', $data);
    }
}
