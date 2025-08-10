<?php
require_once '../app/models/User.php';
header('Content-Type: application/json');
$user = new User();
if (!empty($_GET['email'])) {
    $existing = $user->first(['email' => $_GET['email']]);
    if ($existing) {
        echo json_encode(['error' => 'Email already registered']);
        exit;
    }
}
echo json_encode(['error' => '']);