<?php
// class Dashboard extends Controller
// {    
//     public function index()
//     {        
//         // $data['name'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->name;

//         if (empty($_SESSION['USER'])) {
//             // guest student
//             $data['username'] = 'Guest';
//             // $data['user_id']       = null;
//             // $data['bio']      = 'No bio available';
//             // $data['mobile']   = 'N/A';
//         } else {
//             // logged-in user
//             $data['username'] = $_SESSION['USER']->name;
//             $data['userrole']       = $_SESSION['USER']->role;
//             $data['useremail']      = $_SESSION['USER']->email;
//             // $data['mobile']   = $_SESSION['USER']->mobile;
//         }
        


//         $this->view('student/dashboard', $data);
//     }
// }



class Dashboard extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Get fresh profile data from database
        $student = new Student();
        $profile = $student->getStudentProfile($_SESSION['student_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('student/auth');
        }

        // Get dashboard statistics (you can expand this)
        $stats = $this->getDashboardStats();

        $data = [
            'title' => 'Student Dashboard - GradBridge',
            'profile' => $profile,  // This contains all the data you need
            'stats' => $stats,
            'user' => $_SESSION  // Session data if needed
        ];

        $this->view('student/dashboard', $data);
    }

    private function getDashboardStats()
    {
        // You can expand this to get real statistics
        return [
            'total_requests' => 0,
            'pending_requests' => 0,
            'mentorship_connections' => 0,
            'events_attended' => 0
        ];
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        redirect('home');
    }
}