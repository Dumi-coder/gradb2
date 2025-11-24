<?php
ob_start(); // Start output buffering

class Auth extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'alumni') {
            // header("Location: " . ROOT . "/alumni/dashboard");
            redirect('alumni/dashboard');
            exit();
        }

        // Prevent caching of auth pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['name'])) {
                $this->handleSignup();
            } else {
                $this->handleLogin();
            }
        } else {
            // Default to login page instead of signup
            if (isset($_GET['action']) && $_GET['action'] == 'signup') {
                $this->view('auth/alumni_signup', $data);
            } else {
                $this->view('auth/alumni_login', $data);
            }
        }
    }

    private function handleSignup()
    {
        $data = [];
        $errors = [];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $alumni_id = trim($_POST['alumni_id'] ?? '');
        $academic_year = trim($_POST['graduated_year'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name)) $errors[] = "Name is required";

        if (empty($email)) $errors[] = "University email is required";

        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";

        if (empty($alumni_id)) $errors[] = "Alumni ID is required";

        // if (empty($academic_year) || !is_numeric($academic_year) || $academic_year < 1 || $academic_year > 5) $errors[] = "Academic year must be between 1 and 5";

        if (empty($faculty)) $errors[] = "Faculty is required";

        if (empty($password)) {
            $errors[] = "Password is required";
        } else {
            $passwordValidation = validatePasswordStrength($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }


        if (empty($errors)) {
            $user = new User();
            if ($user->first(['email' => $email])) $errors[] = "Email already exists";
            $alumni = new Alumni();
            if ($alumni->first(['alumni_id' => $alumni_id])) $errors[] = "Alumni ID already exists in the system";
        }

        if (empty($errors)) {
            $faculty_model = new Faculty();
            $faculty_record = $faculty_model->first(['faculty_name' => $faculty]);
            if (!$faculty_record) $errors[] = "Invalid faculty selected";
        }

        // VERIFY ALUMNI EXISTS IN RECORDS TABLE
        // User cannot register if their alumni_id is not in alumni_records table
        if (empty($errors) && isset($faculty_record) && $faculty_record) {
            $alumniModel = new Alumni();
            error_log("=== Alumni Registration Verification ===");
            error_log("Alumni ID: " . $alumni_id);
            error_log("Faculty ID: " . $faculty_record->faculty_id);
            
            $exists = $alumniModel->existsInRecords($alumni_id, $faculty_record->faculty_id);
            
            if (!$exists) {
                $errors[] = "Invalid Alumni ID.";
                error_log("Verification FAILED - Alumni ID not found in alumni_records table");
            } else {
                error_log("Verification PASSED - Alumni ID found in alumni_records table");
            }
        } elseif (empty($errors) && !isset($faculty_record)) {
            $errors[] = "Faculty verification failed. Please try again.";
        }

        if (empty($errors)) {
            $user_data = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'alumni'
            ];

            $user = new User();
            if ($user->insert($user_data)) {
                $created_user = $user->first(['email' => $email]);
                
                if ($created_user) {
                    $alumni_data = [
                        'alumni_id' => $alumni_id,
                        'user_id' => $created_user->user_id,
                        'faculty_id' => $faculty_record->faculty_id,
                        // 'graduated_year' => $graduated_year
                    ];

                    $alumni = new Alumni();
                    if (!$alumni->insert($alumni_data)) {
                        // Log error for debugging
                        error_log("Failed to insert alumni data: " . print_r($alumni_data, true));
                        // Check if record was actually inserted
                        if ($alumni->first(['alumni_id' => $alumni_id])) {
                            // Record exists, proceed with session and redirect
                            $_SESSION['user_id'] = $created_user->user_id;
                            $_SESSION['role'] = 'alumni';
                            $_SESSION['alumni_id'] = $alumni_id;
                            $_SESSION['name'] = $name;
                            ob_end_flush();
                            
                            // Check if AJAX request
                            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                                header('Content-Type: application/json');
                                echo json_encode(['success' => true, 'redirect' => ROOT . '/alumni/dashboard']);
                                exit();
                            }
                            
                            redirect('alumni/dashboard');
                            exit();
                        } else {
                            $errors[] = "Failed to create alumni record.";
                        }
                    } else {
                        // Insert succeeded, set session and redirect
                        $_SESSION['user_id'] = $created_user->user_id;
                        $_SESSION['role'] = 'alumni';
                        $_SESSION['alumni_id'] = $alumni_id;
                        $_SESSION['name'] = $name;
                        ob_end_flush();
                        
                        // Check if AJAX request
                        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => true, 'redirect' => ROOT . '/alumni/dashboard']);
                            exit();
                        }
                        
                        redirect('alumni/dashboard');
                        exit();
                    }
                } else {
                    $errors[] = "Failed to retrieve created user.";
                }
            } else {
                $errors[] = "Failed to create user.";
            }
        }

        // Check if AJAX request - check multiple ways
        $isAjax = false;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isAjax = true;
        } elseif (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            $isAjax = true;
        } elseif (isset($_POST['_ajax']) && $_POST['_ajax'] == '1') {
            $isAjax = true;
        }
        
        // ALWAYS return JSON for AJAX requests - never render view
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-cache, must-revalidate');
            ob_clean(); // Clear any output
            echo json_encode(['success' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Only render view for non-AJAX requests
        $data['errors'] = $errors;
        $this->view('auth/alumni_signup', $data);
    }



    private function handleLogin()
    {
        $data = [];
        $errors = [];

        // Get form data
        $alumni_id = trim($_POST['alumni_id'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($alumni_id)) {
            $errors[] = "alumni ID is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (empty($errors)) {
            // Find alumni with user data
            $alumni = new Alumni();
            $alumni_record = $alumni->getalumniWithUser($alumni_id);

            if ($alumni_record) {
                // Verify password
                if (password_verify($password, $alumni_record->password)) {
                    // Login successful - set session
                    $_SESSION['user_id'] = $alumni_record->user_id;
                    $_SESSION['role'] = 'alumni';
                    $_SESSION['alumni_id'] = $alumni_id;
                    $_SESSION['name'] = $alumni_record->name;
                    
                    // Redirect to dashboard
                    redirect('alumni/dashboard');
                    return;
                } else {
                    $errors[] = "Invalid alumni ID or password";
                }
            } else {
                $errors[] = "Invalid alumni ID or password";
            }
        }

        // If we reach here, login failed
        $data['errors'] = $errors;
        $this->view('auth/alumni_login', $data);
    }

    public function logout()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to home page
        redirect('home');
    }
}