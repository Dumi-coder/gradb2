<?php

class ResourceModeration extends Controller
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

        // Get resource moderation data
        $resourceData = $this->getResourceData();

        $data = [
            'title' => 'Resource Moderation - GradBridge',
            'page_title' => 'Resource Moderation',
            'page_subtitle' => 'Moderate and manage shared resources from students and alumni.',
            'user' => $_SESSION,
            'resourceData' => $resourceData
        ];

        $this->view('superadmin/resource-moderation', $data);
    }

    private function getResourceData()
    {
        // Mock data for resource moderation
        return [
            'reported_resources' => [
                [
                    'id' => 1,
                    'title' => 'Study Guide for CS 301',
                    'author' => 'Student User',
                    'author_email' => 'student@university.edu',
                    'resource_type' => 'Document',
                    'file_size' => '2.5 MB',
                    'description' => 'Comprehensive study guide for CS 301 final exam',
                    'report_reason' => 'Copyright Violation',
                    'reported_by' => 'Faculty Member',
                    'reported_date' => '2024-01-15',
                    'status' => 'pending',
                    'downloads' => 45
                ],
                [
                    'id' => 2,
                    'title' => 'Career Advice Video',
                    'author' => 'Alumni User',
                    'author_email' => 'alumni@example.com',
                    'resource_type' => 'Video',
                    'file_size' => '15.2 MB',
                    'description' => 'Video sharing career advice for new graduates',
                    'report_reason' => 'Inappropriate Content',
                    'reported_by' => 'Student User',
                    'reported_date' => '2024-01-14',
                    'status' => 'pending',
                    'downloads' => 23
                ]
            ],
            'recent_resources' => [
                [
                    'id' => 3,
                    'title' => 'Resume Template Collection',
                    'author' => 'Career Services',
                    'resource_type' => 'Document',
                    'file_size' => '1.8 MB',
                    'description' => 'Professional resume templates for different industries',
                    'upload_date' => '2024-01-13',
                    'downloads' => 78,
                    'status' => 'approved'
                ],
                [
                    'id' => 4,
                    'title' => 'Interview Tips Guide',
                    'author' => 'Alumni User',
                    'resource_type' => 'Document',
                    'file_size' => '3.2 MB',
                    'description' => 'Tips and strategies for successful job interviews',
                    'upload_date' => '2024-01-12',
                    'downloads' => 56,
                    'status' => 'approved'
                ]
            ],
            'stats' => [
                'total_resources' => 345,
                'reported_resources' => 2,
                'pending_reports' => 2,
                'approved_resources' => 320,
                'total_downloads' => 15678
            ]
        ];
    }
}
