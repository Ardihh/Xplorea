<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username',
        'email',
        'password_hash',
        'is_admin',
        'is_artist',
        'is_active',
        'artist_profile_approved',
        'fullname',
        'art_categories',
        'location',
        'phone',
        'artist_bio',
        'artist_website',
        'profile_url',
        'stripe_connect_id',
        'created_at',
        'updated_at'
    ];
}
