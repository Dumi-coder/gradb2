<?php

class Events extends Controller
{
    private $eventModel;

    public function __construct()
    {
        $this->eventModel = new Event();
    }

    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as superadmin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        $pendingEvents = $this->eventModel->getAllActiveEvents();
        if (!is_array($pendingEvents)) {
            $pendingEvents = [];
        }

        $eventStats = $this->eventModel->getActivityStats();

        $data = [
            'title' => 'Approve Events - GradBridge',
            'page_title' => 'Approve Events',
            'page_subtitle' => 'Review and moderate event submissions.',
            'user' => $_SESSION,
            'pendingEvents' => $pendingEvents,
            'eventStats' => $eventStats
        ];

        $this->view('superadmin/events', $data);
    }
}
