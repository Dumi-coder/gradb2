<?php

class FacultyAnnouncements extends Controller
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

        // Get faculty announcements data
        $announcementsData = $this->getAnnouncementsData();

        $data = [
            'title' => 'Faculty Announcements - GradBridge',
            'user' => $_SESSION,
            'announcementsData' => $announcementsData
        ];

        $this->view('admin/faculty-announcements', $data);
    }

    private function getAnnouncementsData()
    {
        // Mock data for faculty announcements
        return [
            'announcements' => [
                [
                    'id' => 1,
                    'title' => 'New Mentorship Program Guidelines',
                    'content' => 'We are pleased to announce new guidelines for the mentorship program. Please review the updated policies and procedures.',
                    'author' => 'Dr. Sarah Johnson',
                    'author_email' => 'sarah.johnson@university.edu',
                    'priority' => 'high',
                    'status' => 'published',
                    'created_date' => '2024-01-15',
                    'expiry_date' => '2024-02-15',
                    'views' => 156,
                    'target_audience' => 'All Faculty'
                ],
                [
                    'id' => 2,
                    'title' => 'Upcoming Faculty Meeting',
                    'content' => 'Monthly faculty meeting scheduled for next Friday at 2:00 PM in the conference room. All faculty members are required to attend.',
                    'author' => 'Dean Michael Chen',
                    'author_email' => 'michael.chen@university.edu',
                    'priority' => 'medium',
                    'status' => 'published',
                    'created_date' => '2024-01-14',
                    'expiry_date' => '2024-01-26',
                    'views' => 89,
                    'target_audience' => 'All Faculty'
                ],
                [
                    'id' => 3,
                    'title' => 'Student Support Resources Update',
                    'content' => 'New student support resources have been added to the platform. Please familiarize yourself with these new tools.',
                    'author' => 'Prof. Lisa Wang',
                    'author_email' => 'lisa.wang@university.edu',
                    'priority' => 'low',
                    'status' => 'draft',
                    'created_date' => '2024-01-13',
                    'expiry_date' => '2024-03-13',
                    'views' => 0,
                    'target_audience' => 'Faculty Admins'
                ],
                [
                    'id' => 4,
                    'title' => 'System Maintenance Notice',
                    'content' => 'Scheduled system maintenance will occur this weekend. The platform will be unavailable from Saturday 10 PM to Sunday 6 AM.',
                    'author' => 'IT Department',
                    'author_email' => 'it@university.edu',
                    'priority' => 'high',
                    'status' => 'published',
                    'created_date' => '2024-01-12',
                    'expiry_date' => '2024-01-20',
                    'views' => 234,
                    'target_audience' => 'All Users'
                ]
            ],
            'stats' => [
                'total_announcements' => 24,
                'published_announcements' => 22,
                'draft_announcements' => 2,
                'high_priority' => 5,
                'total_views' => 3456
            ]
        ];
    }
}
