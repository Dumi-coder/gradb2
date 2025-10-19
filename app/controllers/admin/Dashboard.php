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

        $data = [
            'title' => 'Admin Dashboard - GradBridge',
            'user' => $_SESSION
        ];

        $this->view('admin/dashboard', $data);
    }
}
