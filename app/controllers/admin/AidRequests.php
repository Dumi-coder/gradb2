<?php

class AidRequests extends Controller
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

        // Get aid requests data
        $aidRequestsData = $this->getAidRequests();

        $data = [
            'title' => 'Approve Aid Requests - GradBridge',
            'page_title' => 'Approve Aid Requests',
            'page_subtitle' => 'Review and approve financial aid requests from students.',
            'user' => $_SESSION,
            'aidRequestsData' => $aidRequestsData
        ];

        $this->view('admin/aid-requests', $data);
    }

    private function getAidRequests()
    {
        // Mock data for aid requests
        return [
            'requests' => [
                [
                    'id' => 1,
                    'student_name' => 'Maria Garcia',
                    'student_email' => 'maria.garcia@university.edu',
                    'student_id' => 'STU001',
                    'aid_type' => 'Emergency Financial Aid',
                    'amount_requested' => 2500,
                    'reason' => 'Family emergency requiring immediate financial assistance for medical expenses.',
                    'status' => 'pending',
                    'submitted_date' => '2024-01-15',
                    'urgency' => 'high',
                    'supporting_documents' => 3
                ],
                [
                    'id' => 2,
                    'student_name' => 'David Kim',
                    'student_email' => 'david.kim@university.edu',
                    'student_id' => 'STU002',
                    'aid_type' => 'Tuition Assistance',
                    'amount_requested' => 5000,
                    'reason' => 'Unexpected job loss in family affecting ability to pay tuition for current semester.',
                    'status' => 'pending',
                    'submitted_date' => '2024-01-14',
                    'urgency' => 'normal',
                    'supporting_documents' => 5
                ],
                [
                    'id' => 3,
                    'student_name' => 'Jennifer Lee',
                    'student_email' => 'jennifer.lee@university.edu',
                    'student_id' => 'STU003',
                    'aid_type' => 'Book and Supplies Grant',
                    'amount_requested' => 800,
                    'reason' => 'Need assistance with expensive textbooks for advanced courses this semester.',
                    'status' => 'pending',
                    'submitted_date' => '2024-01-13',
                    'urgency' => 'normal',
                    'supporting_documents' => 2
                ]
            ],
            'stats' => [
                'total_requests' => 3,
                'pending_requests' => 3,
                'approved_requests' => 0,
                'rejected_requests' => 0,
                'total_amount_requested' => 8300
            ]
        ];
    }
}
