<?php

namespace App\Models;
use CodeIgniter\Model;

class OrdersModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'user_id',
        'session_id',
        'total_amount',
        'status',
        'created_at'
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'user_id'      => 'permit_empty|integer',
        'session_id'   => 'required|string|max_length[255]',
        'total_amount' => 'required|decimal',
        'status'       => 'in_list[pending,paid,shipped,delivered,cancelled]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
}