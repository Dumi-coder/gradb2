<?php
class EventBoard extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and is an alumni
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Sample event data
        $eventData = [
            'pending_events' => [
                [
                    'id' => 1,
                    'title' => 'AI & Machine Learning Workshop',
                    'location' => 'Tech Center, Room 205',
                    'status' => 'pending',
                    'description' => 'Hands-on workshop covering latest AI/ML technologies and their practical applications in industry.',
                    'date' => 'Oct 5, 2025',
                    'time' => '2:00 PM'
                ],
                [
                    'id' => 2,
                    'title' => 'Entrepreneurship Bootcamp',
                    'location' => 'Business School Auditorium',
                    'status' => 'pending',
                    'description' => 'Three-day intensive bootcamp for aspiring entrepreneurs with guest speakers from successful startups.',
                    'date' => 'Oct 15, 2025',
                    'time' => '9:00 AM'
                ]
            ],
            'registered_events' => [
                [
                    'id' => 3,
                    'title' => 'Annual Alumni Networking Gala',
                    'location' => 'Grand Ballroom, University Hotel',
                    'status' => 'registered',
                    'type' => 'alumni',
                    'description' => 'Annual networking event for alumni to connect and share experiences.',
                    'date' => 'Sep 15, 2025',
                    'time' => '6:00 PM',
                    'organizer' => 'Alumni Association'
                ],
                [
                    'id' => 4,
                    'title' => 'Student Startup Pitch Competition',
                    'location' => 'Innovation Hub, Main Campus',
                    'status' => 'registered',
                    'type' => 'student',
                    'description' => 'Watch students pitch their innovative startup ideas to a panel of industry experts.',
                    'date' => 'Sep 22, 2025',
                    'time' => '3:30 PM',
                    'organizer' => 'Entrepreneurship Club'
                ],
                [
                    'id' => 5,
                    'title' => 'Engineering Alumni Reunion',
                    'location' => 'Engineering Building, Room 101',
                    'status' => 'registered',
                    'type' => 'alumni',
                    'description' => 'Reunion event for engineering alumni to reconnect and share career experiences.',
                    'date' => 'Oct 10, 2025',
                    'time' => '7:00 PM',
                    'organizer' => 'Engineering Alumni Association'
                ]
            ],
            'statistics' => [
                'pending_events' => 2,
                'registered_events' => 3
            ]
        ];

        $data = [
            'title' => 'Event Board - GradBridge',
            'user' => $_SESSION,
            'eventData' => $eventData
        ];

        $this->view('alumni/event-board', $data);
    }
}
