<?php

class AidReqForm extends Controller
{
    public function index()
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

        $studentModel = new Student();
        $studentInfo = $studentModel->first(['user_id' => $userId]);

        if (!$studentInfo) {
            redirect('student/auth');
        }

        $studentProfile = $studentModel->getStudentProfile($studentInfo->student_id);
        if (!$studentProfile) {
            $studentProfile = $studentInfo;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->submitForm($studentProfile);
            return;
        }

        $this->view('student/aid-requests-form', [
            'studentInfo' => $studentProfile,
            'formData' => []
        ]);
    }

    private function submitForm($studentInfo)
    {
        $formData = [
            'mobile_number' => trim($_POST['mobile_number'] ?? ''),
            'aid_type' => trim($_POST['aid_type'] ?? ''),
            'amount' => trim($_POST['amount'] ?? ''),
            'reason' => trim($_POST['reason'] ?? ''),
        ];

        $errors = [];

        if ($formData['mobile_number'] === '' || !preg_match('/^[0-9]{10}$/', $formData['mobile_number'])) {
            $errors[] = 'Mobile number must be 10 digits (07XXXXXXXX)';
        }

        $allowedAidTypes = ['money', 'laptop', 'textbooks', 'stationery', 'other'];
        if (!in_array($formData['aid_type'], $allowedAidTypes, true)) {
            $errors[] = 'Type of aid request is required';
        }

        if ($formData['aid_type'] === 'money') {
            if ($formData['amount'] === '' || !is_numeric($formData['amount']) || (int)$formData['amount'] <= 0) {
                $errors[] = 'Valid amount is required for money aid requests';
            }
        }

        if ($formData['reason'] === '') {
            $errors[] = 'Reason is required';
        }

        if (!$this->isValidUpload('student_id_pdf', ['pdf', 'jpg', 'jpeg', 'png'])) {
            $errors[] = 'Student ID file is required (PDF, JPG, JPEG, PNG)';
        }

        if (!$this->isValidUpload('income_statement', ['pdf'])) {
            $errors[] = 'Income statement is required (PDF only)';
        }

        if (!$this->isValidUpload('gramaseva_certificate', ['pdf'])) {
            $errors[] = 'Gramaseva Niladhari certificate is required (PDF only)';
        }

        if (!empty($errors)) {
            $this->view('student/aid-requests-form', [
                'studentInfo' => $studentInfo,
                'formData' => $formData,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => implode(' | ', $errors),
                ],
            ]);
            return;
        }

        $basePath = dirname(__DIR__, 3);
        $uploadDir = $basePath . '/public/uploads/aid-requests';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $studentIdPdf = $this->saveFile($_FILES['student_id_pdf'], $uploadDir, ['pdf', 'jpg', 'jpeg', 'png']);
        $incomeStatement = $this->saveFile($_FILES['income_statement'], $uploadDir, ['pdf']);
        $gramasevaCertificate = $this->saveFile($_FILES['gramaseva_certificate'], $uploadDir, ['pdf']);

        if (!$studentIdPdf || !$incomeStatement || !$gramasevaCertificate) {
            $this->view('student/aid-requests-form', [
                'studentInfo' => $studentInfo,
                'formData' => $formData,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'File upload failed. Please try again with valid PDF files.',
                ],
            ]);
            return;
        }

        $requestModel = new Request();
        $inserted = $requestModel->insert([
            'student_user_id' => $_SESSION['user_id'] ?? ($_SESSION['USER']->user_id ?? null),
            'request_type' => 'aid',
            'status' => 'pending_verification',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$inserted) {
            $this->view('student/aid-requests-form', [
                'studentInfo' => $studentInfo,
                'formData' => $formData,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'Failed to create aid request. Please try again.',
                ],
            ]);
            return;
        }

        $latestRequest = $requestModel->get_row(
            'SELECT request_id FROM requests WHERE student_user_id = :student_user_id ORDER BY request_id DESC LIMIT 1',
            ['student_user_id' => $_SESSION['user_id'] ?? ($_SESSION['USER']->user_id ?? null)]
        );

        if (!$latestRequest) {
            $this->view('student/aid-requests-form', [
                'studentInfo' => $studentInfo,
                'formData' => $formData,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'Aid request created but details could not be saved.',
                ],
            ]);
            return;
        }

        $aidRequestModel = new AidRequest();
        $savedDetails = $aidRequestModel->insert([
            'request_id' => (int)$latestRequest->request_id,
            'mobile_number' => $formData['mobile_number'],
            'aid_type' => $formData['aid_type'],
            'amount' => $formData['aid_type'] === 'money' ? (int)$formData['amount'] : null,
            'reason' => $formData['reason'],
            'student_id_pdf_path' => $studentIdPdf,
            'income_statement_path' => $incomeStatement,
            'gramaseva_cert_path' => $gramasevaCertificate,
        ]);

        if (!$savedDetails) {
            $this->view('student/aid-requests-form', [
                'studentInfo' => $studentInfo,
                'formData' => $formData,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'Request saved, but supporting details failed to save.',
                ],
            ]);
            return;
        }

        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => 'Aid request submitted successfully. Counselor verification is pending.',
        ];

        redirect('student/dashboard');
    }

    private function isValidUpload($fieldName, $allowedExtensions)
    {
        if (!isset($_FILES[$fieldName])) {
            return false;
        }

        $file = $_FILES[$fieldName];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($extension, $allowedExtensions, true);
    }

    private function saveFile($file, $uploadDir, $allowedExtensions)
    {
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
}
