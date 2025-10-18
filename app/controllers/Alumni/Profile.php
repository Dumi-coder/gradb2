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
        $degree = trim($_POST['degree'] ?? '');
        $graduation_year = trim($_POST['graduation_year'] ?? '');
        $current_job = trim($_POST['current_job'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $profile_picture = $_FILES['profile_picture'] ?? null;

        // Validation
        if (empty($name)) {
            $errors['name'] = "Name is required";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Valid email is required";
        }
        if (empty($degree)) {
            $errors['degree'] = "Degree is required";
        }
        if (empty($graduation_year) || !is_numeric($graduation_year) || $graduation_year < 1950 || $graduation_year > date('Y')) {
            $errors['graduation_year'] = "Graduation year must be between 1950 and current year";
        }
        if (strlen($bio) > 500) {
            $errors['bio'] = "Bio must be less than 500 characters";
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

                if ($degree !== $current_profile->degree) $alumni_data['degree'] = $degree;
                if ($graduation_year != $current_profile->graduation_year) $alumni_data['graduation_year'] = $graduation_year;
                if ($current_job !== $current_profile->current_job) $alumni_data['current_job'] = $current_job ?: null;
                if ($location !== $current_profile->location) $alumni_data['location'] = $location ?: null;
                if ($bio !== $current_profile->bio) $alumni_data['bio'] = $bio ?: null;

                $profile_photo_url = $current_profile->profile_photo_url; // Keep current if no new upload

                // Handle profile picture upload
                if ($profile_picture && $profile_picture['error'] == UPLOAD_ERR_OK) {
                    // Create upload directory in public folder
                    $upload_dir = '../public/assets/uploads/profiles/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Generate unique filename
                    $file_extension = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
                    $file_name = 'profile_' . $_SESSION['alumni_id'] . '_' . time() . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                        $profile_photo_url = ROOT . '/assets/uploads/profiles/' . $file_name;
                        
                        // Delete old profile picture if it exists
                        if ($current_profile->profile_photo_url && 
                            strpos($current_profile->profile_photo_url, '/assets/uploads/profiles/') !== false) {
                            $old_file = '../public' . str_replace(ROOT, '', $current_profile->profile_photo_url);
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                    } else {
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
}