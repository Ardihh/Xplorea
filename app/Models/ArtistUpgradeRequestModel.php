<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtistUpgradeRequestModel extends Model
{
    protected $table = 'artist_upgrade_requests';
    protected $primaryKey = 'id';
    
    // Hanya kolom yang benar-benar ada di database
    protected $allowedFields = [
        'user_id',
        'request_data',
        'status',
        'admin_notes',
        'requested_at',
        'processed_at'
    ];
    
    protected $useTimestamps = false;
    
    // Validation rules - disesuaikan dengan kolom yang ada
    protected $validationRules = [
        'user_id' => 'required|integer',
        'request_data' => 'permit_empty|string',
        'status' => 'required|in_list[pending,approved,rejected]',
        'admin_notes' => 'permit_empty|string|max_length[500]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be pending, approved, or rejected'
        ],
        'admin_notes' => [
            'max_length' => 'Admin notes cannot exceed 500 characters'
        ]
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get all pending requests
     */
    public function getPendingRequests()
    {
        return $this->where('status', self::STATUS_PENDING)
                   ->orderBy('requested_at', 'ASC')
                   ->findAll();
    }

    /**
     * Get request by user ID
     */
    public function getRequestByUser($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('requested_at', 'DESC')
                   ->first();
    }

    /**
     * Get all requests by user ID
     */
    public function getRequestsByUser($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('requested_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get requests with user information
     */
    public function getRequestsWithUsers($status = null)
    {
        $builder = $this->db->table($this->table)
                           ->select('artist_upgrade_requests.*, users.username, users.email, users.fullname')
                           ->join('users', 'users.id = artist_upgrade_requests.user_id', 'left');
        
        if ($status) {
            $builder->where('artist_upgrade_requests.status', $status);
        }
        
        return $builder->orderBy('artist_upgrade_requests.requested_at', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get request statistics
     */
    public function getRequestStats()
    {
        return [
            'total' => $this->countAll(),
            'pending' => $this->where('status', self::STATUS_PENDING)->countAllResults(),
            'approved' => $this->where('status', self::STATUS_APPROVED)->countAllResults(),
            'rejected' => $this->where('status', self::STATUS_REJECTED)->countAllResults()
        ];
    }

    /**
     * Check if user has pending request
     */
    public function hasPendingRequest($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('status', self::STATUS_PENDING)
                   ->countAllResults() > 0;
    }

    /**
     * Create new request
     */
    public function createRequest($userId, $requestData)
    {
        $data = [
            'user_id' => $userId,
            'request_data' => is_array($requestData) ? json_encode($requestData) : $requestData,
            'status' => self::STATUS_PENDING,
            'requested_at' => date('Y-m-d H:i:s')
        ];

        return $this->save($data);
    }

    /**
     * Process request (approve or reject) - tanpa processed_by
     */
    public function processRequest($requestId, $status, $adminNotes = null)
    {
        $data = [
            'status' => $status,
            'admin_notes' => $adminNotes,
            'processed_at' => date('Y-m-d H:i:s')
        ];

        return $this->update($requestId, $data);
    }

    /**
     * Approve request
     */
    public function approveRequest($requestId, $adminNotes = null)
    {
        return $this->processRequest($requestId, self::STATUS_APPROVED, $adminNotes);
    }

    /**
     * Reject request
     */
    public function rejectRequest($requestId, $adminNotes)
    {
        return $this->processRequest($requestId, self::STATUS_REJECTED, $adminNotes);
    }

    /**
     * Get latest request for user
     */
    public function getLatestRequestForUser($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('id', 'DESC')
                   ->first();
    }

    /**
     * Custom update method with debugging
     */
    public function updateRequest($id, $data)
    {
        // Log untuk debugging
        log_message('info', 'Updating request ID: ' . $id . ' with data: ' . json_encode($data));
        
        $result = $this->update($id, $data);
        
        if (!$result) {
            log_message('error', 'Failed to update request. Errors: ' . json_encode($this->errors()));
        } else {
            log_message('info', 'Successfully updated request ID: ' . $id);
        }
        
        return $result;
    }

    /**
     * Before update callback untuk debugging
     */
    protected function beforeUpdate(array $data)
    {
        log_message('info', 'Before update - Data: ' . json_encode($data));
        return $data;
    }

    /**
     * After update callback untuk debugging
    */
    protected function afterUpdate(array $data)
    {
        log_message('info', 'After update - Data: ' . json_encode($data));
        return $data;
    }
}