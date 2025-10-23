<?php
class Mentorship extends Controller
{
    private $mentorshipRequestModel;

    public function __construct()
    {
        $this->mentorshipRequestModel = new MentorshipRequest();
    }

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

        // Get fresh profile data from database
        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('alumni/auth');
        }

        // Get real mentorship data from database
        $mentorshipData = $this->getMentorshipData();

        $data = [
            'title' => 'Mentorship - GradBridge',
            'profile' => $profile,
            'mentorshipData' => $mentorshipData,
            'user' => $_SESSION
        ];

        $this->view('alumni/mentorship', $data);
    }

    public function accept($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('Alumni/Auth');
        }

        if (!$requestId) {
            $_SESSION['error'] = 'Invalid request ID.';
            redirect('Alumni/Mentorship');
        }

        $alumnusId = $_SESSION['user_id'];
        $result = $this->mentorshipRequestModel->acceptRequest($requestId, $alumnusId);
        
        if ($result) {
            $_SESSION['success'] = 'Mentorship request accepted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to accept mentorship request.';
        }
        
        redirect('Alumni/Mentorship');
    }

    public function reject($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('Alumni/Auth');
        }

        if (!$requestId) {
            $_SESSION['error'] = 'Invalid request ID.';
            redirect('Alumni/Mentorship');
        }

        $alumnusId = $_SESSION['user_id'];
        $result = $this->mentorshipRequestModel->rejectRequest($requestId, $alumnusId);
        
        if ($result) {
            $_SESSION['success'] = 'Mentorship request rejected.';
        } else {
            $_SESSION['error'] = 'Failed to reject mentorship request.';
        }
        
        redirect('Alumni/Mentorship');
    }

    private function getMentorshipData()
    {
        $alumnusId = $_SESSION['user_id'];
        
        // Get pending mentorship requests from students in the same faculty
        $pendingRequests = $this->mentorshipRequestModel->getRequestsForAlumnusFaculty($alumnusId);
        
        // Get accepted mentorship requests
        $acceptedRequests = $this->mentorshipRequestModel->getAcceptedRequestsForAlumnus($alumnusId);
        
        // Format pending requests for display
        $formattedPendingRequests = [];
        foreach ($pendingRequests as $request) {
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
        
        // Format accepted requests for display
        $formattedAcceptedRequests = [];
        foreach ($acceptedRequests as $request) {
            $formattedAcceptedRequests[] = [
                'id' => $request['request_id'],
                'student_name' => $request['student_name'],
                'student_email' => $request['student_email'],
                'student_id' => $request['student_id'],
                'academic_year' => $request['academic_year'],
                'faculty_name' => $request['faculty_name'],
                'mentorship_type' => $request['mentorship_category'] === 'other' ? $request['other_category'] : $request['mentorship_category'],
                'description' => $request['request_reason'],
                'status' => 'active',
                'created_at' => $request['created_at']
            ];
        }
        
        return [
            'requests' => $formattedPendingRequests,
            'active' => $formattedAcceptedRequests,
            'completed' => [] // TODO: Implement completed mentorships tracking
        ];
    }
}
