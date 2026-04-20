<?php
// define('APPROOT',dirname(dirname(__DIR__)));// This defines the root directory of the application, which is two levels up from the current file's directory.
//  echo "config.php loaded<br> ";
// This file contains the configuration settings for the application, including database connection details and application constants.

// This is a comment explaining that the following code checks if the server name is local.
// If it is, it sets the database configuration for a local development environment.
// If it is not, it sets the database configuration for a production environment.
$serverName = $_SERVER['SERVER_NAME'] ?? '';
$isLocalServer = in_array($serverName, ['localhost', '127.0.0.1', '::1'], true);
$dbProfile = getenv('DB_PROFILE') ?: ($isLocalServer ? 'local' : 'cloud');

if($dbProfile === 'local')
{
       /** database config */
       define('DBNAME', getenv('DBNAME') ?: 'gradb2');
       define('DBHOST', getenv('DBHOST') ?: 'localhost');
       define('DBUSER', getenv('DBUSER') ?: 'root');
       define('DBPORT', getenv('DBPORT') ?: '3306');
       define('DBPASS', getenv('DBPASS') !== false ? getenv('DBPASS') : '');
       define('DBDRIVER', getenv('DBDRIVER') ?: '');

       define('ROOT', getenv('ROOT_URL') ?: 'http://localhost/gradb2/public');
}
else
{
       /** database config */
       define('DBNAME', getenv('DBNAME') ?: 'gradb2_gradb2');
       define('DBPORT', getenv('DBPORT') ?: '3306');
       define('DBHOST', getenv('DBHOST') ?: 'mysql-gradb2.alwaysdata.net');
       define('DBUSER', getenv('DBUSER') ?: 'gradb2');
       define('DBPASS', getenv('DBPASS') ?: 'passwordmysql');
       define('DBDRIVER', getenv('DBDRIVER') ?: '');

       define('ROOT', getenv('ROOT_URL') ?: 'https://www.GradBridge.com');
}

// File system paths
// APPROOT should point to the project root which contains both `app/` and `public/`.
// Since this file lives in app/core, go two levels up.
define('APPROOT', dirname(__DIR__, 2));

// Public directory path (for file uploads)
define('PUBLICPATH', APPROOT . '/public');

// Upload directory paths
define('UPLOAD_PATH', PUBLICPATH . '/assets/uploads/');
define('PROFILE_UPLOAD_PATH', UPLOAD_PATH . 'profiles/');
// Standard upload path for shared resources (documents uploaded from Resources page)
define('RESOURCE_UPLOAD_PATH', UPLOAD_PATH . 'resources/');


define('APP_NAME','GradBridge');// This is the name of the application, used in the title tag and other places.
// This is the name of the application, used in the title tag and other places.
// It is also used in the config file to set the application name.
define('APP_DESC','Best website on the planet');// This is the description of the application, used in the meta description tag and other places.

// true means show errors
define('DEBUG',true); // This constant is used to enable or disable error reporting in the application.
// If set to true, errors will be displayed on the screen. If set to false,
// errors will be logged to a file instead. This is useful for debugging during development.

// Fundraising payment configuration
// Use 'demo' for local/test, or 'payhere', 'paypal', etc. for real gateways
define('FUNDRAISING_PAYMENT_MODE', getenv('FUNDRAISING_PAYMENT_MODE') ?: 'demo');

// Password reset (OTP) settings
define('OTP_EXP_MINUTES', 10);
define('OTP_RATE_LIMIT_SECONDS', 60);
define('OTP_MAX_ATTEMPTS', 5);
define('MAIL_FROM', 'no-reply@gradbridge.com');

// SMTP settings for PHPMailer (Gmail example)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_PASS', 'gkcf gyax jpan ceby');
define('SMTP_FROM', '2023cs058@stu.ucsc.cmb.ac.lk');
define('SMTP_FROM_NAME', 'GradBridge');
