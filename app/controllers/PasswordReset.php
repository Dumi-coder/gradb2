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
            'step' => 'request',
            'masked_email' => '',
            'values' => [
                'email' => trim($_POST['email'] ?? ''),
                'student_id' => trim($_POST['student_id'] ?? ''),
                'alumni_id' => trim($_POST['alumni_id'] ?? ''),
                'otp' => trim($_POST['otp'] ?? ''),
            ],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleReset($data);
        } else {
            $sessionStep = $_SESSION['reset_step'] ?? '';
            if (in_array($sessionStep, ['confirm', 'verify', 'reset'], true)) {
                $data['step'] = $sessionStep;
            }
            if ($data['step'] === 'confirm') {
                $data['masked_email'] = $this->maskEmail((string)($_SESSION['reset_email'] ?? ''));
            }
        }

        $this->view('auth/password_reset', $data);
    }

    private function handleReset(array &$data)
    {
        $errors = [];
        $role = strtolower(trim($_POST['role'] ?? ''));
        $stage = trim($_POST['stage'] ?? 'request');
        $email = trim($_POST['email'] ?? '');
        $studentId = trim($_POST['student_id'] ?? '');
        $alumniId = trim($_POST['alumni_id'] ?? '');
        $otp = trim($_POST['otp'] ?? '');
        if ($otp === '' && !empty($_POST['otp_digits']) && is_array($_POST['otp_digits'])) {
            $otp = implode('', array_map('trim', $_POST['otp_digits']));
        }

        $data['role'] = $role;
        $data['step'] = $stage;

        if (!isset(self::ROLE_CONFIG[$role])) {
            $data['errors'] = ['Invalid reset link. Please use the login page to start again.'];
            return;
        }

        if ($stage === 'request') {
            $userInfo = $this->resolveUserForOtp($role, $email, $studentId, $alumniId, $errors);
            if (!empty($errors)) {
                $data['errors'] = $errors;
                return;
            }

            $_SESSION['reset_user_id'] = $userInfo['user_id'];
            $_SESSION['reset_email'] = $userInfo['email'];
            $_SESSION['reset_role'] = $role;
            $_SESSION['otp_verified'] = false;
            $_SESSION['reset_step'] = 'confirm';
            $this->redirectToSelf($role);
        }

        if ($stage === 'send_otp') {
            $userId = $_SESSION['reset_user_id'] ?? null;
            $email = $_SESSION['reset_email'] ?? '';
            if (!$userId || $email === '') {
                $data['errors'] = ['Session expired. Please start again.'];
                $data['step'] = 'request';
                return;
            }

            if (!$this->canRequestOtp($userId)) {
                $data['errors'] = ['Please wait before requesting another OTP.'];
                $data['step'] = 'confirm';
                $data['masked_email'] = $this->maskEmail($email);
                return;
            }

            $otpValue = (string)random_int(100000, 999999);
            $otpHash = password_hash($otpValue, PASSWORD_DEFAULT);
            $expiresAt = $this->addMinutesUtc(OTP_EXP_MINUTES);

            $reset = new Passwordresetotp();
            $inserted = $reset->query(
                'INSERT INTO password_resets (user_id, otp_hash, expires_at, request_ip) VALUES (:user_id, :otp_hash, :expires_at, :request_ip)',
                [
                    'user_id' => $userId,
                    'otp_hash' => $otpHash,
                    'expires_at' => $expiresAt,
                    'request_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                ]
            );

            if (!$inserted || !$this->sendOtpEmail($email, $otpValue)) {
                $data['errors'] = ['Unable to send OTP. Please try again.'];
                $data['step'] = 'confirm';
                $data['masked_email'] = $this->maskEmail($email);
                return;
            }

            $_SESSION['reset_step'] = 'verify';
            $this->redirectToSelf($role);
        }

        if ($stage === 'verify_otp') {
            $userId = $_SESSION['reset_user_id'] ?? null;
            if (!$userId) {
                $data['errors'] = ['Session expired. Please start again.'];
                $data['step'] = 'request';
                return;
            }

            if (!preg_match('/^[0-9]{6}$/', $otp)) {
                $data['errors'] = ['Invalid OTP format.'];
                $data['step'] = 'verify';
                return;
            }

            $row = $this->getLatestReset($userId);
            if (!$row) {
                $data['errors'] = ['No active OTP found. Please request a new OTP.'];
                $data['step'] = 'request';
                return;
            }

            if ($this->isExpiredUtc((string)$row->expires_at)) {
                $data['errors'] = ['OTP expired. Please request a new OTP.'];
                $data['step'] = 'request';
                return;
            }

            $attempts = (int)$row->attempts;
            if ($attempts >= OTP_MAX_ATTEMPTS) {
                $data['errors'] = ['Too many attempts. Please request a new OTP.'];
                $data['step'] = 'request';
                return;
            }

            if (!password_verify($otp, $row->otp_hash)) {
                $attempts++;
                $reset = new Passwordresetotp();
                $reset->query('UPDATE password_resets SET attempts = :attempts WHERE id = :id', [
                    'attempts' => $attempts,
                    'id' => $row->id,
                ]);
                $data['errors'] = ['Incorrect OTP.'];
                $data['step'] = 'verify';
                return;
            }

            $reset = new Passwordresetotp();
            $reset->query('UPDATE password_resets SET used_at = :used_at WHERE id = :id', [
                'used_at' => $this->nowUtc(),
                'id' => $row->id,
            ]);

            $_SESSION['otp_verified'] = true;
            $_SESSION['reset_step'] = 'reset';
            $this->redirectToSelf($role);
        }

        if ($stage === 'reset_password') {
            $userId = $_SESSION['reset_user_id'] ?? null;
            $otpVerified = $_SESSION['otp_verified'] ?? false;
            $resetRole = $_SESSION['reset_role'] ?? $role;
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (!$userId || !$otpVerified) {
                $data['errors'] = ['Unauthorized. Please verify OTP first.'];
                $data['step'] = 'request';
                return;
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

            if (!empty($errors)) {
                $data['errors'] = $errors;
                $data['step'] = 'reset';
                return;
            }

            $user = new User();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $updated = $user->update($userId, ['password' => $hash], 'user_id');
            if (!$updated) {
                $data['errors'] = ['Failed to reset password. Please try again.'];
                $data['step'] = 'reset';
                return;
            }

            $reset = new Passwordresetotp();
            $reset->query('DELETE FROM password_resets WHERE user_id = :user_id', ['user_id' => $userId]);

            unset($_SESSION['otp_verified'], $_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['reset_role'], $_SESSION['reset_step']);

            $loginPath = $this->getLoginPathByRole((string)$resetRole);
            header('Location: ' . ROOT . '/' . ltrim($loginPath, '/'));
            exit;
        }
    }

    private function resolveUserForOtp($role, $email, $studentId, $alumniId, array &$errors)
    {
        if ($role === 'student') {
            $student = new Student();
            $record = $student->getStudentWithUser($studentId);
            if (!$record || empty($record->email)) {
                if (ctype_digit($studentId)) {
                    $user = new User();
                    $account = $user->first(['user_id' => (int)$studentId, 'role' => 'student']);
                    if ($account && !empty($account->email)) {
                        return [
                            'user_id' => (int)$account->user_id,
                            'email' => (string)$account->email,
                        ];
                    }
                }
                $errors[] = 'Student account not found for the provided details.';
                return null;
            }
            return [
                'user_id' => (int)$record->user_id,
                'email' => (string)$record->email,
            ];
        }

        if ($role === 'alumni') {
            $alumni = new Alumni();
            $record = $alumni->getalumniWithUser($alumniId);
            if (!$record || empty($record->email)) {
                if (ctype_digit($alumniId)) {
                    $user = new User();
                    $account = $user->first(['user_id' => (int)$alumniId, 'role' => 'alumni']);
                    if ($account && !empty($account->email)) {
                        return [
                            'user_id' => (int)$account->user_id,
                            'email' => (string)$account->email,
                        ];
                    }
                }
                $errors[] = 'Alumni account not found for the provided details.';
                return null;
            }
            return [
                'user_id' => (int)$record->user_id,
                'email' => (string)$record->email,
            ];
        }

        $user = new User();
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
            return null;
        }
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

        return [
            'user_id' => (int)$account->user_id,
            'email' => (string)$account->email,
        ];
    }

    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }
        $name = $parts[0];
        $domain = $parts[1];
        $visible = substr($name, 0, 2);
        return $visible . str_repeat('*', max(1, strlen($name) - 2)) . '@' . $domain;
    }

    private function canRequestOtp(int $userId): bool
    {
        $reset = new Passwordresetotp();
        $row = $reset->query(
            'SELECT created_at FROM password_resets WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1',
            ['user_id' => $userId]
        );
        if (!$row || !is_array($row) || !isset($row[0]->created_at)) {
            return true;
        }
        $lastTime = strtotime($row[0]->created_at);
        if (!$lastTime) {
            return true;
        }
        return (time() - $lastTime) >= OTP_RATE_LIMIT_SECONDS;
    }

    private function getLatestReset(int $userId)
    {
        $reset = new Passwordresetotp();
        $row = $reset->query(
            'SELECT id, otp_hash, expires_at, attempts FROM password_resets WHERE user_id = :user_id AND used_at IS NULL ORDER BY id DESC LIMIT 1',
            ['user_id' => $userId]
        );
        return $row && is_array($row) ? $row[0] : false;
    }

    private function isExpiredUtc(string $expiresAt): bool
    {
        try {
            $expiry = new DateTime($expiresAt, new DateTimeZone('UTC'));
            $now = new DateTime('now', new DateTimeZone('UTC'));
            return $expiry < $now;
        } catch (Exception $e) {
            error_log('[PasswordReset] Invalid expires_at value: ' . $expiresAt);
            return true;
        }
    }

    private function sendOtpEmail(string $to, string $otp): bool
    {
        $subject = 'Your ' . APP_NAME . ' password reset OTP';
        $message = "Your OTP is: {$otp}\n\nThis code expires in " . OTP_EXP_MINUTES . " minutes.";

            $autoload = __DIR__ . '/../../otp/mailer/vendor/autoload.php';
        if (!file_exists($autoload)) {
            error_log('[PasswordReset] PHPMailer autoload not found: ' . $autoload);
            return false;
        }

        require_once $autoload;

        try {
            $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
            $mailer->isSMTP();
            $mailer->Host = SMTP_HOST;
            $mailer->SMTPAuth = true;
            $mailer->Username = SMTP_USER;
            $mailer->Password = SMTP_PASS;
            $mailer->SMTPSecure = SMTP_SECURE;
            $mailer->Port = SMTP_PORT;

            $mailer->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mailer->addAddress($to);
            $mailer->Subject = $subject;
            $mailer->Body = $message;

            return $mailer->send();
        } catch (Exception $e) {
            error_log('[PasswordReset] PHPMailer send failed: ' . $e->getMessage());
            return false;
        }
    }

    private function nowUtc(): string
    {
        $dt = new DateTime('now', new DateTimeZone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }

    private function addMinutesUtc(int $minutes): string
    {
        $dt = new DateTime('now', new DateTimeZone('UTC'));
        $dt->modify('+' . $minutes . ' minutes');
        return $dt->format('Y-m-d H:i:s');
    }

    private function redirectToSelf(string $role): void
    {
        header('Location: ' . ROOT . '/PasswordReset?role=' . urlencode($role));
        exit;
    }

    private function getLoginPathByRole(string $role): string
    {
        $map = [
            'student' => 'student/Auth?action=login',
            'alumni' => 'alumni/Auth?action=login',
            'counselor' => 'counselor',
            'faculty_admin' => 'admin',
            'super_admin' => 'superadmin',
        ];

        return $map[$role] ?? 'login';
    }
}
