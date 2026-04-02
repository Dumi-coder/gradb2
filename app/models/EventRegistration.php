<?php

class EventRegistration
{
    use Model;

    private function toArrayRow($row)
    {
        return is_object($row) ? (array)$row : $row;
    }
    
    protected $table = 'event_registrations';
    protected $order_column = 'registered_at';
    protected $id_column = 'registration_id';
    
    protected $allowedColumns = [
        'event_id',
        'student_id',
        'registered_alumni_id',
        'registered_at'
    ];

    public function isStudentRegistered($event_id, $student_id)
    {
        return (bool)$this->first([
            'event_id' => $event_id,
            'student_id' => $student_id
        ]);
    }

    public function isAlumniRegistered($event_id, $alumni_id)
    {
        return (bool)$this->first([
            'event_id' => $event_id,
            'registered_alumni_id' => $alumni_id
        ]);
    }

    public function registerStudent($event_id, $student_id)
    {
        if ($this->isStudentRegistered($event_id, $student_id)) {
            return false;
        }

        return $this->insert([
            'event_id' => $event_id,
            'student_id' => $student_id,
            'alumni_id' => null,
            'registered_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function registerAlumni($event_id, $alumni_id)
    {
        if ($this->isAlumniRegistered($event_id, $alumni_id)) {
            return false;
        }

        return $this->insert([
            'event_id' => $event_id,
            'student_id' => null,
            'registered_alumni_id' => $alumni_id,
            'registered_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function unregisterStudent($event_id, $student_id)
    {
        $registration = $this->first([
            'event_id' => $event_id,
            'student_id' => $student_id
        ]);

        if (is_object($registration)) {
            $registration = (array)$registration;
        }

        if (empty($registration['registration_id'])) {
            return false;
        }

        return $this->delete($registration['registration_id']);
    }

    public function unregisterAlumni($event_id, $alumni_id)
    {
        $registration = $this->first([
            'event_id' => $event_id,
            'registered_alumni_id' => $alumni_id
        ]);

        if (is_object($registration)) {
            $registration = (array)$registration;
        }

        if (empty($registration['registration_id'])) {
            return false;
        }

        return $this->delete($registration['registration_id']);
    }

    // Backward-compatible wrappers
    public function register($event_id, $student_id, $alumni_id = null)
    {
        if ($student_id) {
            return $this->registerStudent($event_id, $student_id);
        }
        if ($alumni_id) {
            return $this->registerAlumni($event_id, $alumni_id);
        }
        return false;
    }

    public function unregister($event_id, $student_id)
    {
        return $this->unregisterStudent($event_id, $student_id);
    }

    // Get registered events for a student
    public function getStudentRegisteredEvents($student_id)
    {
        $query = "SELECT e.*, er.registered_at, u.name as organizer_name
                  FROM {$this->table} er
                  LEFT JOIN events e ON er.event_id = e.event_id
                  LEFT JOIN users u ON e.alumni_id = u.user_id
                  WHERE er.student_id = :student_id
                  AND e.status = 'active'
                  AND TIMESTAMP(e.event_date, COALESCE(e.end_time, e.start_time)) >= NOW()
                  ORDER BY e.event_date ASC";
        $result = $this->query($query, ['student_id' => $student_id]);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getAlumniRegisteredEvents($alumni_id)
    {
        $query = "SELECT e.*, er.registered_at, u.name as organizer_name,
                  (SELECT COUNT(*) FROM event_registrations er2 WHERE er2.event_id = e.event_id) as registered_count
                  FROM {$this->table} er
                  LEFT JOIN events e ON er.event_id = e.event_id
                  LEFT JOIN users u ON e.host_alumnus_id = u.user_id
                  WHERE er.registered_alumni_id = :registered_alumni_id
                  AND e.status = 'active'
                  AND TIMESTAMP(e.event_date, COALESCE(e.end_time, e.start_time)) >= NOW()
                  ORDER BY e.event_date ASC";
        $result = $this->query($query, ['registered_alumni_id' => $alumni_id]);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getRegistrationCountByEvent($event_id)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE event_id = :event_id";
        $result = $this->query($query, ['event_id' => $event_id]);
        if (!is_array($result) || empty($result)) {
            return 0;
        }

        $row = $this->toArrayRow($result[0]);
        return (int)($row['total'] ?? 0);
    }
}


