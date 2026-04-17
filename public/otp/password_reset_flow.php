<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../../config.php';

$step = defined('OTP_FLOW_STEP') ? OTP_FLOW_STEP : 'forgot';
$message = '';

if ($step === 'forgot') {
    unset($_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['otp_verified']);
}

if ($step === 'send') {
    $identifier = isset($_POST['identifier']) ? trim((string)$_POST['identifier']) : '';
    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : '';
    $isNumericId = ctype_digit($identifier);

    if (!$isNumericId) {
        safe_sleep_ms(200, 400);
        echo 'Please enter a valid ID.';
        exit;
    }

    $mysqli = db_connect();

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
            $stmt = $mysqli->prepare('SELECT created_at FROM password_resets WHERE user_id = ? ORDER BY created_at DESC LIMIT 1');
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $rateResult = $stmt->get_result();
            $last = $rateResult->fetch_assoc();
            $stmt->close();

            if ($last) {
                $lastTime = strtotime((string)$last['created_at']);
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
}

if ($step === 'verify') {
    $userId = $_SESSION['reset_user_id'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $otp = isset($_POST['otp']) ? trim((string)$_POST['otp']) : '';

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
                $expired = strtotime((string)$row['expires_at']) < time();
                if ($expired) {
                    $message = 'OTP expired. Please request a new OTP.';
                } else {
                    $attempts = (int)$row['attempts'];
                    if ($attempts >= OTP_MAX_ATTEMPTS) {
                        $message = 'Too many attempts. Please request a new OTP.';
                    } elseif (verify_otp($otp, (string)$row['otp_hash'])) {
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
}

if ($step === 'reset') {
    $userId = $_SESSION['reset_user_id'] ?? null;
    $otpVerified = $_SESSION['otp_verified'] ?? false;

    if (!$userId || !$otpVerified) {
        $message = 'Unauthorized. Please verify OTP first.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId && $otpVerified) {
        $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
        $confirm = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

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

            $stmt = $mysqli->prepare('DELETE FROM password_resets WHERE user_id = ?');
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();

            $mysqli->close();

            unset($_SESSION['otp_verified'], $_SESSION['reset_user_id'], $_SESSION['reset_email']);
            $message = 'Password reset successful. You can now log in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        <?php if ($step === 'forgot'): ?>Forgot Password<?php endif; ?>
        <?php if ($step === 'send'): ?>Confirm Email<?php endif; ?>
        <?php if ($step === 'verify'): ?>Verify OTP<?php endif; ?>
        <?php if ($step === 'reset'): ?>Reset Password<?php endif; ?>
    </title>
</head>
<body>
    <?php if ($step === 'forgot'): ?>
        <h2>Forgot Password</h2>
        <form action="send_otp.php" method="post">
            <label for="identifier">Student ID / Alumni ID:</label><br>
            <input type="text" id="identifier" name="identifier" required><br><br>
            <button type="submit">Send OTP</button>
        </form>
    <?php endif; ?>

    <?php if ($step === 'send'): ?>
        <h2>Confirm Registered Email</h2>
        <p>We will send the OTP to: <strong><?php echo htmlspecialchars($_SESSION['reset_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <form action="send_otp.php" method="post">
            <input type="hidden" name="identifier" value="<?php echo htmlspecialchars($identifier ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" value="send">
            <button type="submit">Send OTP</button>
        </form>
    <?php endif; ?>

    <?php if ($step === 'verify'): ?>
        <h2>Verify OTP</h2>
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form action="verify_otp.php" method="post">
            <label for="otp">Enter OTP:</label><br>
            <input type="text" id="otp" name="otp" maxlength="6" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
    <?php endif; ?>

    <?php if ($step === 'reset'): ?>
        <h2>Reset Password</h2>
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if (($userId ?? null) && ($otpVerified ?? false)): ?>
            <form action="reset_password.php" method="post">
                <label for="password">New Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br><br>

                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
