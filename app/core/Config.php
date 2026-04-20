<?php
if($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DBNAME','gradb2');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','root');
    define('DBPORT','3307');
    define('DBDRIVER','');
    define('ROOT','http://localhost/gradb2/public');
} else {
    define('DBNAME','gradb2_gradb2');
    define('DBPORT','3306');
    define('DBHOST','mysql-gradb2.alwaysdata.net');
    define('DBUSER','gradb2');
    define('DBPASS','passwordmysql');
    define('DBDRIVER','');
    define('ROOT','https://www.GradBridge.com');
}

define('APPROOT', dirname(__DIR__, 2));
define('PUBLICPATH', APPROOT . '/public');
define('UPLOAD_PATH', PUBLICPATH . '/assets/uploads/');
define('PROFILE_UPLOAD_PATH', UPLOAD_PATH . 'profiles/');
define('RESOURCE_UPLOAD_PATH', UPLOAD_PATH . 'resources/');

define('APP_NAME','GradBridge');
define('APP_DESC','Best website on the planet');
define('DEBUG',true);

define('FUNDRAISING_PAYMENT_MODE', getenv('FUNDRAISING_PAYMENT_MODE') ?: 'demo');
define('OTP_EXP_MINUTES', 10);
define('OTP_RATE_LIMIT_SECONDS', 60);
define('OTP_MAX_ATTEMPTS', 5);
define('MAIL_FROM', 'no-reply@gradbridge.com');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_PASS', 'gkcf gyax jpan ceby');
define('SMTP_FROM', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_FROM_NAME', 'GradBridge');