<?php

class Userprofile extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
            return;
        }

        $userId = (int)($_GET['id'] ?? 0);
        if ($userId <= 0) {
            redirect('superadmin/usermanagement');
            return;
        }

        $userModel = new User();
        $rows = $userModel->query(
            "SELECT
                u.user_id,
                u.name,
                u.email,
                u.role,
                CASE WHEN u.role = 'student' THEN s.student_id WHEN u.role = 'alumni' THEN a.alumni_id ELSE NULL END AS membership_id,
                CASE WHEN u.role = 'student' THEN s.faculty_id WHEN u.role = 'alumni' THEN a.faculty_id ELSE NULL END AS faculty_id,
                f.faculty_name,
                CASE WHEN u.role = 'student' THEN s.academic_year ELSE NULL END AS academic_year,
                CASE WHEN u.role = 'student' THEN s.bio WHEN u.role = 'alumni' THEN a.bio ELSE NULL END AS bio,
                CASE WHEN u.role = 'student' THEN s.mobile WHEN u.role = 'alumni' THEN a.mobile ELSE NULL END AS mobile,
                CASE WHEN u.role = 'student' THEN s.profile_photo_url WHEN u.role = 'alumni' THEN a.profile_photo_url ELSE NULL END AS profile_photo_url,
                CASE WHEN u.role = 'student' THEN s.LinkedIn WHEN u.role = 'alumni' THEN a.linkedin_url ELSE NULL END AS linkedin_url,
                CASE WHEN u.role = 'student' THEN s.GitHub WHEN u.role = 'alumni' THEN a.github_url ELSE NULL END AS github_url,
                CASE WHEN u.role = 'alumni' THEN a.twitter_url ELSE NULL END AS twitter_url,
                CASE WHEN u.role = 'alumni' THEN a.personal_website ELSE NULL END AS personal_website,
                CASE WHEN u.role = 'alumni' THEN a.degrees ELSE NULL END AS degrees,
                CASE WHEN u.role = 'alumni' THEN a.current_workplace ELSE NULL END AS current_workplace,
                CASE WHEN u.role = 'alumni' THEN a.current_job ELSE NULL END AS current_job,
                CASE WHEN u.role = 'alumni' THEN a.expertise_area ELSE NULL END AS expertise_area,
                CASE WHEN u.role = 'alumni' THEN a.graduated_year ELSE NULL END AS graduated_year,
                CASE WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
                     WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
                     ELSE 0 END AS is_suspended
             FROM users u
             LEFT JOIN students s ON s.user_id = u.user_id
             LEFT JOIN alumnis a ON a.user_id = u.user_id
             LEFT JOIN faculties f ON f.faculty_id = CASE WHEN u.role = 'student' THEN s.faculty_id WHEN u.role = 'alumni' THEN a.faculty_id ELSE NULL END
             WHERE u.user_id = :user_id
               AND u.role IN ('student', 'alumni')
             LIMIT 1",
            ['user_id' => $userId]
        );

        if (!is_array($rows) || empty($rows[0])) {
            redirect('superadmin/usermanagement');
            return;
        }

        $profile = $rows[0];
        $this->view('superadmin/user-profile', [
            'title' => 'User Profile - GradBridge',
            'profile' => $profile
        ]);
    }
}
