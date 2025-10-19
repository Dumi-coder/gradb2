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

        $data = [
            'title' => 'Super Admin Dashboard - GradBridge',
            'user' => $_SESSION
        ];

        $this->view('superadmin/dashboard', $data);
    }
}