<?php
session_start();

// Include database config
require_once '../app/core/Config.php';

echo "<h3>Faculty Admin Debug</h3>";

// Check session
echo "<strong>Session user_id:</strong> " . ($_SESSION['user_id'] ?? 'NOT SET') . "<br>";
echo "<strong>Session role:</strong> " . ($_SESSION['role'] ?? 'NOT SET') . "<br><br>";

// Check database connection and data
try {
    $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
    $con = new PDO($string, DBUSER, DBPASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<strong>Database connection:</strong> SUCCESS<br><br>";
    
    // Get all faculty_admins records
    $query = "SELECT * FROM faculty_admins";
    $stm = $con->query($query);
    $admins = $stm->fetchAll(PDO::FETCH_OBJ);
    
    echo "<strong>All Faculty Admin Records:</strong><br>";
    echo "<pre>";
    print_r($admins);
    echo "</pre><br>";
    
    // Try to find current user
    if (isset($_SESSION['user_id'])) {
        $query = "SELECT * FROM faculty_admins WHERE user_id = :user_id";
        $stm = $con->prepare($query);
        $stm->execute(['user_id' => $_SESSION['user_id']]);
        $result = $stm->fetch(PDO::FETCH_OBJ);
        
        echo "<strong>Current User's Faculty Admin Record:</strong><br>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
    
} catch (PDOException $e) {
    echo "<strong>Database error:</strong> " . $e->getMessage();
}
?>
