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

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = $_POST['action'] ?? '';
            $requestId = (int)($_POST['request_id'] ?? 0);

            if ($action === 'complete' && $requestId > 0) {
                $ok = $requestModel->markAidRequestCompletedByCounselor($requestId, (int)($_SESSION['user_id'] ?? 0));
                $_SESSION['flash_message'] = $ok
                    ? 'Aid request marked as completed.'
                    : 'Unable to mark this request as completed. It may not be alumni-accepted yet.';

                redirect('counselor/ApprovedRequests');
            }

            redirect('counselor/ApprovedRequests');
        }

        $approvedRequests = $requestModel->getAidRequestsForCounselorByStatuses(['open', 'approved', 'accepted']);
        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $this->view('counselor/approved-requests', [
            'approvedRequests' => $approvedRequests,
            'flashMessage' => $flashMessage,
        ]);
    }
}


