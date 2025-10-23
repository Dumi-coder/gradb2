<?php
class Dashboard extends Controller
{    
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Prevent caching of dashboard pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Get fresh profile data from database
        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('alumni/auth');
        }

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get mentorship data for dashboard preview
        $mentorshipData = $this->getMentorshipData();

        $data = [
            'title' => 'Alumni Dashboard - GradBridge',
            'profile' => $profile,  // This contains all the data you need
            'stats' => $stats,
            'mentorshipData' => $mentorshipData,
            'user' => $_SESSION  // Session data if needed
        ];

        $this->view('alumni/dashboard', $data);
    }

    private function getDashboardStats()
    {
        // You can expand this to get real statistics
        return [
            'mentorship_requests' => 0,
            'aid_requests' => 0,
            'events_attended' => 0,
            'total_donations' => 0
        ];
    }
    
    private function getMentorshipData()
    {
        $mentorshipRequestModel = new MentorshipRequest();
        $alumnusId = $_SESSION['user_id'];
        
        // Get pending mentorship requests from students in the same faculty (limit to 1 for dashboard)
        $pendingRequests = $mentorshipRequestModel->getRequestsForAlumnusFaculty($alumnusId);
        
        // Limit to 1 request for dashboard preview
        $limitedRequests = array_slice($pendingRequests, 0, 1);
        
        // Format pending requests for display
        $formattedPendingRequests = [];
        foreach ($limitedRequests as $request) {
            $formattedPendingRequests[] = [
                'id' => $request['request_id'],
                'student_name' => $request['student_name'],
                'student_email' => $request['student_email'],
                'student_id' => $request['student_id'],
                'academic_year' => $request['academic_year'],
                'faculty_name' => $request['faculty_name'],
                'guidance_type' => $request['mentorship_category'] === 'other' ? $request['other_category'] : $request['mentorship_category'],
                'description' => $request['request_reason'],
                'status' => 'pending',
                'created_at' => $request['created_at']
            ];
        }
        
        return [
            'requests' => $formattedPendingRequests
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