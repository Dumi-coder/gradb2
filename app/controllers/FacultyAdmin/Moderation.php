<?php

class Moderation extends Controller
{
    public function index()
    {
        // Check if user is logged in and is faculty admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('login');
            return;
        }

        // Get forum moderation data
        $forumModel = new ForumPost();
        $replyModel = new ForumReply();
        
        $faculty_id = $_SESSION['user_id'];
        $faculty_code = $_SESSION['faculty'] ?? null;
        
        // Get my published posts
        $my_posts = $forumModel->where(['author_id' => $faculty_id, 'author_role' => 'faculty_admin']);
        
        // Get all faculty posts
        $faculty_posts = [];
        if ($faculty_code) {
            $faculty_posts = $forumModel->where(['faculty' => $faculty_code]);
        }
        
        // Get my replies
        $my_replies = $replyModel->where(['user_id' => $faculty_id, 'user_role' => 'faculty_admin']);
        
        // Get reported posts (if you have a reports table)
        $reported_posts = []; // TODO: Implement reported posts functionality
        
        $data = [
            'title' => 'Forum Moderation - GradBridge',
            'page_title' => 'Forum Moderation',
            'page_subtitle' => 'Manage and moderate forum discussions',
            'my_posts' => $my_posts,
            'faculty_posts' => $faculty_posts,
            'my_replies' => $my_replies,
            'reported_posts' => $reported_posts
        ];

        $this->view('faculty_admin/forum-moderation', $data);
    }

    public function createPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $forumModel = new ForumPost();
        
        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'author_id' => $_SESSION['user_id'],
            'author_role' => 'faculty_admin',
            'faculty' => $_SESSION['faculty'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($forumModel->insert($data)) {
            echo json_encode(['success' => true, 'message' => 'Post created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create post']);
        }
    }

    public function getPost($post_id)
    {
        $forumModel = new ForumPost();
        $post = $forumModel->first(['post_id' => $post_id]);
        
        if ($post) {
            echo json_encode(['success' => true, 'post' => $post]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
        }
    }

    public function updatePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $forumModel = new ForumPost();
        
        $post_id = $_POST['post_id'] ?? 0;
        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
        ];

        if ($forumModel->update($post_id, $data, 'post_id')) {
            echo json_encode(['success' => true, 'message' => 'Post updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update post']);
        }
    }

    public function deletePost($post_id)
    {
        $forumModel = new ForumPost();
        
        if ($forumModel->delete($post_id, 'post_id')) {
            echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete post']);
        }
    }

    public function incrementView($post_id)
    {
        $forumModel = new ForumPost();
        $post = $forumModel->first(['post_id' => $post_id]);
        
        if ($post) {
            $views = ($post->views ?? 0) + 1;
            $forumModel->update($post_id, ['views' => $views], 'post_id');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function getPostDetail($post_id)
    {
        $forumModel = new ForumPost();
        $replyModel = new ForumReply();
        
        $post = $forumModel->first(['post_id' => $post_id]);
        $replies = $replyModel->where(['forum_id' => $post_id]);
        
        if ($post) {
            echo json_encode(['success' => true, 'post' => $post, 'replies' => $replies]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
        }
    }

    public function addReply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $replyModel = new ForumReply();
        
        $data = [
            'forum_id' => $_POST['post_id'] ?? 0,
            'reply' => $_POST['reply'] ?? '',
            'user_id' => $_SESSION['user_id'],
            'user_role' => 'faculty_admin',
            'repliedtime' => date('Y-m-d H:i:s')
        ];

        if ($replyModel->insert($data)) {
            // Update reply count in forum post
            $forumModel = new ForumPost();
            $post = $forumModel->first(['post_id' => $data['forum_id']]);
            if ($post) {
                $replies = ($post->replies ?? 0) + 1;
                $forumModel->update($data['forum_id'], ['replies' => $replies], 'post_id');
            }
            
            echo json_encode(['success' => true, 'message' => 'Reply posted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to post reply']);
        }
    }

    public function deleteReply($reply_id)
    {
        $replyModel = new ForumReply();
        
        if ($replyModel->delete($reply_id, 'id')) {
            echo json_encode(['success' => true, 'message' => 'Reply deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete reply']);
        }
    }

    public function likeReply($reply_id)
    {
        $replyModel = new ForumReply();
        $reply = $replyModel->first(['id' => $reply_id]);
        
        if ($reply) {
            $likes = ($reply->likes ?? 0) + 1;
            $replyModel->update($reply_id, ['likes' => $likes], 'id');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    // Moderation actions
    public function approvePost($post_id)
    {
        // TODO: Implement approve post logic
        echo json_encode(['success' => true, 'message' => 'Post approved']);
    }

    public function hidePost($post_id)
    {
        $forumModel = new ForumPost();
        
        if ($forumModel->update($post_id, ['status' => 'hidden'], 'post_id')) {
            echo json_encode(['success' => true, 'message' => 'Post hidden successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to hide post']);
        }
    }

    public function warnUser($user_id)
    {
        // TODO: Implement warn user logic
        echo json_encode(['success' => true, 'message' => 'Warning sent to user']);
    }
}

