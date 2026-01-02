<?php

class FaqModeration extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as superadmin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        // Handle POST actions (publish, unpublish, add, edit, delete)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePostAction();
            return;
        }

        // Get FAQ moderation data
        $faqData = $this->getFaqData();

        $data = [
            'title' => 'FAQ Moderation - GradBridge',
            'page_title' => 'FAQ Moderation',
            'page_subtitle' => 'Manage frequently asked questions and answers.',
            'user' => $_SESSION,
            'faqData' => $faqData
        ];

        $this->view('superadmin/faq-moderation', $data);
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
        $data = [
            'question' => trim($_POST['question'] ?? ''),
            'answer' => trim($_POST['answer'] ?? ''),
            'category' => trim($_POST['category'] ?? 'general'),
            'status' => trim($_POST['status'] ?? 'draft'),
            'priority' => trim($_POST['priority'] ?? 'normal'),
            'faculty_id' => null, // Super admin creates global FAQs
            'visibility_scope' => 'global',
            'created_by_user_id' => $_SESSION['user_id'],
            'created_by_role' => 'super_admin',
            'tags' => trim($_POST['tags'] ?? ''),
            'views' => 0,
            'helpful_count' => 0
        ];

        // Debug: Log the data being inserted
        error_log("SuperAdmin FAQ Insert - faculty_id: " . var_export($data['faculty_id'], true));

        $faqModel = new FaqModel();
        if ($faqModel->insert($data)) {
            echo json_encode(['success' => true, 'message' => 'FAQ added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add FAQ']);
        }
    }

    private function handleEditFaq()
    {
        $faq_id = isset($_POST['faq_id']) ? (int)$_POST['faq_id'] : null;
        
        if (!$faq_id) {
            echo json_encode(['success' => false, 'message' => 'FAQ ID required']);
            return;
        }

        // Get the existing FAQ to check if it's a global FAQ
        $faqModel = new FaqModel();
        $existingFaq = $faqModel->getById($faq_id);
        
        if (!$existingFaq) {
            echo json_encode(['success' => false, 'message' => 'FAQ not found']);
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
        
        // If it's a global FAQ (faculty_id is NULL), keep it that way
        if ($existingFaq->faculty_id === null) {
            $data['faculty_id'] = null;
        }

        if ($faqModel->update($faq_id, $data, 'faq_id')) {
            echo json_encode(['success' => true, 'message' => 'FAQ updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update FAQ']);
        }
    }

    private function getFaqData()
    {
        $faqModel = new FaqModel();
        
        // Get all FAQs (super admin sees everything)
        $faqs = $faqModel->getAllForSuperAdmin();
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
        
        // Get stats (all FAQs)
        $stats = $faqModel->getStats();
        
        // Get categories (all FAQs)
        $categories = $faqModel->getCategories();
        
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
