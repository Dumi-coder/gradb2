<?php

class PasswordReset extends Controller
{
    private const ROLE_CONFIG = [
        'student' => [
            'label' => 'Student',
            'id_field' => 'student_id',
            'id_label' => 'Student ID',
        ],
        'alumni' => [
            'label' => 'Alumni',
            'id_field' => 'alumni_id',
            'id_label' => 'Alumni ID',
        ],
        'counselor' => [
            'label' => 'Counselor',
            'id_field' => null,
            'id_label' => null,
        ],
        'faculty_admin' => [
            'label' => 'Faculty Admin',
            'id_field' => null,
            'id_label' => null,
        ],
        'super_admin' => [
            'label' => 'Super Admin',
            'id_field' => null,
            'id_label' => null,
        ],
    ];

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $role = strtolower(trim($_GET['role'] ?? ($_POST['role'] ?? '')));
        if (!isset(self::ROLE_CONFIG[$role])) {
            $role = '';
        }

        $data = [
            'role' => $role,
            'config' => self::ROLE_CONFIG,
            'errors' => [],
            'success' => '',
            'values' => [
                'email' => trim($_POST['email'] ?? ''),
                'student_id' => trim($_POST['student_id'] ?? ''),
                'alumni_id' => trim($_POST['alumni_id'] ?? ''),
            ],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleReset($data);
        }

        $this->view('auth/password_reset', $data);
    }

    private function handleReset(array &$data)
    {
        $errors = [];
        $role = strtolower(trim($_POST['role'] ?? ''));
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $studentId = trim($_POST['student_id'] ?? '');
        $alumniId = trim($_POST['alumni_id'] ?? '');

        if (!isset(self::ROLE_CONFIG[$role])) {
            $errors[] = 'Invalid reset link. Please use the login page to start again.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if ($role === 'student' && $studentId === '') {
            $errors[] = 'Student ID is required.';
        }

        if ($role === 'alumni' && $alumniId === '') {
            $errors[] = 'Alumni ID is required.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        } else {
            $strength = validatePasswordStrength($password);
            if (!$strength['valid']) {
                $errors = array_merge($errors, $strength['errors']);
            }
        }

        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        $userId = null;
        if (empty($errors)) {
            $userId = $this->resolveUserId($role, $email, $studentId, $alumniId, $errors);
        }

        if (!empty($errors)) {
            $data['errors'] = $errors;
            $data['role'] = $role;
            return;
        }

        $user = new User();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $updated = $user->update($userId, ['password' => $hash], 'user_id');
        if (!$updated) {
            $data['errors'] = ['Failed to reset password. Please try again.'];
            $data['role'] = $role;
            return;
        }

        $data['success'] = 'Password updated. You can sign in now.';
        $data['role'] = $role;
        $data['values'] = [
            'email' => '',
            'student_id' => '',
            'alumni_id' => '',
        ];
    }

    private function resolveUserId($role, $email, $studentId, $alumniId, array &$errors)
    {
        if ($role === 'student') {
            $student = new Student();
            $record = $student->getStudentWithUser($studentId);
            if (!$record || empty($record->email) || strcasecmp($record->email, $email) !== 0) {
                $errors[] = 'Student account not found for the provided details.';
                return null;
            }
            return (int)$record->user_id;
        }

        if ($role === 'alumni') {
            $alumni = new Alumni();
            $record = $alumni->getalumniWithUser($alumniId);
            if (!$record || empty($record->email) || strcasecmp($record->email, $email) !== 0) {
                $errors[] = 'Alumni account not found for the provided details.';
                return null;
            }
            return (int)$record->user_id;
        }

        $user = new User();
        $account = $user->first(['email' => $email, 'role' => $role]);
        if (!$account) {
            $errors[] = 'Account not found for the provided details.';
            return null;
        }

        if ($role === 'faculty_admin') {
            $facultyAdmin = new FacultyAdmin();
            $active = $facultyAdmin->getActiveAdminByUserId($account->user_id);
            if (!$active) {
                $errors[] = 'Your admin account is suspended.';
                return null;
            }
        }

        if ($role === 'counselor' && (int)$account->user_id !== 1) {
            $errors[] = 'Access denied for this counselor account.';
            return null;
        }

        return (int)$account->user_id;
    }
}
