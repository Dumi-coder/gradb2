<?php
session_start();
require_once __DIR__ . '/db.php';

// Clear previous reset session data
unset($_SESSION['reset_user_id'], $_SESSION['otp_verified']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f6f6f6; }
        .card { max-width: 420px; margin: 50px auto; background:#fff; padding:24px; border-radius:8px; }
        .input { width:100%; padding:10px; margin:10px 0; }
        .btn { width:100%; padding:10px; background:#2d6cdf; color:#fff; border:0; border-radius:4px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Forgot Password</h2>
        <form action="send_otp.php" method="post">
            <label for="user_id">User ID</label>
            <input class="input" type="text" id="user_id" name="user_id" required>
            <button class="btn" type="submit">Send OTP</button>
        </form>
    </div>
</body>
</html>
