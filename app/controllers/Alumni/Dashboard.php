<?php
class Dashboard extends Controller
{    
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Prevent caching of dashboard pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Get fresh profile data from database
        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('alumni/auth');
        }

        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        $data = [
            'title' => 'Alumni Dashboard - GradBridge',
            'profile' => $profile,  // This contains all the data you need
            'stats' => $stats,
            'user' => $_SESSION  // Session data if needed
        ];

        $this->view('alumni/dashboard', $data);
    }

    private function getDashboardStats()
    {
        // You can expand this to get real statistics
        return [
            'mentorship_requests' => 0,
            'aid_requests' => 0,
            'events_attended' => 0,
            'total_donations' => 0
        ];
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        redirect('home');
    }
}