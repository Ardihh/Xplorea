<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductSizeModel extends Model
{
    protected $table = 'product_sizes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'master_size_id']; // Perhatikan: ini harus 'master_size_id' bukan 'master_frame_id'
    
    // Tambahkan validasi sebelum insert
    public function insertWithValidation($data)
    {
        $masterSizeModel = new \App\Models\MasterSizeModel();
        
        // Validasi master_size_id
        if (!$masterSizeModel->find($data['master_size_id'])) {
            throw new \RuntimeException('Invalid master_size_id: Size not found');
        }
        
        return $this->insert($data);
    }
}
