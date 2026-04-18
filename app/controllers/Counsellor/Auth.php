<?php

class Auth extends Controller
{
    // TEMP: Development-only login bypass for counsellor routes.
    // Set to false to restore normal email/password authentication.
    private const TEMP_BYPASS_LOGIN = false;

    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // TEMP BYPASS: Auto-login as counsellor without credentials.
        // Remove this block or set TEMP_BYPASS_LOGIN to false before production.
        if (self::TEMP_BYPASS_LOGIN) {
            $_SESSION['user_id'] = 1;
            $_SESSION['role'] = 'counsellor';
            $_SESSION['name'] = $_SESSION['name'] ?? 'Counsellor';

            redirect('counsellor/dashboard');
            exit();
        }

        // Check if user is already logged in as counsellor
        if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'counsellor' && (int)($_SESSION['user_id'] ?? 0) === 1) {
            redirect('counsellor/dashboard');
            exit();
        }

        // Prevent caching of auth pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleLogin();
        } else {
            $this->view('auth/counsellor-login', $data);
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
            $normalizedEmail = strtolower($email);

            $userModel = new User();
            $counsellorModel = new Counsellor();

            $counsellorModel->ensurePrimaryExists();
            $counsellorModel->syncPrimaryFromUsers();

            $userAccount = $userModel->first(['user_id' => Counsellor::PRIMARY_USER_ID]);
            $counsellorAccount = $counsellorModel->getPrimaryCounsellor();

            if ($userAccount && $counsellorAccount) {
                $userRole = strtolower(trim((string)($userAccount->role ?? '')));
                $isCounsellorRole = $userRole === 'counsellor';
                if (!$isCounsellorRole) {
                    $userModel->update(Counsellor::PRIMARY_USER_ID, ['role' => 'counsellor'], 'user_id');
                    $userRole = 'counsellor';
                    $isCounsellorRole = true;
                }

                $userEmail = strtolower(trim((string)($userAccount->email ?? '')));
                $counsellorEmail = strtolower(trim((string)($counsellorAccount->email ?? '')));

                $emailMatchesUsersTable = $userEmail !== '' && hash_equals($userEmail, $normalizedEmail);
                $emailMatchesCounsellorTable = $counsellorEmail !== '' && hash_equals($counsellorEmail, $normalizedEmail);

                $passwordMatchesUsersTable = $this->verifyPassword($password, (string)($userAccount->password ?? ''));
                $passwordMatchesCounsellorTable = $this->verifyPassword($password, (string)($counsellorAccount->password ?? ''));

                if ($isCounsellorRole && $emailMatchesUsersTable && $emailMatchesCounsellorTable && $passwordMatchesUsersTable && $passwordMatchesCounsellorTable) {
                    $_SESSION['user_id'] = Counsellor::PRIMARY_USER_ID;
                    $_SESSION['role'] = 'counsellor';
                    $_SESSION['name'] = $userAccount->name ?? $counsellorAccount->name ?? 'Counsellor';
                    $_SESSION['profile_picture'] = $counsellorAccount->profile_photo_url ?? null;
                    $_SESSION['profile_photo_url'] = $counsellorAccount->profile_photo_url ?? null;

                    redirect('counsellor/dashboard');
                    return;
                }
            }

                $errors[] = "Invalid email or password";
        }

        // If we reach here, login failed
        $data['errors'] = $errors;
        $this->view('auth/counsellor-login', $data);
    }

    private function verifyPassword(string $rawPassword, string $storedHash): bool
    {
        if ($storedHash === '') {
            return false;
        }

        if (password_verify($rawPassword, $storedHash)) {
            return true;
        }

        return hash_equals($storedHash, $rawPassword);
    }

    public function logout()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to counsellor login
        header('Location: ' . ROOT . '/counsellor/');
        exit();
    }
}
