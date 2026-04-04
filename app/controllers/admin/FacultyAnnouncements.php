<?php

class FacultyAnnouncements extends Controller
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostAction();
            return;
        }

        $faculty_id = $this->getFacultyIdForAdmin();

        $announcementModel = new Announcement();
        $announcementsData = $faculty_id ? $this->buildAnnouncementsPayload($announcementModel->getFacultyAnnouncementsForAdmin($faculty_id)) : $this->emptyAnnouncementsPayload();
        $universityAnnouncementsData = $this->buildAnnouncementsPayload($announcementModel->getPublishedUniversityAnnouncements());
        $adminAnnouncementsData = $this->buildAnnouncementsPayload($announcementModel->getPublishedAdminAnnouncements());

        $data = [
            'title' => 'Faculty Announcements - GradBridge',
            'page_title' => 'Faculty Announcements',
            'page_subtitle' => 'Create and manage announcements for faculty and students.',
            'user' => $_SESSION,
            'announcementsData' => $announcementsData,
            'universityAnnouncementsData' => $universityAnnouncementsData,
            'adminAnnouncementsData' => $adminAnnouncementsData
        ];

        $this->view('admin/faculty-announcements', $data);
    }

    private function getFacultyIdForAdmin()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            return null;
        }

        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT faculty_id FROM faculty_admins WHERE user_id = :user_id";
            $stm = $con->prepare($query);
            $stm->execute(['user_id' => $user_id]);

            $result = $stm->fetch(PDO::FETCH_OBJ);
            return ($result && !empty($result->faculty_id)) ? (int)$result->faculty_id : null;
        } catch (PDOException $e) {
            error_log("FacultyAnnouncements::getFacultyIdForAdmin Error: " . $e->getMessage());
            return null;
        }
    }

    private function emptyAnnouncementsPayload()
    {
        return [
            'announcements' => [],
            'stats' => [
                'total_announcements' => 0,
                'published_announcements' => 0,
                'draft_announcements' => 0,
                'total_views' => 0
            ]
        ];
    }

    private function buildAnnouncementsPayload($announcements)
    {
        $items = is_array($announcements) ? $announcements : [];

        return [
            'announcements' => $items,
            'stats' => [
                'total_announcements' => count($items),
                'published_announcements' => count(array_filter($items, function ($item) {
                    return (int)($item['is_published'] ?? 0) === 1;
                })),
                'draft_announcements' => count(array_filter($items, function ($item) {
                    return (int)($item['is_published'] ?? 0) !== 1;
                })),
                'total_views' => array_sum(array_map(function ($item) {
                    return (int)($item['view_count'] ?? 0);
                }, $items))
            ]
        ];
    }

    private function handlePostAction()
    {
        header('Content-Type: application/json');

        $action = $_POST['action'] ?? '';

        $faculty_id = $this->getFacultyIdForAdmin();
        if (!$faculty_id) {
            echo json_encode(['success' => false, 'message' => 'Faculty not found for this admin account']);
            exit();
        }

        $announcementModel = new Announcement();

        if ($action === 'view') {
            $announcement_id = (int)($_POST['announcement_id'] ?? 0);
            if ($announcement_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
                exit();
            }

            $announcement = $announcementModel->getAnnouncementById($announcement_id);
            if (!$announcement) {
                echo json_encode(['success' => false, 'message' => 'Announcement not found']);
                exit();
            }

            $announcementType = (int)($announcement['type'] ?? Announcement::TYPE_FACULTY);
            $announcementFacultyId = (int)($announcement['faculty_id'] ?? 0);
            $isPublished = (int)($announcement['is_published'] ?? 0) === 1;

            $canView = false;
            if ($announcementType === Announcement::TYPE_FACULTY) {
                $canView = $announcementFacultyId === (int)$faculty_id;
            } elseif (
                $announcementType === Announcement::TYPE_UNIVERSITY ||
                $announcementType === Announcement::TYPE_ADMIN
            ) {
                $canView = $isPublished;
            }

            if (!$canView) {
                echo json_encode(['success' => false, 'message' => 'Announcement not found']);
                exit();
            }

            $announcementModel->incrementViewCount($announcement_id);
            $announcement = $announcementModel->getAnnouncementById($announcement_id);

            echo json_encode([
                'success' => true,
                'announcement' => $announcement
            ]);
            exit();
        }

        if ($action === 'publish' || $action === 'unpublish') {
            $announcement_id = (int)($_POST['announcement_id'] ?? 0);
            if ($announcement_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
                exit();
            }

            $before = $announcementModel->getAnnouncementByIdForFaculty($announcement_id, $faculty_id);
            if (!$before) {
                echo json_encode(['success' => false, 'message' => 'Announcement not found']);
                exit();
            }

            $saved = $announcementModel->updatePublishState($announcement_id, $action === 'publish' ? 1 : 0, $faculty_id);

            if ($saved && $action === 'publish' && (int)($before['is_published'] ?? 0) !== 1) {
                $notificationModel = new Notification();
                $notificationModel->createAnnouncementPublishedNotifications(
                    (int)($before['type'] ?? Announcement::TYPE_FACULTY),
                    (string)($before['title'] ?? 'New announcement'),
                    (int)($_SESSION['user_id'] ?? 0),
                    (int)($before['faculty_id'] ?? 0)
                );
            }

            echo json_encode(['success' => (bool)$saved]);
            exit();
        }

        if ($action === 'delete') {
            $announcement_id = (int)($_POST['announcement_id'] ?? 0);
            if ($announcement_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
                exit();
            }

            $saved = $announcementModel->softDeleteAnnouncement($announcement_id, $faculty_id);
            echo json_encode(['success' => (bool)$saved]);
            exit();
        }

        if ($action === 'edit') {
            $announcement_id = (int)($_POST['announcement_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if ($announcement_id <= 0 || $title === '' || $content === '') {
                echo json_encode(['success' => false, 'message' => 'Invalid edit payload']);
                exit();
            }

            $saved = $announcementModel->updateAnnouncementText($announcement_id, $title, $content, $faculty_id);
            echo json_encode(['success' => (bool)$saved]);
            exit();
        }

        if ($action !== 'add') {
            echo json_encode(['success' => false, 'message' => 'Unsupported action']);
            exit();
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $priority = (int)($_POST['priority'] ?? 0);
        $type = Announcement::TYPE_FACULTY; // Faculty admins can only create faculty announcements

        if ($title === '' || $content === '' || $priority < 0 || $priority > 2) {
            echo json_encode(['success' => false, 'message' => 'Title and content are required']);
            exit();
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

            echo json_encode(['success' => true, 'message' => 'Announcement published successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save announcement']);
        }

        exit();
    }
}
