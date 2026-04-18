<?php
class AidRequests extends Controller
{
    private function getAuthenticatedStudentUserId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionRole = strtolower((string)($_SESSION['role'] ?? ''));
        $legacyRole = strtolower((string)($_SESSION['USER']->role ?? ''));
        $role = $sessionRole !== '' ? $sessionRole : $legacyRole;
        $userId = $_SESSION['user_id'] ?? ($_SESSION['USER']->user_id ?? null);

        if (!$userId || $role !== 'student') {
            redirect('student/auth');
        }

        return (int)$userId;
    }

    private function getOwnedAidRequest($requestId, $studentUserId)
    {
        $requestModel = new Request();

        return $requestModel->get_row(
            "SELECT r.request_id, r.status, r.created_at,
                    ar.mobile_number, ar.aid_type, ar.amount, ar.reason,
                    ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path
             FROM requests r
             LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
             WHERE r.request_type = 'aid'
               AND r.request_id = :request_id
               AND r.student_user_id = :student_user_id
             LIMIT 1",
            [
                'request_id' => (int)$requestId,
                'student_user_id' => (int)$studentUserId,
            ]
        );
    }

    private function ensurePendingBeforeStudentChange($request)
    {
        $status = strtolower((string)($request->status ?? ''));
        return $status === 'pending_verification';
    }

    private function saveOptionalFile($file, $uploadDir, $allowedExtensions)
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) {
            return false;
        }

        $uniqueName = uniqid('aid_', true) . '.' . $extension;
        $destination = $uploadDir . '/' . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return false;
        }

        return 'uploads/aid-requests/' . $uniqueName;
    }

    private function removeUploadedFileIfLocal($relativePath)
    {
        $relativePath = trim((string)$relativePath);
        if ($relativePath === '' || strpos($relativePath, 'uploads/aid-requests/') !== 0) {
            return;
        }

        $absolutePath = dirname(__DIR__, 3) . '/public/' . $relativePath;
        if (file_exists($absolutePath) && is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    public function index()
    {
        $userId = $this->getAuthenticatedStudentUserId();

        $requestModel = new Request();
        $requests = $requestModel->getAidRequestsForStudent($userId);

        $this->view('student/aid-requests', [
            'requests' => $requests,
        ]);
    }

    public function update($requestId = null)
    {
        $studentUserId = $this->getAuthenticatedStudentUserId();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$requestId || !is_numeric($requestId)) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Invalid aid request update action.',
            ];
            redirect('student/aidrequests');
        }

        $request = $this->getOwnedAidRequest((int)$requestId, $studentUserId);
        if (!$request || !$this->ensurePendingBeforeStudentChange($request)) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Only counsellor-pending requests can be edited.',
            ];
            redirect('student/aidrequests');
        }

        $mobileNumber = trim((string)($_POST['mobile_number'] ?? ''));
        $aidType = trim((string)($_POST['aid_type'] ?? ''));
        $otherAidType = trim((string)($_POST['other_aid_type'] ?? ''));
        $amount = trim((string)($_POST['amount'] ?? ''));
        $reason = trim((string)($_POST['reason'] ?? ''));

        $errors = [];
        if ($mobileNumber === '' || !preg_match('/^[0-9]{10}$/', $mobileNumber)) {
            $errors[] = 'Mobile number must be 10 digits (07XXXXXXXX).';
        }

        $allowedAidTypes = ['money', 'laptop', 'textbooks', 'stationery', 'other'];
        if (!in_array($aidType, $allowedAidTypes, true)) {
            $errors[] = 'Type of aid request is required.';
        }

        if ($aidType === 'money') {
            if ($amount === '' || !is_numeric($amount) || (int)$amount <= 0) {
                $errors[] = 'Valid amount is required for money aid requests.';
            }
        }

        if ($aidType === 'other') {
            if ($otherAidType === '' || strlen($otherAidType) < 3) {
                $errors[] = 'Please specify the aid needed when selecting Other.';
            }
        }

        if ($reason === '') {
            $errors[] = 'Reason is required.';
        }

        $basePath = dirname(__DIR__, 3);
        $uploadDir = $basePath . '/public/uploads/aid-requests';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $studentIdPdfPath = (string)($request->student_id_pdf_path ?? '');
        $incomeStatementPath = (string)($request->income_statement_path ?? '');
        $gramasevaCertPath = (string)($request->gramaseva_cert_path ?? '');

        $newStudentIdPdf = $this->saveOptionalFile($_FILES['student_id_pdf'] ?? [], $uploadDir, ['pdf', 'jpg', 'jpeg', 'png']);
        $newIncomeStatement = $this->saveOptionalFile($_FILES['income_statement'] ?? [], $uploadDir, ['pdf']);
        $newGramasevaCert = $this->saveOptionalFile($_FILES['gramaseva_certificate'] ?? [], $uploadDir, ['pdf']);

        if ($newStudentIdPdf === false || $newIncomeStatement === false || $newGramasevaCert === false) {
            $errors[] = 'One or more uploaded files are invalid. Please use allowed file types.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => implode(' | ', $errors),
            ];
            redirect('student/aidrequests');
        }

        $reasonText = $reason;
        if ($aidType === 'other' && $otherAidType !== '') {
            $specifiedItem = preg_replace('/\s+/', ' ', trim($otherAidType));
            $reasonText = 'Requested item (' . $specifiedItem . '): ' . $reason;
        }

        if (is_string($newStudentIdPdf) && $newStudentIdPdf !== '') {
            $this->removeUploadedFileIfLocal($studentIdPdfPath);
            $studentIdPdfPath = $newStudentIdPdf;
        }
        if (is_string($newIncomeStatement) && $newIncomeStatement !== '') {
            $this->removeUploadedFileIfLocal($incomeStatementPath);
            $incomeStatementPath = $newIncomeStatement;
        }
        if (is_string($newGramasevaCert) && $newGramasevaCert !== '') {
            $this->removeUploadedFileIfLocal($gramasevaCertPath);
            $gramasevaCertPath = $newGramasevaCert;
        }

        $requestModel = new Request();

        $updatedAid = $requestModel->query(
            "UPDATE aid_requests
             SET mobile_number = :mobile_number,
                 aid_type = :aid_type,
                 amount = :amount,
                 reason = :reason,
                 student_id_pdf_path = :student_id_pdf_path,
                 income_statement_path = :income_statement_path,
                 gramaseva_cert_path = :gramaseva_cert_path
             WHERE request_id = :request_id",
            [
                'mobile_number' => $mobileNumber,
                'aid_type' => $aidType,
                'amount' => $aidType === 'money' ? (int)$amount : null,
                'reason' => $reasonText,
                'student_id_pdf_path' => $studentIdPdfPath,
                'income_statement_path' => $incomeStatementPath,
                'gramaseva_cert_path' => $gramasevaCertPath,
                'request_id' => (int)$requestId,
            ]
        );

        if ($updatedAid === false) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Failed to update aid request details.',
            ];
            redirect('student/aidrequests');
        }

        // Keep the request in counsellor pending queue and bump recency so edits are visible.
        $requestModel->query(
            "UPDATE requests
             SET created_at = NOW()
             WHERE request_id = :request_id
               AND student_user_id = :student_user_id
               AND request_type = 'aid'
               AND status = 'pending_verification'",
            [
                'request_id' => (int)$requestId,
                'student_user_id' => (int)$studentUserId,
            ]
        );

        try {
            $nextLogIdRow = $requestModel->get_row("SELECT COALESCE(MAX(log_id), 0) + 1 AS next_id FROM request_logs");
            $nextLogId = isset($nextLogIdRow->next_id) ? (int)$nextLogIdRow->next_id : 1;

            $requestModel->query(
                "INSERT INTO request_logs (log_id, request_id, actor_user_id, action, log_timestamp, notes)
                 VALUES (:log_id, :request_id, :actor_user_id, :action, NOW(), :notes)",
                [
                    'log_id' => $nextLogId,
                    'request_id' => (int)$requestId,
                    'actor_user_id' => (int)$studentUserId,
                    'action' => 'STUDENT_EDITED',
                    'notes' => '[STUDENT EDITED] Aid request details were updated by student before counsellor approval.',
                ]
            );
        } catch (Throwable $e) {
            error_log('Aid request edit log failed: ' . $e->getMessage());
        }

        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => 'Aid request updated and re-submitted for counsellor review.',
        ];
        redirect('student/aidrequests');
    }

    public function delete($requestId = null)
    {
        $studentUserId = $this->getAuthenticatedStudentUserId();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$requestId || !is_numeric($requestId)) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Invalid aid request delete action.',
            ];
            redirect('student/aidrequests');
        }

        $request = $this->getOwnedAidRequest((int)$requestId, $studentUserId);
        if (!$request || !$this->ensurePendingBeforeStudentChange($request)) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Only counsellor-pending requests can be deleted.',
            ];
            redirect('student/aidrequests');
        }

        $requestModel = new Request();
        $deletedAid = $requestModel->query("DELETE FROM aid_requests WHERE request_id = :request_id", [
            'request_id' => (int)$requestId,
        ]);

        $deletedRequest = $requestModel->query(
            "DELETE FROM requests
             WHERE request_id = :request_id
               AND student_user_id = :student_user_id
               AND request_type = 'aid'
               AND status = 'pending_verification'",
            [
                'request_id' => (int)$requestId,
                'student_user_id' => (int)$studentUserId,
            ]
        );

        if ($deletedAid === false || $deletedRequest === false) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Failed to delete aid request.',
            ];
            redirect('student/aidrequests');
        }

        $this->removeUploadedFileIfLocal($request->student_id_pdf_path ?? '');
        $this->removeUploadedFileIfLocal($request->income_statement_path ?? '');
        $this->removeUploadedFileIfLocal($request->gramaseva_cert_path ?? '');

        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => 'Aid request deleted successfully.',
        ];
        redirect('student/aidrequests');
    }
}