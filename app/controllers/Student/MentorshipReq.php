<?php 
class MentorshipReq extends Controller
{
    private $mentorshipRequestModel;

    public function __construct()
    {
        $this->mentorshipRequestModel = new MentorshipRequest();
    }

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $_SESSION['error'] = 'Open mentorship requests are disabled. Please choose a specific mentor from the Mentorship page.';
        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }

    public function create()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $_SESSION['error'] = 'Open mentorship requests are disabled. Please choose a specific mentor from the Mentorship page.';
        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }

    public function edit($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $_SESSION['error'] = 'Open mentorship requests are disabled. Please choose a specific mentor from the Mentorship page.';
        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }

    public function delete($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $_SESSION['error'] = 'Open mentorship requests are disabled. Please choose a specific mentor from the Mentorship page.';
        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }
}

