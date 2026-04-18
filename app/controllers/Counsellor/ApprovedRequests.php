<?php
class ApprovedRequests extends Controller
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

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = $_POST['action'] ?? '';
            $requestId = (int)($_POST['request_id'] ?? 0);

            if ($action === 'complete' && $requestId > 0) {
                $completionNote = trim((string)($_POST['completion_note'] ?? ''));
                if ($completionNote === '') {
                    $_SESSION['flash_message'] = 'Completion note is required before marking as completed.';
                    redirect('counsellor/ApprovedRequests');
                }

                $completionContext = $requestModel->getAidCompletionContextByRequestId($requestId);
                $ok = $requestModel->markAidRequestCompletedByCounsellor(
                    $requestId,
                    (int)($_SESSION['user_id'] ?? 0),
                    $completionNote
                );

                if ($ok) {
                    if (!empty($completionContext->alumnus_user_id)) {
                        try {
                            $notificationModel = new Notification();
                            $notificationModel->createAidCompletionNotificationForAlumnus(
                                (int)$completionContext->alumnus_user_id,
                                (int)($_SESSION['user_id'] ?? 0),
                                (int)$requestId,
                                (string)($completionContext->student_name ?? 'Student'),
                                (string)($completionContext->aid_type ?? 'aid'),
                                $completionNote
                            );
                        } catch (Throwable $e) {
                            error_log('Aid completion notification failed: ' . $e->getMessage());
                        }
                    }
                    $_SESSION['flash_message'] = 'Aid request marked as completed and alumnus notified.';
                    redirect('counsellor/CompletedRequests');
                } else {
                    $_SESSION['flash_message'] = 'Unable to mark this request as completed. It may not be alumni-accepted yet.';
                }

                redirect('counsellor/ApprovedRequests');
            }

            redirect('counsellor/ApprovedRequests');
        }

        $approvedRequests = $requestModel->getAidRequestsForCounsellorByStatuses(['open', 'approved', 'accepted']);
        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $this->view('counsellor/approved-requests', [
            'approvedRequests' => $approvedRequests,
            'flashMessage' => $flashMessage,
        ]);
    }
}


