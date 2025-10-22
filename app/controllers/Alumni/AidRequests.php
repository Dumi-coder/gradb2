<?php
class AidRequests extends Controller
{
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

        // Get aid requests data (you can expand this to get real data)
        $aidRequestsData = $this->getAidRequestsData();

        $data = [
            'title' => 'Aid Requests - GradBridge',
            'profile' => $profile,
            'aidRequestsData' => $aidRequestsData,
            'user' => $_SESSION
        ];

        $this->view('alumni/aid-requests', $data);
    }

    private function getAidRequestsData()
    {
        // You can expand this to get real aid requests data from database
        return [
            'pending' => [
                [
                    'id' => 1,
                    'student_name' => 'Michael Brown',
                    'request_type' => 'Emergency Transportation',
                    'description' => 'Emergency financial assistance needed for transportation costs. I live far from campus and my usual transportation has become unavailable due to family circumstances. Need immediate help to continue attending classes.',
                    'amount_requested' => '$300',
                    'aid_type' => 'Monetary Aid',
                    'status' => 'urgent'
                ],
                [
                    'id' => 2,
                    'student_name' => 'John Doe',
                    'request_type' => 'Boarding Cost Assistance',
                    'description' => 'I am facing difficulties with boarding costs for this semester. My family has experienced financial hardship due to recent medical expenses, and I need assistance to continue my studies without interruption.',
                    'amount_requested' => '$800',
                    'aid_type' => 'Monetary Aid',
                    'status' => 'urgent'
                ],
                [
                    'id' => 3,
                    'student_name' => 'Sarah Johnson',
                    'request_type' => 'Lab Equipment',
                    'description' => 'I need scientific calculator and lab equipment for my engineering courses. These tools are mandatory for coursework and I cannot afford to purchase them at this time.',
                    'estimated_value' => '$150',
                    'aid_type' => 'Physical Aid',
                    'status' => 'pending'
                ],
                [
                    'id' => 4,
                    'student_name' => 'David Kim',
                    'request_type' => 'Laptop for Studies',
                    'description' => 'My current laptop is very old and cannot run the required software for my programming courses. I need a replacement laptop to continue my studies effectively.',
                    'estimated_value' => '$600',
                    'aid_type' => 'Physical Aid',
                    'status' => 'pending'
                ],
                [
                    'id' => 5,
                    'student_name' => 'Lisa Wang',
                    'request_type' => 'Medical Expenses',
                    'description' => 'I need financial assistance for medical expenses that are affecting my ability to continue my studies. Looking for support to cover necessary healthcare costs.',
                    'amount_requested' => '$500',
                    'aid_type' => 'Monetary Aid',
                    'status' => 'pending'
                ]
            ],
            'approved' => [
                [
                    'id' => 1,
                    'student_name' => 'Mary Smith',
                    'request_type' => 'Laptop for Programming',
                    'description' => 'Laptop provided for computer science studies. Equipment has been delivered and student is making excellent progress in programming courses.',
                    'provided_value' => '$650',
                    'aid_type' => 'Physical Aid',
                    'status' => 'approved'
                ],
                [
                    'id' => 2,
                    'student_name' => 'Jessica Martinez',
                    'request_type' => 'Course Materials',
                    'description' => 'Provided essential course materials and lab equipment for engineering studies. Student successfully completed all required coursework and projects.',
                    'provided_value' => '$200',
                    'aid_type' => 'Physical Aid',
                    'status' => 'approved'
                ]
            ],
            'completed' => [
                [
                    'id' => 1,
                    'student_name' => 'Emma Wilson',
                    'request_type' => 'Textbook Fund',
                    'description' => 'Successfully provided textbook funding for computer science courses. Student completed semester with excellent grades and expressed gratitude for the support.',
                    'amount_provided' => '$350',
                    'aid_type' => 'Monetary Aid',
                    'completed_date' => 'Aug 28, 2025'
                ],
                [
                    'id' => 2,
                    'student_name' => 'James Rodriguez',
                    'request_type' => 'Engineering Equipment',
                    'description' => 'Provided scientific calculator and lab equipment for mechanical engineering studies. Student successfully completed all lab requirements and advanced to next semester.',
                    'provided_value' => '$180',
                    'aid_type' => 'Physical Aid',
                    'completed_date' => 'Aug 20, 2025'
                ],
                [
                    'id' => 3,
                    'student_name' => 'Marcus Johnson',
                    'request_type' => 'Medical Emergency',
                    'description' => 'Emergency medical assistance provided for urgent dental treatment. Student received necessary care and recovered fully, allowing continued focus on academic studies.',
                    'amount_provided' => '$450',
                    'aid_type' => 'Monetary Aid',
                    'completed_date' => 'Aug 10, 2025'
                ],
                [
                    'id' => 4,
                    'student_name' => 'Aisha Patel',
                    'request_type' => 'Art Supplies',
                    'description' => 'Provided professional art supplies and drawing tablet for graphic design coursework. Student created outstanding portfolio and secured internship at design agency.',
                    'provided_value' => '$320',
                    'aid_type' => 'Physical Aid',
                    'completed_date' => 'Jul 30, 2025'
                ],
                [
                    'id' => 5,
                    'student_name' => 'Daniel Lee',
                    'request_type' => 'Conference Travel',
                    'description' => 'Travel assistance provided for academic conference presentation. Student successfully presented research, networked with industry professionals, and received job offers.',
                    'amount_provided' => '$400',
                    'aid_type' => 'Monetary Aid',
                    'completed_date' => 'Jul 25, 2025'
                ]
            ]
        ];
    }
}
