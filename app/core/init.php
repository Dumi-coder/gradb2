<?php
//  echo "init.php loaded<br> ";
spl_autoload_register(function($classname){// Autoload classes
    require $filename = "../app/models/".ucfirst($classname).".php";// Automatically load model classes
});
// Include necessary files for the application
require 'config.php';// Load configuration settings
require 'functions.php';// Load utility functions
require 'Database.php';// Load the Database class for database operations
require 'Model.php';// Load the Model trait for database interaction
require 'Controller.php';// Load the Controller class for handling views and requests
require 'App.php';// Load the App class for routing and controller management


// foreach($paths as $path)
// {
//     $file=APPROOT . '/' . $path . $className
// }