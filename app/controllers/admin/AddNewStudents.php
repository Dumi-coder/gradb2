<?php

class AddNewStudents extends Controller
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
            $student_id = trim($_POST['student_id'] ?? '');
            $academic_year = trim($_POST['academic_year'] ?? '');
            $faculty = trim($_POST['faculty'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($name)) {
                $errors[] = "Name is required";
            }

            if (empty($email)) {
                $errors[] = "University email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            if (empty($student_id)) {
                $errors[] = "Student ID is required";
            }

            if (empty($academic_year) || !is_numeric($academic_year) || $academic_year < 1 || $academic_year > 5) {
                $errors[] = "Academic year must be between 1 and 5";
            }

            if (empty($faculty)) {
                $errors[] = "Faculty is required";
            }

            if (empty($password) || strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }

            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match";
            }

            // Check if email or student_id already exists
            if (empty($errors)) {
                $user = new User();
                if ($user->first(['email' => $email])) {
                    $errors[] = "Email already exists";
                }
                
                $student = new Student();
                if ($student->first(['student_id' => $student_id])) {
                    $errors[] = "Student ID already exists";
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

            // If no errors, create the student
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
                        if (!$student->insert($student_data)) {
                            // Log error for debugging
                            error_log("Failed to insert student data: " . print_r($student_data, true));
                            // Check if record was actually inserted
                            if ($student->first(['student_id' => $student_id])) {
                                // Record exists, show success
                                $_SESSION['success_message'] = "Student registered successfully!";
                                redirect('admin/AddNewStudents');
                                return;
                            } else {
                                $errors[] = "Failed to create student record.";
                            }
                        } else {
                            // Insert succeeded, show success
                            $_SESSION['success_message'] = "Student registered successfully!";
                            redirect('admin/AddNewStudents');
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

        $this->view('admin/add-new-students', $data);
    }
}

