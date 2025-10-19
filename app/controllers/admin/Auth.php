<?php

class Auth extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is already logged in as admin
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'faculty_admin') {
            redirect('admin/dashboard');
            exit();
        }

        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleLogin();
        } else {
            $this->view('auth/admin-login', $data);
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
            // Find admin user
            $user = new User();
            $admin = $user->first(['email' => $email, 'role' => 'faculty_admin']);

            if ($admin) {
                // Verify password
                if (password_verify($password, $admin->password)) {
                    // Login successful - set session
                    $_SESSION['user_id'] = $admin->user_id;
                    $_SESSION['role'] = 'faculty_admin';
                    $_SESSION['name'] = $admin->name;
                    
                    // Redirect to admin dashboard
                    redirect('admin/dashboard');
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
        $this->view('auth/admin-login', $data);
    }

    public function logout()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to admin login
        header("Location: http://localhost/gradb2/admin/");
        exit();
    }
}
