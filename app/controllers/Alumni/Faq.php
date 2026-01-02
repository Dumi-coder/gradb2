<?php
class Faq extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Get alumni's faculty_id
        $alumniModel = new Alumni();
        $alumni_id = $_SESSION['alumni_id'] ?? null;
        
        if (!$alumni_id) {
            redirect('alumni/auth');
        }

        $profile = $alumniModel->getalumniProfile($alumni_id);
        
        if (!$profile) {
            redirect('alumni/auth');
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

        $this->view('alumni/faq', $data);
    }
}
