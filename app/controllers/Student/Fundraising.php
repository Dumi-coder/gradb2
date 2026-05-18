
<?php
class Fundraising extends Controller
{
    private function normalizeMoneyInput($value)
    {
        $raw = trim((string)$value);
        if ($raw === '') {
            return null;
        }

        if (!preg_match('/^\d+(?:\.\d{1,2})?$/', $raw)) {
            return null;
        }

        $parts = explode('.', $raw, 2);
        $whole = ltrim($parts[0], '0');
        if ($whole === '') {
            $whole = '0';
        }

        $fraction = $parts[1] ?? '';
        $fraction = str_pad($fraction, 2, '0');

        return $whole . '.' . substr($fraction, 0, 2);
    }

    private function getOtpPhoneLast3($userId)
    {
        try {
            $userModel = new User();
            $user = $userModel->first(['user_id' => (int)$userId]);

            $studentModel = new Student();
            $student = $studentModel->first(['user_id' => (int)$userId]);

            $rawMobile = (string)($user->mobile ?? ($user->phone ?? ($student->mobile ?? '')));
            $digits = preg_replace('/\D+/', '', $rawMobile);
            if ($digits === '') {
                return '000';
            }
            return substr(str_pad($digits, 3, '0', STR_PAD_LEFT), -3);
        } catch (Throwable $e) {
            return '000';
        }
    }

    private function isApiRequest()
    {
        $requestedWith = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
        if ($requestedWith === 'xmlhttprequest') {
            return true;
        }

        $accept = strtolower((string)($_SERVER['HTTP_ACCEPT'] ?? ''));
        if (strpos($accept, 'application/json') !== false) {
            return true;
        }

        $uri = strtolower((string)($_SERVER['REQUEST_URI'] ?? ''));
        return strpos($uri, '/demo_create_payment') !== false;
    }

