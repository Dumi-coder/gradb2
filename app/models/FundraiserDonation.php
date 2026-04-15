<?php

class FundraiserDonation
{
    use Model;

    private function normalizeMoneyValue($value)
    {
        $raw = trim((string)$value);
        if ($raw === '' || !preg_match('/^\d+(?:\.\d{1,2})?$/', $raw)) {
            return null;
        }

        $parts = explode('.', $raw, 2);
        $whole = ltrim($parts[0], '0');
        if ($whole === '') {
            $whole = '0';
        }

        $fraction = str_pad($parts[1] ?? '', 2, '0');
        return $whole . '.' . substr($fraction, 0, 2);
    }

    protected $table = 'fundraiser_donations';
    protected $id_column = 'donation_id';
    protected $order_column = 'created_at';
    protected $order_type = 'desc';

    protected $allowedColumns = [
        'fundraiser_id',
        'donor_user_id',
        'amount',
        'currency',
        'status',
        'created_at',
        'captured_at',
    ];

    public function createPendingDonation($fundraiserId, $donorUserId, $amount, $currency = 'LKR')
    {
        $con = $this->connect();
        $normalizedAmount = $this->normalizeMoneyValue($amount);

        if ($normalizedAmount === null || (int)str_replace('.', '', $normalizedAmount) <= 0) {
            return ['success' => false, 'message' => 'Invalid donation amount'];
        }

        $stmt = $con->prepare(
            "INSERT INTO fundraiser_donations
            (fundraiser_id, donor_user_id, amount, currency, status, created_at)
            VALUES
            (:fundraiser_id, :donor_user_id, :amount, :currency, 'created', :created_at)"
        );

        $ok = $stmt->execute([
            'fundraiser_id' => (int)$fundraiserId,
            'donor_user_id' => (int)$donorUserId,
            'amount' => $normalizedAmount,
            'currency' => strtoupper((string)$currency),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$ok) {
            return ['success' => false, 'message' => 'Could not create donation'];
        }

        return [
            'success' => true,
            'donation_id' => (int)$con->lastInsertId(),
        ];
    }

    public function saveGatewayOrder($gatewayOrderId, $donationId, $rawJson = null)
    {
        return $this->query(
            "INSERT INTO paypal_orders
            (paypal_order_id, donation_id, raw_json, created_at, updated_at)
            VALUES
            (:paypal_order_id, :donation_id, :raw_json, NOW(), NOW())",
            [
                'paypal_order_id' => (string)$gatewayOrderId,
                'donation_id' => (int)$donationId,
                'raw_json' => $rawJson,
            ]
        ) !== false;
    }

    public function getDonationByOrderId($gatewayOrderId)
    {
        $row = $this->get_row(
            "SELECT d.*
             FROM fundraiser_donations d
             INNER JOIN paypal_orders po ON po.donation_id = d.donation_id
             WHERE po.paypal_order_id = :paypal_order_id
             LIMIT 1",
            ['paypal_order_id' => (string)$gatewayOrderId]
        );

        return $row ?: false;
    }

    public function markCapturedByOrder($gatewayOrderId, $transactionId, $payerEmail = null, $rawJson = null)
    {
        $con = $this->connect();
        $con->beginTransaction();

        try {
            $donation = $this->get_row(
                "SELECT d.donation_id, d.status
                 FROM fundraiser_donations d
                 INNER JOIN paypal_orders po ON po.donation_id = d.donation_id
                 WHERE po.paypal_order_id = :paypal_order_id
                 LIMIT 1",
                ['paypal_order_id' => (string)$gatewayOrderId]
            );

            if (!$donation) {
                $con->rollBack();
                return ['success' => false, 'message' => 'Donation not found for order'];
            }

            if ((string)$donation->status === 'captured') {
                $con->rollBack();
                return ['success' => true, 'already_captured' => true];
            }

            $updateDonation = $con->prepare(
                "UPDATE fundraiser_donations
                 SET status = 'captured',
                     captured_at = NOW()
                 WHERE donation_id = :donation_id"
            );
            $updateDonation->execute(['donation_id' => (int)$donation->donation_id]);

            $updateOrder = $con->prepare(
                "UPDATE paypal_orders
                 SET paypal_capture_id = :paypal_capture_id,
                     payer_email = :payer_email,
                     raw_json = :raw_json,
                     updated_at = NOW()
                 WHERE paypal_order_id = :paypal_order_id"
            );
            $updateOrder->execute([
                'paypal_capture_id' => (string)$transactionId,
                'payer_email' => $payerEmail ? (string)$payerEmail : null,
                'raw_json' => $rawJson,
                'paypal_order_id' => (string)$gatewayOrderId,
            ]);

            $con->commit();
            return ['success' => true, 'donation_id' => (int)$donation->donation_id];
        } catch (Throwable $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function markFailedByOrder($gatewayOrderId, $rawJson = null)
    {
        return $this->query(
            "UPDATE fundraiser_donations d
             INNER JOIN paypal_orders po ON po.donation_id = d.donation_id
             SET d.status = 'failed',
                 po.raw_json = :raw_json,
                 po.updated_at = NOW()
             WHERE po.paypal_order_id = :paypal_order_id
               AND d.status = 'created'",
            [
                'raw_json' => $rawJson,
                'paypal_order_id' => (string)$gatewayOrderId,
            ]
        ) !== false;
    }

    public function getRecentDonationsByUser($userId, $limit = 10)
    {
        $limit = max(1, (int)$limit);
        $query = "SELECT d.*, f.title AS fundraiser_title
                  FROM fundraiser_donations d
                  INNER JOIN fundraisers f ON f.fundraiser_id = d.fundraiser_id
                  WHERE d.donor_user_id = :donor_user_id
                    AND d.status = 'captured'
                  ORDER BY d.captured_at DESC
                  LIMIT {$limit}";

        $rows = $this->query($query, ['donor_user_id' => (int)$userId]);
        return is_array($rows) ? $rows : [];
    }
}
