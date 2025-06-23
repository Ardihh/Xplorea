<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductFrameModel extends Model
{
    protected $table = 'product_frames';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'master_frame_id'];
}
