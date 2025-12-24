<?php

class DeletedUsers extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as faculty admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Handle POST actions (recover user)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handlePostAction();
            return;
        }

        // Get faculty_id for this admin
        $faculty_id = $this->getFacultyIdForAdmin();
        
        if (!$faculty_id) {
            die('Faculty admin configuration error. Please contact administrator.');
        }

        // Get deleted users data
        $deletedUsers = $this->getDeletedUsers($faculty_id);

        $data = [
            'title' => 'Deleted Users - GradBridge',
            'page_title' => 'Deleted Users',
            'page_subtitle' => 'View and recover deleted user accounts from your faculty.',
            'user' => $_SESSION,
            'deletedUsers' => $deletedUsers
        ];

        $this->view('admin/deleted-users', $data);
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
            
            if ($result && $result->faculty_id) {
                return $result->faculty_id;
            }
        } catch (PDOException $e) {
            error_log("getFacultyIdForAdmin: Database error - " . $e->getMessage());
        }
        
        return null;
    }

    private function getDeletedUsers($faculty_id)
    {
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            error_log("Faculty Admin checking for deleted users with faculty_id: " . $faculty_id);
            
            // Get deleted students
            $query = "SELECT s.student_id as id, s.user_id, u.name, u.email, 'student' as role,
                             s.academic_year, NOW() as deleted_date, 
                             f.faculty_name
                      FROM students s
                      JOIN users u ON s.user_id = u.user_id
                      JOIN faculties f ON s.faculty_id = f.faculty_id
                      WHERE (s.is_deleted = 1 OR s.is_deleted = '1') AND s.faculty_id = :faculty_id
                      UNION ALL
                      SELECT a.alumni_id as id, a.user_id, u.name, u.email, 'alumni' as role,
                             a.graduated_year as academic_year, NOW() as deleted_date,
                             f.faculty_name
                      FROM alumnis a
                      JOIN users u ON a.user_id = u.user_id
                      JOIN faculties f ON a.faculty_id = f.faculty_id
                      WHERE (a.is_deleted = 1 OR a.is_deleted = '1') AND a.faculty_id = :faculty_id
                      ORDER BY deleted_date DESC";
            
            $stm = $con->prepare($query);
            $stm->execute(['faculty_id' => $faculty_id]);
            
            $results = $stm->fetchAll(PDO::FETCH_ASSOC);
            error_log("Faculty Admin getDeletedUsers returned " . count($results) . " users");
            
            return $results;
        } catch (PDOException $e) {
            error_log("getDeletedUsers error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
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
