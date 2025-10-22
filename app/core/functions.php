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
