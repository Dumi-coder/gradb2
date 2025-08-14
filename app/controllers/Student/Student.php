<?php

// class Student extends Controller
// {
//     public function index()
//     {
//         // Redirect to dashboard by default
//         $this->dashboard();
//     }

//     public function dashboard()
//     {
//         // Check if user is logged in and is a student
//         Auth::check_access('student');
        
//         $data = [];
//         $data['user'] = Auth::user();
//         $data['user_type'] = Auth::user_type();
        
//         // Get some dashboard statistics
//         $student_model = new StudentModel();
//         $alumni_model = new AlumniModel();
        
//         // You can add more dashboard data here
//         $data['total_alumni'] = count($alumni_model->findAll());
//         $data['faculty_alumni'] = count($alumni_model->where(['faculty' => Auth::user()->faculty]));
        
//         $this->view('student/dashboard', $data);
//     }

//     public function profile()
//     {
//         Auth::check_access('student');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $student_model = new StudentModel();
            
//             $update_data = [
//                 'name' => trim($_POST['name']),
//                 'email' => trim($_POST['email']),
//                 'faculty' => trim($_POST['faculty'])
//             ];
            
//             // Only update password if provided
//             if (!empty($_POST['new_password'])) {
//                 if ($_POST['new_password'] === $_POST['confirm_password']) {
//                     $update_data['password'] = $_POST['new_password'];
//                 } else {
//                     $data['errors'] = ['Passwords do not match'];
//                 }
//             }
            
//             if (empty($data['errors']) && $student_model->updateProfile(Auth::user_id(), $update_data)) {
//                 $data['success'] = 'Profile updated successfully';
//                 // Update session data
//                 $_SESSION['USER'] = $student_model->getProfile(Auth::user_id());
//                 $data['user'] = $_SESSION['USER'];
//             }
//         }
        
//         $this->view('student/profile', $data);
//     }

//     public function alumni_network()
//     {
//         Auth::check_access('student');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         $alumni_model = new AlumniModel();
        
//         // Get alumni from same faculty by default
//         $data['alumni_list'] = $alumni_model->where(['faculty' => Auth::user()->faculty]);
        
//         // Handle search/filter
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $search_faculty = $_POST['faculty'] ?? '';
//             $search_year = $_POST['graduation_year'] ?? '';
            
//             $search_params = [];
//             if (!empty($search_faculty)) {
//                 $search_params['faculty'] = $search_faculty;
//             }
//             if (!empty($search_year)) {
//                 $search_params['graduation_year'] = $search_year;
//             }
            
//             if (!empty($search_params)) {
//                 $data['alumni_list'] = $alumni_model->where($search_params);
//             } else {
//                 $data['alumni_list'] = $alumni_model->findAll();
//             }
//         }
        
//         $this->view('student/alumni_network', $data);
//     }

//     public function messages()
//     {
//         Auth::check_access('student');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         // TODO: Implement messaging functionality
//         $data['messages'] = []; // Placeholder for future messaging system
        
//         $this->view('student/messages', $data);
//     }

//     public function career_guidance()
//     {
//         Auth::check_access('student');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         // TODO: Implement career guidance features
//         $data['guidance_resources'] = []; // Placeholder for career guidance resources
        
//         $this->view('student/career_guidance', $data);
//     }
// }

// // Alias for the model to avoid naming conflicts
// class StudentModel extends Student
// {
//     // This is just to distinguish between controller and model
//     // In your actual implementation, keep using the Student model class
// }