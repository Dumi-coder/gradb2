<?php

class FaqModel
{
    use Model;

    protected $table = 'faq';
    protected $order_column = 'created_at';
    protected $id_column = 'faq_id';

    protected $allowedColumns = [
        'question',
        'answer',
        'category',
        'status',
        'visibility_scope',
        'faculty_id',
        'priority',
        'tags',
        'views',
        'helpful_count',
        'created_by_user_id',
        'created_by_role',
    ];

    /**
     * Override insert to properly handle NULL faculty_id
     */
    public function insert($data)
    {
        // Remove unwanted data
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ")";
        
        // Get PDO connection
        $string = "mysql:host=" . DBHOST . ";port=" . DBPORT . ";dbname=" . DBNAME . ";charset=utf8";
        $con = new PDO($string, DBUSER, DBPASS);
        $stm = $con->prepare($query);
        
        // Bind parameters with proper types for NULL handling
        foreach ($data as $key => $value) {
            if ($key === 'faculty_id' && $value === null) {
                // Explicitly bind NULL for faculty_id
                $stm->bindValue(":$key", null, PDO::PARAM_NULL);
            } else {
                $stm->bindValue(":$key", $value);
            }
        }
        
        $result = $stm->execute();
        return $result !== false;
    }

    /**
     * Override update to properly handle NULL faculty_id
     */
    public function update($id, $data, $id_column = null)
    {
        if (!$id_column) {
            $id_column = $this->id_column;
        }

        // Remove unwanted data
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        $query = trim($query, ", ");
        $query .= " WHERE $id_column = :$id_column";

        // Get PDO connection
        $string = "mysql:host=" . DBHOST . ";port=" . DBPORT . ";dbname=" . DBNAME . ";charset=utf8";
        $con = new PDO($string, DBUSER, DBPASS);
        $stm = $con->prepare($query);

        // Bind parameters with proper types for NULL handling
        foreach ($data as $key => $value) {
            if ($key === 'faculty_id' && $value === null) {
                // Explicitly bind NULL for faculty_id
                $stm->bindValue(":$key", null, PDO::PARAM_NULL);
            } else {
                $stm->bindValue(":$key", $value);
            }
        }
        
        // Bind the ID
        $stm->bindValue(":$id_column", $id);

        $result = $stm->execute();
        return $result !== false;
    }

    /**
     * Get FAQs visible to a student/alumni by faculty.
     * Shows:
     *  - Global FAQs from super admin (faculty_id IS NULL)
     *  - Faculty-specific FAQs for the given faculty_id
     * Only FAQs with status = 'published' are returned.
     */
    public function getVisibleForFaculty($faculty_id)
    {
        try {
            $query = "SELECT * FROM $this->table
                      WHERE status = 'published'
                      AND (faculty_id IS NULL OR faculty_id = :faculty_id)
                      ORDER BY 
                        CASE priority
                            WHEN 'urgent' THEN 1
                            WHEN 'high' THEN 2
                            ELSE 3
                        END,
                        created_at DESC";

            $result = $this->query($query, ['faculty_id' => $faculty_id]);
            return $result ? $result : [];
        } catch (Exception $e) {
            error_log("FaqModel::getVisibleForFaculty Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get FAQs owned by a specific faculty (for faculty admin moderation).
     */
    public function getByFaculty($faculty_id)
    {
        try {
            $query = "SELECT * FROM $this->table
                      WHERE faculty_id = :faculty_id
                      ORDER BY created_at DESC";

            $result = $this->query($query, ['faculty_id' => $faculty_id]);
            return $result ? $result : [];
        } catch (Exception $e) {
            error_log("FaqModel::getByFaculty Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all FAQs (for super admin moderation).
     */
    public function getAllForSuperAdmin()
    {
        try {
            $query = "SELECT * FROM $this->table
                      ORDER BY created_at DESC";

            $result = $this->query($query);
            return $result ? $result : [];
        } catch (Exception $e) {
            error_log("FaqModel::getAllForSuperAdmin Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Publish an FAQ (set status to 'published')
     */
    public function publish($faq_id)
    {
        return $this->update($faq_id, ['status' => 'published'], 'faq_id');
    }

    /**
     * Unpublish an FAQ (set status to 'draft')
     */
    public function unpublish($faq_id)
    {
        return $this->update($faq_id, ['status' => 'draft'], 'faq_id');
    }

    /**
     * Get FAQ by ID
     */
    public function getById($faq_id)
    {
        return $this->first(['faq_id' => $faq_id]);
    }

    /**
     * Get statistics for moderation dashboard
     */
    public function getStats($faculty_id = null)
    {
        $where_clause = '';
        $params = [];
        
        if ($faculty_id !== null) {
            $where_clause = "WHERE faculty_id = :faculty_id";
            $params['faculty_id'] = $faculty_id;
        }

        $query = "SELECT 
                    COUNT(*) as total_faqs,
                    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_faqs,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_faqs,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_faqs,
                    SUM(views) as total_views
                  FROM $this->table
                  $where_clause";

        $result = $this->query($query, $params);
        return $result ? $result[0] : (object)[
            'total_faqs' => 0,
            'published_faqs' => 0,
            'pending_faqs' => 0,
            'draft_faqs' => 0,
            'total_views' => 0
        ];
    }

    /**
     * Get unique categories from FAQs
     */
    public function getCategories($faculty_id = null)
    {
        $where_clause = '';
        $params = [];
        
        if ($faculty_id !== null) {
            $where_clause = "WHERE faculty_id = :faculty_id";
            $params['faculty_id'] = $faculty_id;
        }

        $query = "SELECT DISTINCT category FROM $this->table $where_clause ORDER BY category";
        $result = $this->query($query, $params);
        
        $categories = [];
        if ($result && is_array($result)) {
            foreach ($result as $row) {
                if (!empty($row->category)) {
                    $categories[] = $row->category;
                }
            }
        }
        
        return $categories;
    }
}








