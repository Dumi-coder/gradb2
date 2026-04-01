<?php

class Auth extends Controller
{
    // TEMP: Development-only login bypass for counselor routes.
    // Set to false to restore normal email/password authentication.
    private const TEMP_BYPASS_LOGIN = false;

    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // TEMP BYPASS: Auto-login as counselor without credentials.
        // Remove this block or set TEMP_BYPASS_LOGIN to false before production.
        if (self::TEMP_BYPASS_LOGIN) {
            $_SESSION['user_id'] = 1;
            $_SESSION['role'] = 'counselor';
            $_SESSION['name'] = $_SESSION['name'] ?? 'Counselor';

            redirect('counselor/dashboard');
            exit();
        }

        // Check if user is already logged in as counselor
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'counselor' && (int)$_SESSION['user_id'] === 1) {
            redirect('counselor/dashboard');
            exit();
        }

        // Prevent caching of auth pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleLogin();
        } else {
            $this->view('auth/counselor-login', $data);
        }
    }

    private function handleLogin()
    {
        $data = [];
        $errors = [];

        // Get form data
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email)) {
            $errors[] = "Email is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (empty($errors)) {
            // Find counselor user
            $user = new User();
            $counselor = $user->first(['email' => $email, 'role' => 'counselor']);

            if ($counselor) {
                // Verify password
                if (password_verify($password, $counselor->password)) {
                    if ((int)$counselor->user_id !== 1) {
                        $errors[] = "Access denied. Only counselor user_id 1 can access this dashboard";
                        $data['errors'] = $errors;
                        $this->view('auth/counselor-login', $data);
                        return;
                    }

                    // Login successful
                    $_SESSION['user_id'] = (int)$counselor->user_id;
                    $_SESSION['role'] = 'counselor';
                    $_SESSION['name'] = $counselor->name ?? 'Counselor';
                    $_SESSION['profile_picture'] = $counselor->profile_photo_url ?? null;
                    
                    // Redirect to counselor dashboard
                    redirect('counselor/dashboard');
                    return;
                } else {
                    $errors[] = "Invalid email or password";
                }
            } else {
                $errors[] = "Invalid email or password";
            }
        }

        // If we reach here, login failed
        $data['errors'] = $errors;
        $this->view('auth/counselor-login', $data);
    }

    public function logout()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to counselor login
        header("Location: http://localhost/gradb2/counselor/");
        exit();
    }
}
