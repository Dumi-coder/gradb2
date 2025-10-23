<?php
class Profile extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }
        
        if(isset($_GET['action']) && $_GET['action'] === 'edit') {
            $this->edit();
            return;
        }

        if(isset($_GET['action']) && $_GET['action'] === 'delete_photo') {
            $this->deletePhoto();
            return;
        }
        if(isset($_GET['action']) && $_GET['action'] === 'delete_account') {
            $this->deleteAccount();
            return;
        }

        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            redirect('alumni/auth');
        }

        $data = [
            'title' => 'Alumni Profile - GradBridge',
            'profile' => $profile
        ];

        $this->view('alumni/profile/show', $data);
    }

    public function edit()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            redirect('alumni/auth');
            exit();
        }

        $data = [
            'title' => 'Edit Profile - GradBridge',
            'profile' => $profile,
            'errors' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleUpdate($profile);
        } else {
            $this->view('alumni/profile/edit', $data);
        }
    }

    private function handleUpdate($current_profile)
    {
        $errors = [];
        // Get form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $degrees = trim($_POST['degrees'] ?? '');
        $graduation_year = trim($_POST['graduation_year'] ?? '');
        $current_workplace = trim($_POST['current_workplace'] ?? '');
        $current_job = trim($_POST['current_job'] ?? '');
        $expertise_area = trim($_POST['expertise_area'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $linkedin_url = trim($_POST['linkedin_url'] ?? '');
        $github_url = trim($_POST['github_url'] ?? '');
        $twitter_url = trim($_POST['twitter_url'] ?? '');
        $personal_website = trim($_POST['personal_website'] ?? '');
        $profile_picture = $_FILES['profile_picture'] ?? null;

        // Validation
        if (empty($name)) {
            $errors['name'] = "Name is required";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Valid email is required";
        }
        if (empty($degrees)) {
            $errors['degrees'] = "Degree is required";
        }
        if (empty($graduation_year) || !is_numeric($graduation_year) || $graduation_year < 1950 || $graduation_year > date('Y')) {
            $errors['graduation_year'] = "Graduation year must be between 1950 and current year";
        }
        if (!empty($mobile) && !preg_match('/^[7][0-9]{8}$/', $mobile)) {
            $errors['mobile'] = "Mobile number must be 9 digits starting with 7";
        }
        if (strlen($bio) > 1000) {
            $errors['bio'] = "Bio must be less than 1000 characters";
        }

        // Validate profile picture
        if ($profile_picture && $profile_picture['error'] != UPLOAD_ERR_NO_FILE) {
            if ($profile_picture['error'] != UPLOAD_ERR_OK) {
                $errors['profile_picture'] = "Upload error occurred";
            } else {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                $detected_type = finfo_file($file_info, $profile_picture['tmp_name']);
                finfo_close($file_info);
                
                if (!in_array($detected_type, $allowed_types)) {
                    $errors['profile_picture'] = "Only JPEG, PNG, and GIF images are allowed";
                }
                if ($profile_picture['size'] > 5000000) { // 5MB limit
                    $errors['profile_picture'] = "Profile picture must be less than 5MB";
                }
            }
        }

        if (empty($errors)) {
            try {
                // Update user table (name and email)
                $user = new User();
                $user_data = [];
                if ($name !== $current_profile->name) $user_data['name'] = $name;
                if ($email !== $current_profile->email) $user_data['email'] = $email;
                if (!empty($user_data) && !$user->logUpdate($current_profile->user_id, $user_data, 'user_id')) {
                    throw new Exception("Failed to update user data for user_id: {$current_profile->user_id}");
                }

                // Update alumni table
                $alumni = new Alumni();
                $alumni_data = [];

                if ($degrees !== ($current_profile->degrees ?? '')) $alumni_data['degrees'] = $degrees;
                if ($graduation_year != $current_profile->graduation_year) $alumni_data['graduated_year'] = $graduation_year;
                if ($current_workplace !== ($current_profile->current_workplace ?? '')) $alumni_data['current_workplace'] = $current_workplace ?: null;
                if ($current_job !== ($current_profile->current_job ?? '')) $alumni_data['current_job'] = $current_job ?: null;
                if ($expertise_area !== ($current_profile->expertise_area ?? '')) $alumni_data['expertise_area'] = $expertise_area ?: null;
                if ($mobile !== ($current_profile->mobile ?? '')) $alumni_data['mobile'] = $mobile ?: null;
                if ($bio !== ($current_profile->bio ?? '')) $alumni_data['bio'] = $bio ?: null;
                if ($linkedin_url !== ($current_profile->linkedin_url ?? '')) $alumni_data['linkedin_url'] = $linkedin_url ?: null;
                if ($github_url !== ($current_profile->github_url ?? '')) $alumni_data['github_url'] = $github_url ?: null;
                if ($twitter_url !== ($current_profile->twitter_url ?? '')) $alumni_data['twitter_url'] = $twitter_url ?: null;
                if ($personal_website !== ($current_profile->personal_website ?? '')) $alumni_data['personal_website'] = $personal_website ?: null;

                $profile_photo_url = $current_profile->profile_photo_url; // Keep current if no new upload

                // Handle profile picture upload
                if ($profile_picture && $profile_picture['error'] == UPLOAD_ERR_OK) {
                        // Create upload directory in public folder (use absolute path from project root)
                        $projectRoot = dirname(__DIR__, 3);
                        $upload_dir = $projectRoot . '/public/assets/uploads/profiles/';
                        if (!is_dir($upload_dir)) {
                            if (!mkdir($upload_dir, 0775, true)) {
                                $errors['profile_picture'] = "Failed to create upload directory";
                            }
                        }

                    // Generate unique filename (sanitize alumni id to avoid path separators / special chars)
                    $file_extension = strtolower(pathinfo($profile_picture['name'], PATHINFO_EXTENSION));
                    $safe_alumni_id = isset($_SESSION['alumni_id']) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION['alumni_id']) : 'unknown';
                    $file_name = 'profile_' . $safe_alumni_id . '_' . time() . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                        $profile_photo_url = ROOT . '/assets/uploads/profiles/' . $file_name;
                        
                        // Delete old profile picture if it exists
                        if ($current_profile->profile_photo_url && 
                            strpos($current_profile->profile_photo_url, '/assets/uploads/profiles/') !== false) {
                            // Build absolute path to the old file safely
                            $old_file = $upload_dir . basename($current_profile->profile_photo_url);
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                    } else {
                        error_log("move_uploaded_file failed for target: $target_file, tmp: " . ($profile_picture['tmp_name'] ?? '')); 
                        $errors['profile_picture'] = "Failed to upload profile picture";
                    }
                }

                if ($profile_photo_url !== $current_profile->profile_photo_url) {
                    $alumni_data['profile_photo_url'] = $profile_photo_url;
                }

                // Ensure at least one field is updated
                if (!empty($alumni_data)) {
                    if (!$alumni->logUpdate($_SESSION['alumni_id'], $alumni_data, 'alumni_id')) {
                        throw new Exception("Failed to update alumni data for alumni_id: {$_SESSION['alumni_id']}");
                    }
                }

                // Update session
                $_SESSION['name'] = $name;

                $errors['success'] = "Profile updated successfully!";
            } catch (Exception $e) {
                error_log("Profile update error: " . $e->getMessage() . ", Data: " . print_r($_POST, true));
                $errors['general'] = "Failed to update profile: " . $e->getMessage();
            }
        }

        // Show form with errors or success
        $data = [
            'title' => 'Edit Profile - GradBridge',
            'profile' => $current_profile,
            'errors' => $errors
        ];
        $this->view('alumni/profile/edit', $data);
    }

    private function deletePhoto()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_photo'])) {
            $alumni = new Alumni();
            $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
            
            if ($profile && !empty($profile->profile_photo_url)) {
                // Delete the file from server
                if (strpos($profile->profile_photo_url, '/assets/uploads/profiles/') !== false) {
                    $projectRoot = dirname(__DIR__, 3);
                    $file_path = $projectRoot . '/public/assets/uploads/profiles/' . basename($profile->profile_photo_url);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                
                // Update database to remove photo URL
                $alumni_data = ['profile_photo_url' => null];
                $alumni->update($_SESSION['alumni_id'], $alumni_data, 'alumni_id');
            }
        }
        
        // Redirect back to edit profile
        redirect('alumni/profile?action=edit');
    }

    /**
     * Delete the logged-in alumni account and all related data/resources.
     */
    public function deleteAccount()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Only allow alumni
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Only accept POST to perform destructive action
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Show confirmation page or redirect back
            redirect('alumni/profile');
            return;
        }

        $user_id = $_SESSION['user_id'];
        $alumni_id = $_SESSION['alumni_id'] ?? null;

        // Delete uploaded resources files and DB rows
        try {
            $resourceModel = new SharedResource();
            $resources = $resourceModel->where(['user_id' => $user_id]);
            if ($resources) {
                foreach ($resources as $r) {
                    if (!empty($r->file_path)) {
                        $fileName = basename($r->file_path);
                        $physicalPath = RESOURCE_UPLOAD_PATH . $fileName;
                        if (file_exists($physicalPath)) {
                            @unlink($physicalPath);
                        }
                    }
                    // delete DB row
                    $resourceModel->delete($r->resource_id, 'resource_id');
                }
            }

            // Delete profile photo file if exists
            if ($alumni_id) {
                $alumniModel = new Alumni();
                $profile = $alumniModel->getalumniProfile($alumni_id);
                if ($profile && !empty($profile->profile_photo_url)) {
                    if (strpos($profile->profile_photo_url, '/assets/uploads/profiles/') !== false) {
                        $old_file = '../public' . str_replace(ROOT, '', $profile->profile_photo_url);
                        if (file_exists($old_file)) {
                            @unlink($old_file);
                        }
                    }
                }

                // Delete alumni DB row
                $alumniModel->delete($alumni_id, 'alumni_id');
            }

            // Delete user row
            $userModel = new User();
            $userModel->delete($user_id, 'user_id');

        } catch (Exception $e) {
            error_log('Account deletion error: ' . $e->getMessage());
            // Continue to destroy session and redirect to home even if cleanup fails partially
        }

        // Destroy session and redirect to public home
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        // Redirect to public home
        header('Location: ' . ROOT . '/');
        exit();
    }
}