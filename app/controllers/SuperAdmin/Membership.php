<?php
/**
 * SuperAdmin Membership Controller
 * For approving alumni into faculty groups and managing membership requests
 */

class Membership extends Controller
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
            'title' => 'Membership Queue - GradBridge',
            'user' => $_SESSION
        ];

        $this->view('superadmin/membership_queue', $data);
    }
}
