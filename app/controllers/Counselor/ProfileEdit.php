<?php

class ProfileEdit extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counselor') {
            redirect('counselor');
        }

        $activeUserId = (int)($_SESSION['user_id'] ?? 0);
        if ($activeUserId <= 0) {
            redirect('counselor');
            return;
        }

        $counselorModel = new Counselor();
        $userModel = new User();

        $counselor = $counselorModel->first(['user_id' => $activeUserId]);
        $user = $userModel->first(['user_id' => $activeUserId]);

        if (!$counselor && $user) {
            $seedData = [
                'user_id' => $activeUserId,
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'password' => $user->password ?? '',
            ];
            $counselorModel->insert($seedData);
            $counselor = $counselorModel->first(['user_id' => $activeUserId]);
        }

        if (!$counselor) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'Profile not found for your counselor account.',
            ];
            redirect('counselor/profile');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdate($counselor, $activeUserId);
            return;
        }

        $this->view('counselor/profile-edit', [
            'title' => 'Edit Profile - GradBridge',
            'user' => $counselor,
            'flashMessage' => null,
        ]);
    }

    private function handleUpdate($counselor, int $activeUserId)
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
            $counselorModel = new Counselor();
            $existing = $counselorModel->first(['email' => $email], ['user_id' => $activeUserId]);
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
            } elseif (!$this->verifyCurrentPassword($passwordCurrent, $counselor)) {
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
        if (!empty($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLICPATH . '/assets/uploads/counselor-profiles/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $file = $_FILES['profile_photo'];
            $fileName = $file['name'];
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];
            
            // Validate file
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if (!in_array($fileExt, $allowedExt)) {
                $errors[] = 'Profile photo must be JPG, PNG, or GIF';
            } elseif ($fileSize > 5 * 1024 * 1024) { // 5MB max
                $errors[] = 'Profile photo must be less than 5MB';
            } else {
                // Generate unique filename
                $newFileName = 'counselor_' . $activeUserId . '_' . time() . '.' . $fileExt;
                $uploadPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    $profilePhotoUrl = ROOT . '/assets/uploads/counselor-profiles/' . $newFileName;
                } else {
                    $errors[] = 'Failed to upload profile photo';
                }
            }
        }

        if (!empty($errors)) {
            $counselor->name = $name !== '' ? $name : ($counselor->name ?? '');
            $counselor->email = $email !== '' ? $email : ($counselor->email ?? '');

            $this->view('counselor/profile-edit', [
                'title' => 'Edit Profile - GradBridge',
                'user' => $counselor,
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

        $counselorModel = new Counselor();
        $currentCounselor = $counselorModel->first(['user_id' => $activeUserId]);
        if (!$currentCounselor) {
            $counselorModel->insert([
                'user_id' => $activeUserId,
                'name' => $name,
                'email' => $email,
                'password' => $passwordToUpdate ?? ($counselor->password ?? ''),
                'profile_photo_url' => $profilePhotoUrl,
            ]);
        }

        $updated = $counselorModel->update($activeUserId, $updateData, 'user_id');

        $userUpdateData = [
            'name' => $name,
            'email' => $email,
        ];
        if ($passwordToUpdate !== null) {
            $userUpdateData['password'] = $passwordToUpdate;
        }

        $userModel = new User();
        $userUpdated = $userModel->update($activeUserId, $userUpdateData, 'user_id');

        if ($updated && $userUpdated) {
            $_SESSION['name'] = $name;
            if ($profilePhotoUrl !== null) {
                $_SESSION['profile_photo_url'] = $profilePhotoUrl;
                $_SESSION['profile_picture'] = $profilePhotoUrl;
            }
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'Profile updated successfully!',
            ];
            redirect('counselor/profile');
        } else {
            $this->view('counselor/profile-edit', [
                'title' => 'Edit Profile - GradBridge',
                'user' => $counselor,
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'Update failed. Please try again.',
                ],
            ]);
        }
    }

    private function verifyCurrentPassword($rawPassword, $counselor)
    {
        $storedHash = (string)($counselor->password ?? '');
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
