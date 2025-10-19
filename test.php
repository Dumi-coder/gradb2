<?php
// Change the password below to whatever you want to hash
$password = 'superadmin@123';

// Generate hashed password
$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "Hashed password: " . $hashed;
