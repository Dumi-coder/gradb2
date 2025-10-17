<?php
class Mentorship extends Controller
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

        // Get mentorship data (you can expand this to get real data)
        $mentorshipData = $this->getMentorshipData();

        $data = [
            'title' => 'Mentorship - GradBridge',
            'profile' => $profile,
            'mentorshipData' => $mentorshipData,
            'user' => $_SESSION
        ];

        $this->view('alumni/mentorship', $data);
    }

    private function getMentorshipData()
    {
        // You can expand this to get real mentorship data from database
        return [
            'requests' => [
                [
                    'id' => 1,
                    'student_name' => 'Michael Chen',
                    'guidance_type' => 'Startup Guidance',
                    'description' => "I'm planning to start my own tech startup after graduation and need mentorship on business planning and funding strategies from experienced professionals.",
                    'status' => 'urgent'
                ],
                [
                    'id' => 2,
                    'student_name' => 'Emily Rodriguez',
                    'guidance_type' => 'Research Guidance',
                    'description' => "I'm working on my thesis project in data science and machine learning. I need guidance on research methodology and data analysis techniques.",
                    'status' => 'urgent'
                ],
                [
                    'id' => 3,
                    'student_name' => 'Sarah Johnson',
                    'guidance_type' => 'Career Guidance',
                    'description' => "I'm a final year computer science student looking for guidance on transitioning into the tech industry. I would appreciate advice on career paths and interview preparation.",
                    'status' => 'pending'
                ],
                [
                    'id' => 4,
                    'student_name' => 'David Kim',
                    'guidance_type' => 'Industry Transition',
                    'description' => "I'm currently in mechanical engineering but interested in transitioning to software development. I need advice on skill development and portfolio building.",
                    'status' => 'pending'
                ]
            ],
            'active' => [
                [
                    'id' => 1,
                    'student_name' => 'Sarah Johnson',
                    'mentorship_type' => 'Career Development',
                    'description' => "Currently working on interview preparation and professional skill development. Making excellent progress with resume optimization and technical interview practice.",
                    'status' => 'active'
                ],
                [
                    'id' => 2,
                    'student_name' => 'Michael Chen',
                    'mentorship_type' => 'Startup Strategy',
                    'description' => "Developing business plan and exploring funding opportunities for tech startup. Regular sessions focusing on market research and product development strategies.",
                    'status' => 'active'
                ],
                [
                    'id' => 3,
                    'student_name' => 'Lisa Wang',
                    'mentorship_type' => 'Graduate School Preparation',
                    'description' => "Preparing for graduate school applications and research opportunities. Working on statement of purpose and research proposal development.",
                    'status' => 'active'
                ]
            ],
            'completed' => [
                [
                    'id' => 1,
                    'student_name' => 'Emily Rodriguez',
                    'topic' => 'Research Methodology',
                    'description' => "Successfully completed thesis project in data science and machine learning. Published research paper and secured PhD position at prestigious university.",
                    'completed_date' => 'Aug 25, 2025'
                ],
                [
                    'id' => 2,
                    'student_name' => 'David Kim',
                    'topic' => 'Career Transition',
                    'description' => "Successfully transitioned from mechanical engineering to software development. Secured full-time position as junior developer at major tech company.",
                    'completed_date' => 'Jul 18, 2025'
                ],
                [
                    'id' => 3,
                    'student_name' => 'Alex Thompson',
                    'topic' => 'Professional Networking',
                    'description' => "Built strong professional network and improved communication skills. Successfully launched marketing career with increased confidence and connections.",
                    'completed_date' => 'Jun 30, 2025'
                ],
                [
                    'id' => 4,
                    'student_name' => 'Jessica Martinez',
                    'topic' => 'Entrepreneurship',
                    'description' => "Launched successful e-commerce business with comprehensive mentorship support. Achieved break-even within 6 months and expanding to new markets.",
                    'completed_date' => 'May 15, 2025'
                ]
            ]
        ];
    }
}
