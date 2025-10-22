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
    if (!empty($mobile) && !is_numeric($mobile)) {
        $errors['mobile'] = "Mobile number must be numeric";
    }
    if (strlen($bio) > 500) {
        $errors['bio'] = "Bio must be less than 500 characters";
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
    // Using constant is better - but fallback to relative path if not defined
    // Define upload directory (used for both upload and deletion)
    $project_root = '/opt/lampp/htdocs/gradb2';
    $upload_dir = $project_root . '/public/assets/uploads/profiles/';

    $profile_photo_url = $current_profile->profile_photo_url;
    $new_file_uploaded = false;

    error_log("=== UPLOAD DEBUG ===");
    error_log("Project root: $project_root");
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
                elseif ($profile_picture['size'] > 10000000) { // 10MB limit
                    $errors['profile_picture'] = "Profile picture must be less than 10MB";
                }
                else {
                // File is valid, proceed with upload
                // Use absolute path to avoid issues
                // $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/profiles/';
                
                // Create directory if it doesn't exist with more permissive permissions
                if (!is_dir($upload_dir)) {
                    if (!mkdir($upload_dir, 0777, true)) {
                        $errors['profile_picture'] = "Failed to create upload directory: $upload_dir";
                        error_log("Failed to create directory: $upload_dir");
                        error_log("Document root: " . $_SERVER['DOCUMENT_ROOT']);
                    } else {
                        error_log("Directory created successfully: $upload_dir");
                    }
                }

                // // Add this in your handleUpdate method right after defining $upload_dir
                // error_log("Controller file location: " . __FILE__);
                // error_log("Upload directory: $upload_dir");
                // error_log("Directory exists: " . (is_dir($upload_dir) ? 'yes' : 'no'));
                // error_log("Parent directory exists: " . (is_dir(dirname($upload_dir)) ? 'yes' : 'no'));
                
                // Only proceed if directory exists or was created successfully
                if (!isset($errors['profile_picture'])) {
                    // Generate unique filename
                    $file_extension = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
                    // Sanitize student ID - replace slashes with underscores
                    $safe_student_id = str_replace('/', '_', $_SESSION['student_id']);
                    $file_name = 'profile_' . $safe_student_id . '_' . time() . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                        $profile_photo_url = $file_name;
                        $new_file_uploaded = true;
                        error_log("File uploaded successfully: $target_file");
                    } else {
                        $errors['profile_picture'] = "Failed to move uploaded file. Check directory permissions.";
                        error_log("Failed to move file from {$profile_picture['tmp_name']} to $target_file");
                        error_log("Upload directory writable: " . (is_writable($upload_dir) ? 'yes' : 'no'));
                        error_log("Upload directory exists: " . (is_dir($upload_dir) ? 'yes' : 'no'));
                    }
                }
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

            // Update student table
            $student = new Student();
            $student_data = [];

            if ($student_id !== $current_profile->student_id) $student_data['student_id'] = $student_id;
            if (isset($faculty_record) && $faculty_record->faculty_id != $current_profile->faculty_id) $student_data['faculty_id'] = $faculty_record->faculty_id;
            if ($academic_year != $current_profile->academic_year) $student_data['academic_year'] = $academic_year;
            if ($mobile !== $current_profile->mobile) $student_data['mobile'] = $mobile ?: null;
            if ($bio !== $current_profile->bio) $student_data['bio'] = $bio ?: null;


            // Add profile photo URL if a new file was uploaded
            if ($new_file_uploaded) {
                $student_data['profile_photo_url'] = $profile_photo_url;
            }

            // Update student data if there are changes
            if (!empty($student_data)) {
                if (!$student->logUpdate($_SESSION['student_id'], $student_data, 'student_id')) {
                    throw new Exception("Failed to update student data for student_id: {$_SESSION['student_id']}");
                }
            }

            // Delete old profile picture ONLY after successful database update
            if ($new_file_uploaded && $current_profile->profile_photo_url) {
                $old_file = $upload_dir . $current_profile->profile_photo_url;
                if (file_exists($old_file)) {
                    unlink($old_file);
                    error_log("Deleted old profile picture: $old_file");
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

}