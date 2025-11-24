<?php
require __DIR__ . '/../app/core/init.php';
echo "<pre>\n";

// helper to run a query via model and print debug
function runCheck($modelName, $sql) {
    try {
        $m = new $modelName();
        $res = $m->query($sql);
        echo "== $modelName -> $sql ==\n";
        var_dump($res ?: 'no rows / false');
    } catch (Throwable $e) {
        echo "$modelName query exception: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

$role = $_GET['role'] ?? 'both';

if ($role === 'student' || $role === 'both') {
    runCheck('Student', "SELECT * FROM student_records LIMIT 5");
    runCheck('Student', "SELECT COUNT(*) AS c FROM student_records");
}

if ($role === 'alumni' || $role === 'both') {
    runCheck('Alumni', "SELECT * FROM alumni_records LIMIT 5");
    runCheck('Alumni', "SELECT COUNT(*) AS c FROM alumni_records");
}

echo "</pre>";