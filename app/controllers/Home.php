<?php
//  echo "Home.php controller<br>";


// class Home extends Controller
// {
//     public function index()
//     {
//         $data = [];
        
//         // Check if user is logged in and provide appropriate data
//         if (Auth::logged_in()) {
//             $data['user'] = Auth::user();
//             $data['user_type'] = Auth::user_type();
//             $data['logged_in'] = true;
            
//             // Add some statistics for logged in users
//             if (Auth::is_student()) {
//                 $alumni = new Alumni();
//                 $data['alumni_count'] = count($alumni->findAll());
//                 $data['faculty_alumni_count'] = count($alumni->where(['faculty' => Auth::user()->faculty]));
//             } elseif (Auth::is_alumni()) {
//                 $student = new Student();
//                 $data['student_count'] = count($student->findAll());
//                 $data['faculty_student_count'] = count($student->where(['faculty' => Auth::user()->faculty]));
//             }
//         } else {
//             $data['logged_in'] = false;
//             // For backward compatibility with your original code
//             $data['username'] = 'Guest';
//         }
        
//         $this->view('home', $data);
//     }
    
//     // Optional: Add a method to handle different user type redirects
//     public function dashboard()
//     {
//         if (!Auth::logged_in()) {
//             redirect('auth/login');
//         }
        
//         if (Auth::is_student()) {
//             redirect('student/dashboard');
//         } elseif (Auth::is_alumni()) {
//             redirect('alumni/dashboard');
//         } else {
//             redirect('auth/login');
//         }
//     }
// }
// This file is part of the application controllers directory.
class Home extends Controller
{
    
    public function index()// This is the index method of the Home controller
    {
        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->name; // Check if the user is logged in and set the username accordingly
        
        $this->view('home',$data);// This loads the 'home' view.
    }
}

