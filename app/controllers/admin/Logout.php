<?php

class Logout extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Clear any existing output
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Redirect to admin login page
        header("Location: http://localhost/gradb2/admin/");
        exit();
    }
}
