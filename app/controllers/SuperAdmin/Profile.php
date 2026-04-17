<?php

class Profile extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as superadmin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        // Check if edit action is requested
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $this->edit();
            return;
        }

        // Check if delete photo action is requested
        if (isset($_GET['action']) && $_GET['action'] === 'delete_photo') {
            $this->deletePhoto();
            return;
        }

        // Get super admin profile data
        $superAdmin = new SuperAdmin();
        $profile = $superAdmin->getSuperAdminProfile($_SESSION['user_id']);
        
        if (!$profile) {
            // Fallback to user data if no super admin record
            $user = new User();
            $profile = $user->first(['user_id' => $_SESSION['user_id'], 'role' => 'super_admin']);
        }
        
        if (!$profile) {
            redirect('superadmin');
        }

        // Get admin statistics
        $stats = $this->getAdminStats();

        $data = [
            'title' => 'Super Admin Profile - GradBridge',
            'page_title' => 'Super Admin Profile',
            'page_subtitle' => 'View and manage your super admin profile information.',
            'profile' => $profile,
            'stats' => $stats
        ];

        $this->view('superadmin/profile/show', $data);
    }

    public function edit()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as superadmin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        // Get super admin profile data
        $superAdmin = new SuperAdmin();
        $profile = $superAdmin->getSuperAdminProfile($_SESSION['user_id']);
        
        if (!$profile) {
            // Fallback to user data if no super admin record
            $user = new User();
            $profile = $user->first(['user_id' => $_SESSION['user_id'], 'role' => 'super_admin']);
        }
        
        if (!$profile) {
            redirect('superadmin');
        }

        $data = [
            'title' => 'Edit Super Admin Profile - GradBridge',
            'page_title' => 'Edit Profile',
            'page_subtitle' => 'Update your super admin profile information and settings.',
            'profile' => $profile,
            'errors' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleUpdate($profile);
        } else {
            $this->view('superadmin/profile/edit', $data);
        }
    }

    private function handleUpdate($current_profile)
    {
        $errors = [];

        // Get form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $linkedin_url = trim($_POST['linkedin_url'] ?? '');
        $github_url = trim($_POST['github_url'] ?? '');
        $twitter_url = trim($_POST['twitter_url'] ?? '');
        $personalweb_url = trim($_POST['personalweb_url'] ?? '');
        $profile_picture = $_FILES['profile_picture'] ?? null;
        $password_current = (string)($_POST['password_current'] ?? '');
        $password_new = (string)($_POST['password_new'] ?? '');
        $password_confirm = (string)($_POST['password_confirm'] ?? '');
        $password_to_update = null;

        // Validation
        if (empty($name)) {
            $errors['name'] = "Name is required";
        }

        if (empty($email)) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }

        if (strlen($bio) > 1000) {
            $errors['bio'] = "Bio must be less than 1000 characters";
        }

        $wants_password_change = ((string)($_POST['change_password'] ?? '0') === '1') || $password_current !== '' || $password_new !== '' || $password_confirm !== '';
        if ($wants_password_change) {
            if ($password_current === '') {
                $errors['password_current'] = "Current password is required";
            }

            if ($password_new === '') {
                $errors['password_new'] = "New password is required";
            }

            if ($password_confirm === '') {
                $errors['password_confirm'] = "Please confirm your new password";
            }

            if ($password_new !== '' && $password_confirm !== '' && $password_new !== $password_confirm) {
                $errors['password_confirm'] = "Passwords do not match";
            }

            if (!isset($errors['password_current'])) {
                $user_for_password = new User();
                $user_auth_row = $user_for_password->first(['user_id' => (int)$_SESSION['user_id']]);
                $stored_password_hash = (string)($user_auth_row->password ?? '');

                $is_current_password_valid = false;
                if ($stored_password_hash !== '') {
                    $is_current_password_valid = password_verify($password_current, $stored_password_hash) || hash_equals($stored_password_hash, $password_current);
                }

                if (!$is_current_password_valid) {
                    $errors['password_current'] = "Current password is incorrect";
                }
            }

            if (!isset($errors['password_new']) && $password_new !== '') {
                $strength = validatePasswordStrength($password_new);
                if (!$strength['valid']) {
                    $errors['password_new'] = implode(' ', $strength['errors']);
                }
            }

            if (!isset($errors['password_current']) && !isset($errors['password_new']) && !isset($errors['password_confirm'])) {
                $password_to_update = password_hash($password_new, PASSWORD_BCRYPT);
            }
        }

        // Validate social media URLs (optional)
        if (!empty($linkedin_url) && !filter_var($linkedin_url, FILTER_VALIDATE_URL)) {
            $errors['linkedin_url'] = "Please enter a valid LinkedIn URL";
        }
        
        if (!empty($github_url) && !filter_var($github_url, FILTER_VALIDATE_URL)) {
            $errors['github_url'] = "Please enter a valid GitHub URL";
        }
        
        if (!empty($twitter_url) && !filter_var($twitter_url, FILTER_VALIDATE_URL)) {
            $errors['twitter_url'] = "Please enter a valid Twitter URL";
        }
        
        if (!empty($personalweb_url) && !filter_var($personalweb_url, FILTER_VALIDATE_URL)) {
            $errors['personalweb_url'] = "Please enter a valid website URL";
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
                if ($password_to_update !== null) $user_data['password'] = $password_to_update;
                if (!empty($user_data)) {
                    $user->update($_SESSION['user_id'], $user_data);
                }

                // Update super_admins table
                $superAdmin = new SuperAdmin();
                $admin_data = [];

                // Keep current picture path if no new upload
                $picture_path = $current_profile->picture_path ?? null;

                // Handle profile picture upload
                if ($profile_picture && $profile_picture['error'] == UPLOAD_ERR_OK) {
                    // Create upload directory in public folder
                    $projectRoot = dirname(__DIR__, 3);
                    $upload_dir = $projectRoot . '/public/assets/uploads/profiles/';
                    if (!is_dir($upload_dir)) {
                        if (!mkdir($upload_dir, 0775, true)) {
                            $errors['profile_picture'] = "Failed to create upload directory";
                        }
                    }

                    // Generate unique filename
                    $file_extension = strtolower(pathinfo($profile_picture['name'], PATHINFO_EXTENSION));
                    $safe_admin_id = preg_replace('/[^A-Za-z0-9_\-]/', '_', $current_profile->super_admin_id ?? $_SESSION['user_id']);
                    $file_name = 'superadmin_profile_' . $safe_admin_id . '_' . time() . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                        $picture_path = ROOT . '/assets/uploads/profiles/' . $file_name;
                        
                        // Delete old profile picture if it exists
                        if (!empty($current_profile->picture_path) && 
                            strpos($current_profile->picture_path, '/assets/uploads/profiles/') !== false) {
                            $old_file = $upload_dir . basename($current_profile->picture_path);
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                    } else {
                        error_log("move_uploaded_file failed for target: $target_file");
                        $errors['profile_picture'] = "Failed to upload profile picture";
                    }
                }

                // Check what data needs to be updated in super_admins table
                if ($bio !== ($current_profile->bio ?? '')) $admin_data['bio'] = $bio ?: null;
                if ($linkedin_url !== ($current_profile->linkedin_url ?? '')) $admin_data['linkedin_url'] = $linkedin_url ?: null;
                if ($github_url !== ($current_profile->github_url ?? '')) $admin_data['github_url'] = $github_url ?: null;
                if ($twitter_url !== ($current_profile->twitter_url ?? '')) $admin_data['twitter_url'] = $twitter_url ?: null;
                if ($personalweb_url !== ($current_profile->personalweb_url ?? '')) $admin_data['personalweb_url'] = $personalweb_url ?: null;
                if ($picture_path !== ($current_profile->picture_path ?? null)) $admin_data['picture_path'] = $picture_path;

                // Update super_admins table if there's data to update
                if (!empty($admin_data) && isset($current_profile->super_admin_id)) {
                    $superAdmin->update($current_profile->super_admin_id, $admin_data, 'super_admin_id');
                }

                // Update session
                $_SESSION['name'] = $name;
                if ($picture_path !== ($current_profile->picture_path ?? null)) {
                    $_SESSION['profile_picture'] = $picture_path;
                }

                $errors['success'] = "Profile updated successfully!";
            } catch (Exception $e) {
                error_log("Super admin profile update error: " . $e->getMessage());
                $errors['general'] = "Failed to update profile: " . $e->getMessage();
            }
        }

        // Show form with errors or success
        $data = [
            'title' => 'Edit Super Admin Profile - GradBridge',
            'page_title' => 'Edit Profile',
            'page_subtitle' => 'Update your super admin profile information and settings.',
            'profile' => $current_profile,
            'errors' => $errors
        ];
        $this->view('superadmin/profile/edit', $data);
    }

    private function deletePhoto()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_photo'])) {
            $superAdmin = new SuperAdmin();
            $profile = $superAdmin->getSuperAdminProfile($_SESSION['user_id']);
            
            if ($profile && !empty($profile->picture_path)) {
                // Delete the file from server
                if (strpos($profile->picture_path, '/assets/uploads/profiles/') !== false) {
                    $projectRoot = dirname(__DIR__, 3);
                    $file_path = $projectRoot . '/public/assets/uploads/profiles/' . basename($profile->picture_path);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                
                // Update database to remove photo URL
                $admin_data = ['picture_path' => null];
                $superAdmin->update($profile->super_admin_id, $admin_data, 'super_admin_id');
                
                // Update session
                $_SESSION['profile_picture'] = null;
            }
        }
        
        // Redirect back to edit profile
        redirect('superadmin/profile?action=edit');
    }

    private function getAdminStats()
    {
        // You can expand this to get real statistics from the database
        return [
            'total_students' => 450,
            'total_alumni' => 320,
            'total_admins' => 24,
            'pending_requests' => 67,
            'events_managed' => 28,
            'mentorship_connections' => 89,
            'system_uptime' => '99.9%'
        ];
    }
}
