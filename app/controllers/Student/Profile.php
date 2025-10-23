<?php
ob_start(); // Start output buffering

class Profile extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
                        
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

        $student = new Student();
        $profile = $student->getStudentProfile($_SESSION['student_id']);
        
        if (!$profile) {
            redirect('student/auth');
            
        }

        $data = [
            'title' => 'Student Profile - GradBridge',
            'profile' => $profile
        ];

        $this->view('student/profile/show', $data);
    }

    public function edit()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
            
        }

        $student = new Student();
        $profile = $student->getStudentProfile($_SESSION['student_id']);
        
        if (!$profile) {
            redirect('student/auth');
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
            $this->view('student/profile/edit', $data);
        }
    }
    private function handleUpdate($current_profile)
   {
         $errors = [];
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $faculty = trim($_POST['faculty'] ?? '');
    $academic_year = trim($_POST['academic_year'] ?? '');
    $student_id = trim($_POST['student_id'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $github_url = trim($_POST['github_url'] ?? '');
    $profile_picture = $_FILES['profile_picture'] ?? null;

    // Validation
    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required";
    }
    if (empty($faculty)) {
        $errors['faculty'] = "Faculty is required";
    }
    if (empty($academic_year) || !is_numeric($academic_year) || $academic_year < 1 || $academic_year > 5) {
        $errors['academic_year'] = "Academic year must be between 1 and 5";
    }
    if (empty($student_id)) {
        $errors['student_id'] = "Student ID is required";
    }
    if (!empty($mobile) && !preg_match('/^[7][0-9]{8}$/', $mobile)) {
        $errors['mobile'] = "Mobile number must be 9 digits starting with 7";
    }
    if (strlen($bio) > 1000) {
        $errors['bio'] = "Bio must be less than 1000 characters";
    }

    // Validate faculty exists
    if (empty($errors['faculty'])) {
        $faculty_model = new Faculty();
        $faculty_record = $faculty_model->first(['faculty_name' => $faculty]);
        if (!$faculty_record) {
            $errors['faculty'] = "Invalid faculty selected";
        }
    }

    // Validate student_id and email uniqueness (exclude current student)
    $student = new Student();
    $user = new User();

    if ($student_id !== $current_profile->student_id) {
        $existing_student = $student->first(['student_id' => $student_id], ['student_id' => $current_profile->student_id]);
        if ($existing_student) {
            $errors['student_id'] = "This Student ID is already in use by another student";
        }
    }
    if ($email !== $current_profile->email) {
        $existing_user = $user->first(['email' => $email], ['user_id' => $current_profile->user_id]);
        if ($existing_user) {
            $errors['email'] = "This email is already in use by another user";
        }
    }

    // Validate profile picture
    // if ($profile_picture && $profile_picture['error'] != UPLOAD_ERR_NO_FILE) {
    //     $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    //     if (!in_array($profile_picture['type'], $allowed_types)) {
    //         $errors['profile_picture'] = "Only JPEG, PNG, and GIF images are allowed";
    //     }
    //     if ($profile_picture['size'] > 5000000) {  // 5MB limit
    //         $errors['profile_picture'] = "Profile picture must be less than 5MB";
    //     }
    // }

    // Define upload directory (used for both upload and deletion)
    $upload_dir = '../public/assets/uploads/profiles/';

    $profile_photo_url = $current_profile->profile_photo_url; // Keep current if no new upload
    $new_file_uploaded = false;

    error_log("=== UPLOAD DEBUG ===");
    error_log("Upload directory: $upload_dir");
    error_log("Upload directory exists: " . (is_dir($upload_dir) ? 'yes' : 'no'));
    error_log("Upload directory writable: " . (is_writable($upload_dir) ? 'yes' : 'no'));
     // Validate and handle profile picture upload
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
                elseif ($profile_picture['size'] > 5000000) { // 5MB limit
                    $errors['profile_picture'] = "Profile picture must be less than 5MB";
                }
                else {
                    // Create directory if it doesn't exist
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0775, true);
                    }

                    // Generate unique filename
                    $file_extension = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
                    $safe_student_id = str_replace('/', '_', $_SESSION['student_id']);
                    $file_name = 'profile_' . $safe_student_id . '_' . time() . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                        $profile_photo_url = ROOT . '/assets/uploads/profiles/' . $file_name;
                        $new_file_uploaded = true;
                        error_log("File uploaded successfully: $target_file");
                        
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
            }
    }    if (empty($errors)) {
        try {
            // Update user table (name and email)
            $user = new User();
            $user_data = [];
            if ($name !== $current_profile->name) $user_data['name'] = $name;
            if ($email !== $current_profile->email) $user_data['email'] = $email;
            if (!empty($user_data) && !$user->logUpdate($current_profile->user_id, $user_data, 'user_id')) {
                throw new Exception("Failed to update user data for user_id: {$current_profile->user_id}");
            }

            // Update student table
            $student = new Student();
            $student_data = [];

            if ($student_id !== $current_profile->student_id) $student_data['student_id'] = $student_id;
            if (isset($faculty_record) && $faculty_record->faculty_id != $current_profile->faculty_id) $student_data['faculty_id'] = $faculty_record->faculty_id;
            if ($academic_year != $current_profile->academic_year) $student_data['academic_year'] = $academic_year;
            if ($mobile !== ($current_profile->mobile ?? '')) $student_data['mobile'] = $mobile ?: null;
            if ($bio !== ($current_profile->bio ?? '')) $student_data['bio'] = $bio ?: null;
            if ($linkedin_url !== ($current_profile->LinkedIn ?? '')) $student_data['LinkedIn'] = $linkedin_url ?: null;
            if ($github_url !== ($current_profile->GitHub ?? '')) $student_data['GitHub'] = $github_url ?: null;

            if ($profile_photo_url !== $current_profile->profile_photo_url) {
                $student_data['profile_photo_url'] = $profile_photo_url;
            }

            // Ensure at least one field is updated
            if (!empty($student_data)) {
                if (!$student->logUpdate($_SESSION['student_id'], $student_data, 'student_id')) {
                    throw new Exception("Failed to update student data for student_id: {$_SESSION['student_id']}");
                }
            }
           

            // Update session
            $_SESSION['name'] = $name;
            if ($student_id !== $current_profile->student_id) {
                $_SESSION['student_id'] = $student_id;
            }

            $errors['success'] = "Profile updated successfully!";
        } catch (Exception $e) {
            error_log("Profile update error: " . $e->getMessage() . ", Data: " . print_r($_POST, true));
            $errors['general'] = "Failed to update profile: " . $e->getMessage();
        }
    }

    // Refresh profile data for display
    $student = new Student();
    $updated_profile = $student->getStudentProfile($_SESSION['student_id']);

    // Show form with errors or success
    $data = [
        'title' => 'Edit Profile - GradBridge',
        // 'profile' => $current_profile,
        // 'profile_photo_url' => $profile_photo_url,
        'profile' => $updated_profile ?: $current_profile,
        'errors' => $errors
    ];
    ob_end_flush();
    $this->view('student/profile/edit', $data);
 }

    private function deletePhoto()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_photo'])) {
            $student = new Student();
            $profile = $student->getStudentProfile($_SESSION['student_id']);
            
            if ($profile && !empty($profile->profile_photo_url)) {
                // Delete the file from server
                if (strpos($profile->profile_photo_url, '/assets/uploads/profiles/') !== false) {
                    $file_path = '../public' . str_replace(ROOT, '', $profile->profile_photo_url);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                
                // Update database to remove photo URL
                $student_data = ['profile_photo_url' => null];
                $student->update($_SESSION['student_id'], $student_data, 'student_id');
            }
        }
        
        // Redirect back to edit profile
        redirect('student/profile?action=edit');
    }

    /**
     * Delete the logged-in student account and all related data/resources.
     */
    public function deleteAccount()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Only allow students
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Only accept POST to perform destructive action
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Show confirmation page or redirect back
            redirect('student/profile');
            return;
        }

        $user_id = $_SESSION['user_id'];
        $student_id = $_SESSION['student_id'] ?? null;

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
            if ($student_id) {
                $studentModel = new Student();
                $profile = $studentModel->getStudentProfile($student_id);
                if ($profile && !empty($profile->profile_photo_url)) {
                    if (strpos($profile->profile_photo_url, '/assets/uploads/profiles/') !== false) {
                        $old_file = '../public' . str_replace(ROOT, '', $profile->profile_photo_url);
                        if (file_exists($old_file)) {
                            @unlink($old_file);
                        }
                    }
                }

                // Delete student DB row
                $studentModel->delete($student_id, 'student_id');
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