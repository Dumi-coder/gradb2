<?php

class Auth extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is already logged in as counselor
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'counselor') {
            redirect('counselor/dashboard');
            exit();
        }

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
                    // Login successful - set session
                    $_SESSION['user_id'] = $counselor->user_id;
                    $_SESSION['role'] = 'counselor';
                    $_SESSION['name'] = $counselor->name;
                    
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
