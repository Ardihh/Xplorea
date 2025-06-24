<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArtistUpgradeRequestModel;
use App\Models\EventModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $artistRequestModel;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $this->userModel = new UserModel();
        $this->artistRequestModel = new ArtistUpgradeRequestModel();
        helper(['text', 'form']);

        $this->checkAdminAuth();
    }

    private function checkAdminAuth()
    {
        if (!session('logged_in')) {
            session()->set('redirect_url', current_url());
            redirect()->to('/login')->send();
            exit;
        }

        if (!session('is_admin')) {
            redirect()->to('/')->with('error', 'Access denied. Admin privileges required.')->send();
            exit;
        }
    }

    public function dashboard()
    {
        $productModel = new \App\Models\ProductModel();
        $eventModel = new \App\Models\EventModel();
        
        // Get chart data
        $userGrowthData = $this->getUserGrowthData();
        $artworksGrowthData = $this->getArtworksGrowthData();
        $rejectedArtworks = $productModel
            ->join('users', 'users.id = products.artist_id')
            ->where('users.is_artist', 1)
            ->where('products.is_approved', 2) 
            ->countAllResults();
        
        // Calculate additional statistics
        $total_users = $this->userModel->countAll();
        $total_artists = $this->userModel->where('is_artist', 1)->countAllResults();
        $approved_artists = $this->userModel
            ->where('is_artist', 1)
            ->where('artist_profile_approved', 1)
            ->countAllResults();
        $total_artworks = $productModel
            ->join('users', 'users.id = products.artist_id')
            ->where('users.is_artist', 1)
            ->countAllResults();
        $approved_artworks = $productModel
            ->join('users', 'users.id = products.artist_id')
            ->where('users.is_artist', 1)
            ->where('products.is_approved', 1)
            ->countAllResults();
        
        // Calculate conversion rate
        $conversion_rate = $total_users > 0 ? ($total_artists / $total_users) * 100 : 0;
        
        // Calculate average artworks per artist
        $avg_artworks = $approved_artists > 0 ? $total_artworks / $approved_artists : 0;
        
        // Calculate artwork approval rate
        $artwork_approval_rate = $total_artworks > 0 ? ($approved_artworks / $total_artworks) * 100 : 0;
        
        // Calculate new users this month
        $current_month = date('Y-m');
        $new_users_month = $this->userModel
            ->where('created_at >=', $current_month . '-01')
            ->where('created_at <=', date('Y-m-t') . ' 23:59:59')
            ->countAllResults();
        
        // Get total events count dynamically
        $total_events = $eventModel->where('is_active', 1)->countAllResults();
        
        $data = [
            'total_users' => $total_users,
            'total_artists' => $total_artists,
            'pending_approvals' => $this->userModel
                ->where('is_artist', 1)
                ->where('artist_profile_approved', 0)
                ->countAllResults(),
            'approved_artists' => $approved_artists,
            'total_artworks' => $total_artworks,
            'approved_artworks' => $approved_artworks,
            'pending_artworks' => $productModel
                ->join('users', 'users.id = products.artist_id')
                ->where('users.is_artist', 1)
                ->where('products.is_approved', 0)
                ->countAllResults(),
            'rejected_artworks' => $rejectedArtworks,
            'total_events' => $total_events, 
            'user_growth_data' => $userGrowthData,
            'artworks_growth_data' => $artworksGrowthData,
            'conversion_rate' => $conversion_rate,
            'avg_artworks' => $avg_artworks,
            'artwork_approval_rate' => $artwork_approval_rate,
            'new_users_month' => $new_users_month
        ];

        return view('admin/dashboard', $data);
    }

    
    private function getUserGrowthData()
    {
        $data = [];
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[] = date('M', strtotime("-$i months"));
            
            $startDate = $date . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $count = $this->userModel
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate . ' 23:59:59')
                ->countAllResults();
            
            $data[] = $count;
        }
        
        return implode(', ', $data);
    }

    private function getArtworksGrowthData()
    {
        $productModel = new \App\Models\ProductModel();
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            
            $startDate = $date . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $count = $productModel
                ->join('users', 'users.id = products.artist_id')
                ->where('users.is_artist', 1)
                ->where('products.created_at >=', $startDate)
                ->where('products.created_at <=', $endDate . ' 23:59:59')
                ->countAllResults();
            
            $data[] = $count;
        }
        
        return implode(', ', $data);
    }

    public function getChartData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $period = $this->request->getGet('period') ?? '6months';
        
        $data = [
            'userGrowth' => $this->getUserGrowthDataByPeriod($period),
            'artworksGrowth' => $this->getArtworksGrowthDataByPeriod($period),
            'artworks' => $this->getArtworksStats()
        ];

        return $this->response->setJSON($data);
    }

    private function getUserGrowthDataByPeriod($period)
    {
        $months = $this->getMonthsByPeriod($period);
        $data = [];
        
        foreach ($months as $month) {
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $count = $this->userModel
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate . ' 23:59:59')
                ->countAllResults();
            
            $data[] = $count;
        }
        
        return $data;
    }

    private function getArtworksGrowthDataByPeriod($period)
    {
        $productModel = new \App\Models\ProductModel();
        $months = $this->getMonthsByPeriod($period);
        $data = [];
        
        foreach ($months as $month) {
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $count = $productModel
                ->join('users', 'users.id = products.artist_id')
                ->where('users.is_artist', 1)
                ->where('products.created_at >=', $startDate)
                ->where('products.created_at <=', $endDate . ' 23:59:59')
                ->countAllResults();
            
            $data[] = $count;
        }
        
        return $data;
    }

    private function getMonthsByPeriod($period)
    {
        $months = [];
        
        switch ($period) {
            case '12months':
                $count = 12;
                break;
            case 'yearly':
                $count = 12;
                break;
            default:
                $count = 6;
                break;
        }
        
        for ($i = $count - 1; $i >= 0; $i--) {
            $months[] = date('Y-m', strtotime("-$i months"));
        }
        
        return $months;
    }

    private function getArtworksStats()
    {
        $productModel = new \App\Models\ProductModel();
        
        return [
            'approved' => $productModel
                ->join('users', 'users.id = products.artist_id')
                ->where('users.is_artist', 1)
                ->where('products.is_approved', 1)
                ->countAllResults(),
            'pending' => $productModel
                ->join('users', 'users.id = products.artist_id')
                ->where('users.is_artist', 1)
                ->where('products.is_approved', 0)
                ->countAllResults(),
            'rejected' => $productModel
                ->join('users', 'users.id = products.artist_id')
                ->where('users.is_artist', 1)
                ->where('products.is_approved', 2)
                ->countAllResults()
        ];
    }

    public function artistApprovals()
    {
        $pendingArtists = $this->userModel
            ->where('is_artist', 1)
            ->where('artist_profile_approved', 0)
            ->findAll();

        return view('admin/artist_approvals', [
            'artists' => $pendingArtists
        ]);
    }

    public function viewArtist($id)
    {
        $artist = $this->userModel->find($id);
        if (!$artist) {
            return redirect()->back()->with('error', 'Artist not found');
        }

        $request = $this->artistRequestModel
            ->where('user_id', $id)
            ->orderBy('requested_at', 'DESC')
            ->first();

        return view('admin/artist_detail', [
            'artist' => $artist,
            'request' => $request
        ]);
    }

    public function approveArtist($id)
    {
        // Add debugging
        // log_message('info', 'Attempting to approve artist ID: ' . $id);
        // log_message('info', 'Session data: ' . json_encode(session()->get()));
        // log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        // Validation rules
        $rules = [
            'admin_notes' => 'permit_empty|string|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $artist = $this->userModel->find($id);
        log_message('info', 'Artist data: ' . json_encode($artist));

        if (!$artist) {
            log_message('error', 'Artist not found with ID: ' . $id);
            return redirect()->back()->with('error', 'Artist not found.');
        }

        if (!$artist['is_artist']) {
            log_message('error', 'User is not an artist: ' . $id);
            return redirect()->back()->with('error', 'User is not an artist.');
        }

        if ($artist['artist_profile_approved']) {
            log_message('error', 'Artist already approved: ' . $id);
            return redirect()->back()->with('error', 'Artist already approved.');
        }

        try {
            log_message('info', 'Starting artist approval process');

            // Update user status
            $updateResult = $this->userModel->update($id, [
                'artist_profile_approved' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            log_message('info', 'User update result: ' . ($updateResult ? 'success' : 'failed'));

            if (!$updateResult) {
                log_message('error', 'Failed to update user: ' . json_encode($this->userModel->errors()));
                throw new \Exception('Failed to update user status');
            }

            // Update the request status if exists
            $request = $this->artistRequestModel
                ->where('user_id', $id)
                ->where('status', 'pending')
                ->first();

            log_message('info', 'Found request: ' . json_encode($request));

            if ($request) {
                $requestData = [
                    'status' => 'approved',
                    'admin_notes' => $this->request->getPost('admin_notes') ?? '',
                    'processed_at' => date('Y-m-d H:i:s')
                ];

                log_message('info', 'Updating request with data: ' . json_encode($requestData));

                $requestUpdateResult = $this->artistRequestModel->updateRequest($request['id'], $requestData);

                log_message('info', 'Request update result: ' . ($requestUpdateResult ? 'success' : 'failed'));

                if (!$requestUpdateResult) {
                    log_message('error', 'Failed to update request: ' . json_encode($this->artistRequestModel->errors()));
                    throw new \Exception('Failed to update request status');
                }
            }

            log_message('info', 'Artist approval completed successfully');

            return redirect()->to('/admin/artists/approvals')
                ->with('success', 'Artist approved successfully');
        } catch (\Exception $e) {
            log_message('error', 'Exception in approveArtist: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to approve artist: ' . $e->getMessage());
        }
    }

    public function rejectArtist($id)
    {
        // Add debugging
        // log_message('info', 'Attempting to reject artist ID: ' . $id);
        // log_message('info', 'POST data: ' . json_encode($this->request->getPost()));

        $rules = [
            'admin_notes' => 'required|string|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Validation failed for rejection: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if artist exists
        $artist = $this->userModel->find($id);
        log_message('info', 'Artist data for rejection: ' . json_encode($artist));

        if (!$artist || !$artist['is_artist']) {
            log_message('error', 'Artist not found or not an artist: ' . $id);
            return redirect()->back()->with('error', 'Artist not found.');
        }

        try {
            log_message('info', 'Starting rejection process');

            // Update user status - revert artist status
            $updateResult = $this->userModel->update($id, [
                'is_artist' => 0,
                'artist_profile_approved' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            log_message('info', 'User revert result: ' . ($updateResult ? 'success' : 'failed'));

            if (!$updateResult) {
                log_message('error', 'Failed to revert user: ' . json_encode($this->userModel->errors()));
                throw new \Exception('Failed to revert user status');
            }

            // Update the request status if exists
            $request = $this->artistRequestModel
                ->where('user_id', $id)
                ->where('status', 'pending')
                ->first();

            log_message('info', 'Found request for rejection: ' . json_encode($request));

            if ($request) {
                $requestData = [
                    'status' => 'rejected',
                    'admin_notes' => $this->request->getPost('admin_notes'),
                    'processed_at' => date('Y-m-d H:i:s')
                ];

                log_message('info', 'Updating request for rejection with data: ' . json_encode($requestData));

                $requestUpdateResult = $this->artistRequestModel->updateRequest($request['id'], $requestData);

                log_message('info', 'Request rejection result: ' . ($requestUpdateResult ? 'success' : 'failed'));

                if (!$requestUpdateResult) {
                    log_message('error', 'Failed to update request for rejection: ' . json_encode($this->artistRequestModel->errors()));
                    throw new \Exception('Failed to update request status');
                }
            }

            log_message('info', 'Artist rejection completed successfully');

            return redirect()->to('/admin/artists/approvals')
                ->with('success', 'Artist rejected successfully');
        } catch (\Exception $e) {
            log_message('error', 'Exception in rejectArtist: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to reject artist: ' . $e->getMessage());
        }
    }

    public function users()
    {
        $users = $this->userModel->findAll();
        
        foreach ($users as &$user) {
            if (!isset($user['is_active'])) {
                $user['is_active'] = 1; 
            }
        }
        
        return view('admin/users', [
            'users' => $users,
            'title' => 'Users Management'
        ]);
    }

    public function artists()
    {
        $artists = $this->userModel
            ->where('is_artist', 1)
            ->findAll();
        return view('admin/artists', ['artists' => $artists]);
    }

    public function artworks()
    {
        $status = $this->request->getGet('status');
        $productModel = new \App\Models\ProductModel();
        $orderItemsModel = new \App\Models\OrderItemsModel();

        $productModel->select('products.*, users.fullname AS artist_name, 
                              (SELECT COUNT(*) FROM order_items WHERE order_items.product_id = products.id) as order_count')
                     ->join('users', 'users.id = products.artist_id', 'left');

        if ($status === 'pending') {
            $productModel->where('is_approved', 0);
        } elseif ($status === 'approved') {
            $productModel->where('is_approved', 1);
        } elseif ($status === 'rejected') {
            $productModel->where('is_approved', -1);
        }

        $artworks = $productModel->findAll();

        return view('admin/artworks', [
            'artworks' => $artworks,
            'status' => $status
        ]);
    }

    public function editArtwork($id)
    {
        $productModel = new \App\Models\ProductModel();
        $artwork = $productModel->find($id);
        
        if (!$artwork) {
            return redirect()->to('admin/artworks')->with('error', 'Artwork not found');
        }
        
        return view('admin/edit_artwork', ['artwork' => $artwork]);
    }

    public function updateArtwork($id)
    {
        $productModel = new \App\Models\ProductModel();
        
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'base_price' => $this->request->getPost('base_price'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $productModel->update($id, $data);
        
        return redirect()->to('admin/artworks')->with('success', 'Artwork updated successfully');
    }

    public function deleteArtwork($id)
    {
        $productModel = new \App\Models\ProductModel();
        $productModel->delete($id);
        
        return redirect()->to('admin/artworks')->with('success', 'Artwork deleted successfully');
    }

    public function approveArtwork($id)
    {
        $productModel = new \App\Models\ProductModel();
        
        $artwork = $productModel->find($id);
        if (!$artwork) {
            return redirect()->to('admin/artworks')->with('error', 'Artwork not found');
        }
        
        $result = $productModel->update($id, [
            'is_approved' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return redirect()->to('admin/artworks')->with('success', 'Artwork approved successfully');
        } else {
            return redirect()->to('admin/artworks')->with('error', 'Failed to approve artwork');
        }
    }

    public function rejectArtwork($id)
    {
        $productModel = new \App\Models\ProductModel();
        
        $artwork = $productModel->find($id);
        if (!$artwork) {
            return redirect()->to('admin/artworks')->with('error', 'Artwork not found');
        }
        
        $result = $productModel->update($id, [
            'is_approved' => 2, 
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return redirect()->to('admin/artworks')->with('success', 'Artwork rejected successfully');
        } else {
            return redirect()->to('admin/artworks')->with('error', 'Failed to reject artwork');
        }
    }

    public function viewUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $html = view('admin/users/view_user_modal', ['user' => $user]);
        
        return $this->response->setJSON([
            'success' => true,
            'html' => $html
        ]);
    }

    public function addUser()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Hash password sebelum simpan
        $password = $this->request->getPost('password');
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => $password_hash,
            'fullname' => $this->request->getPost('fullname'),
            'phone' => $this->request->getPost('phone'),
            'bio' => $this->request->getPost('bio'),
            'is_artist' => $this->request->getPost('is_artist') ? 1 : 0,
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->save($userData)) {
            return redirect()->to(base_url('admin/users'))->with('success', 'User created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user');
    }

    public function editUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $html = view('admin/users/edit_user_modal', ['user' => $user]);
        
        return $this->response->setJSON([
            'success' => true,
            'html' => $html
        ]);
    }

    public function updateUser($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[20]|is_unique[users.username,id,$id]",
            'email' => "required|valid_email|is_unique[users.email,id,$id]"
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'fullname' => $this->request->getPost('fullname'),
            'phone' => $this->request->getPost('phone'),
            'bio' => $this->request->getPost('bio'),
            'is_artist' => $this->request->getPost('is_artist') ? 1 : 0,
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle password change if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 8) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password must be at least 8 characters'
                ]);
            }
            $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $userData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update user'
        ]);
    }

    // Soft delete user (set is_active = 0 instead of actually deleting)
    public function deleteUser($id)
    {
        log_message('info', '=== SOFT DELETE USER START ===');
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Prevent admin from deleting themselves
        if ($user['id'] == session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ]);
        }

        // Soft delete by setting is_active = 0
        $result = $this->userModel->update($id, [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            log_message('info', '=== SOFT DELETE USER SUCCESS ===');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User deactivated successfully (soft delete)',
                'debug' => [
                    'user_id' => $id,
                    'username' => $user['username']
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to deactivate user'
            ]);
        }
    }

    public function toggleUserStatus()
    {
        // Debug: log semua input
        log_message('info', '=== TOGGLE USER STATUS START ===');
        log_message('info', 'Request method: ' . $this->request->getMethod());
        log_message('info', 'Is AJAX: ' . ($this->request->isAJAX() ? 'YES' : 'NO'));
        
        $userId = $this->request->getPost('user_id');
        $status = $this->request->getPost('status');
        
        log_message('info', 'User ID: ' . $userId);
        log_message('info', 'Status: ' . ($status ? 'true' : 'false'));
        
        // Validasi input
        if (!$userId || !is_numeric($userId)) {
            log_message('error', 'Invalid user ID: ' . $userId);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid user ID'
            ]);
        }

        // Cek apakah user exists
        $user = $this->userModel->find($userId);
        if (!$user) {
            log_message('error', 'User not found with ID: ' . $userId);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        log_message('info', 'Found user: ' . $user['username']);
        log_message('info', 'Current is_active: ' . ($user['is_active'] ?? 'null'));

        // Update field is_active di database
        $data = [
            'is_active' => $status ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        log_message('info', 'Data to update: ' . json_encode($data));
        
        // Coba update dengan debugging
        try {
            $result = $this->userModel->update($userId, $data);
            log_message('info', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            if (!$result) {
                log_message('error', 'Model errors: ' . json_encode($this->userModel->errors()));
            }
            
            // Verify the update
            $updatedUser = $this->userModel->find($userId);
            log_message('info', 'After update - is_active: ' . ($updatedUser['is_active'] ?? 'null'));
            
            if ($result) {
                log_message('info', '=== TOGGLE USER STATUS SUCCESS ===');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User status updated successfully',
                    'new_status' => $status ? 'active' : 'inactive',
                    'debug' => [
                        'user_id' => $userId,
                        'username' => $user['username'],
                        'old_status' => $user['is_active'] ?? 'null',
                        'new_status' => $updatedUser['is_active'] ?? 'null',
                        'update_data' => $data
                    ]
                ]);
            } else {
                log_message('error', '=== TOGGLE USER STATUS FAILED ===');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user status',
                    'debug' => [
                        'user_id' => $userId,
                        'model_errors' => $this->userModel->errors(),
                        'update_data' => $data
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Exception in toggleUserStatus: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function revokeArtist($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/artists')->with('error', 'Artist not found');
        }
        // Set status approval ke 0 (pending)
        $this->userModel->update($id, ['artist_profile_approved' => 0]);
        return redirect()->to('admin/artists')->with('success', 'Artist approval revoked.');
    }

    
    public function profile()
    {
        $adminId = session()->get('user_id');
        $adminData = $this->userModel->find($adminId);

        if (!$adminData) {
            return redirect()->to('admin/dashboard')->with('error', 'Admin profile not found');
        }

        $data = [
            'title' => 'Admin Profile',
            'admin' => $adminData
        ];

        return view('admin/profile', $data);
    }

    public function updateProfile()
    {
        $adminId = session()->get('user_id');
        
        // Validation rules
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,' . $adminId . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $adminId . ']',
            'fullname' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|min_length[10]|max_length[15]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare update data
        $updateData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'fullname' => $this->request->getPost('fullname'),
            'phone' => $this->request->getPost('phone'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle password update if provided
        $newPassword = $this->request->getPost('new_password');
        if (!empty($newPassword)) {
            $currentPassword = $this->request->getPost('current_password');
            $adminData = $this->userModel->find($adminId);
            
            if (!password_verify($currentPassword, $adminData['password_hash'])) {
                return redirect()->back()->withInput()->with('error', 'Current password is incorrect');
            }
            
            if (strlen($newPassword) < 6) {
                return redirect()->back()->withInput()->with('error', 'New password must be at least 6 characters long');
            }
            
            $updateData['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Update admin profile
        if ($this->userModel->update($adminId, $updateData)) {
            // Update session data
            session()->set([
                'username' => $updateData['username'],
                'email' => $updateData['email'],
                'fullname' => $updateData['fullname']
            ]);
            
            return redirect()->to('admin/profile')->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update profile');
        }
    }
}
