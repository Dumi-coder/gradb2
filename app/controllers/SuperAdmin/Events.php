<?php

class Events extends Controller
{
    private $eventModel;
    private $eventRegistrationModel;

    public function __construct()
    {
        $this->eventModel = new Event();
        $this->eventRegistrationModel = new EventRegistration();
    }

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        $events = $this->eventModel->getModerationEvents();
        $hostingEvents = $this->eventModel->getEventsByAlumni((int)$_SESSION['user_id']);
        $registeredEvents = $this->eventRegistrationModel->getAlumniRegisteredEvents((int)$_SESSION['user_id']);

        $data = [
            'title' => 'Events Moderation - GradBridge',
            'page_title' => 'Events Moderation',
            'page_subtitle' => 'Monitor and manage events across all faculties.',
            'user' => $_SESSION,
            'events' => $events,
            'hostingEvents' => $hostingEvents,
            'attendingEvents' => $registeredEvents,
            'registeredEvents' => $registeredEvents,
        ];

        $this->view('superadmin/events', $data);
    }

    public function create()
    {
        $this->assertSuperAdminJsonAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonFail('Invalid request method');
            return;
        }

        $title = trim((string)($_POST['eventTitle'] ?? ''));
        $description = trim((string)($_POST['eventDescription'] ?? ''));
        $eventDate = trim((string)($_POST['eventDate'] ?? ''));
        $startTime = trim((string)($_POST['startTime'] ?? ''));

        if ($title === '' || $description === '' || $eventDate === '' || $startTime === '') {
            $this->jsonFail('Title, description, date, and start time are required');
            return;
        }

        $imagePath = null;
        if (isset($_FILES['event_image']) && (int)$_FILES['event_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ((int)$_FILES['event_image']['error'] !== UPLOAD_ERR_OK) {
                $this->jsonFail('Image upload failed. Please try again.');
                return;
            }

            $uploadDir = rtrim(UPLOAD_PATH, '/\\') . '/events/';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                $this->jsonFail('Unable to create upload directory.');
                return;
            }

            $ext = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed, true)) {
                $this->jsonFail('Invalid image type. Use jpg, png, gif, or webp.');
                return;
            }

            $fileName = 'event_' . time() . '_' . uniqid() . '.' . $ext;
            $filePath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['event_image']['tmp_name'], $filePath)) {
                $this->jsonFail('Failed to save uploaded image.');
                return;
            }

            $imagePath = '/assets/uploads/events/' . $fileName;
        }

        $eventData = [
            'host_alumnus_id' => (int)$_SESSION['user_id'],
            'title' => $title,
            'description' => $description,
            'category' => trim((string)($_POST['eventCategory'] ?? 'general')),
            'event_date' => $eventDate,
            'start_time' => $startTime,
            'end_time' => trim((string)($_POST['endTime'] ?? '')),
            'venue' => trim((string)($_POST['eventLocation'] ?? 'TBA')),
            'mode' => trim((string)($_POST['eventMode'] ?? 'offline')),
            'registration_link' => trim((string)($_POST['externalLink'] ?? '')),
            'max_attendees' => !empty($_POST['maxAttendees']) ? (int)$_POST['maxAttendees'] : null,
            'tags' => trim((string)($_POST['eventTags'] ?? '')),
            'status' => 'active',
            'registration_status' => 'open',
        ];

        if ($imagePath !== null) {
            $eventData['image_path'] = $imagePath;
        }

        $ok = $this->eventModel->insert($eventData);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Event created successfully']);
            return;
        }

        $this->jsonFail('Failed to create event');
    }

    public function register()
    {
        $this->assertSuperAdminJsonAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonFail('Invalid request method');
            return;
        }

        $eventId = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;
        if ($eventId <= 0) {
            $this->jsonFail('Event ID is required');
            return;
        }

        $event = $this->eventModel->getModerationEventById($eventId);
        if (!$event) {
            $this->jsonFail('Event not found');
            return;
        }

        if (($event['registration_status'] ?? 'open') !== 'open') {
            $this->jsonFail('Registrations are closed for this event');
            return;
        }

        $registeredCount = $this->eventRegistrationModel->getRegistrationCountByEvent($eventId);
        $maxAttendees = isset($event['max_attendees']) ? (int)$event['max_attendees'] : 0;
        if (!empty($event['max_attendees']) && $registeredCount >= $maxAttendees) {
            $this->jsonFail('Registrations are full for this event');
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        if ($this->eventRegistrationModel->isAlumniRegistered($eventId, $userId)) {
            $this->jsonFail('You are already registered for this event');
            return;
        }

        $ok = $this->eventRegistrationModel->registerAlumni($eventId, $userId);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Registered successfully']);
            return;
        }

        $this->jsonFail('Failed to register');
    }

    public function unregister()
    {
        $this->assertSuperAdminJsonAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonFail('Invalid request method');
            return;
        }

        $eventId = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;
        if ($eventId <= 0) {
            $this->jsonFail('Event ID is required');
            return;
        }

        $ok = $this->eventRegistrationModel->unregisterAlumni($eventId, (int)$_SESSION['user_id']);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Registration cancelled']);
            return;
        }

        $this->jsonFail('No registration found');
    }

    public function toggleRegistration()
    {
        $this->assertSuperAdminJsonAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonFail('Invalid request method');
            return;
        }

        $eventId = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;
        if ($eventId <= 0) {
            $this->jsonFail('Event ID is required');
            return;
        }

        $event = $this->eventModel->getModerationEventById($eventId);
        if (!$event) {
            $this->jsonFail('Event not found');
            return;
        }

        $newStatus = ($event['registration_status'] ?? 'open') === 'open' ? 'closed' : 'open';
        $ok = $this->eventModel->update($eventId, ['registration_status' => $newStatus]);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Registration status updated', 'status' => $newStatus]);
            return;
        }

        $this->jsonFail('Failed to update registration status');
    }

    public function delete()
    {
        $this->assertSuperAdminJsonAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonFail('Invalid request method');
            return;
        }

        $eventId = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;
        if ($eventId <= 0) {
            $this->jsonFail('Event ID is required');
            return;
        }

        $event = $this->eventModel->getModerationEventById($eventId);
        if (!$event) {
            $this->jsonFail('Event not found');
            return;
        }

        $ok = $this->eventModel->update($eventId, ['status' => 'deleted', 'registration_status' => 'closed']);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
            return;
        }

        $this->jsonFail('Failed to delete event');
    }

    public function getEvent()
    {
        $this->assertSuperAdminJsonAuth();

        $eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($eventId <= 0) {
            $this->jsonFail('Event ID is required');
            return;
        }

        $event = $this->eventModel->getModerationEventById($eventId);
        if ($event) {
            echo json_encode(['success' => true, 'data' => $event]);
            return;
        }

        $this->jsonFail('Event not found');
    }

    private function assertSuperAdminJsonAuth()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
    }

    private function jsonFail($message)
    {
        echo json_encode(['success' => false, 'message' => $message]);
    }
}
