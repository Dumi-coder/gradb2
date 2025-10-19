<?php

class Auth extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is already logged in as superadmin
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'super_admin') {
            redirect('superadmin/dashboard');
            exit();
        }

        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleLogin();
        } else {
            $this->view('auth/superadmin-login', $data);
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
            // Find superadmin user
            $user = new User();
            $superadmin = $user->first(['email' => $email, 'role' => 'super_admin']);

            if ($superadmin) {
                // Verify password
                if (password_verify($password, $superadmin->password)) {
                    // Login successful - set session
                    $_SESSION['user_id'] = $superadmin->user_id;
                    $_SESSION['role'] = 'super_admin';
                    $_SESSION['name'] = $superadmin->name;
                    
                    // Redirect to superadmin dashboard
                    redirect('superadmin/dashboard');
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
        $this->view('auth/superadmin-login', $data);
    }

    public function logout()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to superadmin login
        header("Location: http://localhost/gradb2/superadmin/");
        exit();
    }
}
