<?php
//  echo "init.php loaded<br> ";
// spl_autoload_register(function($classname){// Autoload classes
//     require $filename = "../app/models/".ucfirst($classname).".php";// Automatically load model classes
// });
// // Include necessary files for the application
// require 'Config.php';// Load configuration settings
// require 'functions.php';// Load utility functions
// require 'Database.php';// Load the Database class for database operations
// require 'Model.php';// Load the Model trait for database interaction
// require 'Controller.php';// Load the Controller class for handling views and requests
// require 'App.php';// Load the App class for routing and controller management



// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Autoload classes
spl_autoload_register(function($classname){
    require $filename = "../app/models/".ucfirst($classname).".php";
});

// Include necessary files for the application
require 'Config.php';        // Load configuration settings
require 'functions.php';     // Load utility functions
require 'Database.php';      // Load the Database trait for database operations
require 'Model.php';         // Load the Model trait for database interaction
require 'Controller.php';    // Load the Controller class for handling views and requests
require 'App.php';          // Load the App class for routing and controller management

// Session helper functions
function isLoggedIn($role = null)
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        return false;
    }
    
    if ($role && $_SESSION['role'] !== $role) {
        return false;
    }
    
    return true;
}

function requireLogin($role = null, $redirect_to = 'login')
{
    if (!isLoggedIn($role)) {
        redirect($redirect_to);
    }
}

function getCurrentUser()
{
    if (!isLoggedIn()) {
        return false;
    }
    
    return [
        'user_id' => $_SESSION['user_id'],
        'role' => $_SESSION['role'],
        'student_id' => $_SESSION['student_id'] ?? null,
        'name' => $_SESSION['name'] ?? null
    ];
}

function setUserSession($user, $additional_data = [])
{
    $_SESSION['user_id'] = $user->user_id;
    $_SESSION['role'] = $user->role;
    $_SESSION['name'] = $user->name;
    
    // Add any additional session data
    foreach ($additional_data as $key => $value) {
        $_SESSION[$key] = $value;
    }
}

function destroyUserSession()
{
    session_destroy();
    session_start(); // Start a new clean session
}