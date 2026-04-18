<?php

class ProfileEdit extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counsellor' || (int)($_SESSION['user_id'] ?? 0) !== 1) {
            redirect('counsellor');
        }

        $activeUserId = (int)($_SESSION['user_id'] ?? 0);
        if ($activeUserId <= 0) {
            redirect('counsellor');
            return;
        }

        $counsellorModel = new Counsellor();
        $counsellorModel->ensurePrimaryExists();
        $counsellor = $counsellorModel->getPrimaryCounsellor();

        if (!$counsellor) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Profile not found for your counsellor account.',
            ];
            redirect('counsellor/profile');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdate($counsellor, $activeUserId);
            return;
        }

        $this->view('counsellor/profile-edit', [
            'title' => 'Edit Profile - GradBridge',
            'user' => $counsellor,
            'flashMessage' => null,
        ]);
    }

    private function handleUpdate($counsellor, int $activeUserId)
    {
        $errors = [];
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passwordCurrent = $_POST['password_current'] ?? '';
        $passwordNew = $_POST['password_new'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $changePasswordFlag = (string)($_POST['change_password'] ?? '0');
        $wantsPasswordChange = $changePasswordFlag === '1'
            || trim($passwordCurrent) !== ''
            || trim($passwordNew) !== ''
            || trim($passwordConfirm) !== '';

        if ($name === '') {
            $errors[] = 'Name is required';
        }

        if ($email === '') {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email format is invalid';
        } else {
            $counsellorModel = new Counsellor();
            $existing = $counsellorModel->first(['email' => $email], ['user_id' => $activeUserId]);
            if ($existing) {
                $errors[] = 'Email is already in use';
            }

            $userModel = new User();
            $existingUser = $userModel->first(['email' => $email], ['user_id' => $activeUserId]);
            if ($existingUser) {
                $errors[] = 'Email is already in use';
            }
        }

        $passwordToUpdate = null;
        if ($wantsPasswordChange) {
            $strengthValidation = $passwordNew !== '' ? validatePasswordStrength($passwordNew) : ['valid' => true, 'errors' => []];
            if ($passwordCurrent === '') {
                $errors[] = 'Current password is required to change password';
            } elseif (!$this->verifyCurrentPassword($passwordCurrent, $counsellor)) {
                $errors[] = 'Current password is incorrect';
            } elseif ($passwordNew === '') {
                $errors[] = 'New password is required';
            } elseif (!$strengthValidation['valid']) {
                $errors[] = implode(' ', $strengthValidation['errors']);
            } elseif ($passwordNew !== $passwordConfirm) {
                $errors[] = 'Passwords do not match';
            } else {
                $passwordToUpdate = password_hash($passwordNew, PASSWORD_BCRYPT);
            }
        }

        // Handle profile photo upload
        $profilePhotoUrl = null;
        if (!empty($_FILES['profile_photo']) && is_array($_FILES['profile_photo'])) {
            $file = $_FILES['profile_photo'];
            $fileError = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);

            if ($fileError !== UPLOAD_ERR_NO_FILE) {
                if ($fileError !== UPLOAD_ERR_OK) {
                    $uploadErrorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'Profile photo exceeds server upload limit.',
                        UPLOAD_ERR_FORM_SIZE => 'Profile photo exceeds form upload limit.',
                        UPLOAD_ERR_PARTIAL => 'Profile photo was only partially uploaded.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Server temporary folder is missing.',
                        UPLOAD_ERR_CANT_WRITE => 'Server could not write the uploaded file.',
                        UPLOAD_ERR_EXTENSION => 'Upload was blocked by a server extension.',
                    ];
                    $errors[] = $uploadErrorMessages[$fileError] ?? 'Failed to upload profile photo.';
                } else {
                    $uploadDir = PUBLICPATH . '/assets/uploads/counsellor-profiles/';

                    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                        $errors[] = 'Upload directory is not available.';
                    } else {
                        $fileName = (string)($file['name'] ?? '');
                        $fileTmp = (string)($file['tmp_name'] ?? '');
                        $fileSize = (int)($file['size'] ?? 0);

                        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        if (!in_array($fileExt, $allowedExt, true)) {
                            $errors[] = 'Profile photo must be JPG, PNG, or GIF';
                        } elseif ($fileSize > 5 * 1024 * 1024) {
                            $errors[] = 'Profile photo must be less than 5MB';
                        } else {
                            $newFileName = 'counsellor_' . $activeUserId . '_' . time() . '.' . $fileExt;
                            $uploadPath = $uploadDir . $newFileName;

                            if (move_uploaded_file($fileTmp, $uploadPath)) {
                                $profilePhotoUrl = ROOT . '/assets/uploads/counsellor-profiles/' . $newFileName;
                            } else {
                                $errors[] = 'Failed to move uploaded profile photo.';
                            }
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            $counsellor->name = $name !== '' ? $name : ($counsellor->name ?? '');
            $counsellor->email = $email !== '' ? $email : ($counsellor->email ?? '');

            $this->view('counsellor/profile-edit', [
                'title' => 'Edit Profile - GradBridge',
                'user' => $counsellor,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => implode(' | ', $errors),
                ],
            ]);
            return;
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
        ];

        if ($passwordToUpdate !== null) {
            $updateData['password'] = $passwordToUpdate;
        }

        if ($profilePhotoUrl !== null) {
            $updateData['profile_photo_url'] = $profilePhotoUrl;
        }

        $counsellorModel = new Counsellor();
        $updated = $counsellorModel->updatePrimaryAcrossTables($updateData);

        if ($updated && $profilePhotoUrl !== null) {
            $savedCounsellor = $counsellorModel->getPrimaryCounsellor();
            $savedPhoto = (string)($savedCounsellor->profile_photo_url ?? '');

            if ($savedPhoto === '' || !hash_equals($savedPhoto, $profilePhotoUrl)) {
                $this->view('counsellor/profile-edit', [
                    'title' => 'Edit Profile - GradBridge',
                    'user' => $counsellor,
                    'flashMessage' => [
                        'type' => 'error',
                        'text' => 'Profile photo upload could not be saved to counsellor account. Please try again.',
                    ],
                ]);
                return;
            }
        }

        if ($updated) {
            $_SESSION['name'] = $name;
            if ($profilePhotoUrl !== null) {
                $_SESSION['profile_photo_url'] = $profilePhotoUrl;
                $_SESSION['profile_picture'] = $profilePhotoUrl;
            }
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'Profile updated successfully!',
            ];

            $targetUrl = ROOT . '/counsellor/profile';
            if (!headers_sent()) {
                header('Location: ' . $targetUrl);
                exit;
            }

            echo '<script>window.location.href=' . json_encode($targetUrl) . ';</script>';
            exit;
        } else {
            $this->view('counsellor/profile-edit', [
                'title' => 'Edit Profile - GradBridge',
                'user' => $counsellor,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'Update failed. Please try again.',
                ],
            ]);
        }
    }

    private function verifyCurrentPassword($rawPassword, $counsellor)
    {
        $storedHash = (string)($counsellor->password ?? '');
        if ($storedHash === '') {
            return false;
        }

        if (password_verify($rawPassword, $storedHash)) {
            return true;
        }

        // Fallback for legacy plain-text records.
        return hash_equals($storedHash, $rawPassword);
    }
}
