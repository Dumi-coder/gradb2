<?php

class Event
{
    use Model;

    private function toArrayRow($row)
    {
        return is_object($row) ? (array)$row : $row;
    }
    
    protected $table = 'events';
    protected $order_column = 'created_at';
    protected $id_column = 'event_id';
    
    protected $allowedColumns = [
        'host_alumnus_id',
        'title',
        'description',
        'category',
        'event_date',
        'start_time',
        'end_time',
        'venue',
        'mode',
        'image_path',
        'registration_link',
        'max_attendees',
        'tags',
        'status',
        'registration_status'
    ];

    // Get all active events
    public function getAllActiveEvents()
    {
                $query = "SELECT e.*, u.name as organizer_name, u.email as organizer_email,
                                    COALESCE(er.registered_count, 0) as registered_count,
                                    CASE
                                        WHEN e.max_attendees IS NOT NULL
                                        AND COALESCE(er.registered_count, 0) >= e.max_attendees THEN 1
                                        ELSE 0
                                    END as is_full
                                    FROM {$this->table} e
                                    LEFT JOIN users u ON e.host_alumnus_id = u.user_id
                                    LEFT JOIN (
                                        SELECT event_id, COUNT(*) as registered_count
                                        FROM event_registrations
                                        GROUP BY event_id
                                    ) er ON er.event_id = e.event_id
                                    WHERE e.status = 'active'
                                    AND TIMESTAMP(e.event_date, COALESCE(e.end_time, e.start_time)) >= NOW()
                                    ORDER BY
                                        CASE
                                            WHEN e.registration_status = 'closed'
                                            OR (e.max_attendees IS NOT NULL AND COALESCE(er.registered_count, 0) >= e.max_attendees)
                                            THEN 1
                                            ELSE 0
                                        END ASC,
                                        e.event_date ASC,
                                        e.start_time ASC";
        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    // Get events by alumni
    public function getEventsByAlumni($alumni_id)
    {
        $query = "SELECT e.*, 
                  (SELECT COUNT(*) FROM event_registrations er WHERE er.event_id = e.event_id) as registered_count
                  FROM {$this->table} e
                  WHERE e.host_alumnus_id = :host_alumnus_id
                  AND e.status = 'active'
                  AND TIMESTAMP(e.event_date, COALESCE(e.end_time, e.start_time)) >= NOW()
                  ORDER BY e.event_date DESC, e.created_at DESC";
        $result = $this->query($query, ['host_alumnus_id' => $alumni_id]);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    // Get single event with organizer info
    public function getEventWithOrganizer($event_id)
    {
        $query = "SELECT e.*, u.name as organizer_name, u.email as organizer_email
                  FROM {$this->table} e
                  LEFT JOIN users u ON e.host_alumnus_id = u.user_id
                  WHERE e.event_id = :event_id
                  AND e.status = 'active'";
        $result = $this->query($query, ['event_id' => $event_id]);
        if (!is_array($result) || empty($result)) {
            return false;
        }

        return $this->toArrayRow($result[0]);
    }

    // Get registered students for an event
    public function getRegisteredStudents($event_id)
    {
        $query = "SELECT er.*, u.name as student_name, u.email as student_email
                  FROM event_registrations er
                  LEFT JOIN users u ON er.student_id = u.user_id
                  WHERE er.event_id = :event_id";
        $result = $this->query($query, ['event_id' => $event_id]);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    // Get dashboard activity stats used by student/alumni event boards
    public function getActivityStats()
    {
        $query = "SELECT
                    (SELECT COUNT(*)
                     FROM {$this->table}
                     WHERE status = 'active'
                     AND YEAR(event_date) = YEAR(CURDATE())
                     AND MONTH(event_date) = MONTH(CURDATE())) AS events_this_month,
                    (SELECT COUNT(*) FROM event_registrations) AS total_registrations,
                    (SELECT COUNT(*)
                     FROM {$this->table}
                     WHERE status = 'active'
                     AND event_date >= CURDATE()
                     AND event_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)) AS upcoming_this_week";

        $result = $this->query($query);
        if (!is_array($result) || empty($result)) {
            return [
                'events_this_month' => 0,
                'total_registrations' => 0,
                'upcoming_this_week' => 0,
            ];
        }

        $row = $this->toArrayRow($result[0]);
        return [
            'events_this_month' => (int)($row['events_this_month'] ?? 0),
            'total_registrations' => (int)($row['total_registrations'] ?? 0),
            'upcoming_this_week' => (int)($row['upcoming_this_week'] ?? 0),
        ];
    }

    public function getModerationEvents($facultyId = null)
    {
        $data = [];
        $facultyFilter = '';

        if ($facultyId !== null) {
            $facultyFilter = ' AND a.faculty_id = :faculty_id';
            $data['faculty_id'] = (int)$facultyId;
        }

        $query = "SELECT e.*, u.name as organizer_name, u.email as organizer_email,
                         a.faculty_id as organizer_faculty_id,
                         f.faculty_name as organizer_faculty_name,
                         COALESCE(er.registered_count, 0) as registered_count,
                         CASE
                             WHEN e.max_attendees IS NOT NULL
                              AND COALESCE(er.registered_count, 0) >= e.max_attendees THEN 1
                             ELSE 0
                         END as is_full
                  FROM {$this->table} e
                  LEFT JOIN users u ON e.host_alumnus_id = u.user_id
                  LEFT JOIN alumnis a ON a.user_id = e.host_alumnus_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  LEFT JOIN (
                      SELECT event_id, COUNT(*) as registered_count
                      FROM event_registrations
                      GROUP BY event_id
                  ) er ON er.event_id = e.event_id
                  WHERE e.status = 'active'" . $facultyFilter . "
                  ORDER BY e.event_date ASC, e.start_time ASC, e.created_at DESC";

        $result = $this->query($query, $data);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getModerationEventById($eventId, $facultyId = null)
    {
        $data = ['event_id' => (int)$eventId];
        $facultyFilter = '';

        if ($facultyId !== null) {
            $facultyFilter = ' AND a.faculty_id = :faculty_id';
            $data['faculty_id'] = (int)$facultyId;
        }

        $query = "SELECT e.*, u.name as organizer_name, u.email as organizer_email,
                         a.faculty_id as organizer_faculty_id,
                         f.faculty_name as organizer_faculty_name,
                         COALESCE(er.registered_count, 0) as registered_count
                  FROM {$this->table} e
                  LEFT JOIN users u ON e.host_alumnus_id = u.user_id
                  LEFT JOIN alumnis a ON a.user_id = e.host_alumnus_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  LEFT JOIN (
                      SELECT event_id, COUNT(*) as registered_count
                      FROM event_registrations
                      GROUP BY event_id
                  ) er ON er.event_id = e.event_id
                  WHERE e.event_id = :event_id
                    AND e.status = 'active'" . $facultyFilter . "
                  LIMIT 1";

        $result = $this->query($query, $data);
        if (!is_array($result) || empty($result)) {
            return false;
        }

        return $this->toArrayRow($result[0]);
    }
}


