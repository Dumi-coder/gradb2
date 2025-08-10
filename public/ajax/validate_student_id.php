<?php
require_once '../app/models/User.php';
header('Content-Type: application/json');
$user = new User();
if (!empty($_GET['student_id'])) {
    $existing = $user->first(['student_id' => $_GET['student_id']]);
    if ($existing) {
        echo json_encode(['error' => 'Student ID already registered']);
        exit;
    }
}
echo json_encode(['error' => '']);