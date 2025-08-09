<?php
//  echo "functions.php loaded<br> ";
function show($stuff) // This function is used to display the contents of a variable in a readable format
{
    echo "<pre>";
    print_r($stuff);// Print the contents of the variable
    echo "</pre>";
}
// show($stuff);
function esc($str)// This function is used to escape special characters in a string for safe output
{
    return htmlspecialchars($str);// Convert special characters to HTML entities
}
function redirect($path)
{
    header("Location: " . ROOT."/".$path);
     die;
    // exit();// Redirect to the specified path and exit the script
}