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

        // Get forum moderation data
        $forumData = $this->getForumData();

        $data = [
            'title' => 'Forum Moderation - GradBridge',
            'user' => $_SESSION,
            'forumData' => $forumData
        ];

        $this->view('admin/forum-moderation', $data);
    }

    private function getForumData()
    {
        // Mock data for forum moderation
        return [
            'reported_posts' => [
                [
                    'id' => 1,
                    'post_title' => 'Looking for study group members',
                    'author' => 'John Doe',
                    'author_email' => 'john.doe@university.edu',
                    'content' => 'I am looking for study group members for CS 301. Anyone interested?',
                    'report_reason' => 'Spam',
                    'reported_by' => 'Jane Smith',
                    'reported_date' => '2024-01-15',
                    'status' => 'pending'
                ],
                [
                    'id' => 2,
                    'post_title' => 'Job opportunities in tech',
                    'author' => 'Mike Johnson',
                    'author_email' => 'mike.johnson@university.edu',
                    'content' => 'Sharing some great job opportunities I found in the tech industry...',
                    'report_reason' => 'Inappropriate Content',
                    'reported_by' => 'Sarah Wilson',
                    'reported_date' => '2024-01-14',
                    'status' => 'pending'
                ]
            ],
            'recent_posts' => [
                [
                    'id' => 3,
                    'post_title' => 'Alumni networking event',
                    'author' => 'Alumni Association',
                    'content' => 'Join us for our monthly alumni networking event...',
                    'post_date' => '2024-01-13',
                    'replies' => 5,
                    'views' => 45
                ],
                [
                    'id' => 4,
                    'post_title' => 'Career advice needed',
                    'author' => 'Student User',
                    'content' => 'I need advice on choosing between two job offers...',
                    'post_date' => '2024-01-12',
                    'replies' => 8,
                    'views' => 32
                ]
            ],
            'stats' => [
                'total_posts' => 156,
                'reported_posts' => 2,
                'pending_reports' => 2,
                'resolved_reports' => 0,
                'active_users' => 89
            ]
        ];
    }
}
