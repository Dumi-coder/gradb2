<?php
session_start();// Start the session to manage user sessions

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "index.php loaded<br>";// Debugging line to confirm index.php is loaded
require "../app/core/init.php";// Load the initialization file


/*if(DEBUG)// Check if debug mode is enabled
{
    ini_set('display_errors',1);// Enable error reporting

}
else{
    ini_set('display_errors',0);// Disable error reporting
}*/

$app = new App;// Create an instance of the App class
$app->loadController();// Load the controller based on the request