<?php

class ForumPost
{
    use Model;
    
    protected $table = 'forum_posts';
    protected $order_column = 'created_at';
    // REMOVED: protected $order_type = 'DESC';  <- This was conflicting!
    // The Model trait already has $order_type = 'desc', which is fine
    protected $id_column = 'post_id';
    
    protected $allowedColumns = [
        'user_id',
        'category',
        'title',
        'content',
        'priority',
        'tags',
        'is_pinned'
    ];

    // Get all posts with author names
    public function getAllPosts()
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  ORDER BY fp.created_at DESC";
        return $this->query($query);
    }

    // Get only current user's posts
    public function getPostsByUser($user_id)
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  WHERE fp.user_id = :user_id 
                  ORDER BY fp.created_at DESC";
        return $this->query($query, ['user_id' => $user_id]);
    }

    // Get single post (for editing)
    public function getPost($post_id)
    {
        $query = "SELECT fp.*, u.name as author_name 
                  FROM {$this->table} fp 
                  LEFT JOIN users u ON fp.user_id = u.user_id 
                  WHERE fp.post_id = :post_id";
        return $this->get_row($query, ['post_id' => $post_id]);
    }
}