<?php

class Login extends Controller
{

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'student') {
                redirect('student/dashboard');
                return;
            }

            if ($_SESSION['role'] === 'alumni') {
                redirect('alumni/dashboard');
                return;
            }
        }

        // Prevent caching of auth pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }

        $this->view('auth/login', $data);
    }

    private function handleLogin()
    {
        $errors = [];
        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($identifier === '') {
            $errors[] = 'Student ID or Alumni ID is required';
        }

        if ($password === '') {
            $errors[] = 'Password is required';
        }

        if (empty($errors)) {
            $student = new Student();
            $alumni = new Alumni();

            $studentRecord = $student->getStudentWithUser($identifier);
            $alumniRecord = $alumni->getalumniWithUser($identifier);

            $studentMatched = false;
            $alumniMatched = false;
            $blockedMessage = null;

            if ($studentRecord) {
                if (isset($studentRecord->is_deleted) && (int)$studentRecord->is_deleted === 1) {
                    $blockedMessage = 'This account has been deleted. Please contact support if you need assistance.';
                } elseif (isset($studentRecord->is_suspended) && (int)$studentRecord->is_suspended === 1) {
                    $blockedMessage = 'Your account has been suspended';
                } elseif (password_verify($password, (string)$studentRecord->password)) {
                    $studentMatched = true;
                }
            }

            if ($alumniRecord) {
                if (isset($alumniRecord->is_deleted) && (int)$alumniRecord->is_deleted === 1) {
                    $blockedMessage = $blockedMessage ?? 'This account has been deleted. Please contact support if you need assistance.';
                } elseif (isset($alumniRecord->is_suspended) && (int)$alumniRecord->is_suspended === 1) {
                    $blockedMessage = $blockedMessage ?? 'Your account has been suspended';
                } elseif (password_verify($password, (string)$alumniRecord->password)) {
                    $alumniMatched = true;
                }
            }

            if ($studentMatched && !$alumniMatched) {
                $_SESSION['user_id'] = $studentRecord->user_id;
                $_SESSION['role'] = 'student';
                $_SESSION['student_id'] = $studentRecord->student_id ?? $identifier;
                $_SESSION['name'] = $studentRecord->name;
                $_SESSION['profile_picture'] = $studentRecord->profile_photo_url ?? null;

                redirect('student/dashboard');
                return;
            }

            if ($alumniMatched && !$studentMatched) {
                $_SESSION['user_id'] = $alumniRecord->user_id;
                $_SESSION['role'] = 'alumni';
                $_SESSION['alumni_id'] = $alumniRecord->alumni_id ?? $identifier;
                $_SESSION['name'] = $alumniRecord->name;
                $_SESSION['profile_picture'] = $alumniRecord->profile_photo_url ?? null;

                redirect('alumni/dashboard');
                return;
            }

            if ($studentMatched && $alumniMatched) {
                $errors[] = 'Multiple accounts matched this ID. Please use the exact account ID.';
            } elseif ($blockedMessage !== null) {
                $errors[] = $blockedMessage;
            } else {
                $errors[] = 'Invalid credentials';
            }
        }

        $data = [
            'errors' => $errors,
            'identifier' => $identifier,
        ];

        $this->view('auth/login', $data);
    }
}
    