<?php
class CompletedRequests extends Controller
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
        $completedRequests = $requestModel->getAidRequestsForCounselorByStatuses(['completed']);

        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $this->view('counselor/completed-requests', [
            'completedRequests' => $completedRequests,
            'flashMessage' => $flashMessage,
        ]);
    }
}
