<?php

class Dashboard extends Controller
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

        // TODO: Fetch real statistics from database
        $stats = [
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

        $data = [
            'title' => 'Super Admin Dashboard - GradBridge',
            'user' => $_SESSION,
            'stats' => $stats
        ];

        $this->view('superadmin/dashboard', $data);
    }
}