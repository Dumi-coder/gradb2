<?php

// class AuthController extends Controller
// {
//     // Display login page
//     public function login()
//     {
//         $data = [];
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $email = trim($_POST['email']);
//             $password = trim($_POST['password']);
//             $user_type = $_POST['user_type'] ?? 'student';
            
//             if (empty($email) || empty($password)) {
//                 $data['errors'] = ['Please fill in all fields'];
//             } else {
//                 $auth_data = [
//                     'email' => $email,
//                     'password' => $password,
//                     'user_type' => $user_type
//                 ];
                
//                 if (Auth::authenticate($auth_data)) {
//                     // Redirect based on user type
//                     if (Auth::is_student()) {
//                         redirect('student/dashboard');
//                     } elseif (Auth::is_alumni()) {
//                         redirect('alumni/dashboard');
//                     }
//                 } else {
//                     $data['errors'] = ['Invalid email or password'];
//                 }
//             }
//         }
        
//         $this->view('auth/login', $data);
//     }

//     // Student signup
//     public function student_signup()
//     {
//         $data = [];
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
//             $student = new Student();
            
//             $signup_data = [
//                 'name' => trim($_POST['name']),
//                 'email' => trim($_POST['email']),
//                 'student_id' => trim($_POST['user_id']), // Note: your form uses 'user_id'
//                 'faculty' => trim($_POST['faculty']),
//                 'password' => $_POST['password'],
//                 'confirm_password' => $_POST['confirm_password']
//             ];
            
//             if ($student->signup($signup_data)) {
//                 // Auto login after successful signup
//                 $auth_data = [
//                     'email' => $signup_data['email'],
//                     'password' => $signup_data['password'],
//                     'user_type' => 'student'
//                 ];
                
//                 if (Auth::authenticate($auth_data)) {
//                     redirect('student/dashboard');
//                 } else {
//                     redirect('login');
//                 }
//             } else {
//                 $data['errors'] = $student->errors;
//             }
//         }
        
//         $this->view('auth/student_signup', $data);
//     }

//     // Alumni signup
//     public function alumni_signup()
//     {
//         $data = [];
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
//             $alumni = new Alumni();
            
//             $signup_data = [
//                 'name' => trim($_POST['name']),
//                 'email' => trim($_POST['email']),
//                 'alumni_id' => trim($_POST['alumni_id']),
//                 'faculty' => trim($_POST['faculty']),
//                 'graduation_year' => trim($_POST['graduation_year']),
//                 'password' => $_POST['password'],
//                 'confirm_password' => $_POST['confirm_password']
//             ];
            
//             if ($alumni->signup($signup_data)) {
//                 // Auto login after successful signup
//                 $auth_data = [
//                     'email' => $signup_data['email'],
//                     'password' => $signup_data['password'],
//                     'user_type' => 'alumni'
//                 ];
                
//                 if (Auth::authenticate($auth_data)) {
//                     redirect('alumni/dashboard');
//                 } else {
//                     redirect('login');
//                 }
//             } else {
//                 $data['errors'] = $alumni->errors;
//             }
//         }
        
//         $this->view('auth/alumni_signup', $data);
//     }

//     // Logout
//     public function logout()
//     {
//         Auth::logout();
//         redirect('login');
//     }

//     // Dashboard redirect
//     public function dashboard()
//     {
//         if (!Auth::logged_in()) {
//             redirect('login');
//         }
        
//         if (Auth::is_student()) {
//             redirect('student/dashboard');
//         } elseif (Auth::is_alumni()) {
//             redirect('alumni/dashboard');
//         } else {
//             redirect('login');
//         }
//     }

//     // Forgot password (placeholder for future implementation)
//     public function forgot_password()
//     {
//         $data = [];
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $email = trim($_POST['email']);
//             $user_type = $_POST['user_type'] ?? 'student';
            
//             if (empty($email)) {
//                 $data['errors'] = ['Please enter your email address'];
//             } else {
//                 // TODO: Implement password reset functionality
//                 $data['message'] = 'Password reset functionality will be implemented soon';
//             }
//         }
        
//         $this->view('auth/forgot_password', $data);
//     }

//     // Profile management
//     public function profile()
//     {
//         Auth::check_access();
        
//         $data = [];
//         $data['user'] = Auth::user();
//         $data['user_type'] = Auth::user_type();
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             if (Auth::is_student()) {
//                 $student = new Student();
//                 $update_data = [
//                     'name' => trim($_POST['name']),
//                     'email' => trim($_POST['email']),
//                     'faculty' => trim($_POST['faculty'])
//                 ];
                
//                 if (!empty($_POST['password'])) {
//                     $update_data['password'] = $_POST['password'];
//                 }
                
//                 if ($student->updateProfile(Auth::user_id(), $update_data)) {
//                     $data['success'] = 'Profile updated successfully';
//                     // Update session data
//                     $_SESSION['USER'] = $student->getProfile(Auth::user_id());
//                     $data['user'] = $_SESSION['USER'];
//                 }
//             } elseif (Auth::is_alumni()) {
//                 $alumni = new Alumni();
//                 $update_data = [
//                     'name' => trim($_POST['name']),
//                     'email' => trim($_POST['email']),
//                     'faculty' => trim($_POST['faculty']),
//                     'graduation_year' => trim($_POST['graduation_year'])
//                 ];
                
//                 if (!empty($_POST['password'])) {
//                     $update_data['password'] = $_POST['password'];
//                 }
                
//                 if ($alumni->updateProfile(Auth::user_id(), $update_data)) {
//                     $data['success'] = 'Profile updated successfully';
//                     // Update session data
//                     $_SESSION['USER'] = $alumni->getProfile(Auth::user_id());
//                     $data['user'] = $_SESSION['USER'];
//                 }
//             }
//         }
        
//         $this->view('auth/profile', $data);
//     }
// }