<?php

class Fundraisers extends Controller
{
    public function index()
    {
        // Placeholder data to be displayed in the view
        $current_page = 'fundraisers';

        $activeFundraisers = [
            [
                'title' => 'Emergency Medical Support',
                'created_by' => 'Alumni Medical Society',
                'goal' => 5000,
                'collected' => 1200,
                'status' => 'Active',
                'priority' => 'urgent',
            ],
            [
                'title' => 'Student Emergency Fund',
                'created_by' => 'Jane Doe',
                'goal' => 10000,
                'collected' => 6500,
                'status' => 'Active',
                'priority' => 'normal',
            ],
            [
                'title' => 'Research Equipment Fund',
                'created_by' => 'Alumni Research Group',
                'goal' => 20000,
                'collected' => 8000,
                'status' => 'Active',
                'priority' => 'normal',
            ],
            [
                'title' => 'Career Development Workshop',
                'created_by' => 'Career Services Alumni',
                'goal' => 7500,
                'collected' => 3200,
                'status' => 'Active',
                'priority' => 'normal',
            ],
        ];

        // Sort active fundraisers to put urgent ones first
        usort($activeFundraisers, function($a, $b) {
            if ($a['priority'] === 'urgent' && $b['priority'] !== 'urgent') {
                return -1;
            }
            if ($b['priority'] === 'urgent' && $a['priority'] !== 'urgent') {
                return 1;
            }
            return 0;
        });

        $completedFundraisers = [
            [
                'title' => 'Library Modernization',
                'goal' => 15000,
                'raised' => 15000,
                'completed_at' => '2025-06-15',
                'proof_url' => '#',
                'status' => 'Completed',
            ],
            [
                'title' => 'Scholarship Support 2024',
                'goal' => 25000,
                'raised' => 26000,
                'completed_at' => '2024-12-20',
                'proof_url' => '#',
                'status' => 'Completed',
            ],
            [
                'title' => 'Computer Lab Upgrade',
                'goal' => 18000,
                'raised' => 18500,
                'completed_at' => '2025-03-10',
                'proof_url' => '#',
                'status' => 'Completed',
            ],
            [
                'title' => 'Sports Equipment Fund',
                'goal' => 8000,
                'raised' => 8200,
                'completed_at' => '2025-01-25',
                'proof_url' => '#',
                'status' => 'Completed',
            ],
            [
                'title' => 'Student Mental Health Support',
                'goal' => 12000,
                'raised' => 13500,
                'completed_at' => '2024-11-30',
                'proof_url' => '#',
                'status' => 'Completed',
            ],
            [
                'title' => 'Alumni Networking Platform',
                'goal' => 22000,
                'raised' => 22000,
                'completed_at' => '2024-09-15',
                'proof_url' => '#',
                'status' => 'Completed',
            ],
        ];

        $donationHistory = [
            [
                'fundraiser_title' => 'Student Emergency Fund',
                'amount' => 250.00,
                'date' => '2025-08-15',
                'status' => 'Completed',
            ],
            [
                'fundraiser_title' => 'Library Modernization',
                'amount' => 500.00,
                'date' => '2025-06-10',
                'status' => 'Completed',
            ],
            [
                'fundraiser_title' => 'Research Equipment Fund',
                'amount' => 150.00,
                'date' => '2025-08-28',
                'status' => 'Pending',
            ],
            [
                'fundraiser_title' => 'Computer Lab Upgrade',
                'amount' => 300.00,
                'date' => '2025-03-05',
                'status' => 'Completed',
            ],
            [
                'fundraiser_title' => 'Sports Equipment Fund',
                'amount' => 100.00,
                'date' => '2025-01-20',
                'status' => 'Completed',
            ],
            [
                'fundraiser_title' => 'Scholarship Support 2024',
                'amount' => 1000.00,
                'date' => '2024-12-15',
                'status' => 'Completed',
            ],
            [
                'fundraiser_title' => 'Emergency Medical Support',
                'amount' => 75.00,
                'date' => '2025-09-01',
                'status' => 'Pending',
            ],
        ];

        // Calculate donation summary
        $totalDonated = array_sum(array_column($donationHistory, 'amount'));
        $totalContributions = count($donationHistory);

        // Include the custom view file using project partials/layout
        // Using a direct include to honor the required filename alumni_fundraisers.php
        if (file_exists('../app/views/alumni/alumni_fundraisers.php')) {
            require '../app/views/alumni/alumni_fundraisers.php';
        } else {
            echo 'Fundraisers view not found.';
        }
    }
}
