<?php

class Announcement
{
    use Model;

    public const TYPE_FACULTY = 0;
    public const TYPE_UNIVERSITY = 1;
    public const TYPE_ADMIN = 2;

    protected $table = 'announcements';
    protected $order_column = 'created_at';
    protected $id_column = 'id';

    protected $allowedColumns = [
        'title',
        'content',
        'faculty_id',
        'created_by_user_id',
        'priority',
        'type',
        'view_count',
        'is_published',
        'is_deleted',
        'created_at',
        'updated_at'
    ];

    private function toArrayRow($row)
    {
        return is_object($row) ? (array)$row : $row;
    }

    public function getPublishedForFaculty($faculty_id)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE is_published = 1
                  AND is_deleted = 0
                  AND type = " . self::TYPE_FACULTY . "
                  AND faculty_id = :faculty_id
                  ORDER BY created_at DESC";

        $result = $this->query($query, ['faculty_id' => (int)$faculty_id]);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getPublishedUniversityAnnouncements()
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_published = 1
                  AND a.is_deleted = 0
                  AND a.type = " . self::TYPE_UNIVERSITY . "
                  ORDER BY a.created_at DESC";

        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getPublishedAdminAnnouncements()
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_published = 1
                  AND a.is_deleted = 0
                  AND a.type = " . self::TYPE_ADMIN . "
                  ORDER BY a.created_at DESC";

        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getFacultyAnnouncementsForAdmin($faculty_id)
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_deleted = 0
                  AND a.type = " . self::TYPE_FACULTY . "
                  AND a.faculty_id = :faculty_id
                  ORDER BY a.created_at DESC";

        $result = $this->query($query, ['faculty_id' => (int)$faculty_id]);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getAllFacultyAnnouncements()
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_deleted = 0
                  AND a.type = " . self::TYPE_FACULTY . "
                  ORDER BY a.created_at DESC";

        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getAllUniversityAnnouncements()
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_deleted = 0
                  AND a.type = " . self::TYPE_UNIVERSITY . "
                  ORDER BY a.created_at DESC";

        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getAllAnnouncements()
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_deleted = 0
                  ORDER BY a.created_at DESC";

        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getAllAdminAnnouncements()
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.is_deleted = 0
                  AND a.type = " . self::TYPE_ADMIN . "
                  ORDER BY a.created_at DESC";

        $result = $this->query($query);
        if (!is_array($result)) {
            return [];
        }

        return array_map([$this, 'toArrayRow'], $result);
    }

    public function getAnnouncementByIdForFaculty($announcement_id, $faculty_id)
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.id = :id
                  AND a.faculty_id = :faculty_id
                  AND a.type = " . self::TYPE_FACULTY . "
                  AND a.is_deleted = 0
                  LIMIT 1";

        $result = $this->query($query, [
            'id' => (int)$announcement_id,
            'faculty_id' => (int)$faculty_id
        ]);

        if (!is_array($result) || empty($result[0])) {
            return null;
        }

        return $this->toArrayRow($result[0]);
    }

    public function getAnnouncementById($announcement_id)
    {
        $query = "SELECT a.*, u.email AS creator_email,
                         CASE
                             WHEN u.role = 'super_admin' THEN 'SuperAdmin'
                             ELSE COALESCE(f.faculty_name, 'Unknown Faculty')
                         END AS creator_faculty_label
                  FROM {$this->table} a
                  LEFT JOIN users u ON u.user_id = a.created_by_user_id
                  LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
                  WHERE a.id = :id
                  AND a.is_deleted = 0
                  LIMIT 1";

        $result = $this->query($query, ['id' => (int)$announcement_id]);
        if (!is_array($result) || empty($result[0])) {
            return null;
        }

        return $this->toArrayRow($result[0]);
    }

    public function incrementViewCount($announcement_id)
    {
        $query = "UPDATE {$this->table}
                  SET view_count = view_count + 1,
                      updated_at = :updated_at
                  WHERE id = :id
                  AND is_deleted = 0";

        return $this->query($query, [
            'id' => (int)$announcement_id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function updatePublishState($announcement_id, $is_published, $faculty_id = null)
    {
        $params = [
            'id' => (int)$announcement_id,
            'is_published' => (int)$is_published,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $where = "id = :id AND is_deleted = 0";
        if ($faculty_id !== null) {
            $where .= " AND faculty_id = :faculty_id";
            $params['faculty_id'] = (int)$faculty_id;
        }

        $query = "UPDATE {$this->table}
                  SET is_published = :is_published,
                      updated_at = :updated_at
                  WHERE {$where}";

        return $this->query($query, $params);
    }

    public function updateAnnouncementText($announcement_id, $title, $content, $faculty_id = null)
    {
        $params = [
            'id' => (int)$announcement_id,
            'title' => $title,
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $where = "id = :id AND is_deleted = 0";
        if ($faculty_id !== null) {
            $where .= " AND faculty_id = :faculty_id";
            $params['faculty_id'] = (int)$faculty_id;
        }

        $query = "UPDATE {$this->table}
                  SET title = :title,
                      content = :content,
                      updated_at = :updated_at
                  WHERE {$where}";

        return $this->query($query, $params);
    }

    public function softDeleteAnnouncement($announcement_id, $faculty_id = null)
    {
        $params = [
            'id' => (int)$announcement_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $where = "id = :id AND is_deleted = 0";
        if ($faculty_id !== null) {
            $where .= " AND faculty_id = :faculty_id";
            $params['faculty_id'] = (int)$faculty_id;
        }

        $query = "UPDATE {$this->table}
                  SET is_deleted = 1,
                      updated_at = :updated_at
                  WHERE {$where}";

        return $this->query($query, $params);
    }

    public function createAnnouncement($data)
    {
        return $this->insert($data);
    }
}
