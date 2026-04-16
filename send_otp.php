<?php
session_start();
require_once __DIR__ . '/config.php';

$identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$isNumericId = ctype_digit($identifier);

if (!$isNumericId) {
    safe_sleep_ms(200, 400);
    echo 'Please enter a valid ID.';
    exit;
}

$mysqli = db_connect();

// Lookup user by id
$stmt = $mysqli->prepare('SELECT user_id, email FROM users WHERE user_id = ? LIMIT 1');
$id = (int)$identifier;
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    $userId = (int)$user['user_id'];
    $_SESSION['reset_user_id'] = $userId;
    $_SESSION['reset_email'] = $user['email'];

    if ($action === 'send') {
        // Rate limiting: check most recent request
        $stmt = $mysqli->prepare('SELECT created_at FROM password_resets WHERE user_id = ? ORDER BY created_at DESC LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $rateResult = $stmt->get_result();
        $last = $rateResult->fetch_assoc();
        $stmt->close();

        if ($last) {
            $lastTime = strtotime($last['created_at']);
            if ($lastTime && (time() - $lastTime) < OTP_RATE_LIMIT_SECONDS) {
                echo 'Please wait before requesting another OTP.';
                exit;
            }
        }

        $otp = (string)random_int(100000, 999999);
        $otpHash = hash_otp($otp);
        $expiresAt = add_minutes_utc(OTP_EXP_MINUTES);

        $stmt = $mysqli->prepare('INSERT INTO password_resets (user_id, otp_hash, expires_at, request_ip) VALUES (?, ?, ?, ?)');
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $stmt->bind_param('isss', $userId, $otpHash, $expiresAt, $ip);
        $stmt->execute();
        $stmt->close();

        send_otp_email($user['email'], $otp);

        $mysqli->close();
        header('Location: verify_otp.php');
        exit;
    }

    $mysqli->close();
} else {
    $mysqli->close();
    echo 'No account found for that ID.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Email</title>
</head>
<body>
    <h2>Confirm Registered Email</h2>
    <p>We will send the OTP to: <strong><?php echo htmlspecialchars($_SESSION['reset_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <form action="send_otp.php" method="post">
        <input type="hidden" name="identifier" value="<?php echo htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="action" value="send">
        <button type="submit">Send OTP</button>
    </form>
</body>
</html>
