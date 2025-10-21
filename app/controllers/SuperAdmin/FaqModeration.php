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

    private function getFaqData()
    {
        // Mock data for FAQ moderation
        return [
            'faq_items' => [
                [
                    'id' => 1,
                    'question' => 'How do I apply for financial aid?',
                    'answer' => 'To apply for financial aid, you need to complete the FAFSA form and submit it along with your university application.',
                    'category' => 'Financial Aid',
                    'status' => 'published',
                    'created_date' => '2024-01-10',
                    'last_updated' => '2024-01-15',
                    'views' => 245,
                    'helpful' => 18
                ],
                [
                    'id' => 2,
                    'question' => 'What are the requirements for mentorship?',
                    'answer' => 'To be eligible for mentorship, you must be a current student or recent graduate with a minimum GPA of 3.0.',
                    'category' => 'Mentorship',
                    'status' => 'pending',
                    'created_date' => '2024-01-12',
                    'last_updated' => '2024-01-12',
                    'views' => 0,
                    'helpful' => 0
                ],
                [
                    'id' => 3,
                    'question' => 'How can I access alumni resources?',
                    'answer' => 'Alumni resources are available through the alumni portal. You can access them by logging into your alumni account.',
                    'category' => 'Alumni Services',
                    'status' => 'published',
                    'created_date' => '2024-01-08',
                    'last_updated' => '2024-01-14',
                    'views' => 189,
                    'helpful' => 12
                ],
                [
                    'id' => 4,
                    'question' => 'What is the process for event registration?',
                    'answer' => 'Event registration can be done through the events page. Simply click on the event you want to attend and follow the registration process.',
                    'category' => 'Events',
                    'status' => 'draft',
                    'created_date' => '2024-01-13',
                    'last_updated' => '2024-01-13',
                    'views' => 0,
                    'helpful' => 0
                ]
            ],
            'categories' => [
                'Financial Aid',
                'Mentorship',
                'Alumni Services',
                'Events',
                'General'
            ],
            'stats' => [
                'total_faqs' => 45,
                'published_faqs' => 42,
                'pending_faqs' => 2,
                'draft_faqs' => 1,
                'total_views' => 12540
            ]
        ];
    }
}
