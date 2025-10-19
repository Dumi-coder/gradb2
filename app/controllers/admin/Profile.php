<?php

class Profile extends Controller
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

        // Check if edit action is requested
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $this->edit();
            return;
        }

        // Get admin profile data
        $user = new User();
        $admin = $user->first(['user_id' => $_SESSION['user_id'], 'role' => 'faculty_admin']);
        
        if (!$admin) {
            redirect('admin');
        }

        // Add social media links to admin profile
        $admin->linkedin_url = 'https://linkedin.com/in/faculty-admin';
        $admin->github_url = 'https://github.com/faculty-admin';
        $admin->twitter_url = 'https://twitter.com/faculty_admin';
        $admin->website_url = 'https://faculty-admin.edu';
        $admin->department = 'Educational Technology';
        $admin->bio = 'Experienced faculty administrator with expertise in educational technology and student support systems. Passionate about creating innovative learning environments.';

        // Get admin statistics
        $stats = $this->getAdminStats();

        $data = [
            'title' => 'Admin Profile - GradBridge',
            'profile' => $admin,
            'stats' => $stats
        ];

        $this->view('admin/profile/show', $data);
    }

    public function edit()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Get admin profile data
        $user = new User();
        $admin = $user->first(['user_id' => $_SESSION['user_id'], 'role' => 'faculty_admin']);
        
        if (!$admin) {
            redirect('admin');
        }

        // Add default social media links and profile data
        $admin->linkedin_url = $admin->linkedin_url ?? 'https://linkedin.com/in/faculty-admin';
        $admin->github_url = $admin->github_url ?? 'https://github.com/faculty-admin';
        $admin->twitter_url = $admin->twitter_url ?? 'https://twitter.com/faculty_admin';
        $admin->website_url = $admin->website_url ?? 'https://faculty-admin.edu';
        $admin->department = $admin->department ?? 'Educational Technology';
        $admin->bio = $admin->bio ?? 'Experienced faculty administrator with expertise in educational technology and student support systems. Passionate about creating innovative learning environments.';

        $data = [
            'title' => 'Edit Admin Profile - GradBridge',
            'profile' => $admin
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleUpdate($data);
        } else {
            $this->view('admin/profile/edit', $data);
        }
    }

    private function handleUpdate($data)
    {
        $errors = [];
        $success = false;

        // Get form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $linkedin_url = trim($_POST['linkedin_url'] ?? '');
        $github_url = trim($_POST['github_url'] ?? '');
        $twitter_url = trim($_POST['twitter_url'] ?? '');
        $website_url = trim($_POST['website_url'] ?? '');

        // Validation
        if (empty($name)) {
            $errors['name'] = "Name is required";
        }

        if (empty($email)) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }

        // Validate social media URLs (optional)
        if (!empty($linkedin_url) && !filter_var($linkedin_url, FILTER_VALIDATE_URL)) {
            $errors['linkedin_url'] = "Please enter a valid LinkedIn URL";
        }
        
        if (!empty($github_url) && !filter_var($github_url, FILTER_VALIDATE_URL)) {
            $errors['github_url'] = "Please enter a valid GitHub URL";
        }
        
        if (!empty($twitter_url) && !filter_var($twitter_url, FILTER_VALIDATE_URL)) {
            $errors['twitter_url'] = "Please enter a valid Twitter URL";
        }
        
        if (!empty($website_url) && !filter_var($website_url, FILTER_VALIDATE_URL)) {
            $errors['website_url'] = "Please enter a valid website URL";
        }

        if (empty($errors)) {
            // Update admin profile
            $user = new User();
            $updateData = [
                'name' => $name,
                'email' => $email,
                'department' => $department,
                'bio' => $bio,
                'linkedin_url' => $linkedin_url,
                'github_url' => $github_url,
                'twitter_url' => $twitter_url,
                'website_url' => $website_url
            ];

            if ($user->update($_SESSION['user_id'], $updateData)) {
                // Update session data
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                
                $success = true;
                $data['success'] = "Profile updated successfully!";
            } else {
                $errors['general'] = "Failed to update profile. Please try again.";
            }
        }

        $data['errors'] = $errors;
        $data['success'] = $success ? "Profile updated successfully!" : null;
        
        $this->view('admin/profile/edit', $data);
    }

    private function getAdminStats()
    {
        // You can expand this to get real statistics from the database
        return [
            'total_students' => 156,
            'total_alumni' => 89,
            'pending_requests' => 23,
            'events_managed' => 12,
            'mentorship_connections' => 45,
            'system_uptime' => '99.9%'
        ];
    }
}
