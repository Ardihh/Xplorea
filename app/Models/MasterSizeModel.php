<?php

namespace App\Models;
use CodeIgniter\Model;

class MasterSizeModel extends Model
{
    protected $table = 'master_sizes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['size_name', 'size_description', 'price_adjustment'];
}

