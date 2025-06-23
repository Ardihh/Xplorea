<?php

namespace App\Controllers;

use App\Models\ForumTopicModel;
use App\Models\ForumCategoryModel;
use App\Models\ForumReplyModel;
use App\Models\UserModel;

class CommunityController extends BaseController
{
    protected $forumTopicModel;
    protected $forumCategoryModel;
    protected $forumReplyModel;
    protected $userModel;

    public function __construct()
    {
        $this->forumTopicModel = new ForumTopicModel();
        $this->forumCategoryModel = new ForumCategoryModel();
        $this->forumReplyModel = new ForumReplyModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Ambil data langsung tanpa cache untuk debugging
        $threads = $this->forumTopicModel
            ->select('forum_topics.*, users.username, COUNT(forum_replies.id) as reply_count')
            ->join('users', 'users.id = forum_topics.user_id', 'left')
            ->join('forum_replies', 'forum_replies.topic_id = forum_topics.id', 'left')
            ->groupBy('forum_topics.id')
            ->orderBy('forum_topics.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Community Forum',
            'threads' => $threads
        ];

        return view('xplorea/community', $data);
    }

    private function getReplyCounts($threads)
    {
        $replyCounts = [];
        $forumReplyModel = new \App\Models\ForumReplyModel();

        foreach ($threads as $thread) {
            $replyCounts[$thread['id']] = $forumReplyModel->where('topic_id', $thread['id'])->countAllResults();
        }

        return $replyCounts;
    }

    public function newThread()
    {
        // Pastikan hanya user yang login bisa membuat thread
        if (!session()->get('user_id')) {
            return redirect()->to('xplorea/login')->with('error', 'Please login to create a new thread');
        }

        $categories = $this->forumCategoryModel->where('is_active', true)->findAll();

        $data = [
            'title' => 'Create New Thread',
            'categories' => $categories,
            'validation' => \Config\Services::validation()
        ];

        return view('xplorea/community/new_thread', $data);
    }

    // Method untuk memproses pembuatan thread baru
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

        // Data yang akan disimpan
        $data = [
            'category_id' => $this->request->getPost('category'),
            'user_id' => session()->get('user_id'), // Pastikan ini sesuai session
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'created_at' => date('Y-m-d H:i:s'), // Timestamp manual
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Simpan dan debug
        if ($this->forumTopicModel->save($data)) {
            $insertedId = $this->forumTopicModel->getInsertID();
            $newThread = $this->forumTopicModel->find($insertedId);
            
            // Clear cache untuk memastikan thread baru muncul
            cache()->delete('all_threads_with_counts');
            
            return redirect()->to('xplorea/community')->with('success', 'Thread created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create thread');
        }
    }

    public function search()
    {
        $searchTerm = $this->request->getVar('q');

        if (!empty($searchTerm)) {
            $results = $this->forumTopicModel
                ->select('forum_topics.*, users.username, COUNT(forum_replies.id) as reply_count')
                ->join('users', 'users.id = forum_topics.user_id', 'left')
                ->join('forum_replies', 'forum_replies.topic_id = forum_topics.id', 'left')
                ->groupBy('forum_topics.id')
                ->like('forum_topics.title', $searchTerm)
                ->orLike('forum_topics.content', $searchTerm)
                ->orderBy('forum_topics.created_at', 'DESC')
                ->findAll();
        } else {
            // Jika search kosong, kembalikan semua thread
            $results = $this->forumTopicModel
                ->select('forum_topics.*, users.username, COUNT(forum_replies.id) as reply_count')
                ->join('users', 'users.id = forum_topics.user_id', 'left')
                ->join('forum_replies', 'forum_replies.topic_id = forum_topics.id', 'left')
                ->groupBy('forum_topics.id')
                ->orderBy('forum_topics.created_at', 'DESC')
                ->findAll();
        }

        return $this->response->setJSON($results);
    }
    public function viewThread($threadId)
    {
        // Ambil thread dengan JOIN ke users dan categories
        $thread = $this->forumTopicModel
            ->select('forum_topics.*, users.username, forum_categories.name as category_name')
            ->join('users', 'users.id = forum_topics.user_id', 'left')
            ->join('forum_categories', 'forum_categories.id = forum_topics.category_id', 'left')
            ->find($threadId);

        if (!$thread) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Ambil replies dengan username
        $replies = $this->forumReplyModel
            ->select('forum_replies.*, users.username')
            ->join('users', 'users.id = forum_replies.user_id', 'left')
            ->where('topic_id', $threadId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        // Update view count
        $this->forumTopicModel->set('view_count', 'view_count + 1', false)
            ->where('id', $threadId)
            ->update();

        $data = [
            'title' => $thread['title'],
            'thread' => $thread,
            'replies' => $replies
        ];

        return view('xplorea/community/thread_view', $data);
    }
    public function addReply($threadId)
    {
        // Debug session user
        if (!session()->get('user_id')) {
            $debugMsg = "User not logged in. Session data: " . print_r(session()->get(), true);
            log_message('error', $debugMsg);
            return redirect()->to('xplorea/login')->with('error', 'Please login to post a reply');
        }

        // Validasi input
        $rules = [
            'content' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            log_message('error', 'Validation failed: ' . print_r($validationErrors, true));
            return redirect()->back()->withInput()->with('errors', $validationErrors);
        }

        // Data yang akan disimpan
        $replyData = [
            'topic_id' => $threadId,
            'user_id' => session()->get('user_id'),
            'content' => $this->request->getPost('content')
        ];

        // Debug data sebelum disimpan
        log_message('info', 'Attempting to save reply: ' . print_r($replyData, true));

        try {
            // Simpan balasan
            if ($this->forumReplyModel->save($replyData)) {
                $insertId = $this->forumReplyModel->getInsertID();
                log_message('info', "Reply saved successfully. ID: {$insertId}");
                
                // Clear cache untuk list threads
                cache()->delete('all_threads_with_counts');
                
                return redirect()->back()->with('success', 'Reply posted successfully');
            } else {
                $errors = $this->forumReplyModel->errors();
                log_message('error', 'Failed to save reply: ' . print_r($errors, true));
                return redirect()->back()->withInput()->with('error', 'Failed to post reply');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception when saving reply: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while posting reply');
        }
    }
}
