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

        // Get faculties for dropdown
        $faculty = new Faculty();
        $faculties = $faculty->findAll();

        // Sample discussion forum data (for display)
        $forumData = [
            'topics' => [
                [
                    'id' => 1,
                    'title' => 'Tips for mastering Data Structures?',
                    'creator' => 'Alex Johnson',
                    'status' => 'active',
                    'description' => 'I struggle to understand linked lists and trees. Any strategies or resources you recommend for visualizing these concepts better?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Help', 'DataStructures'],
                    'views' => 89,
                    'replies' => 15,
                    'last_activity' => '2 hours ago'
                ],
                [
                    'id' => 2,
                    'title' => 'Study Plan for Final Exams',
                    'creator' => 'Priya Patel',
                    'status' => 'trending',
                    'description' => 'Here\'s a comprehensive study schedule that helped me last semester. Includes time management tips and resource recommendations!',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Finals', 'Planning'],
                    'views' => 156,
                    'replies' => 28,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 3,
                    'title' => 'Anyone up for a Study Group?',
                    'creator' => 'Sarah Williams',
                    'status' => 'active',
                    'description' => 'Looking to form a study group for Database Systems course. Planning to meet twice a week. DM if interested!',
                    'category' => 'General',
                    'tags' => ['StudyGroup', 'Collaboration', 'General'],
                    'views' => 67,
                    'replies' => 12,
                    'last_activity' => '3 hours ago'
                ],
                [
                    'id' => 4,
                    'title' => 'Best Resources for Algorithm Practice',
                    'creator' => 'Michael Chen',
                    'status' => 'active',
                    'description' => 'Share your favorite websites, books, or YouTube channels for practicing algorithms and problem-solving. What has worked best for you?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Algorithms', 'Resources'],
                    'views' => 134,
                    'replies' => 22,
                    'last_activity' => '5 hours ago'
                ],
                [
                    'id' => 5,
                    'title' => 'How to Stay Motivated During Semester',
                    'creator' => 'Emma Rodriguez',
                    'status' => 'active',
                    'description' => 'Sometimes I lose motivation midway through the semester. What are your tips for staying focused and maintaining good study habits?',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Motivation', 'General'],
                    'views' => 98,
                    'replies' => 18,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 6,
                    'title' => 'Python vs Java: Which to Learn First?',
                    'creator' => 'David Kim',
                    'status' => 'active',
                    'description' => 'I\'m debating which language to focus on learning first. Both seem important but I want to choose the right starting point. Thoughts?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Python', 'Java'],
                    'views' => 112,
                    'replies' => 25,
                    'last_activity' => '2 days ago'
                ],
                [
                    'id' => 7,
                    'title' => 'Effective Note-Taking Methods',
                    'creator' => 'Lisa Anderson',
                    'status' => 'active',
                    'description' => 'What note-taking systems work best for technical courses? Cornell method, mind maps, or something else?',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Notes', 'Learning'],
                    'views' => 145,
                    'replies' => 20,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 8,
                    'title' => 'Project Ideas for Portfolio',
                    'creator' => 'James Wilson',
                    'status' => 'trending',
                    'description' => 'Looking for project ideas to add to my portfolio. What projects have impressed potential employers or helped you land internships?',
                    'category' => 'General',
                    'tags' => ['Projects', 'Portfolio', 'Career'],
                    'views' => 198,
                    'replies' => 31,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 9,
                    'title' => 'Dealing with Exam Anxiety',
                    'creator' => 'Maria Garcia',
                    'status' => 'active',
                    'description' => 'I get really anxious during exams even when I know the material. Any tips for managing test anxiety and staying calm?',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Wellness', 'General'],
                    'views' => 87,
                    'replies' => 16,
                    'last_activity' => '4 days ago'
                ],
                [
                    'id' => 10,
                    'title' => 'Understanding Big O Notation',
                    'creator' => 'Kevin Brown',
                    'status' => 'active',
                    'description' => 'Big O notation is confusing me. Can someone explain it in simple terms with real-world examples?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Algorithms', 'Help'],
                    'views' => 123,
                    'replies' => 19,
                    'last_activity' => '2 days ago'
                ],
                [
                    'id' => 11,
                    'title' => 'Balancing Work and Study',
                    'creator' => 'Rachel Lee',
                    'status' => 'active',
                    'description' => 'How do you balance part-time work with studies? Looking for time management strategies that actually work.',
                    'category' => 'General',
                    'tags' => ['General', 'TimeManagement', 'Advice'],
                    'views' => 92,
                    'replies' => 14,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 12,
                    'title' => 'Git and Version Control Basics',
                    'creator' => 'Tom Martinez',
                    'status' => 'active',
                    'description' => 'Need help understanding Git workflows. What are the essential commands every student should know?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Git', 'Tools'],
                    'views' => 108,
                    'replies' => 17,
                    'last_activity' => '1 day ago'
                ]
            ],
            'statistics' => [
                'active_discussions' => 52,
                'total_posts' => 287
            ]
        ];

               // Create model instance
               $forumPost = new ForumPost();
        
               // Get data from database for CRUD operations
               $all_posts = $forumPost->getAllPosts();
               $my_posts = $forumPost->getPostsByUser($_SESSION['user_id']);
               
               // Convert false to empty array (when no results)
               $data['all_posts'] = is_array($all_posts) ? $all_posts : [];
               $data['my_posts'] = is_array($my_posts) ? $my_posts : [];
               $data['forumData'] = $forumData;
               $data['faculties'] = is_array($faculties) ? $faculties : [];
        
        // Send data to view
        $this->view('student/discussion-forum', $data);
    }

    public function viewall()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and is a student
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
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
                    'title' => 'Tips for mastering Data Structures?',
                    'creator' => 'Alex Johnson',
                    'status' => 'active',
                    'description' => 'I struggle to understand linked lists and trees. Any strategies or resources you recommend for visualizing these concepts better?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Help', 'DataStructures'],
                    'views' => 89,
                    'replies' => 15,
                    'last_activity' => '2 hours ago'
                ],
                [
                    'id' => 2,
                    'title' => 'Study Plan for Final Exams',
                    'creator' => 'Priya Patel',
                    'status' => 'trending',
                    'description' => 'Here\'s a comprehensive study schedule that helped me last semester. Includes time management tips and resource recommendations!',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Finals', 'Planning'],
                    'views' => 156,
                    'replies' => 28,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 3,
                    'title' => 'Anyone up for a Study Group?',
                    'creator' => 'Sarah Williams',
                    'status' => 'active',
                    'description' => 'Looking to form a study group for Database Systems course. Planning to meet twice a week. DM if interested!',
                    'category' => 'General',
                    'tags' => ['StudyGroup', 'Collaboration', 'General'],
                    'views' => 67,
                    'replies' => 12,
                    'last_activity' => '3 hours ago'
                ],
                [
                    'id' => 4,
                    'title' => 'Best Resources for Algorithm Practice',
                    'creator' => 'Michael Chen',
                    'status' => 'active',
                    'description' => 'Share your favorite websites, books, or YouTube channels for practicing algorithms and problem-solving. What has worked best for you?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Algorithms', 'Resources'],
                    'views' => 134,
                    'replies' => 22,
                    'last_activity' => '5 hours ago'
                ],
                [
                    'id' => 5,
                    'title' => 'How to Stay Motivated During Semester',
                    'creator' => 'Emma Rodriguez',
                    'status' => 'active',
                    'description' => 'Sometimes I lose motivation midway through the semester. What are your tips for staying focused and maintaining good study habits?',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Motivation', 'General'],
                    'views' => 98,
                    'replies' => 18,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 6,
                    'title' => 'Python vs Java: Which to Learn First?',
                    'creator' => 'David Kim',
                    'status' => 'active',
                    'description' => 'I\'m debating which language to focus on learning first. Both seem important but I want to choose the right starting point. Thoughts?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Python', 'Java'],
                    'views' => 112,
                    'replies' => 25,
                    'last_activity' => '2 days ago'
                ],
                [
                    'id' => 7,
                    'title' => 'Effective Note-Taking Methods',
                    'creator' => 'Lisa Anderson',
                    'status' => 'active',
                    'description' => 'What note-taking systems work best for technical courses? Cornell method, mind maps, or something else?',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Notes', 'Learning'],
                    'views' => 145,
                    'replies' => 20,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 8,
                    'title' => 'Project Ideas for Portfolio',
                    'creator' => 'James Wilson',
                    'status' => 'trending',
                    'description' => 'Looking for project ideas to add to my portfolio. What projects have impressed potential employers or helped you land internships?',
                    'category' => 'General',
                    'tags' => ['Projects', 'Portfolio', 'Career'],
                    'views' => 198,
                    'replies' => 31,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 9,
                    'title' => 'Dealing with Exam Anxiety',
                    'creator' => 'Maria Garcia',
                    'status' => 'active',
                    'description' => 'I get really anxious during exams even when I know the material. Any tips for managing test anxiety and staying calm?',
                    'category' => 'StudyTips',
                    'tags' => ['StudyTips', 'Wellness', 'General'],
                    'views' => 87,
                    'replies' => 16,
                    'last_activity' => '4 days ago'
                ],
                [
                    'id' => 10,
                    'title' => 'Understanding Big O Notation',
                    'creator' => 'Kevin Brown',
                    'status' => 'active',
                    'description' => 'Big O notation is confusing me. Can someone explain it in simple terms with real-world examples?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Algorithms', 'Help'],
                    'views' => 123,
                    'replies' => 19,
                    'last_activity' => '2 days ago'
                ],
                [
                    'id' => 11,
                    'title' => 'Balancing Work and Study',
                    'creator' => 'Rachel Lee',
                    'status' => 'active',
                    'description' => 'How do you balance part-time work with studies? Looking for time management strategies that actually work.',
                    'category' => 'General',
                    'tags' => ['General', 'TimeManagement', 'Advice'],
                    'views' => 92,
                    'replies' => 14,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 12,
                    'title' => 'Git and Version Control Basics',
                    'creator' => 'Tom Martinez',
                    'status' => 'active',
                    'description' => 'Need help understanding Git workflows. What are the essential commands every student should know?',
                    'category' => 'CSF',
                    'tags' => ['CSF', 'Git', 'Tools'],
                    'views' => 108,
                    'replies' => 17,
                    'last_activity' => '1 day ago'
                ]
            ]
        ];

        $data = [
            'title' => 'All Forum Topics - GradBridge',
            'user' => $_SESSION,
            'forumData' => $forumData
        ];

        $this->view('student/all-forums', $data);
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

        // Security check (FIXED)
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_POST['post_id'] ?? 0;
        
        // Security: Make sure user owns this post (FIXED)
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

        // Security check (FIXED)
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $forumPost = new ForumPost();
        $post_id = $_POST['post_id'] ?? 0;
        
        // Debug logging
        error_log("Delete attempt - post_id: " . $post_id);
        error_log("User ID: " . $_SESSION['user_id']);
        
        // Security check - use simpler method for ownership
        $existing = $forumPost->getPostForOwnership($post_id);
        
        error_log("Post found: " . ($existing ? "yes" : "no"));
        if ($existing) {
            error_log("Post user_id: " . $existing->user_id);
        }
        
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Post not found (ID: ' . $post_id . ')']);
            exit;
        }
        
        // Check if user owns this post
        if ($existing->user_id != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete this post (not your post)']);
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
}