<?php
class Mentorship extends Controller
{
    private $mentorshipRequestModel;

    public function __construct()
    {
        $this->mentorshipRequestModel = new MentorshipRequest();
    }
    
    public function index()
    {
        // Check if user is logged in and is a student
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $studentId = $_SESSION['user_id'];
        $requests = $this->mentorshipRequestModel->getRequestsByStudent($studentId);
        
        $data = [
            'requests' => $requests ? $requests : []
        ];
        
        $this->view('student/mentorship', $data);
    }
}