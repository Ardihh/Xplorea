<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumTopicModel extends Model
{
    protected $table = 'forum_topics';
    protected $primaryKey = 'id';
    protected $allowedFields = ['category_id', 'user_id', 'title', 'content', 'view_count'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Get topic with user data
    public function getTopicWithUser($id)
    {
        return $this->select('forum_topics.*, users.username, users.email')
            ->join('users', 'users.id = forum_topics.user_id')
            ->where('forum_topics.id', $id)
            ->first();
    }

    // Increment view count
    public function incrementViewCount($id)
    {
        return $this->builder()
            ->where('id', $id)
            ->set('view_count', 'view_count+1', false)
            ->update();
    }

    // Get reply count for a topic
    public function getReplyCount($topicId)
    {
        $replyModel = new ForumReplyModel();
        return $replyModel->where('topic_id', $topicId)->countAllResults();
    }
    public function getThreadWithReplies($id)
    {
        return $this->select('forum_topics.*, users.username, users.email, forum_categories.name as category_name, COUNT(forum_replies.id) as reply_count')
            ->join('users', 'users.id = forum_topics.user_id')
            ->join('forum_categories', 'forum_categories.id = forum_topics.category_id')
            ->join('forum_replies', 'forum_replies.topic_id = forum_topics.id', 'left')
            ->where('forum_topics.id', $id)
            ->groupBy('forum_topics.id')
            ->first();
    }
    public function createThread()
    {
        // Validasi input
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'category' => 'required|numeric',
            'content' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan thread baru
        $this->forumTopicModel->save([
            'category_id' => $this->request->getPost('category'),
            'user_id' => session()->get('user_id'), // Pastikan ini sesuai dengan session Anda
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content')
        ]);

        return redirect()->to('xplorea/community')->with('success', 'Thread created successfully');
    }
}
