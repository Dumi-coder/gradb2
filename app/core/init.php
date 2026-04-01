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

// Initialize database tables
function initializeDatabaseTables()
{
    try {
        $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
        $con = new PDO($string, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create counselor table if it doesn't exist
        $createCounselorTable = "
        CREATE TABLE IF NOT EXISTS `counselor` (
            `user_id` int NOT NULL PRIMARY KEY,
            `name` varchar(255) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `password` varchar(255) DEFAULT NULL,
            `profile_photo_url` varchar(500) DEFAULT NULL,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY `email` (`email`)
        )
        ";
        
        $con->exec($createCounselorTable);
        
        // Sync counselor data from users table if not already synced
        $syncCounselors = "
        INSERT INTO counselor (user_id, name, email, password)
        SELECT user_id, name, email, password FROM users 
        WHERE role = 'counselor' AND user_id NOT IN (SELECT user_id FROM counselor)
        ";
        
        try {
            $con->exec($syncCounselors);
        } catch (Exception $e) {
            // Sync might fail if there's a constraint, that's ok
        }
        
        // Ensure default counselor "lakshani" exists
        $checkLakshani = "SELECT user_id FROM counselor WHERE name = 'lakshani' LIMIT 1";
        $result = $con->query($checkLakshani);
        
        if (!$result || $result->rowCount() == 0) {
            // Find the next available user_id or use a default
            $getMaxId = "SELECT COALESCE(MAX(user_id), 0) + 1 as next_id FROM counselor";
            $idResult = $con->query($getMaxId)->fetch(PDO::FETCH_ASSOC);
            $nextId = $idResult['next_id'] ?: 9366;
            
            // Create password hash for "lakshani" (password: lakshani123)
            $hashedPassword = password_hash('lakshani123', PASSWORD_BCRYPT);
            
            // Insert lakshani counselor
            $insertLakshani = "
            INSERT INTO counselor (user_id, name, email, password) 
            VALUES (?, ?, ?, ?)
            ";
            
            $stmt = $con->prepare($insertLakshani);
            $stmt->execute([$nextId, 'lakshani', 'lakshani@gradb.com', $hashedPassword]);
        }
        
    } catch (Exception $e) {
        // Database initialization error - log but don't break the app
        error_log("Database initialization error: " . $e->getMessage());
    }
}

// Initialize tables on application load
initializeDatabaseTables();