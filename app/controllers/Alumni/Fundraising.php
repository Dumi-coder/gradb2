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

            $alumniModel = new Alumni();
            $alumni = $alumniModel->first(['user_id' => (int)$userId]);

            $rawMobile = (string)($user->mobile ?? ($user->phone ?? ($alumni->mobile ?? '')));
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

    private function requireAlumni()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $role = strtolower((string)($_SESSION['role'] ?? ($_SESSION['USER']->role ?? '')));
        $userId = (int)($_SESSION['user_id'] ?? ($_SESSION['USER']->user_id ?? 0));
        if ($userId <= 0 || $role !== 'alumni') {
            if ($this->isApiRequest()) {
                $this->jsonResponse(['success' => false, 'message' => 'Authentication required'], 401);
            }
            redirect('alumni/auth');
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
        $userId = $this->requireAlumni();

        $fundraiserModel = new Fundraiser();
        $donationModel = new FundraiserDonation();
        $otpPhoneLast3 = $this->getOtpPhoneLast3($userId);

        $this->view('alumni/fundraising', [
            'campaigns' => $fundraiserModel->getApprovedForFeed(),
            'recentFundraisers' => $fundraiserModel->getRecentStoppedForFeed(12),
            'myDonations' => $donationModel->getRecentDonationsByUser($userId, 20),
            'otpPhoneLast3' => $otpPhoneLast3,
            'paymentMode' => FUNDRAISING_PAYMENT_MODE,
            'flash' => $_SESSION['flash_message'] ?? null,
        ]);

        unset($_SESSION['flash_message']);
    }

    private function createDemoOrderId($donationId)
    {
        return 'DEMO_' . (int)$donationId . '_' . time();
    }

    public function demo_create_payment()
    {
        try {
            $userId = $this->requireAlumni();

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
}
