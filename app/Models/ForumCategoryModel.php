<?php namespace App\Models;

use CodeIgniter\Model;

class ForumCategoryModel extends Model
{
    protected $table = 'forum_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 
        'description', 
        'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get all active categories
    public function getActiveCategories()
    {
        return $this->where('is_active', true)->findAll();
    }
}