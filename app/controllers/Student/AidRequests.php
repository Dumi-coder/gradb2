<?php
class AidRequests extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionRole = strtolower((string)($_SESSION['role'] ?? ''));
        $legacyRole = strtolower((string)($_SESSION['USER']->role ?? ''));
        $role = $sessionRole !== '' ? $sessionRole : $legacyRole;
        $userId = $_SESSION['user_id'] ?? ($_SESSION['USER']->user_id ?? null);

        if (!$userId || $role !== 'student') {
            redirect('student/auth');
        }

        $requestModel = new Request();
        $requests = $requestModel->getAidRequestsForStudent($userId);

        $this->view('student/aid-requests', [
            'requests' => $requests,
        ]);
    }
}