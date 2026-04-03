<?php

class Home extends Controller
{
    
    public function index()// This is the index method of the Home controller
    {
        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->name; // Check if the user is logged in and set the username accordingly
        
        $this->view('home',$data);// This loads the 'home' view.
    }

    public function getNotifications()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized', 'notifications' => []]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['success' => false, 'message' => 'Invalid request', 'notifications' => []]);
            return;
        }

        $notificationModel = new Notification();
        $userId = (int)$_SESSION['user_id'];

        // Fetch ONLY UNREAD notifications (is_read = 0)
        $notifications = $notificationModel->query(
            "SELECT notification_id, recipient_user_id, actor_user_id, title, message, is_read, read_at, created_at
             FROM notifications
             WHERE recipient_user_id = :user_id AND is_read = 0
             ORDER BY created_at DESC
             LIMIT 50",
            ['user_id' => $userId]
        );

        $notificationList = [];
        if ($notifications && is_array($notifications)) {
            foreach ($notifications as $notification) {
                $notificationList[] = [
                    'notification_id' => $notification->notification_id ?? null,
                    'title' => $notification->title ?? '',
                    'message' => $notification->message ?? '',
                    'is_read' => (int)($notification->is_read ?? 0),
                    'created_at' => $notification->created_at ?? '',
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'notifications' => $notificationList,
            'count' => count($notificationList)
        ]);
    }

    public function markNotificationsAsRead()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $notificationIds = $input['notification_ids'] ?? [];

        if (empty($notificationIds) || !is_array($notificationIds)) {
            echo json_encode(['success' => false, 'message' => 'No notification IDs provided']);
            return;
        }

        // Sanitize IDs
        $notificationIds = array_map('intval', $notificationIds);
        $notificationIds = array_filter($notificationIds, function($id) { return $id > 0; });

        if (empty($notificationIds)) {
            echo json_encode(['success' => false, 'message' => 'Invalid notification IDs']);
            return;
        }

        $notificationModel = new Notification();
        $readAt = date('Y-m-d H:i:s');
        $placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
        
        $result = $notificationModel->query(
            "UPDATE notifications
             SET is_read = 1, read_at = ?
             WHERE notification_id IN (" . $placeholders . ")",
            array_merge([$readAt], $notificationIds)
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Notifications marked as read']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to mark notifications as read']);
        }
    }
}

