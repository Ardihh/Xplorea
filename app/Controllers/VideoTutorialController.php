<?php

namespace App\Controllers;

use App\Models\VideoTutorialModel;
use App\Models\UserModel;

class VideoTutorialController extends BaseController
{
    protected $videoTutorialModel;
    protected $userModel;

    public function __construct()
    {
        $this->videoTutorialModel = new VideoTutorialModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $category = $this->request->getGet('category');
        $difficulty = $this->request->getGet('difficulty');

        $builder = $this->videoTutorialModel
            ->select('video_tutorials.*, users.username as artist_name')
            ->join('users', 'users.id = video_tutorials.artist_id')
            ->where('video_tutorials.is_approved', 1);

        if ($category) {
            $builder->where('video_tutorials.category', $category);
        }

        if ($difficulty) {
            $builder->where('video_tutorials.difficulty_level', $difficulty);
        }

        $tutorials = $builder->orderBy('video_tutorials.created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Video Tutorials',
            'tutorials' => $tutorials,
            'categories' => [
                'watercolor' => 'Watercolor',
                'oil_painting' => 'Oil Painting',
                'digital_art' => 'Digital Art',
                'sketching' => 'Sketching',
                'acrylic' => 'Acrylic',
                'other' => 'Other'
            ],
            'difficulty_levels' => [
                'beginner' => 'Beginner',
                'intermediate' => 'Intermediate',
                'advanced' => 'Advanced'
            ]
        ];

        return view('xplorea/video_tutorials/index', $data);
    }

    public function create()
    {
        // Check if user is approved artist
        if (!session()->get('is_artist') || !session()->get('artist_profile_approved')) {
            return redirect()->to('xplorea/login')->with('error', 'Only approved artists can create tutorials');
        }

        $data = [
            'title' => 'Create Video Tutorial',
            'categories' => [
                'watercolor' => 'Watercolor',
                'oil_painting' => 'Oil Painting',
                'digital_art' => 'Digital Art',
                'sketching' => 'Sketching',
                'acrylic' => 'Acrylic',
                'other' => 'Other'
            ],
            'difficulty_levels' => [
                'beginner' => 'Beginner',
                'intermediate' => 'Intermediate',
                'advanced' => 'Advanced'
            ]
        ];

        return view('xplorea/video_tutorials/create', $data);
    }

    public function store()
    {
        // Check if user is approved artist
        if (!session()->get('is_artist') || !session()->get('artist_profile_approved')) {
            return redirect()->to('xplorea/login')->with('error', 'Only approved artists can create tutorials');
        }

        // Validation rules
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'video_url' => 'required|valid_url',
            'category' => 'required|in_list[watercolor,oil_painting,digital_art,sketching,acrylic,other]',
            'difficulty_level' => 'required|in_list[beginner,intermediate,advanced]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'artist_id' => session()->get('user_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_url' => $this->request->getPost('video_url'),
            'thumbnail_url' => $this->request->getPost('thumbnail_url') ?: null,
            'duration' => $this->request->getPost('duration') ?: null,
            'category' => $this->request->getPost('category'),
            'difficulty_level' => $this->request->getPost('difficulty_level'),
            'is_approved' => 0, // Default to pending approval
            'view_count' => 0
        ];

        // Save tutorial
        if ($this->videoTutorialModel->save($data)) {
            return redirect()->to('artist/tutorials')->with('success', 'Video tutorial submitted successfully! It will be reviewed by admin.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create tutorial');
        }
    }

    public function show($id)
    {
        $tutorial = $this->videoTutorialModel
            ->select('video_tutorials.*, users.username as artist_name')
            ->join('users', 'users.id = video_tutorials.artist_id')
            ->where('video_tutorials.id', $id)
            ->where('video_tutorials.is_approved', 1)
            ->first();

        if (!$tutorial) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Increment view count
        $this->videoTutorialModel->incrementViewCount($id);

        $data = [
            'title' => $tutorial['title'],
            'tutorial' => $tutorial
        ];

        return view('xplorea/video_tutorials/show', $data);
    }

    public function artistTutorials()
    {
        // Check if user is artist
        if (!session()->get('is_artist')) {
            return redirect()->to('xplorea/login')->with('error', 'Access denied');
        }

        $tutorials = $this->videoTutorialModel
            ->where('artist_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'My Video Tutorials',
            'tutorials' => $tutorials
        ];

        return view('xplorea/video_tutorials/artist_tutorials', $data);
    }
} 