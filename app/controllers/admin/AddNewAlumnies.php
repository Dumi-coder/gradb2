<?php

class AddNewAlumnies extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if admin is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty_admin') {
            redirect('admin/auth');
            return;
        }

        $data = [];
        $data['errors'] = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $alumni_id = trim($_POST['alumni_id'] ?? '');
            $faculty = trim($_POST['faculty'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($name)) {
                $errors[] = "Name is required";
            }

            if (empty($email)) {
                $errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            if (empty($alumni_id)) {
                $errors[] = "Alumni ID is required";
            }

            if (empty($faculty)) {
                $errors[] = "Faculty is required";
            }

            if (empty($password)) {
                $errors[] = "Password is required";
            } else {
                $passwordValidation = validatePasswordStrength($password);
                if (!$passwordValidation['valid']) {
                    $errors = array_merge($errors, $passwordValidation['errors']);
                }
            }

            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match";
            }

            // Check if email or alumni_id already exists
            if (empty($errors)) {
                $user = new User();
                if ($user->first(['email' => $email])) {
                    $errors[] = "Email already exists";
                }
                
                $alumni = new Alumni();
                if ($alumni->first(['alumni_id' => $alumni_id])) {
                    $errors[] = "Alumni ID already exists";
                }
            }

            // Validate faculty exists
            if (empty($errors)) {
                $faculty_model = new Faculty();
                $faculty_record = $faculty_model->first(['faculty_name' => $faculty]);
                if (!$faculty_record) {
                    $errors[] = "Invalid faculty selected";
                }
            }

            // If no errors, create the alumni
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
                            'faculty_id' => $faculty_record->faculty_id
                        ];

                        $alumni = new Alumni();
                        if (!$alumni->insert($alumni_data)) {
                            // Log error for debugging
                            error_log("Failed to insert alumni data: " . print_r($alumni_data, true));
                            // Check if record was actually inserted
                            if ($alumni->first(['alumni_id' => $alumni_id])) {
                                // Record exists, show success
                                $_SESSION['success_message'] = "Alumni registered successfully!";
                                redirect('admin/AddNewAlumnies');
                                return;
                            } else {
                                $errors[] = "Failed to create alumni record.";
                            }
                        } else {
                            // Insert succeeded, show success
                            $_SESSION['success_message'] = "Alumni registered successfully!";
                            redirect('admin/AddNewAlumnies');
                            return;
                        }
                    } else {
                        $errors[] = "Failed to retrieve created user.";
                    }
                } else {
                    $errors[] = "Failed to create user.";
                }
            }

            $data['errors'] = $errors;
        }

        // Check for success message
        if (isset($_SESSION['success_message'])) {
            $data['success'] = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }

        $this->view('admin/add-new-alumnies', $data);
    }
}

