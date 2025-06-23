<?php namespace App\Models;

use CodeIgniter\Model;

class ForumReplyModel extends Model
{
    protected $table = 'forum_replies';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'topic_id', 
        'user_id', 
        'content', 
        'is_best_answer'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get replies with user data for a topic
    public function getRepliesWithUsers($topicId)
    {
        return $this->select('forum_replies.*, users.username, users.email')
                   ->join('users', 'users.id = forum_replies.user_id')
                   ->where('topic_id', $topicId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }
}