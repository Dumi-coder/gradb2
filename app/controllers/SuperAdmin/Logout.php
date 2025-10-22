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
        
        // Redirect directly to the clean superadmin login view
        $this->view('auth/superadmin-login', []);
    }
}
