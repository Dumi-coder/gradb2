<?php

class Profile extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counselor' || (int)$_SESSION['user_id'] !== 1) {
            redirect('counselor');
        }

        $activeUserId = 1;

        $counselorModel = new Counselor();
        $userModel = new User();

        $counselor = $counselorModel->first(['user_id' => $activeUserId]);
        $user = $userModel->first(['user_id' => $activeUserId]);

        if (!$counselor && $user) {
            $seedData = [
                'user_id' => $activeUserId,
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'password' => $user->password ?? '',
            ];
            $counselorModel->insert($seedData);
            $counselor = $counselorModel->first(['user_id' => $activeUserId]);
        }

        if (!$counselor && !$user) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'No profile data found for user_id 1 in counselor/users tables.',
            ];
            redirect('counselor/dashboard');
            return;
        }

        $name = $counselor->name ?? ($user->name ?? 'Counselor');
        $email = $counselor->email ?? ($user->email ?? 'N/A');
        $passwordRaw = $counselor->password ?? ($user->password ?? '');
        $photo = $counselor->profile_photo_url ?? ($user->profile_photo_url ?? null);

        $_SESSION['name'] = $name;
        $_SESSION['profile_photo_url'] = $photo;
        $_SESSION['profile_picture'] = $photo;

        $profile = (object) [
            'name' => $name,
            'email' => $email,
            'password_display' => !empty($passwordRaw) ? '********' : 'Not set',
            'role' => 'counselor',
            'profile_photo_url' => $photo,
        ];

        $this->view('counselor/profile', [
            'title' => 'Counselor Profile - GradBridge',
            'profile' => $profile,
        ]);
    }
}
