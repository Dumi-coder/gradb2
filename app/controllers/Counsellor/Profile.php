<?php

class Profile extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counsellor' || (int)($_SESSION['user_id'] ?? 0) !== 1) {
            redirect('counsellor');
        }

        $activeUserId = (int)($_SESSION['user_id'] ?? 0);
        if ($activeUserId <= 0) {
            redirect('counsellor');
            return;
        }

        $counsellorModel = new Counsellor();
        $userModel = new User();

        $counsellorModel->ensurePrimaryExists();

        $counsellor = $counsellorModel->getPrimaryCounsellor();
        $user = $userModel->first(['user_id' => $activeUserId]);

        if (!$counsellor && !$user) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'text' => 'No profile data found for your counsellor account.',
            ];
            redirect('counsellor/dashboard');
            return;
        }

        $name = $counsellor->name ?? ($user->name ?? 'Counsellor');
        $email = $counsellor->email ?? ($user->email ?? 'N/A');
        $passwordRaw = $counsellor->password ?? ($user->password ?? '');
        $photo = $counsellor->profile_photo_url ?? ($user->profile_photo_url ?? null);

        $_SESSION['name'] = $name;
        $_SESSION['profile_photo_url'] = $photo;
        $_SESSION['profile_picture'] = $photo;

        $profile = (object) [
            'name' => $name,
            'email' => $email,
            'password_display' => !empty($passwordRaw) ? '********' : 'Not set',
            'role' => 'counsellor',
            'profile_photo_url' => $photo,
        ];

        $this->view('counsellor/profile', [
            'title' => 'Counsellor Profile - GradBridge',
            'profile' => $profile,
        ]);
    }
}