    private function requireStudent()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $role = strtolower((string)($_SESSION['role'] ?? ($_SESSION['USER']->role ?? '')));
        $userId = (int)($_SESSION['user_id'] ?? ($_SESSION['USER']->user_id ?? 0));
        if ($userId <= 0 || $role !== 'student') {
            if ($this->isApiRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Authentication required'], 401);
            }
            redirect('student/auth');
        }
        return $userId;
    }

    private function jsonResponse(array $payload, $statusCode = 200)
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        http_response_code((int)$statusCode);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }

    public function index()
    {
        $userId = $this->requireStudent();
        $showAllCampaigns = strtolower(trim((string)($_GET['view'] ?? ''))) === 'all';

        $fundraiserModel = new Fundraiser();
        $donationModel = new FundraiserDonation();
        $otpPhoneLast3 = $this->getOtpPhoneLast3($userId);

        $this->view('student/fundraising', [
            'myFundraisers' => $fundraiserModel->getByCreator($userId),
            'campaigns' => $fundraiserModel->getApprovedForFeed($userId),
            'recentFundraisers' => $fundraiserModel->getRecentStoppedForFeed(null, $userId),
            'myDonations' => $donationModel->getRecentDonationsByUser($userId, 20),
            'showAllCampaigns' => $showAllCampaigns,
            'otpPhoneLast3' => $otpPhoneLast3,
            'paymentMode' => FUNDRAISING_PAYMENT_MODE,
            'flash' => $_SESSION['flash_message'] ?? null,
        ]);

        unset($_SESSION['flash_message']);
    }

    public function create()
    {
        $userId = $this->requireStudent();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('student/fundraising');
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $targetAmount = trim((string)($_POST['target_amount'] ?? ''));
        $targetAmountNormalized = $this->normalizeMoneyInput($targetAmount);
        $currency = strtoupper(trim((string)($_POST['currency'] ?? 'LKR')));

        $errors = [];
        if ($title === '' || strlen($title) < 8) {
            $errors[] = 'Fundraiser title must be at least 8 characters.';
        }
        if ($description === '' || strlen($description) < 20) {
            $errors[] = 'Description is required and should be detailed.';
        }
        if ($targetAmountNormalized === null || (int)str_replace('.', '', $targetAmountNormalized) < 100) {
            $errors[] = 'Please provide a valid target amount.';
        }
        if ($currency === '' || strlen($currency) !== 3) {
            $errors[] = 'Currency must be a 3-letter code.';
        }

        $documents = $this->collectUploadedDocuments($errors);

        if (!empty($errors)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => implode(' ', $errors)];
            redirect('student/fundraising');
        }

        $fundraiserModel = new Fundraiser();
        $result = $fundraiserModel->createWithDocuments([
            'creator_user_id' => $userId,
            'title' => $title,
            // Keep writing reasoning for backward-compatible schema.
            'reasoning' => $description,
            'description' => $description,
            'target_amount' => $targetAmountNormalized,
            'currency' => $currency,
            'status' => 'pending',
        ], $documents);

        if (!$result['success']) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Failed to create fundraiser request.'];
            redirect('student/fundraising');
        }

        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Fundraiser submitted for admin review.'];
        redirect('student/fundraising');
    }

    private function createDemoOrderId($donationId)
    {
        return 'DEMO_' . (int)$donationId . '_' . time();
    }

    public function demo_create_payment()
    {
        try {
            $userId = $this->requireStudent();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            }

            $fundraiserId = (int)($_POST['fundraiser_id'] ?? 0);
            $amountRaw = (string)($_POST['amount'] ?? '');
            $amount = $this->normalizeMoneyInput($amountRaw);
            $currency = strtoupper(trim((string)($_POST['currency'] ?? 'LKR')));

            if ($fundraiserId <= 0 || $amount === null || (int)str_replace('.', '', $amount) <= 0) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid fundraiser or amount'], 422);
            }

            $fundraiserModel = new Fundraiser();
            $campaign = $fundraiserModel->getByIdWithStats($fundraiserId);
            if (!$campaign || (string)$campaign->status !== 'approved') {
                $this->jsonResponse(['success' => false, 'message' => 'Fundraiser is not available for donations'], 404);
            }
            if ((int)$campaign->creator_user_id === $userId) {
                $this->jsonResponse(['success' => false, 'message' => 'You cannot donate to your own fundraiser'], 403);
            }

            $donationModel = new FundraiserDonation();
            $createDonation = $donationModel->createPendingDonation($fundraiserId, $userId, $amount, $currency);
            if (!$createDonation['success']) {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to initialize donation'], 500);
            }

            $orderId = $this->createDemoOrderId((int)$createDonation['donation_id']);
            $payload = [
                'mode' => 'demo',
                'order_id' => $orderId,
                'fundraiser_id' => $fundraiserId,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'captured',
            ];

            if (!$donationModel->saveGatewayOrder($orderId, $createDonation['donation_id'], json_encode($payload))) {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to save demo payment record'], 500);
            }

            $captureResult = $donationModel->markCapturedByOrder(
                $orderId,
                'DEMO_CAPTURE_' . (int)$createDonation['donation_id'],
                null,
                json_encode($payload)
            );

            if (empty($captureResult['success'])) {
                $this->jsonResponse(['success' => false, 'message' => $captureResult['message'] ?? 'Demo payment could not be completed'], 500);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Demo donation completed successfully.',
                'order_id' => $orderId,
                'amount' => $amount,
                'currency' => $currency,
            ]);
        } catch (Throwable $e) {
            $message = DEBUG ? ('Unexpected server error: ' . $e->getMessage()) : 'Unexpected server error';
            $this->jsonResponse(['success' => false, 'message' => $message], 500);
        }
    }

    private function collectUploadedDocuments(array &$errors)
    {
        $documents = [];
        $files = $_FILES['supporting_documents'] ?? null;

        if (!$files || !isset($files['name']) || !is_array($files['name'])) {
            $errors[] = 'At least one supporting document is required.';
            return [];
        }

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $uploadDir = APPROOT . '/public/uploads/fundraisers';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($files['name'] as $index => $originalName) {
            $error = $files['error'][$index] ?? UPLOAD_ERR_NO_FILE;
            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if ($error !== UPLOAD_ERR_OK) {
                $errors[] = 'Failed to upload one of the documents.';
                continue;
            }

            $ext = strtolower(pathinfo((string)$originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions, true)) {
                $errors[] = 'Unsupported document type: ' . esc($originalName);
                continue;
            }

            $size = (int)($files['size'][$index] ?? 0);
            if ($size > 10 * 1024 * 1024) {
                $errors[] = 'Each document must be 10MB or less.';
                continue;
            }

            $uniqueName = uniqid('fundraiser_doc_', true) . '.' . $ext;
            $targetPath = $uploadDir . '/' . $uniqueName;

            if (!move_uploaded_file($files['tmp_name'][$index], $targetPath)) {
                $errors[] = 'Could not save uploaded document.';
                continue;
            }

            $documents[] = [
                'doc_type' => 'supporting',
                'file_path' => 'uploads/fundraisers/' . $uniqueName,
                'original_name' => (string)$originalName,
                'mime_type' => (string)($files['type'][$index] ?? ''),
                'size_bytes' => $size,
            ];
        }

        if (empty($documents)) {
            $errors[] = 'Please upload at least one valid supporting document.';
        }

        return $documents;
    }

    public function stop()
    {
        $userId = $this->requireStudent();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('student/fundraising');
        }
        $fundraiserId = (int)($_POST['fundraiser_id'] ?? 0);
        if ($fundraiserId <= 0) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Invalid fundraiser.'];
            redirect('student/fundraising');
        }
        $fundraiserModel = new Fundraiser();
        $success = $fundraiserModel->stopFundraiser($fundraiserId, $userId);
        if ($success) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Fundraiser closed successfully.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Unable to close fundraiser.'];
        }
        redirect('student/fundraising');
    }

    public function edit_pending()
    {
        $userId = $this->requireStudent();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('student/fundraising');
        }

        $fundraiserId = (int)($_POST['fundraiser_id'] ?? 0);
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $targetAmount = trim((string)($_POST['target_amount'] ?? ''));
        $targetAmountNormalized = $this->normalizeMoneyInput($targetAmount);
        $currency = strtoupper(trim((string)($_POST['currency'] ?? 'LKR')));

        $errors = [];
        if ($fundraiserId <= 0) {
            $errors[] = 'Invalid fundraiser.';
        }
        if ($title === '' || strlen($title) < 8) {
            $errors[] = 'Fundraiser title must be at least 8 characters.';
        }
        if ($description === '' || strlen($description) < 20) {
            $errors[] = 'Description is required and should be detailed.';
        }
        if ($targetAmountNormalized === null || (int)str_replace('.', '', $targetAmountNormalized) < 100) {
            $errors[] = 'Please provide a valid target amount.';
        }
        if ($currency === '' || strlen($currency) !== 3) {
            $errors[] = 'Currency must be a 3-letter code.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => implode(' ', $errors)];
            redirect('student/fundraising');
        }

        $fundraiserModel = new Fundraiser();
        $updated = $fundraiserModel->updatePendingByCreator($fundraiserId, $userId, [
            'title' => $title,
            'description' => $description,
            'target_amount' => $targetAmountNormalized,
            'currency' => $currency,
        ]);

        if ($updated) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Pending fundraiser updated successfully.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Only your pending fundraisers can be edited.'];
        }

        redirect('student/fundraising');
    }

    public function delete_pending()
    {
        $userId = $this->requireStudent();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('student/fundraising');
        }

        $fundraiserId = (int)($_POST['fundraiser_id'] ?? 0);
        if ($fundraiserId <= 0) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Invalid fundraiser.'];
            redirect('student/fundraising');
        }

        $fundraiserModel = new Fundraiser();
        $documents = $fundraiserModel->getDocuments($fundraiserId);
        $deleted = $fundraiserModel->deletePendingByCreator($fundraiserId, $userId);

        if ($deleted) {
            if (!empty($documents)) {
                foreach ($documents as $doc) {
                    $relativePath = ltrim((string)($doc->file_path ?? ''), '/');
                    if ($relativePath === '') {
                        continue;
                    }
                    $absolutePath = APPROOT . '/public/' . str_replace('\\', '/', $relativePath);
                    if (is_file($absolutePath)) {
                        @unlink($absolutePath);
                    }
                }
            }
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Pending fundraiser deleted successfully.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Only your pending fundraisers can be deleted.'];
        }

        redirect('student/fundraising');
    }
}
