<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterFrameModel extends Model
{
    protected $table = 'master_frames';
    protected $primaryKey = 'id';
    protected $allowedFields = ['frame_name'];
}
