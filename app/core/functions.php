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
    if (is_array($str)) {
        if (isset($str['text']) && is_scalar($str['text'])) {
            $str = (string)$str['text'];
        } else {
            $str = json_encode($str, JSON_UNESCAPED_UNICODE) ?: '';
        }
    } elseif (is_object($str)) {
        if (method_exists($str, '__toString')) {
            $str = (string)$str;
        } else {
            $str = json_encode($str, JSON_UNESCAPED_UNICODE) ?: '';
        }
    } elseif ($str === null) {
        $str = '';
    } else {
        $str = (string)$str;
    }

    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');// Convert special characters to HTML entities
}
function redirect($path)
{
    header("Location: " . ROOT."/".$path);
     die;
    // exit();// Redirect to the specified path and exit the script
}

function format_money($value, $withThousands = true)
{
    $raw = trim((string)$value);
    if (!preg_match('/^-?\d+(?:\.\d+)?$/', $raw)) {
        return '0.00';
    }

    $negative = false;
    if (strpos($raw, '-') === 0) {
        $negative = true;
        $raw = substr($raw, 1);
    }

    $parts = explode('.', $raw, 2);
    $whole = ltrim($parts[0], '0');
    if ($whole === '') {
        $whole = '0';
    }

    $fractionRaw = preg_replace('/\D/', '', (string)($parts[1] ?? ''));
    $fractionRaw = str_pad($fractionRaw, 3, '0');
    $cents = (int)substr($fractionRaw, 0, 2);
    $roundDigit = (int)substr($fractionRaw, 2, 1);

    if ($roundDigit >= 5) {
        $cents += 1;
        if ($cents >= 100) {
            $cents = 0;
            $whole = (string)(((int)$whole) + 1);
        }
    }

    if ($withThousands) {
        $whole = preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $whole);
    }

    $fraction = str_pad((string)$cents, 2, '0', STR_PAD_LEFT);

    return ($negative ? '-' : '') . $whole . '.' . $fraction;
}

function time_elapsed_string($datetime, $full = false) {
    if (!$datetime) {
        return 'unknown';
    }
    
    try {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $weeks = (int) floor($diff->d / 7);
        $days = $diff->d - ($weeks * 7);

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        $diffParts = [
            'y' => $diff->y,
            'm' => $diff->m,
            'w' => $weeks,
            'd' => $days,
            'h' => $diff->h,
            'i' => $diff->i,
            's' => $diff->s,
        ];

        $string['w'] = 'week';
        
        foreach ($string as $k => &$v) {
            if (!empty($diffParts[$k])) {
                $v = $diffParts[$k] . ' ' . $v . ($diffParts[$k] > 1 ? 's' : '');
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
 * Password must be at least 10 characters and contain:
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
    if (strlen($password) < 10) {
        $errors[] = "Password must be at least 10 characters long";
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
    return "Password must be at least 10 characters and include: at least one letter, one number, and one special character (!@#$%^&*()_+-=[]{}|;':\",./<>?)";
}
