<?php
class EventsBoard extends Controller
{
    private $eventModel;
    private $eventNotificationModel;
    private $eventRegistrationModel;

    public function __construct()
    {
        $this->eventModel = new Event();
        $this->eventNotificationModel = new EventNotification();
        $this->eventRegistrationModel = new EventRegistration();
    }

    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in and is a student
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        $student_id = $_SESSION['user_id'];

        // Get all active events
        $upcomingEvents = $this->eventModel->getAllActiveEvents();
        if (!is_array($upcomingEvents)) {
            $upcomingEvents = [];
        }

        $eventStats = $this->eventModel->getActivityStats();

        // Get registered events for this student
        $registeredEvents = $this->eventRegistrationModel->getStudentRegisteredEvents($student_id);
        if (!is_array($registeredEvents)) {
            $registeredEvents = [];
        }

        // Get notifications for this student
        $notifications = $this->eventNotificationModel->getUserNotifications($student_id, 0); // Unread only
        if (!is_array($notifications)) {
            $notifications = [];
        }

        // Use unread notifications only and derive badge count from this small result set
        // to avoid an extra full unread COUNT(*) query on every page load.
        $headerNotifications = $this->eventNotificationModel->getUserNotifications($student_id, 0, 8);
        $unreadNotificationCount = is_array($headerNotifications) ? count($headerNotifications) : 0;

        // Attach notifications to registered events
        $eventsWithNotifications = [];
        foreach ($registeredEvents as $event) {
            $eventNotifications = array_filter($notifications, function($notif) use ($event) {
                return $notif['event_id'] == $event['event_id'];
            });
            $event['notifications'] = array_values($eventNotifications);
            $eventsWithNotifications[] = $event;
        }

        $data = [
            'title' => 'Events Board - GradBridge',
            'user' => $_SESSION,
            'upcomingEvents' => $upcomingEvents ?: [],
            'registeredEvents' => $eventsWithNotifications ?: [],
            'notifications' => $notifications ?: [],
            'eventStats' => $eventStats,
            'header_notifications' => $headerNotifications ?: [],
            'notification_count' => $unreadNotificationCount
        ];

        $this->view('student/events-board', $data);
    }

    public function register()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $student_id = $_SESSION['user_id'];
        $event_id = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;

        if ($event_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Event ID is required']);
            return;
        }

        $event = $this->eventModel->getEventWithOrganizer($event_id);
        if (!$event) {
            echo json_encode(['success' => false, 'message' => 'Event not found']);
            return;
        }

        if (($event['registration_status'] ?? 'open') !== 'open') {
            echo json_encode(['success' => false, 'message' => 'Registrations are closed for this event']);
            return;
        }

        $registeredCount = $this->eventRegistrationModel->getRegistrationCountByEvent($event_id);
        $maxAttendees = isset($event['max_attendees']) ? (int)$event['max_attendees'] : 0;
        if (!empty($event['max_attendees']) && $registeredCount >= $maxAttendees) {
            echo json_encode(['success' => false, 'message' => 'Registrations are full for this event']);
            return;
        }

        if ($this->eventRegistrationModel->isStudentRegistered($event_id, $student_id)) {
            echo json_encode(['success' => false, 'message' => 'You are already registered for this event']);
            return;
        }

        $ok = $this->eventRegistrationModel->registerStudent($event_id, $student_id);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Registered successfully']);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'Failed to register']);
    }

    public function unregister()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $student_id = $_SESSION['user_id'];
        $event_id = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;

        if ($event_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Event ID is required']);
            return;
        }

        $ok = $this->eventRegistrationModel->unregisterStudent($event_id, $student_id);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Registration cancelled']);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'No registration found']);
    }

    public function markNotificationsRead()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $student_id = (int)$_SESSION['user_id'];
        $ok = $this->eventNotificationModel->markAllAsReadForUser($student_id);

        echo json_encode(['success' => (bool)$ok]);
    }
}
