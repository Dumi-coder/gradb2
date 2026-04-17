<?php

trait DashboardMetrics
{
    protected function getDashboardStats(?int $facultyId = null): array
    {
        $db = new User();

        return [
            'registered_students' => $this->countRegisteredUsers($db, 'student', $facultyId),
            'students_online' => $this->countOnlineUsers($db, 'student', $facultyId),
            'pending_aid_requests' => $this->countAidRequests($db, $facultyId),
            'events_waiting_approval' => $this->countEventsWaitingApproval($db, $facultyId),
            'password_verification_requests' => $this->countPasswordVerificationRequests($db),
            'registered_alumni' => $this->countRegisteredUsers($db, 'alumni', $facultyId),
            'alumni_online' => $this->countOnlineUsers($db, 'alumni', $facultyId),
            'pending_mentorship_requests' => $this->countMentorshipRequests($db, $facultyId),
            'pending_complaints' => $this->countPendingComplaints($db),
            'upcoming_events' => $this->countUpcomingEvents($db, $facultyId),
        ];
    }

    private function countRegisteredUsers(User $db, string $role, ?int $facultyId = null): int
    {
        if ($role === 'student') {
            if (!$this->tableExists($db, 'students')) {
                return 0;
            }

            $query = "SELECT COUNT(*) AS total
                      FROM students s
                      INNER JOIN users u ON u.user_id = s.user_id
                      WHERE u.role = :role
                        AND (s.is_deleted IS NULL OR s.is_deleted = 0)";
            $params = ['role' => $role];

            if ($facultyId !== null) {
                $query .= " AND s.faculty_id = :faculty_id";
                $params['faculty_id'] = $facultyId;
            }

            return $this->fetchCount($db, $query, $params);
        }

        if ($role === 'alumni') {
            if (!$this->tableExists($db, 'alumnis')) {
                return 0;
            }

            $query = "SELECT COUNT(*) AS total
                      FROM alumnis a
                      INNER JOIN users u ON u.user_id = a.user_id
                      WHERE u.role = :role
                        AND (a.is_deleted IS NULL OR a.is_deleted = 0)";
            $params = ['role' => $role];

            if ($facultyId !== null) {
                $query .= " AND a.faculty_id = :faculty_id";
                $params['faculty_id'] = $facultyId;
            }

            return $this->fetchCount($db, $query, $params);
        }

        return 0;
    }

    private function countOnlineUsers(User $db, string $role, ?int $facultyId = null): int
    {
        $activeUserIds = $this->getActiveSessionUserIds($role);
        if (empty($activeUserIds)) {
            return 0;
        }

        $table = $role === 'student' ? 'students' : 'alumnis';
        $alias = $role === 'student' ? 's' : 'a';

        if (!$this->tableExists($db, $table)) {
            return 0;
        }

        $placeholders = [];
        $params = ['role' => $role];
        foreach (array_values($activeUserIds) as $index => $userId) {
            $key = 'user_id_' . $index;
            $placeholders[] = ':' . $key;
            $params[$key] = (int)$userId;
        }

        $query = "SELECT COUNT(DISTINCT {$alias}.user_id) AS total
                  FROM {$table} {$alias}
                  INNER JOIN users u ON u.user_id = {$alias}.user_id
                  WHERE u.role = :role
                    AND u.user_id IN (" . implode(',', $placeholders) . ")
                    AND ({$alias}.is_deleted IS NULL OR {$alias}.is_deleted = 0)";

        if ($facultyId !== null && $this->columnExists($db, $table, 'faculty_id')) {
            $query .= " AND {$alias}.faculty_id = :faculty_id";
            $params['faculty_id'] = $facultyId;
        }

        return $this->fetchCount($db, $query, $params);
    }

    private function countAidRequests(User $db, ?int $facultyId = null): int
    {
        if (!$this->tableExists($db, 'requests') || !$this->tableExists($db, 'students')) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total
                  FROM requests r
                  INNER JOIN students s ON s.user_id = r.student_user_id
                  WHERE r.request_type = 'aid'
                    AND r.status = 'pending_verification'
                    AND (s.is_deleted IS NULL OR s.is_deleted = 0)";
        $params = [];

        if ($facultyId !== null) {
            $query .= " AND s.faculty_id = :faculty_id";
            $params['faculty_id'] = $facultyId;
        }

        return $this->fetchCount($db, $query, $params);
    }

    private function countEventsWaitingApproval(User $db, ?int $facultyId = null): int
    {
        if (!$this->tableExists($db, 'events')) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total
                  FROM events e
                  WHERE LOWER(COALESCE(e.status, '')) NOT IN ('active', 'deleted')";
        $params = [];

        if ($facultyId !== null && $this->tableExists($db, 'alumnis')) {
            $query = "SELECT COUNT(*) AS total
                      FROM events e
                      INNER JOIN alumnis a ON a.user_id = e.host_alumnus_id
                      WHERE LOWER(COALESCE(e.status, '')) NOT IN ('active', 'deleted')
                        AND (a.is_deleted IS NULL OR a.is_deleted = 0)";
            if ($this->columnExists($db, 'alumnis', 'faculty_id')) {
                $query .= " AND a.faculty_id = :faculty_id";
                $params['faculty_id'] = $facultyId;
            }
        }

        return $this->fetchCount($db, $query, $params);
    }

