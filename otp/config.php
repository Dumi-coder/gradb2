<?php
// Basic DB config for standalone password reset pages

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

define('DB_HOST', 'mysql-gradb2.alwaysdata.net');
define('DB_NAME', 'gradb2_gradb2');
define('DB_USER', 'gradb2');
define('DB_PASS', 'passwordmysql');
define('DB_PORT', 3306);

define('OTP_EXP_MINUTES', 10);
define('OTP_RATE_LIMIT_SECONDS', 60);
define('OTP_MAX_ATTEMPTS', 5);

define('MAIL_FROM', 'no-reply@yourdomain.com');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_PASS', 'gkcf gyax jpan ceby');
define('SMTP_FROM', MAIL_FROM);

define('APP_NAME', 'GradBridge');
define('SMTP_FROM_NAME', APP_NAME);

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
    $autoload = __DIR__ . '/mailer/vendor/autoload.php';
    if (!is_file($autoload)) {
        if (DEBUG_MODE) {
            error_log('PHPMailer autoload not found: ' . $autoload);
        }
        return false;
    }

    require_once $autoload;

    if (SMTP_USER === '' || SMTP_PASS === '') {
        if (DEBUG_MODE) {
            error_log('SMTP credentials are not configured.');
        }
        return false;
    }

    $mailer = new PHPMailer(true);

    try {
        $mailer->isSMTP();
        $mailer->Host = SMTP_HOST;
        $mailer->SMTPAuth = true;
        $mailer->Username = SMTP_USER;
        $mailer->Password = SMTP_PASS;
        $mailer->SMTPSecure = SMTP_SECURE;
        $mailer->Port = SMTP_PORT;

        $mailer->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mailer->addAddress($toEmail);
        $mailer->Subject = 'Your ' . APP_NAME . ' password reset OTP';
        $mailer->Body = "Your OTP is: {$otp}\n\nThis code expires in " . OTP_EXP_MINUTES . " minutes.";

        return $mailer->send();
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            error_log('[PHPMailer] Send failed: ' . $mailer->ErrorInfo);
        }
        return false;
    }
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
