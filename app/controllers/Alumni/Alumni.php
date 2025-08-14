<?php

// class Alumni extends Controller
// {
//     public function index()
//     {
//         // Redirect to dashboard by default
//         $this->dashboard();
//     }

//     public function dashboard()
//     {
//         // Check if user is logged in and is alumni
//         Auth::check_access('alumni');
        
//         $data = [];
//         $data['user'] = Auth::user();
//         $data['user_type'] = Auth::user_type();
        
//         // Get some dashboard statistics
//         $student_model = new StudentModel();
//         $alumni_model = new AlumniModel();
        
//         // You can add more dashboard data here
//         $data['total_students'] = count($student_model->findAll());
//         $data['faculty_students'] = count($student_model->where(['faculty' => Auth::user()->faculty]));
        
//         $this->view('alumni/dashboard', $data);
//     }

//     public function profile()
//     {
//         Auth::check_access('alumni');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $alumni_model = new AlumniModel();
            
//             $update_data = [
//                 'name' => trim($_POST['name']),
//                 'email' => trim($_POST['email']),
//                 'faculty' => trim($_POST['faculty']),
//                 'graduation_year' => trim($_POST['graduation_year'])
//             ];
            
//             // Only update password if provided
//             if (!empty($_POST['new_password'])) {
//                 if ($_POST['new_password'] === $_POST['confirm_password']) {
//                     $update_data['password'] = $_POST['new_password'];
//                 } else {
//                     $data['errors'] = ['Passwords do not match'];
//                 }
//             }
            
//             if (empty($data['errors']) && $alumni_model->updateProfile(Auth::user_id(), $update_data)) {
//                 $data['success'] = 'Profile updated successfully';
//                 // Update session data
//                 $_SESSION['USER'] = $alumni_model->getProfile(Auth::user_id());
//                 $data['user'] = $_SESSION['USER'];
//             }
//         }
        
//         $this->view('alumni/profile', $data);
//     }

//     public function student_network()
//     {
//         Auth::check_access('alumni');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         $student_model = new StudentModel();
        
//         // Get students from same faculty by default
//         $data['students_list'] = $student_model->where(['faculty' => Auth::user()->faculty]);
        
//         // Handle search/filter
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $search_faculty = $_POST['faculty'] ?? '';
            
//             $search_params = [];
//             if (!empty($search_faculty)) {
//                 $search_params['faculty'] = $search_faculty;
//             }
            
//             if (!empty($search_params)) {
//                 $data['students_list'] = $student_model->where($search_params);
//             } else {
//                 $data['students_list'] = $student_model->findAll();
//             }
//         }
        
//         $this->view('alumni/student_network', $data);
//     }

//     public function mentorship()
//     {
//         Auth::check_access('alumni');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         // TODO: Implement mentorship functionality
//         $data['mentorship_requests'] = []; // Placeholder for mentorship requests
//         $data['current_mentees'] = []; // Placeholder for current mentees
        
//         $this->view('alumni/mentorship', $data);
//     }

//     public function messages()
//     {
//         Auth::check_access('alumni');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         // TODO: Implement messaging functionality
//         $data['messages'] = []; // Placeholder for future messaging system
        
//         $this->view('alumni/messages', $data);
//     }

//     public function job_opportunities()
//     {
//         Auth::check_access('alumni');
        
//         $data = [];
//         $data['user'] = Auth::user();
        
//         // TODO: Implement job posting functionality
//         $data['posted_jobs'] = []; // Placeholder for job postings by this alumni
        
//         if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_job'])) {
//             // TODO: Handle job posting
//             $data['success'] = 'Job posting functionality will be implemented';
//         }
        
//         $this->view('alumni/job_opportunities', $data);
//     }
// }

// // Alias for the model to avoid naming conflicts
// class AlumniModel extends Alumni
// {
//     // This is just to distinguish between controller and model
//     // In your actual implementation, keep using the Alumni model class
// }