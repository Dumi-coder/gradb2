<?php

class DeletedUsers extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as super admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('login');
        }

        // Handle POST actions (recover user)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePostAction();
            return;
        }

        // Get all deleted users
        $deletedUsers = $this->getDeletedUsers();
        $faculties = $this->getFaculties();

        $data = [
            'title' => 'Deleted Users - GradBridge',
            'page_title' => 'Deleted Users',
            'page_subtitle' => 'View and recover deleted user accounts from all faculties.',
            'user' => $_SESSION,
            'deletedUsers' => $deletedUsers,
            'faculties' => $faculties
        ];

        $this->view('superadmin/deleted-users', $data);
    }

    private function getDeletedUsers()
    {
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // First, let's check if is_deleted column exists and has data
            $checkQuery = "SELECT COUNT(*) as count FROM students WHERE is_deleted IS NOT NULL";
            $checkStm = $con->query($checkQuery);
            $checkResult = $checkStm->fetch(PDO::FETCH_ASSOC);
            error_log("Students with is_deleted column: " . $checkResult['count']);
            
            // Check for deleted students specifically
            $deletedCheckQuery = "SELECT student_id, user_id, is_deleted FROM students WHERE is_deleted = 1 OR is_deleted = '1'";
            $deletedCheckStm = $con->query($deletedCheckQuery);
            $deletedStudents = $deletedCheckStm->fetchAll(PDO::FETCH_ASSOC);
            error_log("Found deleted students: " . print_r($deletedStudents, true));
            
            // Get deleted students and alumni
            $query = "SELECT s.student_id as id, s.user_id, u.name, u.email, 'student' as role,
                             s.academic_year, NOW() as deleted_date, 
                             f.faculty_name, s.faculty_id
                      FROM students s
                      JOIN users u ON s.user_id = u.user_id
                      JOIN faculties f ON s.faculty_id = f.faculty_id
                      WHERE (s.is_deleted = 1 OR s.is_deleted = '1')
                      UNION ALL
                      SELECT a.alumni_id as id, a.user_id, u.name, u.email, 'alumni' as role,
                             a.graduated_year as academic_year, NOW() as deleted_date,
                             f.faculty_name, a.faculty_id
                      FROM alumnis a
                      JOIN users u ON a.user_id = u.user_id
                      JOIN faculties f ON a.faculty_id = f.faculty_id
                      WHERE (a.is_deleted = 1 OR a.is_deleted = '1')
                      ORDER BY deleted_date DESC";
            
            $stm = $con->query($query);
            $results = $stm->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("getDeletedUsers returned " . count($results) . " users");
            error_log("Deleted users data: " . print_r($results, true));
            
            return $results;
        } catch (PDOException $e) {
            error_log("getDeletedUsers error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return [];
        }
    }

    private function getFaculties()
    {
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $query = "SELECT faculty_id, faculty_name FROM faculties ORDER BY faculty_name";
            $stm = $con->query($query);
            
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getFaculties error: " . $e->getMessage());
            return [];
        }
    }

    private function handlePostAction()
    {
        $action = $_POST['action'] ?? '';
        $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $role = $_POST['role'] ?? '';

        if ($action === 'recover' && $user_id && $role) {
            $this->recoverUser($user_id, $role);
        }
    }

    private function recoverUser($user_id, $role)
    {
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Determine table based on role
            $table = ($role === 'student') ? 'students' : 'alumnis';
            
            // Update is_deleted to 0
            $query = "UPDATE $table SET is_deleted = 0 WHERE user_id = :user_id";
            $stm = $con->prepare($query);
            $result = $stm->execute(['user_id' => $user_id]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'User account recovered successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to recover user account']);
            }
        } catch (PDOException $e) {
            error_log("recoverUser error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error occurred']);
        }
        exit();
    }
}
