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
            'title' => 'Discussion Forum - GradBridge',
            'user' => $_SESSION,
            'forumData' => $forumData
        ];

        $this->view('alumni/discussion-forum', $data);
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
