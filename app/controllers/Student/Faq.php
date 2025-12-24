<?php
class Faq extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Get student's faculty_id
        $studentModel = new Student();
        $student_id = $_SESSION['student_id'] ?? null;
        
        if (!$student_id) {
            redirect('student/auth');
        }

        $profile = $studentModel->getStudentProfile($student_id);
        
        if (!$profile) {
            redirect('student/auth');
        }

        $faculty_id = isset($profile->faculty_id) ? (int)$profile->faculty_id : null;

        // Load FAQs visible to this faculty (global + same faculty)
        $faqs = [];
        
        if ($faculty_id !== null) {
            try {
                $faqModel = new FaqModel();
                $result = $faqModel->getVisibleForFaculty($faculty_id);
                $faqs = is_array($result) ? $result : [];
            } catch (Exception $e) {
                // If table doesn't exist yet, return empty array
                $faqs = [];
                if (defined('DEBUG') && DEBUG) {
                    error_log("FAQ Error: " . $e->getMessage());
                }
            } catch (Error $e) {
                // Handle fatal errors (like missing class or table)
                $faqs = [];
                if (defined('DEBUG') && DEBUG) {
                    error_log("FAQ Fatal Error: " . $e->getMessage());
                }
            }
        }

        $data = [
            'faqs' => $faqs,
        ];

        $this->view('student/faq', $data);
    }
}