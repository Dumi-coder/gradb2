<?php

class FundraiserModeration extends Controller
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

        // Get fundraiser moderation data
        $fundraiserData = $this->getFundraiserData();

        $data = [
            'title' => 'Moderate Fundraisers - GradBridge',
            'page_title' => 'Moderate Fundraisers',
            'page_subtitle' => 'Review and moderate fundraising campaigns from students and alumni.',
            'user' => $_SESSION,
            'fundraiserData' => $fundraiserData
        ];

        $this->view('admin/fundraiser-moderation', $data);
    }

    private function getFundraiserData()
    {
        // Mock data for fundraiser moderation
        return [
            'reported_fundraisers' => [
                [
                    'id' => 1,
                    'title' => 'Emergency Medical Fund for Student',
                    'organizer' => 'Student Council',
                    'organizer_email' => 'council@university.edu',
                    'target_amount' => 10000,
                    'current_amount' => 3500,
                    'description' => 'Raising funds for emergency medical expenses for a fellow student',
                    'report_reason' => 'Suspicious Activity',
                    'reported_by' => 'Faculty Member',
                    'reported_date' => '2024-01-15',
                    'status' => 'pending',
                    'donors' => 45
                ],
                [
                    'id' => 2,
                    'title' => 'Scholarship Fund Drive',
                    'organizer' => 'Alumni Association',
                    'organizer_email' => 'alumni@university.edu',
                    'target_amount' => 50000,
                    'current_amount' => 12500,
                    'description' => 'Annual scholarship fund drive to support underprivileged students',
                    'report_reason' => 'Inappropriate Content',
                    'reported_by' => 'Student User',
                    'reported_date' => '2024-01-14',
                    'status' => 'pending',
                    'donors' => 78
                ]
            ],
            'recent_fundraisers' => [
                [
                    'id' => 3,
                    'title' => 'Library Renovation Project',
                    'organizer' => 'Library Committee',
                    'target_amount' => 25000,
                    'current_amount' => 18750,
                    'description' => 'Fundraising for library renovation and modernization',
                    'start_date' => '2024-01-10',
                    'end_date' => '2024-03-10',
                    'donors' => 156,
                    'status' => 'approved'
                ],
                [
                    'id' => 4,
                    'title' => 'Student Emergency Fund',
                    'organizer' => 'Student Services',
                    'target_amount' => 15000,
                    'current_amount' => 9200,
                    'description' => 'Emergency fund to help students in financial crisis',
                    'start_date' => '2024-01-08',
                    'end_date' => '2024-02-28',
                    'donors' => 89,
                    'status' => 'approved'
                ]
            ],
            'stats' => [
                'total_fundraisers' => 12,
                'reported_fundraisers' => 2,
                'pending_reports' => 2,
                'approved_fundraisers' => 10,
                'total_amount_raised' => 125000
            ]
        ];
    }
}
