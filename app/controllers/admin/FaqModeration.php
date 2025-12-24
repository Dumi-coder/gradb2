<?php

class FaqModeration extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Handle POST actions (publish, unpublish, add, edit, delete)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePostAction();
            return;
        }

        // Get faculty_id for this admin from faculty_admins table
        $faculty_id = $this->getFacultyIdForAdmin();
        
        if (!$faculty_id) {
            // If faculty_id not found, show detailed error
            $user_id = $_SESSION['user_id'] ?? 'NOT SET';
            $error_msg = "Faculty admin configuration error.<br><br>";
            $error_msg .= "Debug Information:<br>";
            $error_msg .= "- User ID in session: " . $user_id . "<br>";
            $error_msg .= "- Role: " . ($_SESSION['role'] ?? 'NOT SET') . "<br>";
            $error_msg .= "<br>Please ensure:<br>";
            $error_msg .= "1. Your user account is linked to a faculty in the faculty_admins table<br>";
            $error_msg .= "2. The faculty_id field is set in your faculty_admins record<br>";
            $error_msg .= "<br>Check the error log for more details or contact administrator.";
            die($error_msg);
        }

        // Get FAQ moderation data
        $faqData = $this->getFaqData($faculty_id);

        $data = [
            'title' => 'FAQ Moderation - GradBridge',
            'page_title' => 'FAQ Moderation',
            'page_subtitle' => 'Manage frequently asked questions and answers.',
            'user' => $_SESSION,
            'faqData' => $faqData
        ];

        $this->view('admin/faq-moderation', $data);
    }

    private function getFacultyIdForAdmin()
    {
        // Get faculty_id from faculty_admins table
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            error_log("getFacultyIdForAdmin: No user_id in session");
            return null;
        }
        
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $query = "SELECT faculty_id FROM faculty_admins WHERE user_id = :user_id";
            $stm = $con->prepare($query);
            $stm->execute(['user_id' => $user_id]);
            
            $result = $stm->fetch(PDO::FETCH_OBJ);
            
            if ($result && $result->faculty_id) {
                error_log("getFacultyIdForAdmin: Found faculty_id {$result->faculty_id} for user_id {$user_id}");
                return $result->faculty_id;
            } else {
                error_log("getFacultyIdForAdmin: No faculty_admin record found for user_id {$user_id}");
                return null;
            }
        } catch (PDOException $e) {
            error_log("getFacultyIdForAdmin: Database error - " . $e->getMessage());
        }
        
        return null;
    }

    private function handlePostAction()
    {
        $action = $_POST['action'] ?? '';
        $faq_id = isset($_POST['faq_id']) ? (int)$_POST['faq_id'] : null;
        
        $faqModel = new FaqModel();
        
        switch ($action) {
            case 'publish':
                if ($faq_id) {
                    if ($faqModel->publish($faq_id)) {
                        echo json_encode(['success' => true, 'message' => 'FAQ published successfully']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to publish FAQ']);
                    }
                }
                break;
                
            case 'unpublish':
                if ($faq_id) {
                    if ($faqModel->unpublish($faq_id)) {
                        echo json_encode(['success' => true, 'message' => 'FAQ unpublished successfully']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to unpublish FAQ']);
                    }
                }
                break;
                
            case 'add':
                $this->handleAddFaq();
                break;
                
            case 'edit':
                $this->handleEditFaq();
                break;
                
            case 'delete':
                if ($faq_id) {
                    if ($faqModel->delete($faq_id, 'faq_id')) {
                        echo json_encode(['success' => true, 'message' => 'FAQ deleted successfully']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to delete FAQ']);
                    }
                }
                break;
        }
        exit();
    }

    private function handleAddFaq()
    {
        // Get faculty_id from faculty_admins table
        $faculty_id = $this->getFacultyIdForAdmin();
        
        if (!$faculty_id) {
            echo json_encode(['success' => false, 'message' => 'Faculty ID not found. Please contact administrator.']);
            return;
        }

        $data = [
            'question' => trim($_POST['question'] ?? ''),
            'answer' => trim($_POST['answer'] ?? ''),
            'category' => trim($_POST['category'] ?? 'general'),
            'status' => trim($_POST['status'] ?? 'draft'),
            'priority' => trim($_POST['priority'] ?? 'normal'),
            'faculty_id' => $faculty_id,
            'visibility_scope' => 'faculty',
            'created_by_user_id' => $_SESSION['user_id'],
            'created_by_role' => 'faculty_admin',
            'tags' => trim($_POST['tags'] ?? ''),
            'views' => 0,
            'helpful_count' => 0
        ];

        $faqModel = new FaqModel();
        if ($faqModel->insert($data)) {
            echo json_encode(['success' => true, 'message' => 'FAQ added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add FAQ']);
        }
    }

    private function handleEditFaq()
    {
        try {
            $faq_id = isset($_POST['faq_id']) ? (int)$_POST['faq_id'] : null;
            
            if (!$faq_id) {
                echo json_encode(['success' => false, 'message' => 'FAQ ID required']);
                return;
            }

            $data = [
                'question' => trim($_POST['question'] ?? ''),
                'answer' => trim($_POST['answer'] ?? ''),
                'category' => trim($_POST['category'] ?? 'general'),
                'status' => trim($_POST['status'] ?? 'draft'),
                'priority' => trim($_POST['priority'] ?? 'normal'),
                'tags' => trim($_POST['tags'] ?? '')
            ];

            $faqModel = new FaqModel();
            if ($faqModel->update($faq_id, $data, 'faq_id')) {
                echo json_encode(['success' => true, 'message' => 'FAQ updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update FAQ']);
            }
        } catch (Exception $e) {
            error_log("Edit FAQ error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    private function getFaqData($faculty_id)
    {
        $faqModel = new FaqModel();
        
        // Get FAQs for this faculty
        $faqs = $faqModel->getByFaculty($faculty_id);
        $faqs = is_array($faqs) ? $faqs : [];
        
        // Format FAQs for view
        $faq_items = [];
        foreach ($faqs as $faq) {
            $faq_items[] = [
                'id' => $faq->faq_id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'category' => $faq->category,
                'status' => $faq->status,
                'priority' => $faq->priority ?? 'normal',
                'tags' => $faq->tags ?? '',
                'created_date' => $faq->created_at ?? date('Y-m-d'),
                'last_updated' => $faq->updated_at ?? date('Y-m-d'),
                'views' => $faq->views ?? 0,
                'helpful' => $faq->helpful_count ?? 0
            ];
        }
        
        // Get stats
        $stats = $faqModel->getStats($faculty_id);
        
        // Get categories
        $categories = $faqModel->getCategories($faculty_id);
        
        return [
            'faq_items' => $faq_items,
            'categories' => $categories,
            'stats' => [
                'total_faqs' => $stats->total_faqs ?? 0,
                'published_faqs' => $stats->published_faqs ?? 0,
                'pending_faqs' => $stats->pending_faqs ?? 0,
                'draft_faqs' => $stats->draft_faqs ?? 0,
                'total_views' => $stats->total_views ?? 0
            ]
        ];
    }
}
