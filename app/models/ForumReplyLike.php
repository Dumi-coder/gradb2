<?php

class ForumReplyLike
{
    use Model;
    
    protected $table = 'form_reply_likes';
    protected $order_column = 'liked_time';
    protected $id_column = 'likeid';
    
    protected $allowedColumns = [
        'replyid',
        'userid'
    ];

    // Check if user has liked a reply
    public function hasUserLiked($reply_id, $user_id)
    {
        return $this->first(['replyid' => $reply_id, 'userid' => $user_id]);
    }

    // Add a like
    public function addLike($reply_id, $user_id)
    {
        return $this->insert(['replyid' => $reply_id, 'userid' => $user_id]);
    }

    // Remove a like
    public function removeLike($reply_id, $user_id)
    {
        $query = "DELETE FROM {$this->table} WHERE replyid = :reply_id AND userid = :user_id";
        return $this->query($query, ['reply_id' => $reply_id, 'user_id' => $user_id]);
    }

    // Get liked reply IDs for a user in a specific post
    public function getLikedRepliesByUser($post_id, $user_id)
    {
        $query = "SELECT frl.replyid 
                  FROM {$this->table} frl
                  INNER JOIN form_replies fr ON frl.replyid = fr.replyid
                  WHERE fr.postid = :post_id AND frl.userid = :user_id";
        $results = $this->query($query, ['post_id' => $post_id, 'user_id' => $user_id]);
        
        if (!$results) return [];
        
        return array_map(function($row) {
            return $row->replyid;
        }, $results);
    }
}
