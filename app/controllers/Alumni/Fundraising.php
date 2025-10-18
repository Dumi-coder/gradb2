<?php
class Fundraising extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        $this->view('alumni/fundraising');
    }
}
