<?php

namespace App\Models;
use CodeIgniter\Model;

class OrderItemsModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'order_id',
        'product_id',
        'size_id',
        'frame_id',
        'quantity',
        'unit_price',
        'status'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'order_id'   => 'required|integer',
        'product_id' => 'required|integer',
        'size_id'    => 'required|integer',
        'frame_id'   => 'required|integer',
        'quantity'   => 'required|integer|min_length[1]',
        'unit_price' => 'required|decimal',
        'status'     => 'in_list[pending,accepted,rejected]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
}
