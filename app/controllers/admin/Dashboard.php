<?php

class Dashboard extends Controller
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

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        $data = [
            'title' => 'Faculty Admin Dashboard - GradBridge',
            'user' => $_SESSION,
            'stats' => $stats
        ];

        $this->view('admin/dashboard', $data);
    }

    private function getDashboardStats()
    {
        // You can expand this to get real statistics from the database
        return [
            'registered_students' => 156,
            'students_online' => 23,
            'pending_aid_requests' => 8,
            'events_waiting_approval' => 5,
            'password_verification_requests' => 7,
            'registered_alumni' => 89,
            'alumni_online' => 12,
            'pending_mentorship_requests' => 15,
            'pending_complaints' => 3,
            'upcoming_events' => 12
        ];
    }
}
