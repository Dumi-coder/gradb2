<?php
class ApprovedRequests extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counselor') {
            redirect('counselor');
        }

        $requestModel = new Request();
        $approvedRequests = $requestModel->getAidRequestsForCounselorByStatuses(['open', 'approved', 'accepted']);

        $this->view('counselor/approved-requests', [
            'approvedRequests' => $approvedRequests,
        ]);
    }
}


