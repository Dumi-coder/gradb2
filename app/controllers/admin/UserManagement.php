<?php

class UserManagement extends Controller
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

        // Get user management data
        $userData = $this->getUserData();

        $data = [
            'title' => 'Suspend/Reactivate Users - GradBridge',
            'page_title' => 'Suspend/Reactivate Users',
            'page_subtitle' => 'Manage user accounts and access permissions.',
            'user' => $_SESSION,
            'userData' => $userData
        ];

        $this->view('admin/user-management', $data);
    }

    public function reactivate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid user']);
            return;
        }

        $facultyId = $this->getFacultyIdForAdmin();
        if (!$facultyId) {
            echo json_encode(['success' => false, 'message' => 'Faculty scope not found']);
            return;
        }

        $userModel = new User();
        $rows = $userModel->query(
            "SELECT u.role,
                    CASE WHEN u.role = 'student' THEN s.faculty_id
                         WHEN u.role = 'alumni' THEN a.faculty_id
                         ELSE NULL END AS faculty_id
             FROM users u
             LEFT JOIN students s ON s.user_id = u.user_id
             LEFT JOIN alumnis a ON a.user_id = u.user_id
             WHERE u.user_id = :user_id
             LIMIT 1",
            ['user_id' => $userId]
        );

        if (!is_array($rows) || empty($rows[0])) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }

        $role = $rows[0]->role ?? '';
        $targetFacultyId = (int)($rows[0]->faculty_id ?? 0);

        if (!in_array($role, ['student', 'alumni'], true)) {
            echo json_encode(['success' => false, 'message' => 'Only student and alumni accounts can be reactivated here.']);
            return;
        }

        if ($targetFacultyId !== (int)$facultyId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized for this user']);
            return;
        }

        $table = $role === 'student' ? 'students' : 'alumnis';
        $ok = $userModel->query(
            "UPDATE {$table}
             SET is_suspended = 0
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        );

        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'User reactivated successfully.']);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'Failed to reactivate user']);
    }

    private function getUserData()
    {
        $facultyId = $this->getFacultyIdForAdmin();
        if (!$facultyId) {
            return [
                'users' => [],
                'stats' => [
                    'total_users' => 0,
                    'active_users' => 0,
                    'suspended_users' => 0
                ]
            ];
        }

        $userModel = new User();

        $users = $userModel->query(
            "SELECT
                u.user_id AS id,
                u.name,
                u.email,
                u.role,
                CASE
                    WHEN u.role = 'student' THEN s.student_id
                    WHEN u.role = 'alumni' THEN a.alumni_id
                    ELSE NULL
                END AS membership_id,
                CASE
                    WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                    ELSE 0
                END AS is_suspended,
                CASE
                    WHEN u.role = 'student' THEN s.faculty_id
                    WHEN u.role = 'alumni' THEN a.faculty_id
                    ELSE NULL
                END AS faculty_id,
                f.faculty_name
             FROM users u
             LEFT JOIN students s ON s.user_id = u.user_id
             LEFT JOIN alumnis a ON a.user_id = u.user_id
             LEFT JOIN faculties f ON f.faculty_id = CASE
                 WHEN u.role = 'student' THEN s.faculty_id
                 WHEN u.role = 'alumni' THEN a.faculty_id
                 ELSE NULL
             END
             WHERE u.role IN ('student', 'alumni')
               AND CASE
                    WHEN u.role = 'student' THEN COALESCE(s.faculty_id, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.faculty_id, 0)
                    ELSE 0
               END = :faculty_id
               AND CASE
                    WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                    ELSE 0
               END = 1
             ORDER BY u.name ASC",
            ['faculty_id' => (int)$facultyId]
        );

        $availableUsers = $userModel->query(
            "SELECT
                u.user_id AS id,
                u.name,
                u.email,
                u.role,
                CASE
                    WHEN u.role = 'student' THEN s.student_id
                    WHEN u.role = 'alumni' THEN a.alumni_id
                    ELSE NULL
                END AS membership_id,
                CASE
                    WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                    ELSE 0
                END AS is_suspended,
                f.faculty_name
             FROM users u
             LEFT JOIN students s ON s.user_id = u.user_id
             LEFT JOIN alumnis a ON a.user_id = u.user_id
             LEFT JOIN faculties f ON f.faculty_id = CASE
                 WHEN u.role = 'student' THEN s.faculty_id
                 WHEN u.role = 'alumni' THEN a.faculty_id
                 ELSE NULL
             END
             WHERE u.role IN ('student', 'alumni')
               AND CASE
                    WHEN u.role = 'student' THEN COALESCE(s.faculty_id, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.faculty_id, 0)
                    ELSE 0
               END = :faculty_id
               AND CASE
                    WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                    ELSE 0
               END = 0
             ORDER BY u.name ASC",
            ['faculty_id' => (int)$facultyId]
        );

        $statsRow = $userModel->query(
            "SELECT
                COUNT(*) AS total_users,
                SUM(CASE
                    WHEN CASE
                        WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                        WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                        ELSE 0
                    END = 0 THEN 1 ELSE 0 END) AS active_users,
                SUM(CASE
                    WHEN CASE
                        WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                        WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                        ELSE 0
                    END = 1 THEN 1 ELSE 0 END) AS suspended_users
             FROM users u
             LEFT JOIN students s ON s.user_id = u.user_id
             LEFT JOIN alumnis a ON a.user_id = u.user_id
             WHERE u.role IN ('student', 'alumni')
               AND CASE
                    WHEN u.role = 'student' THEN COALESCE(s.faculty_id, 0)
                    WHEN u.role = 'alumni' THEN COALESCE(a.faculty_id, 0)
                    ELSE 0
               END = :faculty_id",
            ['faculty_id' => (int)$facultyId]
        );

        $stats = [
            'total_users' => 0,
            'active_users' => 0,
            'suspended_users' => 0
        ];

        if (is_array($statsRow) && !empty($statsRow[0])) {
            $stats = [
                'total_users' => (int)($statsRow[0]->total_users ?? 0),
                'active_users' => (int)($statsRow[0]->active_users ?? 0),
                'suspended_users' => (int)($statsRow[0]->suspended_users ?? 0)
            ];
        }

        $formattedUsers = [];
        if (is_array($users)) {
            foreach ($users as $user) {
                $formattedUsers[] = [
                    'id' => (int)($user->id ?? 0),
                    'name' => $user->name ?? 'Unknown',
                    'email' => $user->email ?? '',
                    'role' => $user->role ?? '',
                    'membership_id' => $user->membership_id ?? '',
                    'faculty_name' => $user->faculty_name ?? '',
                    'status' => ((int)($user->is_suspended ?? 0) === 1) ? 'suspended' : 'active'
                ];
            }
        }

        $formattedAvailableUsers = [];
        if (is_array($availableUsers)) {
            foreach ($availableUsers as $user) {
                $formattedAvailableUsers[] = [
                    'id' => (int)($user->id ?? 0),
                    'name' => $user->name ?? 'Unknown',
                    'email' => $user->email ?? '',
                    'role' => $user->role ?? '',
                    'membership_id' => $user->membership_id ?? '',
                    'faculty_name' => $user->faculty_name ?? '',
                    'status' => ((int)($user->is_suspended ?? 0) === 1) ? 'suspended' : 'active'
                ];
            }
        }

        return [
            'users' => $formattedUsers,
            'available_users' => $formattedAvailableUsers,
            'stats' => $stats
        ];
    }

    public function suspend()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid user']);
            return;
        }

        $facultyId = $this->getFacultyIdForAdmin();
        if (!$facultyId) {
            echo json_encode(['success' => false, 'message' => 'Faculty scope not found']);
            return;
        }

        $userModel = new User();
        $rows = $userModel->query(
            "SELECT u.role,
                    CASE WHEN u.role = 'student' THEN s.faculty_id
                         WHEN u.role = 'alumni' THEN a.faculty_id
                         ELSE NULL END AS faculty_id
             FROM users u
             LEFT JOIN students s ON s.user_id = u.user_id
             LEFT JOIN alumnis a ON a.user_id = u.user_id
             WHERE u.user_id = :user_id
             LIMIT 1",
            ['user_id' => $userId]
        );

        if (!is_array($rows) || empty($rows[0])) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }

        $role = $rows[0]->role ?? '';
        $targetFacultyId = (int)($rows[0]->faculty_id ?? 0);

        if (!in_array($role, ['student', 'alumni'], true)) {
            echo json_encode(['success' => false, 'message' => 'Only student and alumni accounts can be suspended here.']);
            return;
        }

        if ($targetFacultyId !== (int)$facultyId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized for this user']);
            return;
        }

        $table = $role === 'student' ? 'students' : 'alumnis';
        $ok = $userModel->query(
            "UPDATE {$table}
             SET is_suspended = 1
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        );

        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'User suspended successfully.']);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'Failed to suspend user']);
    }

    private function getFacultyIdForAdmin()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        $facultyAdmin = new FacultyAdmin();
        $row = $facultyAdmin->query(
            "SELECT faculty_id
             FROM faculty_admins
             WHERE user_id = :user_id
             ORDER BY faculty_admin_id DESC
             LIMIT 1",
            ['user_id' => $userId]
        );

        if (is_array($row) && !empty($row[0]) && isset($row[0]->faculty_id)) {
            return (int)$row[0]->faculty_id;
        }

        return null;
    }
}
