<?php
class RejectedRequests extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counsellor' || (int)($_SESSION['user_id'] ?? 0) !== 1) {
            redirect('counsellor');
        }

        $requestModel = new Request();
        $rejectedRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['rejected']);

        $this->view('counsellor/rejected-requests', [
            'rejectedRequests' => $rejectedRequests,
        ]);
    }
}

