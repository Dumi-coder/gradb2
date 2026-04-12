<?php

class FacultyAnnouncements extends Controller
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostAction();
            return;
        }

        $announcementModel = new Announcement();

        $announcementsData = $this->buildAnnouncementsData($announcementModel->getAllFacultyAnnouncements());
        $universityAnnouncementsData = $this->buildAnnouncementsData($announcementModel->getAllUniversityAnnouncements());
        $adminAnnouncementsData = $this->buildAnnouncementsData($announcementModel->getAllAdminAnnouncements());

        $data = [
            'title' => 'Faculty Announcements - GradBridge',
            'page_title' => 'Faculty Announcements',
            'page_subtitle' => 'Create and manage announcements for faculty and students.',
            'user' => $_SESSION,
            'announcementsData' => $announcementsData,
            'universityAnnouncementsData' => $universityAnnouncementsData,
            'adminAnnouncementsData' => $adminAnnouncementsData
        ];

        $this->view('superadmin/faculty-announcements', $data);
    }

    private function handlePostAction()
    {
        header('Content-Type: application/json');

        $action = $_POST['action'] ?? '';
        $announcement_id = (int)($_POST['announcement_id'] ?? 0);
        $announcementModel = new Announcement();

        if ($action === 'view') {
            if ($announcement_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
                exit();
            }

            $announcement = $announcementModel->getAnnouncementById($announcement_id);
            if (!$announcement) {
                echo json_encode(['success' => false, 'message' => 'Announcement not found']);
                exit();
            }

            $announcementModel->incrementViewCount($announcement_id);
            $announcement = $announcementModel->getAnnouncementById($announcement_id);

            echo json_encode(['success' => true, 'announcement' => $announcement]);
            exit();
        }

        if ($action === 'publish' || $action === 'unpublish') {
            if ($announcement_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
                exit();
            }

            $before = $announcementModel->getAnnouncementById($announcement_id);
            if (!$before) {
                echo json_encode(['success' => false, 'message' => 'Announcement not found']);
                exit();
            }

            $saved = $announcementModel->updatePublishState($announcement_id, $action === 'publish' ? 1 : 0);

            if ($saved && $action === 'publish' && (int)($before['is_published'] ?? 0) !== 1) {
                $notificationModel = new Notification();
                $notificationModel->createAnnouncementPublishedNotifications(
                    (int)($before['type'] ?? Announcement::TYPE_FACULTY),
                    (string)($before['title'] ?? 'New announcement'),
                    (int)($_SESSION['user_id'] ?? 0),
                    isset($before['faculty_id']) ? (int)$before['faculty_id'] : null
                );
            }

            echo json_encode(['success' => (bool)$saved]);
            exit();
        }

        if ($action === 'delete') {
            if ($announcement_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
                exit();
            }

            $saved = $announcementModel->softDeleteAnnouncement($announcement_id);
            echo json_encode(['success' => (bool)$saved]);
            exit();
        }

        if ($action === 'edit') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if ($announcement_id <= 0 || $title === '' || $content === '') {
                echo json_encode(['success' => false, 'message' => 'Invalid edit payload']);
                exit();
            }

            $saved = $announcementModel->updateAnnouncementText($announcement_id, $title, $content);
            echo json_encode(['success' => (bool)$saved]);
            exit();
        }

        if ($action === 'add') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $priority = (int)($_POST['priority'] ?? 0);
            $type = (int)($_POST['type'] ?? 0);
            $validTypes = [
                Announcement::TYPE_FACULTY,
                Announcement::TYPE_UNIVERSITY,
                Announcement::TYPE_ADMIN
            ];

            if ($title === '' || $content === '' || $priority < 0 || $priority > 2 || !in_array($type, $validTypes, true)) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement payload']);
                exit();
            }

            $faculty_id = null;
            if ($type === Announcement::TYPE_FACULTY) {
                // Superadmin-created faculty announcements are global faculty notices.
                $faculty_id = null;
            }

            $saved = $announcementModel->createAnnouncement([
                'title' => $title,
                'content' => $content,
                'faculty_id' => $faculty_id,
                'created_by_user_id' => (int)$_SESSION['user_id'],
                'priority' => $priority,
                'type' => $type,
                'view_count' => 0,
                'is_published' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($saved) {
                $notificationModel = new Notification();
                $notificationModel->createAnnouncementPublishedNotifications(
                    $type,
                    $title,
                    (int)($_SESSION['user_id'] ?? 0),
                    $faculty_id
                );
            }

            echo json_encode([
                'success' => (bool)$saved,
                'message' => $saved ? 'Announcement published successfully' : 'Failed to save announcement'
            ]);
            exit();
        }

        echo json_encode(['success' => false, 'message' => 'Unsupported action']);
        exit();
    }

    private function buildAnnouncementsData($rows)
    {
        $items = [];

        foreach ((array)$rows as $row) {
            $priorityValue = (int)($row['priority'] ?? 0);
            $priorityLabel = 'low';
            if ($priorityValue === 2) {
                $priorityLabel = 'high';
            } elseif ($priorityValue === 1) {
                $priorityLabel = 'medium';
            }

            $items[] = [
                'id' => (int)($row['id'] ?? 0),
                'title' => (string)($row['title'] ?? ''),
                'content' => (string)($row['content'] ?? ''),
                'author' => (string)($row['creator_email'] ?? 'unknown@unknown.com'),
                'author_email' => (string)($row['creator_faculty_label'] ?? 'Unknown Faculty'),
                'priority' => $priorityLabel,
                'status' => (int)($row['is_published'] ?? 0) === 1 ? 'published' : 'draft',
                'created_date' => (string)($row['created_at'] ?? date('Y-m-d H:i:s')),
                'views' => (int)($row['view_count'] ?? 0)
            ];
        }

        return [
            'announcements' => $items,
            'stats' => [
                'total_announcements' => count($items),
                'published_announcements' => count(array_filter($items, function ($item) {
                    return $item['status'] === 'published';
                })),
                'draft_announcements' => count(array_filter($items, function ($item) {
                    return $item['status'] === 'draft';
                })),
                'high_priority' => count(array_filter($items, function ($item) {
                    return $item['priority'] === 'high';
                })),
                'total_views' => array_sum(array_map(function ($item) {
                    return (int)$item['views'];
                }, $items))
            ]
        ];
    }
}
