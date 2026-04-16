<?php
// Database configuration

define('DB_HOST', 'localhost');
define('DB_NAME', 'gradb2');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

// OTP settings

define('OTP_EXP_MINUTES', 10);
define('OTP_MAX_ATTEMPTS', 5);

// PHPMailer SMTP settings (Gmail example)

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // tls or ssl

define('SMTP_USER', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_PASS', 'gkcf gyax jpan ceby');

define('SMTP_FROM', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_FROM_NAME', 'GradBridge');

define('APP_NAME', 'GradBridge');

define('DEBUG_MODE', true);

function db_connect(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4;port=' . DB_PORT;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    return new PDO($dsn, DB_USER, DB_PASS, $options);
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

function generic_response(): void
{
    echo 'If the user ID is registered, an OTP has been sent.';
}
