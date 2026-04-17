<?php
session_start();
require_once __DIR__ . '/../otp/config.php';

$userId = $_SESSION['reset_user_id'] ?? null;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';

    if (!$userId) {
        $message = 'Session expired. Please request a new OTP.';
    } elseif (!preg_match('/^[0-9]{6}$/', $otp)) {
        $message = 'Invalid OTP format.';
    } else {
        $mysqli = db_connect();

        $stmt = $mysqli->prepare('SELECT id, otp_hash, expires_at, attempts FROM password_resets WHERE user_id = ? AND used_at IS NULL ORDER BY created_at DESC LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            $message = 'No active OTP found. Please request a new OTP.';
        } else {
            $expired = strtotime($row['expires_at']) < time();
            if ($expired) {
                $message = 'OTP expired. Please request a new OTP.';
            } else {
                $attempts = (int)$row['attempts'];
                if ($attempts >= OTP_MAX_ATTEMPTS) {
                    $message = 'Too many attempts. Please request a new OTP.';
                } elseif (verify_otp($otp, $row['otp_hash'])) {
                    // Mark as used
                    $stmt = $mysqli->prepare('UPDATE password_resets SET used_at = ? WHERE id = ?');
                    $now = now_utc();
                    $stmt->bind_param('si', $now, $row['id']);
                    $stmt->execute();
                    $stmt->close();

                    $_SESSION['otp_verified'] = true;
                    $_SESSION['reset_user_id'] = $userId;

                    $mysqli->close();
                    header('Location: reset_password.php');
                    exit;
                } else {
                    $attempts++;
                    $stmt = $mysqli->prepare('UPDATE password_resets SET attempts = ? WHERE id = ?');
                    $stmt->bind_param('ii', $attempts, $row['id']);
                    $stmt->execute();
                    $stmt->close();

                    $message = 'Incorrect OTP.';
                }
            }
        }

        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
</head>
<body>
    <h2>Verify OTP</h2>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <form action="verify_otp.php" method="post">
        <label for="otp">Enter OTP:</label><br>
        <input type="text" id="otp" name="otp" maxlength="6" required><br><br>
        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>
