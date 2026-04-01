<?php
class RejectedRequests extends Controller
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
        $rejectedRequests = $requestModel->getAidRequestsForCounselorByStatuses(['rejected']);

        $this->view('counselor/rejected-requests', [
            'rejectedRequests' => $rejectedRequests,
        ]);
    }
}


