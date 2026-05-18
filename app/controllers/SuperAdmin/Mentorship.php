<?php

class Mentorship extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        $mentorshipModel = new MentorshipRequest();
        $pending = $mentorshipModel->getMentorshipCardsByStatuses(['pending']);
        $ongoing = $mentorshipModel->getMentorshipCardsByStatuses(['accepted']);
        $completed = $mentorshipModel->getMentorshipCardsByStatuses(['completed']);

        $mentorshipData = [
            'pending' => $pending,
            'ongoing' => $ongoing,
            'completed' => $completed,
            'stats' => [
                'pending' => count($pending),
                'ongoing' => count($ongoing),
                'completed' => count($completed),
            ]
        ];

        $data = [
            'title' => 'Approve Mentorships - GradBridge',
            'page_title' => 'Approve Mentorships',
            'page_subtitle' => 'Track mentorship requests and sessions across all faculties.',
            'user' => $_SESSION,
            'mentorshipData' => $mentorshipData
        ];

        $this->view('superadmin/mentorship', $data);
    }
}
