<?php

class Dashboard extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as counselor
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'counselor') {
            redirect('counselor');
        }

        // Prevent caching of dashboard pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $data = [
            'title' => 'Counselor Dashboard - GradBridge',
            'user' => $_SESSION
        ];

        $this->view('counselor/dashboard', $data);
    }
}