    private function countPasswordVerificationRequests(User $db): int
    {
        if (!$this->tableExists($db, 'password_resets')) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total
                  FROM password_resets
                  WHERE used_at IS NULL
                    AND expires_at >= NOW()";

        return $this->fetchCount($db, $query);
    }

    private function countMentorshipRequests(User $db, ?int $facultyId = null): int
    {
        if (!$this->tableExists($db, 'requests') || !$this->tableExists($db, 'students')) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total
                  FROM requests r
                  INNER JOIN students s ON s.user_id = r.student_user_id
                  WHERE r.request_type = 'mentorship'
                    AND r.status = 'pending'
                    AND (s.is_deleted IS NULL OR s.is_deleted = 0)";
        $params = [];

        if ($facultyId !== null) {
            $query .= " AND s.faculty_id = :faculty_id";
            $params['faculty_id'] = $facultyId;
        }

        return $this->fetchCount($db, $query, $params);
    }

    private function countPendingComplaints(User $db): int
    {
        $tables = ['user_complaints', 'complaints', 'complaint_reports'];

        foreach ($tables as $table) {
            if (!$this->tableExists($db, $table)) {
                continue;
            }

            if ($this->columnExists($db, $table, 'status')) {
                $query = "SELECT COUNT(*) AS total
                          FROM {$table}
                          WHERE LOWER(COALESCE(status, '')) IN ('pending', 'open', 'unresolved', 'waiting')";
                return $this->fetchCount($db, $query);
            }

            if ($this->columnExists($db, $table, 'complaint_status')) {
                $query = "SELECT COUNT(*) AS total
                          FROM {$table}
                          WHERE LOWER(COALESCE(complaint_status, '')) IN ('pending', 'open', 'unresolved', 'waiting')";
                return $this->fetchCount($db, $query);
            }

            if ($this->columnExists($db, $table, 'is_resolved')) {
                $query = "SELECT COUNT(*) AS total
                          FROM {$table}
                          WHERE COALESCE(is_resolved, 0) = 0";
                return $this->fetchCount($db, $query);
            }

            return $this->fetchCount($db, "SELECT COUNT(*) AS total FROM {$table}");
        }

        return 0;
    }

    private function countUpcomingEvents(User $db, ?int $facultyId = null): int
    {
        if (!$this->tableExists($db, 'events')) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total
                  FROM events e
                  WHERE e.status = 'active'
                    AND e.event_date >= CURDATE()";
        $params = [];

        if ($facultyId !== null && $this->tableExists($db, 'alumnis')) {
            $query = "SELECT COUNT(*) AS total
                      FROM events e
                      INNER JOIN alumnis a ON a.user_id = e.host_alumnus_id
                      WHERE e.status = 'active'
                        AND e.event_date >= CURDATE()
                        AND (a.is_deleted IS NULL OR a.is_deleted = 0)";
            if ($this->columnExists($db, 'alumnis', 'faculty_id')) {
                $query .= " AND a.faculty_id = :faculty_id";
                $params['faculty_id'] = $facultyId;
            }
        }

        return $this->fetchCount($db, $query, $params);
    }

    private function getActiveSessionUserIds(string $role): array
    {
        $savePath = session_save_path();
        if ($savePath === false || $savePath === '') {
            return [];
        }

        if (strpos($savePath, ';') !== false) {
            $savePath = substr($savePath, strrpos($savePath, ';') + 1);
        }

        $savePath = rtrim($savePath, "\\/");
        if ($savePath === '' || !is_dir($savePath)) {
            return [];
        }

        $cutoff = time() - max(300, (int)ini_get('session.gc_maxlifetime'));
        $userIds = [];

        foreach (glob($savePath . DIRECTORY_SEPARATOR . 'sess_*') ?: [] as $sessionFile) {
            if (!is_file($sessionFile) || @filemtime($sessionFile) < $cutoff) {
                continue;
            }

            $contents = @file_get_contents($sessionFile);
            if ($contents === false || strpos($contents, 'role|s:') === false) {
                continue;
            }

            if (strpos($contents, 'role|s:' . strlen($role) . ':"' . $role . '";') === false) {
                continue;
            }

            if (preg_match('/user_id\|(?:(?:i:(\d+);)|(?:s:\d+:"(\d+)";))/', $contents, $matches)) {
                $userId = (int)($matches[1] ?? $matches[2] ?? 0);
                if ($userId > 0) {
                    $userIds[$userId] = true;
                }
            }
        }

        return array_keys($userIds);
    }

    private function tableExists(User $db, string $table): bool
    {
        $row = $db->get_row(
            'SELECT COUNT(*) AS total FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table_name',
            ['table_name' => $table]
        );

        return $row && (int)($row->total ?? 0) > 0;
    }

    private function columnExists(User $db, string $table, string $column): bool
    {
        $row = $db->get_row(
            'SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name',
            [
                'table_name' => $table,
                'column_name' => $column,
            ]
        );

        return $row && (int)($row->total ?? 0) > 0;
    }

    private function fetchCount(User $db, string $query, array $params = []): int
    {
        $row = $db->get_row($query, $params);
        if (!$row) {
            return 0;
        }

        return (int)($row->total ?? 0);
    }
}