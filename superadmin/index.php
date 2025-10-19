<?php
// Super Admin Entry Point
session_start();

// Load the application core
require dirname(__DIR__) . '/app/core/init.php';

// Create and run the application
$app = new App;
$app->loadController();
