<?php
class EventBoard extends Controller
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

        // Check if user is logged in and is an alumni
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        $alumni_id = $_SESSION['user_id'];

        // Get all active events (for upcoming events section), excluding events hosted by this alumni.
        $upcomingEvents = $this->eventModel->getAllActiveEvents();
        if (is_array($upcomingEvents)) {
            $upcomingEvents = array_values(array_filter($upcomingEvents, function($event) use ($alumni_id) {
                return (int)($event['host_alumnus_id'] ?? 0) !== (int)$alumni_id;
            }));
        }

        $eventStats = $this->eventModel->getActivityStats();

        // Get events hosted by this alumni
        $hostingEvents = $this->eventModel->getEventsByAlumni($alumni_id);

        // Get events this alumni is attending
        $attendingEvents = $this->eventRegistrationModel->getAlumniRegisteredEvents($alumni_id);

        // Use unread notifications only and derive badge count from this small result set
        // to avoid an extra full unread COUNT(*) query on every page load.
        $headerNotifications = $this->eventNotificationModel->getUserNotifications($alumni_id, 0, 8);
        $unreadNotificationCount = is_array($headerNotifications) ? count($headerNotifications) : 0;

        $data = [
            'title' => 'Event Board - GradBridge',
            'user' => $_SESSION,
            'upcomingEvents' => $upcomingEvents ?: [],
            'hostingEvents' => $hostingEvents ?: [],
            'attendingEvents' => $attendingEvents ?: [],
            'eventStats' => $eventStats,
            'header_notifications' => $headerNotifications ?: [],
            'notification_count' => $unreadNotificationCount
        ];

        $this->view('alumni/event-board', $data);
    }

    public function create()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];

        // Handle image upload
        $image_path = null;
        if (isset($_FILES['event_image']) && (int)$_FILES['event_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ((int)$_FILES['event_image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed. Please try again.']);
                return;
            }

            $upload_dir = rtrim(UPLOAD_PATH, '/\\') . '/events/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Unable to create upload directory.']);
                return;
            }

            $file_extension = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($file_extension, $allowedExtensions, true)) {
                echo json_encode(['success' => false, 'message' => 'Invalid image type. Use jpg, png, gif, or webp.']);
                return;
            }

            $file_name = 'event_' . time() . '_' . uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;

            if (!move_uploaded_file($_FILES['event_image']['tmp_name'], $file_path)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save uploaded image.']);
                return;
            }

            $image_path = '/assets/uploads/events/' . $file_name;
        }

        // Prepare event data
        $eventData = [
            'host_alumnus_id' => $alumni_id,
            'title' => $_POST['eventTitle'] ?? '',
            'description' => $_POST['eventDescription'] ?? '',
            'category' => $_POST['eventCategory'] ?? '',
            'event_date' => $_POST['eventDate'] ?? '',
            'start_time' => $_POST['startTime'] ?? '',
            'end_time' => $_POST['endTime'] ?? '',
            'venue' => $_POST['eventLocation'] ?? '',
            'mode' => $_POST['eventMode'] ?? 'offline',
            'registration_link' => $_POST['externalLink'] ?? '',
            'max_attendees' => !empty($_POST['maxAttendees']) ? (int)$_POST['maxAttendees'] : null,
            'tags' => $_POST['eventTags'] ?? '',
            'status' => 'active',
            'registration_status' => 'open'
        ];

        if ($image_path) {
            $eventData['image_path'] = $image_path;
        }

        // Insert event
        if ($this->eventModel->insert($eventData)) {
            echo json_encode(['success' => true, 'message' => 'Event created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create event']);
        }
    }

    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];
        $event_id = $_POST['eventId'] ?? null;

        if (!$event_id) {
            echo json_encode(['success' => false, 'message' => 'Event ID required']);
            return;
        }

        // Verify ownership
        $event = $this->eventModel->getEventWithOrganizer($event_id);
        if (!$event || $event['host_alumnus_id'] != $alumni_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized to edit this event']);
            return;
        }

        // Track changes for notifications
        $changes = [];
        $notificationMessages = [];

        // Handle image upload
        if (isset($_FILES['event_image']) && (int)$_FILES['event_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ((int)$_FILES['event_image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed. Please try again.']);
                return;
            }

            $upload_dir = rtrim(UPLOAD_PATH, '/\\') . '/events/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Unable to create upload directory.']);
                return;
            }

            $file_extension = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($file_extension, $allowedExtensions, true)) {
                echo json_encode(['success' => false, 'message' => 'Invalid image type. Use jpg, png, gif, or webp.']);
                return;
            }

            $file_name = 'event_' . time() . '_' . uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;

            if (!move_uploaded_file($_FILES['event_image']['tmp_name'], $file_path)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save uploaded image.']);
                return;
            }

            $old_image = $event['image_path'] ?? null;
            if ($old_image && file_exists(PUBLICPATH . $old_image)) {
                @unlink(PUBLICPATH . $old_image);
            }
            $changes['image_path'] = '/assets/uploads/events/' . $file_name;
        }

        // Check for venue change
        if (isset($_POST['eventLocation']) && $_POST['eventLocation'] !== $event['venue']) {
            $changes['venue'] = $_POST['eventLocation'];
            $notificationMessages[] = [
                'type' => 'venue_changed',
                'message' => "Venue changed to " . $_POST['eventLocation'],
                'old_value' => $event['venue'],
                'new_value' => $_POST['eventLocation']
            ];
        }

        // Check for date change
        if (isset($_POST['eventDate']) && $_POST['eventDate'] !== $event['event_date']) {
            $changes['event_date'] = $_POST['eventDate'];
            $notificationMessages[] = [
                'type' => 'date_changed',
                'message' => "Event date changed to " . date('M d, Y', strtotime($_POST['eventDate'])),
                'old_value' => $event['event_date'],
                'new_value' => $_POST['eventDate']
            ];
        }

        // Check for time change
        if (isset($_POST['startTime']) && $_POST['startTime'] !== $event['start_time']) {
            $changes['start_time'] = $_POST['startTime'];
            $notificationMessages[] = [
                'type' => 'time_changed',
                'message' => "Start time changed to " . $_POST['startTime'],
                'old_value' => $event['start_time'],
                'new_value' => $_POST['startTime']
            ];
        }

        // Update other fields with explicit form-key to DB-column mapping
        $fieldMap = [
            'eventTitle' => 'title',
            'eventDescription' => 'description',
            'eventCategory' => 'category',
            'endTime' => 'end_time',
            'eventMode' => 'mode',
            'externalLink' => 'registration_link',
            'maxAttendees' => 'max_attendees',
            'eventTags' => 'tags'
        ];

        foreach ($fieldMap as $postKey => $dbColumn) {
            if (!isset($_POST[$postKey])) {
                continue;
            }

            $newValue = $_POST[$postKey];
            if ($dbColumn === 'max_attendees') {
                $newValue = $newValue !== '' ? (int)$newValue : null;
            }

            $oldValue = $event[$dbColumn] ?? null;
            if ($newValue != $oldValue) {
                $changes[$dbColumn] = $newValue;
            }
        }

        // Update event
        if (!empty($changes)) {
            if ($this->eventModel->update($event_id, $changes)) {
                // Send notifications for each change
                foreach ($notificationMessages as $notif) {
                    $this->eventNotificationModel->notifyRegisteredStudents(
                        $event_id,
                        $notif['type'],
                        $notif['message'],
                        $notif['old_value'],
                        $notif['new_value']
                    );
                }

                echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update event']);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'No changes detected']);
        }
    }

    public function delete()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];
        $event_id = $_POST['eventId'] ?? null;

        if (!$event_id) {
            echo json_encode(['success' => false, 'message' => 'Event ID required']);
            return;
        }

        // Verify ownership
        $event = $this->eventModel->getEventWithOrganizer($event_id);
        if (!$event || $event['host_alumnus_id'] != $alumni_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized to delete this event']);
            return;
        }

        $eventDateText = !empty($event['event_date']) ? date('M d, Y', strtotime($event['event_date'])) : 'TBD';
        $startTimeText = !empty($event['start_time']) ? date('g:i A', strtotime($event['start_time'])) : '';
        $venueText = !empty($event['venue']) ? $event['venue'] : 'TBA';
        $organizerText = !empty($event['organizer_name']) ? $event['organizer_name'] : 'the organizer';

        $detailedMessage = 'Event cancelled: "' . $event['title'] . '" on ' . $eventDateText;
        if ($startTimeText !== '') {
            $detailedMessage .= ' at ' . $startTimeText;
        }
        $detailedMessage .= ' (' . $venueText . '). Posted by ' . $organizerText . '.';

        // Notify all student/alumni users about cancellation
        $this->eventNotificationModel->notifyAllUsers(
            $event_id,
            'event_cancelled',
            $detailedMessage,
            null,
            null,
            $alumni_id
        );

        // Soft delete: retain event row in DB and hide it from active views.
        if ($this->eventModel->update($event_id, ['status' => 'deleted', 'registration_status' => 'closed'])) {
            echo json_encode(['success' => true, 'message' => 'Event removed from view successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove event']);
        }
    }

    public function postpone()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];
        $event_id = $_POST['eventId'] ?? null;
        $new_date = $_POST['newDate'] ?? null;

        if (!$event_id || !$new_date) {
            echo json_encode(['success' => false, 'message' => 'Event ID and new date required']);
            return;
        }

        // Verify ownership
        $event = $this->eventModel->getEventWithOrganizer($event_id);
        if (!$event || $event['host_alumnus_id'] != $alumni_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized to postpone this event']);
            return;
        }

        // Update event date
        if ($this->eventModel->update($event_id, ['event_date' => $new_date])) {
            // Notify registered students
            $this->eventNotificationModel->notifyRegisteredStudents(
                $event_id,
                'event_postponed',
                'Event "' . $event['title'] . '" has been postponed to ' . date('M d, Y', strtotime($new_date)),
                $event['event_date'],
                $new_date
            );

            echo json_encode(['success' => true, 'message' => 'Event postponed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to postpone event']);
        }
    }

    public function toggleRegistration()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];
        $event_id = $_POST['eventId'] ?? null;

        if (!$event_id) {
            echo json_encode(['success' => false, 'message' => 'Event ID required']);
            return;
        }

        // Verify ownership
        $event = $this->eventModel->getEventWithOrganizer($event_id);
        if (!$event || $event['host_alumnus_id'] != $alumni_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $new_status = $event['registration_status'] === 'open' ? 'closed' : 'open';
        
        if ($this->eventModel->update($event_id, ['registration_status' => $new_status])) {
            echo json_encode(['success' => true, 'message' => 'Registration status updated', 'status' => $new_status]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update registration status']);
        }
    }

    public function register()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];
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

        if ((int)($event['host_alumnus_id'] ?? 0) === (int)$alumni_id) {
            echo json_encode(['success' => false, 'message' => 'You cannot register for your own event']);
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

        if ($this->eventRegistrationModel->isAlumniRegistered($event_id, $alumni_id)) {
            echo json_encode(['success' => false, 'message' => 'You are already registered for this event']);
            return;
        }

        $ok = $this->eventRegistrationModel->registerAlumni($event_id, $alumni_id);
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = $_SESSION['user_id'];
        $event_id = isset($_POST['eventId']) ? (int)$_POST['eventId'] : 0;

        if ($event_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Event ID is required']);
            return;
        }

        $ok = $this->eventRegistrationModel->unregisterAlumni($event_id, $alumni_id);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Registration cancelled']);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'No registration found']);
    }

    public function getEvent()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $event_id = $_GET['id'] ?? null;

        if (!$event_id) {
            echo json_encode(['success' => false, 'message' => 'Event ID required']);
            return;
        }

        $event = $this->eventModel->getEventWithOrganizer($event_id);
        
        if ($event) {
            echo json_encode(['success' => true, 'data' => $event]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Event not found']);
        }
    }

    public function markNotificationsRead()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $alumni_id = (int)$_SESSION['user_id'];
        $ok = $this->eventNotificationModel->markAllAsReadForUser($alumni_id);

        echo json_encode(['success' => (bool)$ok]);
    }
}
