<?php

namespace App\Models;

use CodeIgniter\Model;

class VideoTutorialModel extends Model
{
    protected $table = 'video_tutorials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'artist_id',
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration',
        'category',
        'difficulty_level',
        'is_approved',
        'view_count',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'artist_id' => 'required|numeric',
        'title' => 'required|min_length[5]|max_length[255]',
        'description' => 'required|min_length[10]',
        'video_url' => 'required|valid_url',
        'category' => 'required|in_list[watercolor,oil_painting,digital_art,sketching,acrylic,other]',
        'difficulty_level' => 'required|in_list[beginner,intermediate,advanced]'
    ];

    protected $validationMessages = [
        'artist_id' => [
            'required' => 'Artist ID is required',
            'numeric' => 'Artist ID must be a number'
        ],
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 5 characters long',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 10 characters long'
        ],
        'video_url' => [
            'required' => 'Video URL is required',
            'valid_url' => 'Please provide a valid video URL'
        ],
        'category' => [
            'required' => 'Category is required',
            'in_list' => 'Please select a valid category'
        ],
        'difficulty_level' => [
            'required' => 'Difficulty level is required',
            'in_list' => 'Please select a valid difficulty level'
        ]
    ];

    // Get approved tutorials with artist info
    public function getApprovedTutorials($limit = null)
    {
        $builder = $this->select('video_tutorials.*, users.username as artist_name')
            ->join('users', 'users.id = video_tutorials.artist_id')
            ->where('video_tutorials.is_approved', 1)
            ->orderBy('video_tutorials.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    // Get tutorials by artist
    public function getTutorialsByArtist($artistId)
    {
        return $this->where('artist_id', $artistId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // Get tutorials by category
    public function getTutorialsByCategory($category, $limit = null)
    {
        $builder = $this->select('video_tutorials.*, users.username as artist_name')
            ->join('users', 'users.id = video_tutorials.artist_id')
            ->where('video_tutorials.is_approved', 1)
            ->where('video_tutorials.category', $category)
            ->orderBy('video_tutorials.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    // Increment view count
    public function incrementViewCount($tutorialId)
    {
        return $this->set('view_count', 'view_count + 1', false)
            ->where('id', $tutorialId)
            ->update();
    }
}