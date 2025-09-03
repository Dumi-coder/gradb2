<?php

class Events extends Controller
{
    public function index()
    {
        // Sample event data - in a real application, this would come from a database
        $events = [
            [
                'id' => 1,
                'title' => 'Annual Alumni Networking Gala',
                'description' => 'Join us for an evening of networking, dining, and reconnecting with fellow alumni. Special guest speakers from top tech companies.',
                'date' => '2025-09-15',
                'time' => '18:00',
                'location' => 'Grand Ballroom, University Hotel',
                'organizer' => 'Alumni Association',
                'type' => 'alumni',
                'registered' => true,
                'status' => 'upcoming',
                'max_attendees' => 200,
                'current_attendees' => 156
            ],
            [
                'id' => 2,
                'title' => 'Tech Career Workshop',
                'description' => 'Learn about emerging technologies and career opportunities in AI, blockchain, and cybersecurity.',
                'date' => '2025-09-08',
                'time' => '14:00',
                'location' => 'Online - Zoom Link',
                'organizer' => 'CS Alumni Chapter',
                'type' => 'alumni',
                'registered' => false,
                'status' => 'upcoming',
                'max_attendees' => 100,
                'current_attendees' => 67
            ],
            [
                'id' => 3,
                'title' => 'Student Startup Pitch Competition',
                'description' => 'Watch current students present their innovative startup ideas. Alumni serve as judges and mentors.',
                'date' => '2025-09-22',
                'time' => '15:30',
                'location' => 'Innovation Hub, Main Campus',
                'organizer' => 'Entrepreneurship Club',
                'type' => 'student',
                'registered' => true,
                'status' => 'upcoming',
                'max_attendees' => 150,
                'current_attendees' => 89
            ],
            [
                'id' => 4,
                'title' => 'Alumni Mentorship Program Launch',
                'description' => 'Official launch of the new mentorship program connecting alumni with current students.',
                'date' => '2025-08-30',
                'time' => '16:00',
                'location' => 'University Auditorium',
                'organizer' => 'Career Services',
                'type' => 'alumni',
                'registered' => true,
                'status' => 'past',
                'proof_url' => '#',
                'summary' => 'Successfully launched with 150+ mentor-mentee pairs.'
            ],
            [
                'id' => 5,
                'title' => 'Engineering Alumni Reunion',
                'description' => 'Class of 2020 Engineering graduates reunion with campus tour and dinner.',
                'date' => '2025-09-12',
                'time' => '12:00',
                'location' => 'Engineering Building',
                'organizer' => 'Engineering Alumni',
                'type' => 'alumni',
                'registered' => false,
                'status' => 'upcoming',
                'max_attendees' => 75,
                'current_attendees' => 42
            ],
            [
                'id' => 6,
                'title' => 'Student Research Symposium',
                'description' => 'Annual showcase of undergraduate and graduate research projects across all departments.',
                'date' => '2025-09-18',
                'time' => '09:00',
                'location' => 'Science Center',
                'organizer' => 'Graduate School',
                'type' => 'student',
                'registered' => false,
                'status' => 'upcoming',
                'max_attendees' => 300,
                'current_attendees' => 178
            ]
        ];

        $current_page = 'events';

        // Pass data to view
        $data = [
            'events' => $events,
            'current_page' => $current_page
        ];

        // Load the view
        require '../app/views/alumni/alumni_events.php';
    }

    public function register()
    {
        // Handle event registration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'] ?? null;
            
            if ($eventId) {
                // In a real application, you would:
                // 1. Validate the event exists
                // 2. Check if user is already registered
                // 3. Check if event has space
                // 4. Add registration to database
                // 5. Send confirmation email
                
                echo json_encode(['success' => true, 'message' => 'Successfully registered for event']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid event ID']);
            }
        }
    }

    public function unregister()
    {
        // Handle event unregistration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'] ?? null;
            
            if ($eventId) {
                // In a real application, you would:
                // 1. Validate the event exists
                // 2. Check if user is registered
                // 3. Remove registration from database
                // 4. Send confirmation email
                
                echo json_encode(['success' => true, 'message' => 'Successfully unregistered from event']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid event ID']);
            }
        }
    }

    public function publish()
    {
        // Handle event publishing
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $location = $_POST['location'] ?? '';
            $type = $_POST['type'] ?? '';
            $maxAttendees = $_POST['max_attendees'] ?? 100;
            
            // Validate required fields
            if (empty($title) || empty($description) || empty($date) || empty($time) || empty($location) || empty($type)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                return;
            }
            
            // In a real application, you would:
            // 1. Validate all input data
            // 2. Save event to database
            // 3. Set organizer to current user
            // 4. Send notifications to relevant users
            
            echo json_encode(['success' => true, 'message' => 'Event published successfully']);
        }
    }
}
