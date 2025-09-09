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
        
        // f(!empty($_SESSION['USER']))
        //     unset($_SESSION['USER']); // Unset the user session
        // Redirect to home page
        redirect('home');
    }
}
