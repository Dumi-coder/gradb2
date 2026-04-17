<?php

class Dashboard extends Controller
{
    use DashboardMetrics;

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

        $facultyId = $this->getFacultyIdForAdmin();
        $stats = $this->getDashboardStats($facultyId);

        $facultyAdminModel = new FacultyAdmin();
        $facultyAdminProfile = $facultyAdminModel->getFacultyAdminProfile($_SESSION['user_id']);
        
        $data = [
            'title' => 'Faculty Admin Dashboard - GradBridge',
            'user' => $_SESSION,
            'stats' => $stats,
            'facultyAdminProfile' => $facultyAdminProfile
        ];

        $this->view('admin/dashboard', $data);
    }

    private function getFacultyIdForAdmin(): ?int
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        $facultyAdmin = new FacultyAdmin();
        $row = $facultyAdmin->query(
            "SELECT faculty_id
             FROM faculty_admins
             WHERE user_id = :user_id
             ORDER BY faculty_admin_id DESC
             LIMIT 1",
            ['user_id' => $userId]
        );

        if (is_array($row) && !empty($row[0]) && isset($row[0]->faculty_id)) {
            return (int)$row[0]->faculty_id;
        }

        return null;
    }
}
