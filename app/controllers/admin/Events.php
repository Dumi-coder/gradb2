<?php

class Events extends Controller
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

        // Get events data
        $eventsData = $this->getEvents();

        $data = [
            'title' => 'Approve Events - GradBridge',
            'user' => $_SESSION,
            'eventsData' => $eventsData
        ];

        $this->view('admin/events', $data);
    }

    private function getEvents()
    {
        // Mock data for events
        return [
            'events' => [
                [
                    'id' => 1,
                    'title' => 'Career Fair 2024',
                    'organizer' => 'Career Services Office',
                    'organizer_email' => 'career@university.edu',
                    'event_type' => 'Career Fair',
                    'description' => 'Annual career fair featuring top companies and organizations for students and alumni.',
                    'date' => '2024-03-15',
                    'time' => '10:00 AM - 4:00 PM',
                    'location' => 'University Conference Center',
                    'expected_attendees' => 500,
                    'status' => 'pending',
                    'submitted_date' => '2024-01-15',
                    'budget_requested' => 5000
                ],
                [
                    'id' => 2,
                    'title' => 'Alumni Networking Mixer',
                    'organizer' => 'Alumni Association',
                    'organizer_email' => 'alumni@university.edu',
                    'event_type' => 'Networking',
                    'description' => 'Evening networking event for current students to connect with successful alumni.',
                    'date' => '2024-02-28',
                    'time' => '6:00 PM - 9:00 PM',
                    'location' => 'Downtown Hotel Ballroom',
                    'expected_attendees' => 150,
                    'status' => 'pending',
                    'submitted_date' => '2024-01-14',
                    'budget_requested' => 2500
                ],
                [
                    'id' => 3,
                    'title' => 'Tech Innovation Workshop',
                    'organizer' => 'Computer Science Department',
                    'organizer_email' => 'cs@university.edu',
                    'event_type' => 'Workshop',
                    'description' => 'Hands-on workshop on emerging technologies and their applications in industry.',
                    'date' => '2024-02-10',
                    'time' => '9:00 AM - 5:00 PM',
                    'location' => 'Engineering Building, Room 101',
                    'expected_attendees' => 75,
                    'status' => 'pending',
                    'submitted_date' => '2024-01-13',
                    'budget_requested' => 1200
                ]
            ],
            'stats' => [
                'total_events' => 3,
                'pending_events' => 3,
                'approved_events' => 0,
                'rejected_events' => 0,
                'total_budget_requested' => 8700
            ]
        ];
    }
}
