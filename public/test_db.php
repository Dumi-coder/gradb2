 <!-- <?php 
// Create a simple test file: test_db.php in your public folder
// require '../app/core/init.php'; 

// public function insert($data)
// {
//     // Hash password
//     if (isset($data['password'])) {
//         $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
//     }

//     try {
//         $con = $this->connect();
        
//         $query = "INSERT INTO users (name, email, password, role) 
//                   VALUES (:name, :email, :password, :role)";
        
//         $stm = $con->prepare($query);
//         $result = $stm->execute([
//             'name' => $data['name'],
//             'email' => $data['email'],
//             'password' => $data['password'],
//             'role' => $data['role']
//         ]);
        
//         if ($result) {
//             return $con->lastInsertId();
//         }
        
//         return false;
        
//     } catch (PDOException $e) {
//         error_log("PDO Exception: " . $e->getMessage());
//         return false;
//     }
// }


// test_connection.php
require '../app/init.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test connection
    $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;
    $con = new PDO($string, DBUSER, DBPASS);
    echo "✅ Database connection successful<br>";
    
    // Test SELECT query
    $student = new Student();
    $result = $student->query("SELECT COUNT(*) as count FROM students");
    echo "✅ SELECT query test: " . ($result ? "SUCCESS" : "FAILED") . "<br>";
    
    // Test UPDATE query
    $test_result = $student->query("UPDATE students SET academic_year = 3 WHERE student_id = :student_id", [
        'student_id' => 'test123' // Use a non-existent ID to avoid actual changes
    ]);
    echo "✅ UPDATE query test: " . ($test_result !== false ? "SUCCESS" : "FAILED") . "<br>";
    
    // Show database info
    echo "<h3>Database Info:</h3>";
    echo "Host: " . DBHOST . "<br>";
    echo "Database: " . DBNAME . "<br>";
    echo "User: " . DBUSER . "<br>";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}