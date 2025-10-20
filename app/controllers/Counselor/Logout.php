<?php

/*class Logout extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect directly to the clean counselor login view
        $this->view('auth/counselor-login', []);
    }
}*/


class Logout extends Controller
{
    public function index()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Destroy all session data
        session_destroy();

        // Redirect to counselor login page
    header("Location: http://localhost/gradb2/counselor/");
    exit();
}
}
?>
