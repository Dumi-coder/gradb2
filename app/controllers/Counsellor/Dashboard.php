<?php

class Dashboard extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Allow only logged-in counsellor with user_id = 1
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counsellor' || (int)$_SESSION['user_id'] !== 1) {
            $_SESSION['flash_message'] = 'Please login as counsellor user_id 1';
            redirect('counsellor');
        }

        $requestModel = new Request();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $requestId = (int)($_POST['request_id'] ?? 0);
            $note = trim($_POST['note'] ?? '');

            if ($requestId > 0) {
                if ($action === 'approve') {
                    $ok = $requestModel->approveAidRequestByCounsellor($requestId);
                    $_SESSION['flash_message'] = $ok
                        ? 'Aid request approved and sent to alumni.'
                        : 'Unable to approve request. It may have been updated already.';
                }

                if ($action === 'reject') {
                    if ($note === '') {
                        $_SESSION['flash_message'] = 'Rejection note is required.';
                    } else {
                        $ok = $requestModel->rejectAidRequestByCounsellor($requestId, $note);
                        $_SESSION['flash_message'] = $ok
                            ? 'Aid request rejected with counsellor note.'
                            : 'Unable to reject request. Please try again.';
                    }
                }
            }

            redirect('counsellor/dashboard');
        }

        $pendingRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['pending_verification']);
        $acceptedRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['open', 'approved', 'accepted']);
        $completedRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['completed']);
        $rejectedRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['rejected']);
        $flashMessage = $_SESSION['flash_message'] ?? null;
        if (is_array($flashMessage)) {
            $flashMessage = $flashMessage['text'] ?? null;
        }
        unset($_SESSION['flash_message']);

        // Prevent caching of dashboard pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $data = [
            'title' => 'Counsellor Dashboard - GradBridge',
            'user' => $_SESSION,
            'pendingRequests' => $pendingRequests,
            'acceptedRequests' => $acceptedRequests,
            'completedRequests' => $completedRequests,
            'rejectedRequests' => $rejectedRequests,
            'flashMessage' => $flashMessage,
        ];

        $this->view('counsellor/dashboard', $data);
    }
}