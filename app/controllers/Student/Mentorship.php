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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $studentId = $_SESSION['user_id'];

        $data = [
            'mentors' => $this->mentorshipRequestModel->getAvailableMentorsForStudent($studentId),
            'requests' => $this->mentorshipRequestModel->getStudentMentorshipRequests($studentId),
            'activeMentorships' => $this->mentorshipRequestModel->getStudentActiveMentorships($studentId),
            'pendingReviews' => $this->mentorshipRequestModel->getStudentPendingReviews($studentId),
        ];

        $this->view('student/mentorship', $data);
    }

    public function request($mentorUserId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if (!$mentorUserId || !is_numeric($mentorUserId)) {
            $_SESSION['error'] = 'Please select a valid mentor.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $mentor = $this->mentorshipRequestModel->getMentorByUserId((int)$mentorUserId);
        if (!$mentor) {
            $_SESSION['error'] = 'Selected mentor is not available.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        if ($this->mentorshipRequestModel->hasBlockingRequestWithMentor((int)$_SESSION['user_id'], (int)$mentorUserId)) {
            $_SESSION['error'] = 'You already have a pending or active mentorship with this mentor. Complete the current session before requesting again.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $this->view('student/mentorship-request', [
            'mentor' => $mentor,
            'is_edit' => false,
        ]);
    }

    public function sendRequest()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $mentorUserId = (int)($_POST['mentor_user_id'] ?? 0);
        $topic = trim($_POST['topic'] ?? '');
        $reason = trim($_POST['request_reason'] ?? '');

        if ($mentorUserId < 1) {
            $_SESSION['error'] = 'Please choose a mentor.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        if ($topic === '') {
            $_SESSION['error'] = 'Topic is required.';
            header('Location: ' . ROOT . '/student/Mentorship/request/' . $mentorUserId);
            exit;
        }

        if (strlen($reason) < 10) {
            $_SESSION['error'] = 'Please provide a clear description (at least 10 characters).';
            header('Location: ' . ROOT . '/student/Mentorship/request/' . $mentorUserId);
            exit;
        }

        if ($this->mentorshipRequestModel->hasBlockingRequestWithMentor((int)$_SESSION['user_id'], (int)$mentorUserId)) {
            $_SESSION['error'] = 'You already have a pending or active mentorship with this mentor. Complete the current session before requesting again.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $result = $this->mentorshipRequestModel->createDirectedRequest($_SESSION['user_id'], $mentorUserId, $topic, $reason);

        if ($result) {
            $_SESSION['success'] = 'Mentorship request sent successfully.';
        } else {
            $_SESSION['error'] = 'Failed to send mentorship request. Try again.';
        }

        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }

    public function end($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if (!$requestId || !is_numeric($requestId)) {
            $_SESSION['error'] = 'Invalid mentorship request.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $_SESSION['error'] = 'Only mentors can end an active mentorship. You can submit feedback once your mentor ends the session.';

        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }

    public function submitReview($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$requestId || !is_numeric($requestId)) {
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $rating = (int)($_POST['rating'] ?? 0);
        $review = trim($_POST['review_comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Feedback score is required and must be between 1 and 5.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $ok = $this->mentorshipRequestModel->submitReviewByStudent((int)$requestId, (int)$_SESSION['user_id'], $rating, $review);
        if ($ok) {
            $_SESSION['success'] = 'Thanks for your feedback. Mentorship marked as completed.';
        } else {
            $_SESSION['error'] = 'Unable to submit feedback right now.';
        }

        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }

    public function messages($requestId = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
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

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
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
}