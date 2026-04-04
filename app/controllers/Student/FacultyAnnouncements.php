<?php

class FacultyAnnouncements extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        $faculty_id = $this->getFacultyIdForStudent();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostAction($faculty_id);
            return;
        }

        $announcementModel = new Announcement();
        $facultyAnnouncements = $faculty_id ? $announcementModel->getPublishedForFaculty($faculty_id) : [];
        $universityAnnouncements = $announcementModel->getPublishedUniversityAnnouncements();

        $data = [
            'title' => 'Faculty Announcements - GradBridge',
            'page_title' => 'Faculty Announcements',
            'page_subtitle' => 'Latest updates for your faculty and university.',
            'facultyAnnouncements' => $facultyAnnouncements,
            'universityAnnouncements' => $universityAnnouncements
        ];

        $this->view('student/faculty-announcements', $data);
    }

    private function getFacultyIdForStudent()
    {
        $studentModel = new Student();
        $profile = $studentModel->getStudentProfile($_SESSION['student_id'] ?? null);
        return $profile ? (int)($profile->faculty_id ?? 0) : 0;
    }

    private function handlePostAction($faculty_id)
    {
        header('Content-Type: application/json');

        $action = $_POST['action'] ?? '';
        if ($action !== 'view') {
            echo json_encode(['success' => false, 'message' => 'Unsupported action']);
            exit();
        }

        $announcement_id = (int)($_POST['announcement_id'] ?? 0);
        if ($announcement_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid announcement']);
            exit();
        }

        $announcementModel = new Announcement();
        $announcement = $announcementModel->getAnnouncementById($announcement_id);
        if (!$announcement) {
            echo json_encode(['success' => false, 'message' => 'Announcement not found']);
            exit();
        }

        $type = (int)($announcement['type'] ?? Announcement::TYPE_FACULTY);
        $announcementFacultyId = (int)($announcement['faculty_id'] ?? 0);
        $isPublished = (int)($announcement['is_published'] ?? 0) === 1;

        $canView = false;
        if ($isPublished) {
            if ($type === Announcement::TYPE_FACULTY) {
                $canView = $announcementFacultyId === (int)$faculty_id;
            } elseif ($type === Announcement::TYPE_UNIVERSITY) {
                $canView = true;
            }
        }

        if (!$canView) {
            echo json_encode(['success' => false, 'message' => 'Announcement not found']);
            exit();
        }

        $announcementModel->incrementViewCount($announcement_id);
        $announcement = $announcementModel->getAnnouncementById($announcement_id);

        echo json_encode(['success' => true, 'announcement' => $announcement]);
        exit();
    }
}
