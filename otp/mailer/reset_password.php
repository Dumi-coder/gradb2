<?php
session_start();
require_once __DIR__ . '/db.php';

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
        $pdo = db_connect();
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hash, $userId]);

        $del = $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?');
        $del->execute([$userId]);

        unset($_SESSION['otp_verified'], $_SESSION['reset_user_id']);
        header('Location: forgot_password.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
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
        <h2>Reset Password</h2>
        <?php if ($message): ?>
            <p class="msg"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if ($userId && $otpVerified): ?>
        <form action="reset_password.php" method="post">
            <label for="password">New Password</label>
            <input class="input" type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input class="input" type="password" id="confirm_password" name="confirm_password" required>

            <button class="btn" type="submit">Reset Password</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
