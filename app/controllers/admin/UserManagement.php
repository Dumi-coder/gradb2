<?php

class UserManagement extends Controller
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

        // Get user management data
        $userData = $this->getUserData();

        $data = [
            'title' => 'Suspend/Reactivate Users - GradBridge',
            'page_title' => 'Suspend/Reactivate Users',
            'page_subtitle' => 'Manage user accounts and access permissions.',
            'user' => $_SESSION,
            'userData' => $userData
        ];

        $this->view('admin/user-management', $data);
    }

    private function getUserData()
    {
        // Mock data for user management
        return [
            'users' => [
                [
                    'id' => 1,
                    'name' => 'John Smith',
                    'email' => 'john.smith@university.edu',
                    'role' => 'student',
                    'status' => 'active',
                    'last_login' => '2024-01-15',
                    'registration_date' => '2023-09-01',
                    'violations' => 0
                ],
                [
                    'id' => 2,
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@university.edu',
                    'role' => 'alumni',
                    'status' => 'suspended',
                    'last_login' => '2024-01-10',
                    'registration_date' => '2022-05-15',
                    'violations' => 3
                ],
                [
                    'id' => 3,
                    'name' => 'Mike Chen',
                    'email' => 'mike.chen@university.edu',
                    'role' => 'student',
                    'status' => 'active',
                    'last_login' => '2024-01-14',
                    'registration_date' => '2023-09-01',
                    'violations' => 1
                ],
                [
                    'id' => 4,
                    'name' => 'Dr. Lisa Wang',
                    'email' => 'lisa.wang@university.edu',
                    'role' => 'alumni',
                    'status' => 'active',
                    'last_login' => '2024-01-13',
                    'registration_date' => '2020-03-15',
                    'violations' => 0
                ]
            ],
            'stats' => [
                'total_users' => 1247,
                'active_users' => 1189,
                'suspended_users' => 58,
                'students' => 856,
                'alumni' => 391
            ]
        ];
    }
}
