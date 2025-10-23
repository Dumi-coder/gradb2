<?php 
class MentorshipReq extends Controller
{
    private $mentorshipRequestModel;

    public function __construct()
    {
        $this->mentorshipRequestModel = new MentorshipRequest();
    }

    public function index()
    {
        // Check if user is logged in and is a student
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        $studentId = $_SESSION['user_id'];
        $requests = $this->mentorshipRequestModel->getRequestsByStudent($studentId);
        
        $data = [
            'requests' => $requests ? $requests : []
        ];
        
        $this->view('student/mentorship-request', $data);
    }

    public function create()
    {
        // Check if user is logged in and is a student
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_user_id' => $_SESSION['user_id'],
                'mentorship_category' => $_POST['mentorship_category'] ?? '',
                'other_mentorship_category' => $_POST['other_mentorship_category'] ?? '',
                'request_reason' => $_POST['request_reason'] ?? ''
            ];

            // Validate input
            if (empty($data['mentorship_category'])) {
                $_SESSION['error'] = 'Please select a mentorship category.';
                header('Location: ' . ROOT . '/student/MentorshipReq');
                exit;
            }
            
            if ($data['mentorship_category'] === 'other' && empty($data['other_mentorship_category'])) {
                $_SESSION['error'] = 'Please specify your mentorship category.';
                header('Location: ' . ROOT . '/student/MentorshipReq');
                exit;
            }
            
            if (empty($data['request_reason'])) {
                $_SESSION['error'] = 'Please provide a reason for your mentorship request.';
                header('Location: ' . ROOT . '/student/MentorshipReq');
                exit;
            }

            $result = $this->mentorshipRequestModel->create($data);
            
            if ($result) {
                $_SESSION['success'] = 'Mentorship request submitted successfully!';
                header('Location: ' . ROOT . '/student/Mentorship');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to submit mentorship request. Please try again.';
                header('Location: ' . ROOT . '/student/MentorshipReq');
                exit;
            }
        }

        $this->view('student/mentorship-request');
    }

    public function edit($requestId = null)
    {
        // Check if user is logged in and is a student
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if (!$requestId) {
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $studentId = $_SESSION['user_id'];
        $request = $this->mentorshipRequestModel->getRequestById($requestId, $studentId);

        if (!$request) {
            $_SESSION['error'] = 'Mentorship request not found or you do not have permission to edit it.';
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'mentorship_category' => $_POST['mentorship_category'] ?? '',
                'other_mentorship_category' => $_POST['other_mentorship_category'] ?? '',
                'request_reason' => $_POST['request_reason'] ?? ''
            ];

            // Validate input
            if (empty($data['mentorship_category'])) {
                $_SESSION['error'] = 'Please select a mentorship category.';
                header('Location: ' . ROOT . '/student/MentorshipReq/edit/' . $requestId);
                exit;
            }
            
            if ($data['mentorship_category'] === 'other' && empty($data['other_mentorship_category'])) {
                $_SESSION['error'] = 'Please specify your mentorship category.';
                header('Location: ' . ROOT . '/student/MentorshipReq/edit/' . $requestId);
                exit;
            }
            
            if (empty($data['request_reason'])) {
                $_SESSION['error'] = 'Please provide a reason for your mentorship request.';
                header('Location: ' . ROOT . '/student/MentorshipReq/edit/' . $requestId);
                exit;
            }

            $result = $this->mentorshipRequestModel->updateRequest($requestId, $studentId, $data);
            
            if ($result) {
                $_SESSION['success'] = 'Mentorship request updated successfully!';
                header('Location: ' . ROOT . '/student/Mentorship');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to update mentorship request. Please try again.';
                header('Location: ' . ROOT . '/student/MentorshipReq/edit/' . $requestId);
                exit;
            }
        }

        $data = [
            'request' => $request,
            'is_edit' => true
        ];
        
        $this->view('student/mentorship-request', $data);
    }

    public function delete($requestId = null)
    {
        // Check if user is logged in and is a student
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . ROOT . '/student/login');
            exit;
        }

        if (!$requestId) {
            header('Location: ' . ROOT . '/student/Mentorship');
            exit;
        }

        $studentId = $_SESSION['user_id'];
        $result = $this->mentorshipRequestModel->deleteRequest($requestId, $studentId);
        
        if ($result) {
            $_SESSION['success'] = 'Mentorship request deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete mentorship request or you do not have permission to delete it.';
        }
        
        header('Location: ' . ROOT . '/student/Mentorship');
        exit;
    }
}

