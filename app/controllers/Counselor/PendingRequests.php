<?php
class PendingRequests extends Controller
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $requestId = (int)($_POST['request_id'] ?? 0);
            $note = trim($_POST['note'] ?? '');

            if ($requestId > 0) {
                if ($action === 'approve') {
                    $ok = $requestModel->approveAidRequestByCounselor($requestId);
                    $_SESSION['flash_message'] = $ok
                        ? 'Aid request approved and sent to alumni.'
                        : 'Unable to approve request. It may have been updated already.';
                }

                if ($action === 'reject') {
                    if ($note === '') {
                        $_SESSION['flash_message'] = 'Rejection note is required.';
                    } else {
                        $ok = $requestModel->rejectAidRequestByCounselor($requestId, $note);
                        $_SESSION['flash_message'] = $ok
                            ? 'Aid request rejected with counselor note.'
                            : 'Unable to reject request. Please try again.';
                    }
                }
            }

            redirect('counselor/PendingRequests');
        }

        $pendingRequests = $requestModel->getAidRequestsForCounselorByStatuses(['pending_verification']);
        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $this->view('counselor/pending-requests', [
            'pendingRequests' => $pendingRequests,
            'flashMessage' => $flashMessage,
        ]);
    }
}