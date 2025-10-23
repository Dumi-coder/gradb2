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

        // Sample discussion forum data
        $forumData = [
            'topics' => [
                [
                    'id' => 1,
                    'title' => 'Mentoring New Graduates: Effective Strategies',
                    'creator' => 'Nimal Perera',
                    'status' => 'active',
                    'description' => 'Share your experiences and tips for mentoring recent graduates in your field. What approaches have worked best for you in guiding new professionals?',
                    'category' => 'Mentorship',
                    'views' => 134,
                    'last_activity' => '1 day ago'
                ],
                [
                    'id' => 2,
                    'title' => 'Industry Trends: AI and Machine Learning Impact',
                    'creator' => 'Sanduni Jayawardena',
                    'status' => 'trending',
                    'description' => 'Discussion about how AI/ML is reshaping different industries and career paths. How are you adapting to these technological changes in your profession?',
                    'category' => 'General',
                    'tags' => ['General', 'Trending'],
                    'views' => 198,
                    'last_activity' => '2 days ago'
                ],
                [
                    'id' => 3,
                    'title' => 'Work-Life Balance in Tech: Your Strategies',
                    'creator' => 'Chaminda Silva',
                    'status' => 'active',
                    'description' => 'How do you maintain a healthy work-life balance in demanding tech roles? Share your strategies and tips for managing stress and personal time.',
                    'category' => 'Career',
                    'views' => 92,
                    'last_activity' => '3 days ago'
                ],
                [
                    'id' => 4,
                    'title' => 'Remote Work Best Practices',
                    'creator' => 'Dilani Fernando',
                    'status' => 'active',
                    'description' => 'What are your best practices for remote work productivity? Looking for tips on home office setup, communication tools, and maintaining team collaboration.',
                    'category' => 'Career',
                    'tags' => ['Career', 'General'],
                    'views' => 67,
                    'last_activity' => '4 days ago'
                ]
            ],
            'statistics' => [
                'active_discussions' => 47,
                'total_posts' => 324
            ]
        ];

        $data = [
            'title' => 'Discussion Forum - GradBridge',
            'user' => $_SESSION,
            'forumData' => $forumData
        ];

        $this->view('alumni/discussion-forum', $data);
    }
}
