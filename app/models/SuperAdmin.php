<?php

class SuperAdmin
{
    use Model;
    
    protected $table = 'super_admin';
    protected $order_column = 'super_admin_id';
    protected $id_column = 'super_admin_id';
    
    protected $allowedColumns = [
        'user_id',
        'admin_level',
        'picture_path',
        'linkedin_url',
        'github_url',
        'twitter_url',
        'personalweb_url',
        'bio'
    ];

    /**
     * Get super admin profile with user data joined
     */
    public function getSuperAdminProfile($user_id)
    {
        $query = "SELECT 
            sa.super_admin_id,
            sa.user_id,
            sa.admin_level,
            sa.picture_path,
            sa.linkedin_url,
            sa.github_url,
            sa.twitter_url,
            sa.personalweb_url,
            sa.bio,
            u.name,
            u.email,
            u.created_at
        FROM {$this->table} sa
        INNER JOIN users u ON sa.user_id = u.user_id
        WHERE sa.user_id = :user_id
        LIMIT 1";
        
        $result = $this->query($query, ['user_id' => $user_id]);
        if ($result && is_array($result) && count($result) > 0) {
            return $result[0];
        }
        return false;
    }
}
