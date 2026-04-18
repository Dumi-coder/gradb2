<?php
class CompletedRequests extends Controller
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
        $completedRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['completed']);

        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $this->view('counsellor/completed-requests', [
            'completedRequests' => $completedRequests,
            'flashMessage' => $flashMessage,
        ]);
    }
}
