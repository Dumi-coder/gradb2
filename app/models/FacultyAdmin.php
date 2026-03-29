<?php

class FacultyAdmin
{
    use Model;
    
    protected $table = 'faculty_admins';
    protected $order_column = 'faculty_admin_id';
    protected $id_column = 'faculty_admin_id';
    
    protected $allowedColumns = [
        'faculty_admin_id',
        'user_id',
        'faculty_id',
        'admin_level',
        'is_deactivated',
        'picture_path',
        'linkedin_url',
        'github_url',
        'twitter_url',
        'personalweb_url',
        'bio'
    ];

    /**
     * Get faculty admin profile with user data joined
     */
    public function getFacultyAdminProfile($user_id)
    {
        $query = "SELECT 
            fa.faculty_admin_id,
            fa.user_id,
            fa.faculty_id,
            fa.admin_level,
            fa.picture_path,
            fa.linkedin_url,
            fa.github_url,
            fa.twitter_url,
            fa.personalweb_url,
            fa.bio,
            u.name,
            u.email,
            u.created_at,
            f.faculty_name
        FROM {$this->table} fa
        INNER JOIN users u ON fa.user_id = u.user_id
        LEFT JOIN faculties f ON fa.faculty_id = f.faculty_id
        WHERE fa.user_id = :user_id
        LIMIT 1";
        
        $result = $this->query($query, ['user_id' => $user_id]);
        if ($result && is_array($result) && count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function getFacultiesWithCurrentAdmin()
    {
        $query = "SELECT
            f.faculty_id,
            f.faculty_name,
            fa.faculty_admin_id,
            fa.user_id AS admin_user_id,
            fa.admin_level,
            fa.is_deactivated,
            u.name AS admin_name,
            u.email AS admin_email
        FROM faculties f
        LEFT JOIN faculty_admins fa ON fa.faculty_admin_id = (
            SELECT fa2.faculty_admin_id
            FROM faculty_admins fa2
            WHERE fa2.faculty_id = f.faculty_id
              AND fa2.is_deactivated = 0
            ORDER BY fa2.faculty_admin_id DESC
            LIMIT 1
        )
        LEFT JOIN users u ON u.user_id = fa.user_id
        ORDER BY f.faculty_name ASC";

        $result = $this->query($query);
        return is_array($result) ? $result : [];
    }

    public function getFacultyWithCurrentAdmin($faculty_id)
    {
        $query = "SELECT
            f.faculty_id,
            f.faculty_name,
            fa.faculty_admin_id,
            fa.user_id AS admin_user_id,
            fa.admin_level,
            fa.is_deactivated,
            fa.bio,
            u.name AS admin_name,
            u.email AS admin_email
        FROM faculties f
        LEFT JOIN faculty_admins fa ON fa.faculty_admin_id = (
            SELECT fa2.faculty_admin_id
            FROM faculty_admins fa2
            WHERE fa2.faculty_id = f.faculty_id
              AND fa2.is_deactivated = 0
            ORDER BY fa2.faculty_admin_id DESC
            LIMIT 1
        )
        LEFT JOIN users u ON u.user_id = fa.user_id
        WHERE f.faculty_id = :faculty_id
        LIMIT 1";

        $result = $this->query($query, ['faculty_id' => $faculty_id]);
        if ($result && is_array($result) && count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function getActiveAdminByUserId($user_id)
    {
        $query = "SELECT *
        FROM faculty_admins
        WHERE user_id = :user_id
          AND is_deactivated = 0
        ORDER BY faculty_admin_id DESC
        LIMIT 1";

        $result = $this->query($query, ['user_id' => $user_id]);
        if ($result && is_array($result) && count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function deactivateAdmin($faculty_admin_id)
    {
        return $this->update($faculty_admin_id, ['is_deactivated' => 1], 'faculty_admin_id');
    }

    public function deactivateActiveByFaculty($faculty_id)
    {
        $query = "UPDATE faculty_admins
                  SET is_deactivated = 1
                  WHERE faculty_id = :faculty_id
                    AND is_deactivated = 0";

        return $this->query($query, ['faculty_id' => $faculty_id]);
    }

    public function generateNextFacultyAdminId()
    {
        $query = "SELECT faculty_admin_id
                  FROM faculty_admins
                  WHERE faculty_admin_id REGEXP '^FAC[0-9]+$'
                  ORDER BY CAST(SUBSTRING(faculty_admin_id, 4) AS UNSIGNED) DESC
                  LIMIT 1";

        $result = $this->query($query);
        if ($result && is_array($result) && count($result) > 0) {
            $current = (string)$result[0]->faculty_admin_id;
            $number = (int)substr($current, 3);
            return 'FAC' . str_pad((string)($number + 1), 3, '0', STR_PAD_LEFT);
        }

        return 'FAC001';
    }
}
