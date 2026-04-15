<?php

class ProfileEdit extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counselor' || (int)$_SESSION['user_id'] !== 1) {
            redirect('counselor');
        }

        $activeUserId = 1;

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
                'text' => 'Profile not found for user_id 1 in counselor/users tables.',
            ];
            redirect('counselor/profile');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdate($counselor);
            return;
        }

        $this->view('counselor/profile-edit', [
            'title' => 'Edit Profile - GradBridge',
            'user' => $counselor,
            'flashMessage' => null,
        ]);
    }

    private function handleUpdate($counselor)
    {
        $errors = [];
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passwordCurrent = $_POST['current_password'] ?? '';
        $passwordNew = $_POST['new_password'] ?? '';
        $passwordConfirm = $_POST['confirm_password'] ?? '';

        $passwordValidation = validatePasswordChange($counselor->password ?? '', $passwordCurrent, $passwordNew, $passwordConfirm);

        if ($name === '') {
            $errors[] = 'Name is required';
        }

        if ($email === '') {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email format is invalid';
        } else {
            $counselorModel = new Counselor();
            $existing = $counselorModel->first(['email' => $email]);
            if ($existing && $existing->user_id != 1) {
                $errors[] = 'Email is already in use';
            }
        }

        if (!$passwordValidation['valid']) {
            $errors[] = implode(' | ', $passwordValidation['errors']);
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
                $newFileName = 'counselor_1_' . time() . '.' . $fileExt;
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
                'errors' => [
                    'password' => implode(' | ', $errors),
                ],
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

        if (!empty($passwordValidation['password_hash'])) {
            $updateData['password'] = $passwordValidation['password_hash'];
        }

        if ($profilePhotoUrl !== null) {
            $updateData['profile_photo_url'] = $profilePhotoUrl;
        }

        $counselorModel = new Counselor();
        $updated = $counselorModel->update(1, $updateData, 'user_id');

        $userUpdateData = [
            'name' => $name,
            'email' => $email,
        ];
        if (!empty($passwordValidation['password_hash'])) {
            $userUpdateData['password'] = $passwordValidation['password_hash'];
        }

        $userModel = new User();
        $userModel->update(1, $userUpdateData, 'user_id');

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
            redirect('counselor/profile');
        } else {
            $this->view('counselor/profile-edit', [
                'title' => 'Edit Profile - GradBridge',
                'user' => $counselor,
                'errors' => [],
                'flashMessage' => [
                    'type' => 'error',
                    'text' => 'Update failed. Please try again.',
                ],
            ]);
        }
    }

}
