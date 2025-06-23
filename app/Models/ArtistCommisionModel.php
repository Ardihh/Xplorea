<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtistCommissionModel extends Model
{
    protected $table = 'artist_commissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'artist_id',
        'order_item_id',
        'amount',
        'percentage',
        'paid_status',
        'paid_at'
    ];
    protected $useTimestamps = false;

    // Method untuk membuat komisi baru
    public function createCommission($artistId, $orderItemId, $amount, $percentage)
    {
        return $this->insert([
            'artist_id' => $artistId,
            'order_item_id' => $orderItemId,
            'amount' => $amount,
            'percentage' => $percentage,
            'paid_status' => false
        ]);
    }

    // Method untuk mendapatkan komisi artist
    public function getArtistCommissions($artistId)
    {
        return $this->where('artist_id', $artistId)
                   ->findAll();
    }

    // Method untuk menandai komisi sebagai dibayar
    public function markAsPaid($commissionId)
    {
        return $this->update($commissionId, [
            'paid_status' => true,
            'paid_at' => date('Y-m-d H:i:s')
        ]);
    }
}