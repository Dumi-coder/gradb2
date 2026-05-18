<?php

class Mentorship extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        $facultyId = $this->getFacultyIdForAdmin();
        $mentorshipModel = new MentorshipRequest();

        $pending = $facultyId ? $mentorshipModel->getMentorshipCardsByStatuses(['pending'], $facultyId) : [];
        $ongoing = $facultyId ? $mentorshipModel->getMentorshipCardsByStatuses(['accepted'], $facultyId) : [];
        $completed = $facultyId ? $mentorshipModel->getMentorshipCardsByStatuses(['completed'], $facultyId) : [];

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
            'page_subtitle' => 'Track mentorship requests and sessions for students in your faculty.',
            'user' => $_SESSION,
            'mentorshipData' => $mentorshipData
        ];

        $this->view('admin/mentorship', $data);
    }

    private function getFacultyIdForAdmin()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        $facultyAdmin = new FacultyAdmin();
        $row = $facultyAdmin->query(
            "SELECT faculty_id
             FROM faculty_admins
             WHERE user_id = :user_id
             ORDER BY faculty_admin_id DESC
             LIMIT 1",
            ['user_id' => $userId]
        );

        if (is_array($row) && !empty($row[0]) && isset($row[0]->faculty_id)) {
            return (int)$row[0]->faculty_id;
        }

        return null;
    }
}
