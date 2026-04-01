<?php

class FacultyModeration extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostAction();
            return;
        }

        $facultyAdminModel = new FacultyAdmin();
        $faculties = $facultyAdminModel->getFacultiesWithCurrentAdmin();

        $faculty_id = isset($_GET['faculty_id']) ? (int)$_GET['faculty_id'] : 0;
        if ($faculty_id <= 0 && !empty($faculties)) {
            $faculty_id = (int)$faculties[0]->faculty_id;
        }

        $selectedFaculty = false;
        if ($faculty_id > 0) {
            $selectedFaculty = $facultyAdminModel->getFacultyWithCurrentAdmin($faculty_id);
        }

        $data = [
            'title' => 'Faculty Moderation - GradBridge',
            'page_title' => 'Faculty Moderation',
            'page_subtitle' => 'Demote current faculty admins and appoint new ones by faculty.',
            'user' => $_SESSION,
            'faculties' => $faculties,
            'selectedFaculty' => $selectedFaculty,
            'selectedFacultyId' => $faculty_id,
            'errors' => $_SESSION['faculty_mod_errors'] ?? [],
            'success' => $_SESSION['faculty_mod_success'] ?? ''
        ];

        unset($_SESSION['faculty_mod_errors'], $_SESSION['faculty_mod_success']);

        $this->view('superadmin/faculty-moderation', $data);
    }

    private function handlePostAction()
    {
        $action = $_POST['action'] ?? '';

        if ($action === 'demote') {
            $this->handleDemotion();
            return;
        }

        if ($action === 'appoint') {
            $this->handleAppointment();
            return;
        }

        $_SESSION['faculty_mod_errors'] = ['Invalid action requested'];
        redirect('superadmin/FacultyModeration');
    }

    private function handleDemotion()
    {
        $faculty_admin_id = trim($_POST['faculty_admin_id'] ?? '');
        $faculty_id = isset($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : 0;

        if ($faculty_admin_id === '' || $faculty_id <= 0) {
            $_SESSION['faculty_mod_errors'] = ['Invalid faculty admin selected'];
            redirect('superadmin/FacultyModeration');
        }

        $facultyAdminModel = new FacultyAdmin();
        $demoted = $facultyAdminModel->deactivateAdmin($faculty_admin_id);

        if ($demoted) {
            $_SESSION['faculty_mod_success'] = 'Faculty admin was demoted successfully.';
        } else {
            $_SESSION['faculty_mod_errors'] = ['Failed to demote faculty admin'];
        }

        redirect('superadmin/FacultyModeration?faculty_id=' . $faculty_id);
    }

    private function handleAppointment()
    {
        $errors = [];

        $faculty_id = isset($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : 0;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($faculty_id <= 0) {
            $errors[] = 'Faculty is required';
        }

        if ($name === '') {
            $errors[] = 'Name is required';
        }

        if ($email === '') {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if ($password === '') {
            $errors[] = 'Password is required';
        } else {
            $passwordValidation = validatePasswordStrength($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }

        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match';
        }

        $facultyModel = new Faculty();
        $faculty = false;
        $facultyAdminModel = new FacultyAdmin();
        if ($faculty_id > 0) {
            $faculty = $facultyModel->first(['faculty_id' => $faculty_id]);
            if (!$faculty) {
                $errors[] = 'Selected faculty was not found';
            }

            $selectedFaculty = $facultyAdminModel->getFacultyWithCurrentAdmin($faculty_id);
            if (!empty($selectedFaculty) && !empty($selectedFaculty->faculty_admin_id)) {
                $errors[] = 'This faculty already has an admin.';
            }
        }

        $userModel = new User();
        if ($email !== '' && $userModel->first(['email' => $email])) {
            $errors[] = 'Email already exists';
        }

        if (!empty($errors)) {
            $_SESSION['faculty_mod_errors'] = $errors;
            redirect('superadmin/FacultyModeration?faculty_id=' . max(0, $faculty_id));
        }

        $user_data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'faculty_admin'
        ];

        if (!$userModel->insert($user_data)) {
            $_SESSION['faculty_mod_errors'] = ['Failed to create faculty admin user'];
            redirect('superadmin/FacultyModeration?faculty_id=' . $faculty_id);
        }

        $created_user = $userModel->first(['email' => $email, 'role' => 'faculty_admin']);
        if (!$created_user) {
            $_SESSION['faculty_mod_errors'] = ['User was created, but could not be loaded'];
            redirect('superadmin/FacultyModeration?faculty_id=' . $faculty_id);
        }

        $facultyAdminModel = new FacultyAdmin();

        $faculty_admin_data = [
            'faculty_admin_id' => $facultyAdminModel->generateNextFacultyAdminId(),
            'user_id' => $created_user->user_id,
            'faculty_id' => $faculty_id,
            'is_deactivated' => 0,
            'admin_level' => 'faculty_admin',
        ];

        if (!$facultyAdminModel->insert($faculty_admin_data)) {
            $userModel->delete($created_user->user_id, 'user_id');
            $_SESSION['faculty_mod_errors'] = ['Failed to create faculty admin profile'];
            redirect('superadmin/FacultyModeration?faculty_id=' . $faculty_id);
        }

        $_SESSION['faculty_mod_success'] = 'New faculty admin appointed successfully for ' . ($faculty->faculty_name ?? 'the selected faculty') . '.';
        redirect('superadmin/FacultyModeration?faculty_id=' . $faculty_id);
    }
}
