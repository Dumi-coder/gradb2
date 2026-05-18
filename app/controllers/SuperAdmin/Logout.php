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
        
        // Redirect to superadmin login page
        header("Location: http://localhost/gradb2/superadmin/");
        exit();
    }
}
