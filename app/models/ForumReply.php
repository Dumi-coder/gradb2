<?php

class ForumReply
{
    use Model;
    
    protected $table = 'form_replies';
    protected $order_column = 'repliedtime';
    protected $id_column = 'replyid';
    
    protected $allowedColumns = [
        'postid',
        'userid',
        'reply',
        'likes'
    ];

    // Get all replies for a specific post with user details
    public function getRepliesByPost($post_id)
    {
        $query = "SELECT fr.*, u.name as user_name, u.email as user_email
                  FROM {$this->table} fr
                  LEFT JOIN users u ON fr.userid = u.user_id
                  LEFT JOIN students s ON u.user_id = s.user_id
                  LEFT JOIN alumnis a ON u.user_id = a.user_id
                  WHERE fr.postid = :post_id
                  AND ((s.user_id IS NOT NULL AND (s.is_deleted IS NULL OR s.is_deleted = 0))
                    OR (a.user_id IS NOT NULL AND (a.is_deleted IS NULL OR a.is_deleted = 0))
                    OR (s.user_id IS NULL AND a.user_id IS NULL))
                  ORDER BY fr.repliedtime DESC";
        return $this->query($query, ['post_id' => $post_id]);
    }

    // Get single reply by ID
    public function getReply($reply_id)
    {
        $query = "SELECT fr.*, u.name as user_name 
                  FROM {$this->table} fr 
                  LEFT JOIN users u ON fr.userid = u.user_id 
                  WHERE fr.replyid = :reply_id";
        return $this->get_row($query, ['reply_id' => $reply_id]);
    }

    // Get reply for ownership check
    public function getReplyForOwnership($reply_id)
    {
        return $this->first(['replyid' => $reply_id]);
    }

    // Get reply count for a post
    public function getReplyCount($post_id)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE postid = :post_id";
        $result = $this->get_row($query, ['post_id' => $post_id]);
        return $result ? $result->count : 0;
    }

    // Increment like count
    public function incrementLikes($reply_id)
    {
        $query = "UPDATE {$this->table} SET likes = likes + 1 WHERE replyid = :reply_id";
        return $this->query($query, ['reply_id' => $reply_id]);
    }

    // Decrement like count
    public function decrementLikes($reply_id)
    {
        $query = "UPDATE {$this->table} SET likes = GREATEST(likes - 1, 0) WHERE replyid = :reply_id";
        return $this->query($query, ['reply_id' => $reply_id]);
    }

    // Delete all replies for a specific post
    public function deleteRepliesByPostId($post_id)
    {
        $query = "DELETE FROM {$this->table} WHERE postid = :post_id";
        return $this->query($query, ['post_id' => $post_id]);
    }

    // Get all replies by a specific user with post details
    public function getRepliesByUser($user_id)
    {
        $query = "SELECT fr.*, fp.title as forum_title, fp.post_id as forum_id
                  FROM {$this->table} fr
                  LEFT JOIN forum_posts fp ON fr.postid = fp.post_id
                  WHERE fr.userid = :user_id
                  ORDER BY fr.repliedtime DESC";
        return $this->query($query, ['user_id' => $user_id]);
    }
}
