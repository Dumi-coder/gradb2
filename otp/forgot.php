<?php
session_start();
unset($_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['otp_verified']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="send_otp.php" method="post">
        <label for="identifier">User ID:</label><br>
        <input type="text" id="identifier" name="identifier" required><br><br>
        <button type="submit">Send OTP</button>
    </form>
</body>
</html>
