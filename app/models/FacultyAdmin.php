<?php

class FacultyAdmin
{
    use Model;
    
    protected $table = 'faculty_admins';
    protected $order_column = 'faculty_admin_id';
    protected $id_column = 'faculty_admin_id';
    
    protected $allowedColumns = [
        'user_id',
        'faculty_id',
        'admin_level',
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
}
