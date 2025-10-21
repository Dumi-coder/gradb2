<?php

class Mentorship extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as superadmin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        // Get mentorship requests data
        $mentorshipData = $this->getMentorshipRequests();

        $data = [
            'title' => 'Approve Mentorships - GradBridge',
            'page_title' => 'Approve Mentorships',
            'page_subtitle' => 'Review and approve mentorship requests from students and alumni.',
            'user' => $_SESSION,
            'mentorshipData' => $mentorshipData
        ];

        $this->view('superadmin/mentorship', $data);
    }

    private function getMentorshipRequests()
    {
        // Mock data for mentorship requests
        return [
            'requests' => [
                [
                    'id' => 1,
                    'student_name' => 'Sarah Johnson',
                    'student_email' => 'sarah.johnson@university.edu',
                    'mentor_name' => 'Dr. Michael Chen',
                    'mentor_email' => 'michael.chen@techcorp.com',
                    'guidance_type' => 'Career Guidance',
                    'description' => 'Looking for guidance on transitioning from academia to industry, specifically in software engineering roles.',
                    'status' => 'pending',
                    'requested_date' => '2024-01-15',
                    'urgency' => 'normal'
                ],
                [
                    'id' => 2,
                    'student_name' => 'Alex Rodriguez',
                    'student_email' => 'alex.rodriguez@university.edu',
                    'mentor_name' => 'Prof. Lisa Wang',
                    'mentor_email' => 'lisa.wang@research.org',
                    'guidance_type' => 'Research Collaboration',
                    'description' => 'Seeking mentorship for PhD research in machine learning applications for healthcare.',
                    'status' => 'pending',
                    'requested_date' => '2024-01-14',
                    'urgency' => 'high'
                ],
                [
                    'id' => 3,
                    'student_name' => 'Emma Thompson',
                    'student_email' => 'emma.thompson@university.edu',
                    'mentor_name' => 'John Smith',
                    'mentor_email' => 'john.smith@startup.com',
                    'guidance_type' => 'Entrepreneurship',
                    'description' => 'Need guidance on starting a tech startup and securing funding.',
                    'status' => 'pending',
                    'requested_date' => '2024-01-13',
                    'urgency' => 'normal'
                ]
            ],
            'stats' => [
                'total_requests' => 3,
                'pending_requests' => 3,
                'approved_requests' => 0,
                'rejected_requests' => 0
            ]
        ];
    }
}
