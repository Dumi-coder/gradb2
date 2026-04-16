<?php
// Basic DB config for standalone password reset pages

define('DB_HOST', 'mysql-gradb2.alwaysdata.net');
define('DB_NAME', 'gradb2_gradb2');
define('DB_USER', 'gradb2');
define('DB_PASS', 'passwordmysql');
define('DB_PORT', 3306);

define('OTP_EXP_MINUTES', 10);
define('OTP_RATE_LIMIT_SECONDS', 60);
define('OTP_MAX_ATTEMPTS', 5);

define('MAIL_FROM', 'no-reply@yourdomain.com');

define('APP_NAME', 'GradBridge');

define('DEBUG_MODE', true);

// Keep all time operations consistent
date_default_timezone_set('UTC');

function db_connect(): mysqli
{
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($mysqli->connect_errno) {
        if (DEBUG_MODE) {
            die('Database connection failed: ' . $mysqli->connect_error);
        }
        die('Database connection failed.');
    }
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}

function now_utc(): string
{
    $dt = new DateTime('now', new DateTimeZone('UTC'));
    return $dt->format('Y-m-d H:i:s');
}

function add_minutes_utc(int $minutes): string
{
    $dt = new DateTime('now', new DateTimeZone('UTC'));
    $dt->modify('+' . $minutes . ' minutes');
    return $dt->format('Y-m-d H:i:s');
}

function send_otp_email(string $toEmail, string $otp): bool
{
    $subject = 'Your ' . APP_NAME . ' password reset OTP';
    $message = "Your OTP is: {$otp}\n\nThis code expires in " . OTP_EXP_MINUTES . " minutes.";

    $headers = "From: " . MAIL_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    return mail($toEmail, $subject, $message, $headers);
}

function hash_otp(string $otp): string
{
    return password_hash($otp, PASSWORD_DEFAULT);
}

function verify_otp(string $otp, string $hash): bool
{
    return password_verify($otp, $hash);
}

function safe_sleep_ms(int $minMs, int $maxMs): void
{
    $ms = random_int($minMs, $maxMs);
    usleep($ms * 1000);
}
