<?php

class PayHereGateway
{
    private $secretSource = 'raw';

    private function getConfiguredMerchantSecret()
    {
        return trim((string)PAYHERE_MERCHANT_SECRET);
    }

    private function getMerchantSecret()
    {
        $secret = $this->getConfiguredMerchantSecret();
        if ($secret === '') {
            $this->secretSource = 'empty';
            return '';
        }

        $decoded = base64_decode($secret, true);
        if ($decoded === false || $decoded === '') {
            $this->secretSource = 'raw';
            return $secret;
        }

        // Support configs where the copied secret was base64-encoded before storing.
        if (preg_match('/^[A-Za-z0-9._-]{16,}$/', $decoded) === 1) {
            $this->secretSource = 'base64-decoded';
            return $decoded;
        }

        $this->secretSource = 'raw';
        return $secret;
    }

    private function getHashedMerchantSecret()
    {
        return strtoupper(md5($this->getMerchantSecret()));
    }

    public function isConfigured()
    {
        return trim((string)PAYHERE_MERCHANT_ID) !== '' && trim((string)PAYHERE_MERCHANT_SECRET) !== '';
    }

    public function createOrderId($donationId)
    {
        return 'PH_' . (int)$donationId . '_' . time();
    }

    private function splitCustomerName($customerName)
    {
        $name = trim((string)$customerName);
        if ($name === '') {
            return ['Donor', 'Supporter'];
        }

        $parts = preg_split('/\s+/', $name);
        $firstName = trim((string)($parts[0] ?? 'Donor'));
        $lastName = trim((string)implode(' ', array_slice($parts, 1)));

        return [$firstName !== '' ? $firstName : 'Donor', $lastName !== '' ? $lastName : 'Supporter'];
    }

    private function normalizePhone($customerPhone)
    {
        $digits = preg_replace('/\D+/', '', (string)$customerPhone);
        return $digits !== '' ? $digits : '0770000000';
    }

    public function createCheckoutPayload($orderId, $amount, $currency, $customerName, $customerEmail, $roleSegment = 'student', $customerPhone = '')
    {
        $amountFormatted = number_format((float)$amount, 2, '.', '');
        $currencyCode = strtoupper((string)$currency);
        $merchantSecretHash = $this->getHashedMerchantSecret();
        $hash = strtoupper(md5(PAYHERE_MERCHANT_ID . $orderId . $amountFormatted . $currencyCode . $merchantSecretHash));
        [$firstName, $lastName] = $this->splitCustomerName($customerName);
        $phone = $this->normalizePhone($customerPhone);

        $payload = [
            'sandbox' => PAYHERE_MODE !== 'live',
            'merchant_id' => PAYHERE_MERCHANT_ID,
            'return_url' => ROOT . '/' . $roleSegment . '/fundraising?payment=success',
            'cancel_url' => ROOT . '/' . $roleSegment . '/fundraising?payment=cancel',
            'notify_url' => ROOT . '/' . $roleSegment . '/fundraising/payhere_notify',
            'order_id' => $orderId,
            'items' => 'Fundraiser donation',
            'currency' => $currencyCode,
            'amount' => $amountFormatted,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $customerEmail ?: 'donor@example.com',
            'phone' => $phone,
            'address' => 'N/A',
            'city' => 'N/A',
            'country' => 'Sri Lanka',
            'custom_1' => 'fundraiser_donation',
            'custom_2' => $customerEmail,
            'hash' => $hash,
        ];

        if (defined('DEBUG') && DEBUG) {
            error_log(sprintf(
                'PayHere debug: mode=%s merchant_id=%s secret_source=%s order_id=%s hash=%s',
                PAYHERE_MODE,
                (string)PAYHERE_MERCHANT_ID,
                $this->secretSource,
                (string)$orderId,
                $hash
            ));
        }

        return $payload;
    }

    public function validateNotifySignature(array $data)
    {
        $merchantId = (string)($data['merchant_id'] ?? '');
        $orderId = (string)($data['order_id'] ?? '');
        $amount = (string)($data['payhere_amount'] ?? '');
        $currency = strtoupper((string)($data['payhere_currency'] ?? ''));
        $statusCode = (string)($data['status_code'] ?? '');
        $receivedSig = strtoupper((string)($data['md5sig'] ?? ''));

        if (
            $merchantId === '' || $orderId === '' || $amount === '' ||
            $currency === '' || $statusCode === '' || $receivedSig === ''
        ) {
            return false;
        }

        $localSig = strtoupper(md5(
            $merchantId .
            $orderId .
            $amount .
            $currency .
            $statusCode .
            $this->getHashedMerchantSecret()
        ));

        return hash_equals($localSig, $receivedSig);
    }
}
