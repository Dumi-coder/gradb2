<?php
session_start();
require_once __DIR__ . '/db.php';

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userIdRaw = trim($_POST['user_id'] ?? '');
if (!ctype_digit($userIdRaw)) {
    header('Location: verify_otp.php');
    exit;
}

$userId = (int)$userIdRaw;
$pdo = db_connect();

$stmt = $pdo->prepare('SELECT id, email FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($user) {
    $otp = (string)random_int(100000, 999999);
    $otpHash = password_hash($otp, PASSWORD_DEFAULT);
    $expiresAt = add_minutes_utc(OTP_EXP_MINUTES);

    $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, otp, expires_at, attempts) VALUES (?, ?, ?, 0)');
    $stmt->execute([$userId, $otpHash, $expiresAt]);

    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host = SMTP_HOST;
        $mailer->SMTPAuth = true;
        $mailer->Username = SMTP_USER;
        $mailer->Password = SMTP_PASS;
        $mailer->SMTPSecure = SMTP_SECURE;
        $mailer->Port = SMTP_PORT;

        $mailer->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mailer->addAddress($user['email']);
        $mailer->Subject = 'Your ' . APP_NAME . ' password reset OTP';
        $mailer->Body = "Your OTP is: {$otp}\n\nThis code expires in " . OTP_EXP_MINUTES . " minutes.";

        $mailer->send();
        $_SESSION['reset_user_id'] = $userId;
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            error_log('[PHPMailer] Send failed: ' . $mailer->ErrorInfo);
        }
    }
}

header('Location: verify_otp.php');
exit;
