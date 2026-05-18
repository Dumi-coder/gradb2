<?php

class EventNotification
{
    use Model;

    private function toArrayRow($row)
    {
        return is_object($row) ? (array)$row : $row;
    }
    
    protected $table = 'event_notifications';
    protected $order_column = 'created_at';
    protected $id_column = 'notification_id';
    
    protected $allowedColumns = [
        'event_id',
        'student_id',
        'notification_type',
        'message',
        'old_value',
        'new_value',
        'is_read'
    ];

    // Create notification for all registered students
    public function notifyRegisteredStudents($event_id, $notification_type, $message, $old_value = null, $new_value = null)
    {
        // Get all registered user IDs (students + alumni) for this event
        $query = "SELECT DISTINCT user_id FROM (
                    SELECT student_id as user_id FROM event_registrations
                    WHERE event_id = :event_id AND student_id IS NOT NULL
                    UNION
                    SELECT alumni_id as user_id FROM event_registrations
                    WHERE event_id = :event_id AND alumni_id IS NOT NULL
                  ) t";
        $users = $this->query($query, ['event_id' => $event_id]);

        if (!$users) {
            return false;
        }
        
        $notifications = [];
        foreach ($users as $user) {
            $user = $this->toArrayRow($user);
            $notifications[] = [
                'event_id' => $event_id,
                'student_id' => $user['user_id'],
                'notification_type' => $notification_type,
                'message' => $message,
                'old_value' => $old_value,
                'new_value' => $new_value,
                'is_read' => 0
            ];
        }
        
        // Insert all notifications
        if (!empty($notifications)) {
            $keys = array_keys($notifications[0]);
            $placeholders = '(' . implode(',', array_map(fn($k) => ":$k", $keys)) . ')';
            $values = implode(',', array_fill(0, count($notifications), $placeholders));
            
            $query = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES " . $values;
            
            $data = [];
            foreach ($notifications as $index => $notification) {
                foreach ($notification as $key => $value) {
                    $data["{$key}_{$index}"] = $value;
                }
            }
            
            // Flatten the data array properly
            $flatData = [];
            foreach ($notifications as $index => $notification) {
                foreach ($notification as $key => $value) {
                    $flatData[":{$key}_{$index}"] = $value;
                }
            }
            
            // Use a simpler approach - insert one by one
            $success = true;
            foreach ($notifications as $notification) {
                if (!$this->insert($notification)) {
                    $success = false;
                }
            }
            return $success;
        }
        
        return false;
    }

    // Get notifications for a student
    public function getStudentNotifications($student_id, $is_read = null)
    {
        return $this->getUserNotifications($student_id, $is_read);
    }

    public function getUserNotifications($user_id, $is_read = null, $limit = null)
    {
        $query = "SELECT en.*, e.title as event_title, e.event_date
                  FROM {$this->table} en
                  LEFT JOIN events e ON en.event_id = e.event_id
                  WHERE en.student_id = :user_id";
        
        $data = ['user_id' => $user_id];
        
        if ($is_read !== null) {
            $query .= " AND en.is_read = :is_read";
            $data['is_read'] = $is_read;
        }
        
        $query .= " ORDER BY en.created_at DESC";

        if ($limit !== null) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->query($query, $data);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    // Mark notification as read
    public function markAsRead($notification_id)
    {
        return $this->update($notification_id, ['is_read' => 1]);
    }

    // Mark all notifications as read for a user.
    public function markAllAsReadForUser($user_id)
    {
        $query = "UPDATE {$this->table}
                  SET is_read = 1
                  WHERE student_id = :user_id
                  AND is_read = 0";

        return $this->query($query, ['user_id' => $user_id]) !== false;
    }

    public function countUnreadForUser($user_id)
    {
        $query = "SELECT COUNT(*) as total
                  FROM {$this->table}
                  WHERE student_id = :user_id
                  AND is_read = 0";
        $result = $this->query($query, ['user_id' => $user_id]);
        if (!is_array($result) || empty($result)) {
            return 0;
        }

        $row = $this->toArrayRow($result[0]);
        return (int)($row['total'] ?? 0);
    }

    public function notifyAllUsers($event_id, $notification_type, $message, $old_value = null, $new_value = null, $excludeUserId = null)
    {
        $query = "SELECT user_id
                  FROM users
                  WHERE role IN ('student', 'alumni')";

        $params = [];
        if ($excludeUserId !== null) {
            $query .= " AND user_id != :exclude_user_id";
            $params['exclude_user_id'] = $excludeUserId;
        }

        $users = $this->query($query, $params);
        if (!is_array($users) || empty($users)) {
            return false;
        }

        $success = true;
        foreach ($users as $user) {
            $user = $this->toArrayRow($user);
            $ok = $this->insert([
                'event_id' => $event_id,
                'student_id' => $user['user_id'],
                'notification_type' => $notification_type,
                'message' => $message,
                'old_value' => $old_value,
                'new_value' => $new_value,
                'is_read' => 0,
            ]);
            if (!$ok) {
                $success = false;
            }
        }

        return $success;
    }
}


