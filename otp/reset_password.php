<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';
$success = false;
$showResetForm = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['stage'] ?? '') === 'verify_identity') {
    $identifier = trim($_POST['identifier'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!ctype_digit($identifier)) {
        safe_sleep_ms(200, 400);
        $error = 'Please enter a valid ID.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $mysqli = db_connect();
        $stmt = $mysqli->prepare('SELECT user_id, email FROM users WHERE user_id = ? AND email = ? LIMIT 1');
        $uid = (int)$identifier;
        $stmt->bind_param('is', $uid, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $mysqli->close();

        if (!$user) {
            safe_sleep_ms(200, 400);
            $error = 'No account found for the provided ID and email.';
        } else {
            $_SESSION['reset_user_id'] = (int)$user['user_id'];
            $_SESSION['reset_email'] = (string)$user['email'];
            $_SESSION['reset_verified'] = true;
            $showResetForm = true;
        }
    }
}

$userId = $_SESSION['reset_user_id'] ?? null;
$resetVerified = $_SESSION['reset_verified'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['stage'] ?? '') === 'reset_password') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$userId || !$resetVerified) {
        $error = 'Session expired. Please start again.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $mysqli = db_connect();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare('UPDATE users SET password = ? WHERE user_id = ?');
        $uid = (int)$userId;
        $stmt->bind_param('si', $hash, $uid);
        $stmt->execute();
        $stmt->close();

        $mysqli->close();

        unset($_SESSION['reset_verified'], $_SESSION['reset_user_id'], $_SESSION['reset_email']);
        $success = true;
    }
}

if (!$showResetForm && $userId && $resetVerified && !$success) {
    $showResetForm = true;
}

if (!$showResetForm && !$success && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot.php');
    exit;
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
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green;">Password reset successful.</p>
        <p><a href="public/Login">Go to Login</a></p>
    <?php elseif ($showResetForm): ?>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="stage" value="reset_password">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
    <?php else: ?>
        <form action="reset_password.php" method="post">
            <input type="hidden" name="stage" value="verify_identity">
            <label for="identifier">User ID:</label><br>
            <input type="text" id="identifier" name="identifier" required><br><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit">Continue</button>
        </form>
    <?php endif; ?>
</body>
</html>
