<?php

class AidRequests extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        $requestModel = new Request();

        $pendingForCounselor = $requestModel->getAidRequestsForStatuses(['pending_verification']);
        $approvedByCounselor = $requestModel->getAidRequestsForStatuses(['open']);
        $acceptedByAlumni = $requestModel->getAidRequestsForStatuses(['approved', 'accepted']);
        $completed = $requestModel->getAidRequestsForStatuses(['completed']);

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
            'page_subtitle' => 'Monitor aid request pipeline and supporting documents across all faculties.',
            'user' => $_SESSION,
            'aidRequestsData' => $aidRequestsData
        ];

        $this->view('superadmin/aid-requests', $data);
    }
}
