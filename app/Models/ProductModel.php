<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'artist_id',
        'title',
        'description',
        'base_price',
        'image_url',
        'is_approved',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true; // otomatis isi created_at & updated_at
}
