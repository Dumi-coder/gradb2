<?php
session_start();
require_once __DIR__ . '/config.php';

$userId = $_SESSION['reset_user_id'] ?? null;
$otpVerified = $_SESSION['otp_verified'] ?? false;

$message = '';

if (!$userId || !$otpVerified) {
    $message = 'Unauthorized. Please verify OTP first.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId && $otpVerified) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $message = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $mysqli = db_connect();
        $stmt = $mysqli->prepare('UPDATE users SET password = ? WHERE user_id = ?');
        $stmt->bind_param('si', $hash, $userId);
        $stmt->execute();
        $stmt->close();

        // Cleanup all reset records for this user
        $stmt = $mysqli->prepare('DELETE FROM password_resets WHERE user_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();

        $mysqli->close();

        unset($_SESSION['otp_verified'], $_SESSION['reset_user_id'], $_SESSION['reset_email']);

        $message = 'Password reset successful. You can now log in.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if ($userId && $otpVerified): ?>
    <form action="reset_password.php" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
</body>
</html>
