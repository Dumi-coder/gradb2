<?php
ob_start(); // Start output buffering

class Auth extends Controller
{
    const TEMP_BYPASS_LOGIN = false; // TEMP: Allow bypass for testing
    
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // TEMP: Bypass login for testing
        if (self::TEMP_BYPASS_LOGIN) {
            $_SESSION['user_id'] = $_SESSION['user_id'] ?? 1;
            $_SESSION['role'] = 'student';
            redirect('student/aid-req-form');
            exit();
        }

        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'student') {
            // header("Location: " . ROOT . "/student/dashboard");
            redirect('student/dashboard');
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
                $this->view('auth/student_signup', $data);
            } else {
                $this->view('auth/student_login', $data);
            }
        }
    }

    private function handleSignup()
    {
        $data = [];
        $errors = [];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $student_id = trim($_POST['student_id'] ?? '');
        // $alumni_id = isset($_POST['alumni_id']) ? trim($_POST['alumni_id']) : null;
        $academic_year = trim($_POST['academic_year'] ?? '');
        $faculty_input = isset($_POST['faculty']) ? trim($_POST['faculty']) : null;
        $password = $_POST['password'] ?? '';
        // $role = (strpos($email, 'alumni') !== false) ? 'alumni' : 'student'; // Determine role based on email

        if (empty($name)) $errors[] = "Name is required";

        if (empty($email)) $errors[] = "University email is required";

        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";

        if (empty($student_id)) $errors[] = "Student ID is required";

        if (empty($academic_year) || !is_numeric($academic_year) || $academic_year < 1 || $academic_year > 5) {
            $errors[] = "Academic year must be between 1 and 5";
        }

        if (empty($faculty_input)) $errors[] = "Faculty is required";

        if (empty($password)) {
            $errors[] = "Password is required";
        } else {
            $passwordValidation = validatePasswordStrength($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }

        // Check if user already exists
        if (empty($errors)) {
            $user = new User();
            if ($user->first(['email' => $email])) {
                $errors[] = "Email already exists";
            }
            
            $student = new Student();
            if ($student->first(['student_id' => $student_id])) {
                $errors[] = "Student ID already exists in the system";
            }
        }
        
        if (empty($errors)) {
            $faculty_model = new Faculty();
            $faculty_record = $faculty_model->first(['faculty_name' => $faculty_input]);
            if (!$faculty_record) $errors[] = "Invalid faculty selected";
        }

        // VERIFY STUDENT EXISTS IN RECORDS TABLE
        // User cannot register if their student_id is not in student_records table
        if (empty($errors) && isset($faculty_record) && $faculty_record) {
            $studentModel = new Student();
            error_log("=== Student Registration Verification ===");
            error_log("Student ID: " . $student_id);
            error_log("Faculty ID: " . $faculty_record->faculty_id);
            
            $exists = $studentModel->existsInRecords($student_id, $faculty_record->faculty_id);
            
            if (!$exists) {
                $errors[] = "Invalid Student ID.";
                error_log("Verification FAILED - Student ID not found in student_records table");
            } else {
                error_log("Verification PASSED - Student ID found in student_records table");
            }
        } elseif (empty($errors) && !isset($faculty_record)) {
            $errors[] = "Faculty verification failed. Please try again.";
        }

        if (empty($errors)) {
            $user_data = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'student'
            ];

            $user = new User();
            if ($user->insert($user_data)) {
                $created_user = $user->first(['email' => $email]);
                
                if ($created_user) {
                    $student_data = [
                        'student_id' => $student_id,
                        'user_id' => $created_user->user_id,
                        'faculty_id' => $faculty_record->faculty_id,
                        'academic_year' => $academic_year
                    ];

                    $student = new Student();
                    $insert_result = $student->insert($student_data);
                    
                    // Log for debugging
                    error_log("Student insert result: " . ($insert_result ? 'true' : 'false'));
                    
                    // Verify student record was created (check database directly)
                    $student_record = $student->first(['student_id' => $student_id]);
                    
                    if ($student_record) {
                        // Student record exists - registration successful
                        // Set session and auto-login the user
                        $_SESSION['user_id'] = $created_user->user_id;
                        $_SESSION['role'] = 'student';
                        $_SESSION['student_id'] = $student_id;
                        $_SESSION['name'] = $name;
                        
                        error_log("Student registration successful - auto-login user: " . $student_id);
                        
                        // Check if AJAX request - must check BEFORE any output
                        $isAjax = false;
                        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                            $isAjax = true;
                        } elseif (isset($_POST['_ajax']) && $_POST['_ajax'] == '1') {
                            $isAjax = true;
                        }
                        
                        if ($isAjax) {
                            // AJAX request - return JSON
                            ob_clean(); // Clear any output buffer
                            header('Content-Type: application/json');
                            echo json_encode(['success' => true, 'redirect' => ROOT . '/student/dashboard']);
                            exit();
                        } else {
                            // Regular request - redirect
                            ob_end_flush();
                            redirect('student/dashboard');
                            exit();
                        }
                    } else {
                        // Record not found - registration failed
                        error_log("Failed to create student record - record not found in database");
                        $errors[] = "Failed to create student record. Please try again.";
                    }
                } else {
                    $errors[] = "Failed to retrieve created user.";
                }
            } else {
                $errors[] = "Failed to create user.";
                error_log("User insert failed for email: " . $email);
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
        
        // Log for debugging
        error_log("Registration errors: " . print_r($errors, true));
        error_log("Is AJAX request: " . ($isAjax ? 'YES' : 'NO'));
        
        // ALWAYS return JSON for AJAX requests - never render view
        if ($isAjax) {
            ob_clean(); // Clear any output buffer first
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-cache, must-revalidate');
            echo json_encode(['success' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Only render view for non-AJAX requests
        $data['errors'] = $errors;
        $this->view('auth/student_signup', $data);
    }



    private function handleLogin()
    {
        $data = [];
        $errors = [];

        // Get form data
        $student_id = trim($_POST['student_id'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($student_id)) {
            $errors[] = "Student ID is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (empty($errors)) {
            // Find student with user data
            $student = new Student();
            $student_record = $student->getStudentWithUser($student_id);

            if ($student_record) {
                // Check if account is deleted
                if (isset($student_record->is_deleted) && $student_record->is_deleted == 1) {
                    $errors[] = "This account has been deleted. Please contact support if you need assistance.";
                }
                // Check if account is suspended
                elseif (isset($student_record->is_suspended) && (int)$student_record->is_suspended === 1) {
                    $errors[] = "Your account has been suspended";
                }
                // Verify password
                elseif (password_verify($password, $student_record->password)) {
                    // Login successful - set session
                    $_SESSION['user_id'] = $student_record->user_id;
                    $_SESSION['role'] = 'student';
                    $_SESSION['student_id'] = $student_id;
                    $_SESSION['name'] = $student_record->name;
                    $_SESSION['profile_picture'] = $student_record->profile_photo_url ?? null;
                    
                    // Redirect to dashboard
                    redirect('student/dashboard');
                    return;
                } else {
                    $errors[] = "Invalid student ID or password";
                }
            } else {
                $errors[] = "Invalid student ID or password";
            }
        }

        // If we reach here, login failed
        $data['errors'] = $errors;
        $this->view('auth/student_login', $data);
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