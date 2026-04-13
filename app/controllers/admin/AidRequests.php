<?php

class AidRequests extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        $requestModel = new Request();
        $facultyId = $this->getFacultyIdForAdmin();

        $pendingForCounselor = $facultyId
            ? $requestModel->getAidRequestsForStatuses(['pending_verification'], $facultyId)
            : [];
        $approvedByCounselor = $facultyId
            ? $requestModel->getAidRequestsForStatuses(['open'], $facultyId)
            : [];
        $acceptedByAlumni = $facultyId
            ? $requestModel->getAidRequestsForStatuses(['approved', 'accepted'], $facultyId)
            : [];
        $completed = $facultyId
            ? $requestModel->getAidRequestsForStatuses(['completed'], $facultyId)
            : [];

        $aidRequestsData = [
            'pending_for_counselor' => $pendingForCounselor,
            'approved_by_counselor' => $approvedByCounselor,
            'accepted_by_alumni' => $acceptedByAlumni,
            'completed' => $completed,
            'stats' => [
                'pending_for_counselor' => count($pendingForCounselor),
                'approved_by_counselor' => count($approvedByCounselor),
                'accepted_by_alumni' => count($acceptedByAlumni),
                'completed' => count($completed),
            ],
        ];

        $data = [
            'title' => 'Aid Requests Supervision - GradBridge',
            'page_title' => 'Aid Requests Supervision',
            'page_subtitle' => 'Monitor aid request pipeline and supporting documents for your faculty.',
            'user' => $_SESSION,
            'aidRequestsData' => $aidRequestsData
        ];

        $this->view('admin/aid-requests', $data);
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
