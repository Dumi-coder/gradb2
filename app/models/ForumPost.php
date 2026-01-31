<?php

class ForumPost
{
    use Model;
    
    protected $table = 'forum_posts';
    protected $order_column = 'created_at';
    protected $id_column = 'post_id';
    
    protected $allowedColumns = [
        'user_id',
        'title',
        'content',
        'tags',
        'visiblefaculties',
        'views',
        'replies'
    ];

    // Get all posts with author names
    public function getAllPosts()
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  LEFT JOIN students s ON u.user_id = s.user_id
                  LEFT JOIN alumnis a ON u.user_id = a.user_id
                  WHERE (s.is_deleted IS NULL OR s.is_deleted = 0)
                  AND (a.is_deleted IS NULL OR a.is_deleted = 0)
                  ORDER BY fp.created_at DESC";
        return $this->query($query);
    }

    // Get only current user's posts
    public function getPostsByUser($user_id)
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  LEFT JOIN students s ON u.user_id = s.user_id
                  LEFT JOIN alumnis a ON u.user_id = a.user_id
                  WHERE fp.user_id = :user_id 
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)
                  AND (a.is_deleted IS NULL OR a.is_deleted = 0)
                  ORDER BY fp.created_at DESC";
        return $this->query($query, ['user_id' => $user_id]);
    }

    // Get single post (for editing)
    public function getPost($post_id)
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  LEFT JOIN students s ON u.user_id = s.user_id
                  LEFT JOIN alumnis a ON u.user_id = a.user_id
                  WHERE fp.post_id = :post_id
                  AND ((s.user_id IS NOT NULL AND (s.is_deleted IS NULL OR s.is_deleted = 0))
                    OR (a.user_id IS NOT NULL AND (a.is_deleted IS NULL OR a.is_deleted = 0))
                    OR (s.user_id IS NULL AND a.user_id IS NULL))";
        return $this->get_row($query, ['post_id' => $post_id]);
    }

    // Get post for ownership check (simpler, no deleted user filters)
    public function getPostForOwnership($post_id)
    {
        return $this->first(['post_id' => $post_id]);
    }

    // Increment view count
    public function incrementViews($post_id)
    {
        $query = "UPDATE {$this->table} SET views = views + 1 WHERE post_id = :post_id";
        return $this->query($query, ['post_id' => $post_id]);
    }

    // Get posts by faculty
    public function getPostsByFaculty($faculty_id)
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  LEFT JOIN students s ON u.user_id = s.user_id
                  LEFT JOIN alumnis a ON u.user_id = a.user_id
                  WHERE (FIND_IN_SET(:faculty_id, fp.visiblefaculties) > 0 
                         OR fp.visiblefaculties = '999' 
                         OR fp.visiblefaculties IS NULL 
                         OR fp.visiblefaculties = '')
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)
                  AND (a.is_deleted IS NULL OR a.is_deleted = 0)
                  ORDER BY fp.created_at DESC";
        return $this->query($query, ['faculty_id' => $faculty_id]);
    }
}