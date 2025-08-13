<?php

class Auth
{
    use Database;

    public static function authenticate($data)
    {
        $email = trim($data['email']);
        $password = trim($data['password']);
        $user_type = $data['user_type'] ?? 'student'; // Default to student

        if ($user_type === 'alumni') {
            $alumni = new Alumni();
            $user = $alumni->first(['email' => $email]);
            
            if ($user && password_verify($password, $user->password)) {
                // Set session data for alumni
                $_SESSION['USER'] = $user;
                $_SESSION['USER_TYPE'] = 'alumni';
                $_SESSION['USER_ID'] = $user->alumni_id;
                return true;
            }
        } else {
            $student = new Student();
            $user = $student->first(['email' => $email]);
            
            if ($user && password_verify($password, $user->password)) {
                // Set session data for student
                $_SESSION['USER'] = $user;
                $_SESSION['USER_TYPE'] = 'student';
                $_SESSION['USER_ID'] = $user->student_id;
                return true;
            }
        }
        
        return false;
    }

    public static function logged_in()
    {
        return isset($_SESSION['USER']) && !empty($_SESSION['USER']);
    }

    public static function user()
    {
        return $_SESSION['USER'] ?? null;
    }

    public static function user_type()
    {
        return $_SESSION['USER_TYPE'] ?? null;
    }

    public static function user_id()
    {
        return $_SESSION['USER_ID'] ?? null;
    }

    public static function logout()
    {
        if (isset($_SESSION['USER'])) {
            unset($_SESSION['USER']);
            unset($_SESSION['USER_TYPE']);
            unset($_SESSION['USER_ID']);
        }
        session_destroy();
    }

    public static function is_student()
    {
        return self::user_type() === 'student';
    }

    public static function is_alumni()
    {
        return self::user_type() === 'alumni';
    }

    // Check if user has access to specific areas
    public static function check_access($required_type = null)
    {
        if (!self::logged_in()) {
            redirect('login');
            return false;
        }

        if ($required_type && self::user_type() !== $required_type) {
            redirect('dashboard');
            return false;
        }

        return true;
    }
}