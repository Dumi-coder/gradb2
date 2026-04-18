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

        $pendingForCounsellor = $requestModel->getAidRequestsForStatuses(['pending_verification']);
        $approvedByCounsellor = $requestModel->getAidRequestsForStatuses(['open']);
        $acceptedByAlumni = $requestModel->getAidRequestsForStatuses(['approved', 'accepted']);
        $completed = $requestModel->getAidRequestsForStatuses(['completed']);

        $aidRequestsData = [
            'pending_for_counsellor' => $pendingForCounsellor,
            'approved_by_counsellor' => $approvedByCounsellor,
            'accepted_by_alumni' => $acceptedByAlumni,
            'completed' => $completed,
            'stats' => [
                'pending_for_counsellor' => count($pendingForCounsellor),
                'approved_by_counsellor' => count($approvedByCounsellor),
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
