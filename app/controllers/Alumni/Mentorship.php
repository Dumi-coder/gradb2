<?php
class Mentorship extends Controller
{
    private $mentorshipRequestModel;
    private $mentorshipChatModel;

    public function __construct()
    {
        $this->mentorshipRequestModel = new MentorshipRequest();
        $this->mentorshipChatModel = new MentorshipChat();
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

        if (!$requestId || !is_numeric($requestId)) {
            $_SESSION['error'] = 'Invalid request ID.';
            redirect('Alumni/Mentorship');
        }

        $alumnusId = (int)$_SESSION['user_id'];
        $result = $this->mentorshipRequestModel->acceptRequestForMentor((int)$requestId, $alumnusId);
        
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

        if (!$requestId || !is_numeric($requestId)) {
            $_SESSION['error'] = 'Invalid request ID.';
            redirect('Alumni/Mentorship');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            redirect('Alumni/Mentorship');
        }

        $reason = trim($_POST['rejection_reason'] ?? '');
        if ($reason === '') {
            $_SESSION['error'] = 'Please provide a rejection reason.';
            redirect('Alumni/Mentorship');
        }

        $alumnusId = (int)$_SESSION['user_id'];
        $result = $this->mentorshipRequestModel->rejectRequestForMentor((int)$requestId, $alumnusId, $reason);
        
        if ($result) {
            $_SESSION['success'] = 'Mentorship request rejected.';
        } else {
            $_SESSION['error'] = 'Failed to reject mentorship request.';
        }
        
        redirect('Alumni/Mentorship');
    }

    public function end($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('Alumni/Auth');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            redirect('Alumni/Mentorship');
        }

        if (!$requestId || !is_numeric($requestId)) {
            $_SESSION['error'] = 'Invalid mentorship request.';
            redirect('Alumni/Mentorship');
        }

        $mentorUserId = (int)$_SESSION['user_id'];
        $ok = $this->mentorshipRequestModel->endMentorshipByMentor((int)$requestId, $mentorUserId);

        if ($ok) {
            $_SESSION['success'] = 'Mentorship ended. Student can now submit a required review.';
        } else {
            $_SESSION['error'] = 'Unable to end mentorship right now.';
        }

        redirect('Alumni/Mentorship');
    }

    public function messages($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if (!$requestId || !is_numeric($requestId)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $payload = $this->mentorshipChatModel->getThreadPayload((int)$requestId, (int)$_SESSION['user_id']);

        if (!$payload) {
            echo json_encode(['success' => false, 'message' => 'Chat not available for this mentorship.']);
            exit;
        }

        echo json_encode(['success' => true, 'thread' => $payload['thread'], 'messages' => $payload['messages']]);
        exit;
    }

    public function sendMessage($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$requestId || !is_numeric($requestId)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $message = trim($_POST['message'] ?? '');
        if ($message === '') {
            echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']);
            exit;
        }

        $messageId = $this->mentorshipChatModel->sendMessage((int)$requestId, (int)$_SESSION['user_id'], $message);

        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Message sent.']);
        exit;
    }

    private function getMentorshipData()
    {
        $alumnusId = (int)$_SESSION['user_id'];

        return [
            'requests' => $this->mentorshipRequestModel->getPendingRequestsForMentor($alumnusId),
            'active' => $this->mentorshipRequestModel->getActiveMentorshipsForMentor($alumnusId),
            'completed' => $this->mentorshipRequestModel->getCompletedMentorshipsForMentor($alumnusId),
            'reputation' => $this->mentorshipRequestModel->getMentorReputation($alumnusId),
        ];
    }
}
