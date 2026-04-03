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
            $facultyAdmin = new FacultyAdmin();
            $activeAdmin = $facultyAdmin->getActiveAdminByUserId($_SESSION['user_id']);

            if ($activeAdmin) {
                redirect('admin/dashboard');
                exit();
            }

            session_unset();
            session_destroy();
            session_start();

            $data = ['errors' => ["Your account has been suspended"]];
            $this->view('auth/admin-login', $data);
            return;
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
                    $facultyAdmin = new FacultyAdmin();
                    $activeAdmin = $facultyAdmin->getActiveAdminByUserId($admin->user_id);

                    if (!$activeAdmin) {
                        $errors[] = "Your account has been suspended";
                        $data['errors'] = $errors;
                        $this->view('auth/admin-login', $data);
                        return;
                    }

                    // Login successful - set session
                    $_SESSION['user_id'] = $admin->user_id;
                    $_SESSION['role'] = 'faculty_admin';
                    $_SESSION['name'] = $admin->name;
                    $_SESSION['email'] = $admin->email;
                    
                    // Get profile picture from faculty_admins table
                    $adminProfile = $facultyAdmin->getFacultyAdminProfile($admin->user_id);
                    $_SESSION['profile_picture'] = $adminProfile->picture_path ?? null;
                    $_SESSION['faculty_id'] = $adminProfile->faculty_id ?? null;
                    $_SESSION['faculty_name'] = $adminProfile->faculty_name ?? null;
                    $_SESSION['faculty_admin_id'] = $adminProfile->faculty_admin_id ?? null;
                    
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

}
