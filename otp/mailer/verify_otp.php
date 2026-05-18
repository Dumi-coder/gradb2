<?php
session_start();
require_once __DIR__ . '/db.php';

$message = '';
$userId = $_SESSION['reset_user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');

    if (!$userId) {
        $message = 'Invalid or expired OTP.';
    } elseif (!preg_match('/^[0-9]{6}$/', $otp)) {
        $message = 'Invalid or expired OTP.';
    } else {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT id, otp, expires_at, attempts FROM password_resets WHERE user_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        if (!$row) {
            $message = 'Invalid or expired OTP.';
        } elseif (is_expired_utc($row['expires_at'])) {
            $message = 'Invalid or expired OTP.';
        } elseif ((int)$row['attempts'] >= OTP_MAX_ATTEMPTS) {
            $message = 'Too many attempts. Please request a new OTP.';
        } elseif (!password_verify($otp, $row['otp'])) {
            $attempts = (int)$row['attempts'] + 1;
            $upd = $pdo->prepare('UPDATE password_resets SET attempts = ? WHERE id = ?');
            $upd->execute([$attempts, $row['id']]);
            $message = 'Invalid or expired OTP.';
        } else {
            $_SESSION['otp_verified'] = true;
            header('Location: reset_password.php');
            exit;
        }
    }
}

function is_expired_utc(string $expiresAt): bool
{
    try {
        $expiry = new DateTime($expiresAt, new DateTimeZone('UTC'));
        $now = new DateTime('now', new DateTimeZone('UTC'));
        return $expiry < $now;
    } catch (Exception $e) {
        return true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f6f6f6; }
        .card { max-width: 420px; margin: 50px auto; background:#fff; padding:24px; border-radius:8px; }
        .input { width:100%; padding:10px; margin:10px 0; }
        .btn { width:100%; padding:10px; background:#2d6cdf; color:#fff; border:0; border-radius:4px; }
        .msg { color:#b00020; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Verify OTP</h2>
        <?php if ($message): ?>
            <p class="msg"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form action="verify_otp.php" method="post">
            <label for="otp">OTP</label>
            <input class="input" type="text" id="otp" name="otp" maxlength="6" required>
            <button class="btn" type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
