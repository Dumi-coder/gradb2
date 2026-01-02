<?php
require_once '../app/core/Config.php';

echo "<h3>Faculty Admin Database Check</h3>";

try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check for user_id 67
    $stmt = $pdo->prepare("SELECT * FROM faculty_admins WHERE user_id = 67");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<strong>Faculty Admin record for user_id 67:</strong><br>";
    if ($admin) {
        echo "<pre>";
        print_r($admin);
        echo "</pre>";
        
        if ($admin['faculty_id'] === null) {
            echo "<p style='color: red;'>❌ faculty_id is NULL - This is the problem!</p>";
            
            // Show available faculties
            $stmt = $pdo->query("SELECT * FROM faculties");
            $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h4>Available Faculties:</h4>";
            echo "<pre>";
            print_r($faculties);
            echo "</pre>";
            
            echo "<h4>Fix Options:</h4>";
            echo "<p>Run one of these SQL commands to fix:</p>";
            foreach ($faculties as $faculty) {
                echo "<code>UPDATE faculty_admins SET faculty_id = {$faculty['faculty_id']} WHERE user_id = 67;</code> -- {$faculty['faculty_name']}<br>";
            }
        } else {
            echo "<p style='color: green;'>✓ faculty_id is set to: " . $admin['faculty_id'] . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ No record found in faculty_admins table for user_id 67!</p>";
        
        // Show available faculties
        $stmt = $pdo->query("SELECT * FROM faculties");
        $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h4>Available Faculties:</h4>";
        echo "<pre>";
        print_r($faculties);
        echo "</pre>";
        
        echo "<h4>Fix: Insert a new record</h4>";
        echo "<p>Run one of these SQL commands:</p>";
        foreach ($faculties as $faculty) {
            echo "<code>INSERT INTO faculty_admins (user_id, faculty_id, admin_level) VALUES (67, {$faculty['faculty_id']}, 'moderator');</code> -- {$faculty['faculty_name']}<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database Error: " . $e->getMessage() . "</p>";
}
?>
