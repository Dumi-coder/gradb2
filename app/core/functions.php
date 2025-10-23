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

function time_elapsed_string($datetime, $full = false) {
    if (!$datetime) {
        return 'unknown';
    }
    
    try {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    } catch (Exception $e) {
        return 'unknown';
    }
}

/**
 * Validate password strength according to security requirements
 * Password must be at least 8 characters and contain:
 * - At least one letter (a-z or A-Z)
 * - At least one number (0-9)
 * - At least one special character (!@#$%^&*()_+-=[]{}|;':",./<>?)
 * 
 * @param string $password The password to validate
 * @return array Array with 'valid' boolean and 'message' string
 */
function validatePasswordStrength($password) {
    $errors = [];
    
    // Check minimum length
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    // Check for at least one letter
    if (!preg_match('/[a-zA-Z]/', $password)) {
        $errors[] = "Password must contain at least one letter";
    }
    
    // Check for at least one number
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    }
    
    // Check for at least one special character
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{}|;\':",.\/<>?]/', $password)) {
        $errors[] = "Password must contain at least one special character (!@#$%^&*()_+-=[]{}|;':\",./<>?)";
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'message' => empty($errors) ? 'Password meets security requirements' : implode(', ', $errors)
    ];
}

/**
 * Get password strength requirements as a formatted string for display
 * @return string Formatted requirements text
 */
function getPasswordRequirements() {
    return "Password must be at least 8 characters and include: at least one letter, one number, and one special character (!@#$%^&*()_+-=[]{}|;':\",./<>?)";
}
