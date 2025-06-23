<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\EventModel;
use App\Models\EventAttendeeModel;

class Xplorea extends BaseController
{
    protected $userModel;
    protected $productModel;

    public function __construct()
    {
        helper('CartHelper'); // load cart helper sekali di construct
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
    }

    // fungsi pembantu untuk inject data cart ke semua view
    protected function renderWithCurrent($view, $currentSegment, $data = [])
    {
        $cart = getCartData();
        $data['current'] = $currentSegment;
        $data['cartItems'] = $cart['items'];
        $data['subtotal'] = $cart['subtotal'];

        return view($view, $data);
    }

    public function index(): string
    {
        $eventModel = new \App\Models\EventModel();
        
        // Ambil event yang masih aktif untuk hero section
        $recentEvents = $eventModel
            ->where('is_active', true)  // Hanya event yang aktif
            ->where('start_datetime >=', date('Y-m-d H:i:s'))  // Event yang belum dimulai atau sedang berlangsung
            ->orderBy('start_datetime', 'ASC')  // Urutkan berdasarkan tanggal mulai terdekat
            ->limit(5)  // Batasi maksimal 5 event
            ->findAll();

        // Fetch latest artworks yang sudah disetujui
        $latestArtworks = $this->productModel
            ->where('is_approved', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->findAll();

        // Fetch upcoming events within next 7 days for the banner
        $upcomingEvents = $eventModel
            ->where('is_active', true)
            ->where('start_datetime >=', date('Y-m-d H:i:s'))
            ->where('start_datetime <=', date('Y-m-d H:i:s', strtotime('+7 days')))
            ->orderBy('start_datetime', 'ASC')
            ->limit(3)
            ->findAll();

        // Fetch all upcoming events for calendar (next 3 months)
        $calendarEvents = $eventModel
            ->where('is_active', true)
            ->where('start_datetime >=', date('Y-m-d H:i:s'))
            ->where('start_datetime <=', date('Y-m-d H:i:s', strtotime('+3 months')))
            ->orderBy('start_datetime', 'ASC')
            ->findAll();

        // Map artworks data
        $mappedArtworks = [];
        foreach ($latestArtworks as $artwork) {
            $mappedArtworks[] = [
                'id' => $artwork['id'],
                'title' => $artwork['title'],
                'base_price' => $artwork['base_price'],
                'image_url' => !empty($artwork['image_url']) ? base_url('uploads/products/' . $artwork['image_url']) : '',
            ];
        }

        // Data lain yang ingin dikirim ke view
        $data['recentEvents'] = $recentEvents;
        $data['latestArtworks'] = $mappedArtworks;
        $data['upcomingEvents'] = $upcomingEvents;
        $data['calendarEvents'] = $calendarEvents;

        return $this->renderWithCurrent('xplorea/index', 'index', $data);
    }

    public function login(): string
    {
        return $this->renderWithCurrent('xplorea/login', 'login');
    }

    public function signup(): string
    {
        return $this->renderWithCurrent('xplorea/signup', 'signup');
    }

    public function modal(): string
    {
        return $this->renderWithCurrent('xplorea/modal', 'modal');
    }

    public function marketplace()
    {
        // Ambil produk yang sudah disetujui (is_approved = 1)
        $products = $this->productModel
            ->select('products.*, users.username as artist_name')
            ->join('users', 'users.id = products.artist_id')
            ->where('products.is_approved', 1)
            ->where('users.is_artist', 1)
            ->where('users.artist_profile_approved', 1)
            ->orderBy('products.created_at', 'DESC')
            ->findAll();

        $mappedProducts = [];
        foreach ($products as $p) {
            $mappedProducts[] = [
                'id' => $p['id'],
                'title' => $p['title'],
                'base_price' => $p['base_price'],
                'artist_name' => $p['artist_name'],
                'image_url' => !empty($p['image_url']) ? base_url('uploads/products/' . $p['image_url']) : '',
            ];
        }

        $data['products'] = $mappedProducts;
        $data['current'] = 'marketplace';
        return view('xplorea/marketplace', $data);
    }

    public function paintings(): string
    {
        return $this->renderWithCurrent('xplorea/paintings', 'paintings');
    }

    public function drawings(): string
    {
        return $this->renderWithCurrent('xplorea/drawings', 'drawings');
    }

    public function digital(): string
    {
        return $this->renderWithCurrent('xplorea/digital', 'digital');
    }

    public function cart(): string
    {
        return $this->renderWithCurrent('xplorea/cart', 'cart');
    }

    public function becomeartist(): string
    {
        return $this->renderWithCurrent('xplorea/becomeartist', 'becomeartist');
    }

    public function profile($username = null): string
    {
        // Jika username diberikan, tampilkan profile user tersebut
        if ($username) {
            $userData = $this->userModel->where('username', $username)->first();
            
            if (!$userData) {
                // User tidak ditemukan
                return redirect()->to('xplorea/community')->with('error', 'User not found');
            }
            
            $isArtist = isset($userData['is_artist']) && $userData['is_artist'];
            $artistProducts = [];
            $artistEvents = [];
            $salesStats = [];

            if ($isArtist) {
                $artistId = $userData['id'];

                // Produk milik seniman
                $productModel = new ProductModel();
                $artistProducts = $productModel->where('artist_id', $artistId)->findAll();

                // Event milik seniman
                $eventModel = new EventModel();
                $artistEvents = $eventModel->where('created_by', $artistId)->findAll();

                // Statistik penjualan tiket event
                $eventAttendeeModel = new EventAttendeeModel();
                $eventIds = array_column($artistEvents, 'id');
                $totalTicketsSold = 0;
                if (!empty($eventIds)) {
                    $attendees = $eventAttendeeModel
                        ->whereIn('event_id', $eventIds)
                        ->where('payment_status', 'paid')
                        ->findAll();
                    foreach ($attendees as $a) {
                        $totalTicketsSold += $a['quantity'];
                    }
                }

                $salesStats = [
                    'totalTicketsSold' => $totalTicketsSold,
                ];
            }

            $data = [
                'user' => $userData,
                'isArtist' => $isArtist,
                'artistProducts' => $artistProducts,
                'artistEvents' => $artistEvents,
                'salesStats' => $salesStats,
                'isOwnProfile' => false, // Ini bukan profile sendiri
            ];

            return view('xplorea/profile', $data);
        }
        
        // Jika tidak ada username, tampilkan profile user yang sedang login
        $userId = session()->get('user_id');
        $userData = [];
        
        if ($userId) {
            $userData = $this->userModel->find($userId);
            
            // Jika user adalah artist, hitung jumlah artworks
            if ($userData && isset($userData['is_artist']) && $userData['is_artist']) {
                $artworksCount = $this->productModel->where('artist_id', $userId)->countAllResults();
                $userData['artworks_count'] = $artworksCount;
            } else {
                $userData['artworks_count'] = 0;
            }
        }

        $isArtist = isset($userData['is_artist']) && $userData['is_artist'];
        $artistProducts = [];
        $artistEvents = [];
        $salesStats = [];

        if ($isArtist) {
            $artistId = $userData['id'];

            // Produk milik seniman
            $productModel = new ProductModel();
            $artistProducts = $productModel->where('artist_id', $artistId)->findAll();

            // Event milik seniman
            $eventModel = new EventModel();
            $artistEvents = $eventModel->where('created_by', $artistId)->findAll();

            // Statistik penjualan tiket event
            $eventAttendeeModel = new EventAttendeeModel();
            $eventIds = array_column($artistEvents, 'id');
            $totalTicketsSold = 0;
            if (!empty($eventIds)) {
                $attendees = $eventAttendeeModel
                    ->whereIn('event_id', $eventIds)
                    ->where('payment_status', 'paid')
                    ->findAll();
                foreach ($attendees as $a) {
                    $totalTicketsSold += $a['quantity'];
                }
            }

            $salesStats = [
                'totalTicketsSold' => $totalTicketsSold,
            ];
        }

        $data = [
            'user' => $userData,
            'isArtist' => $isArtist,
            'artistProducts' => $artistProducts,
            'artistEvents' => $artistEvents,
            'salesStats' => $salesStats,
            'isOwnProfile' => true, // Ini adalah profile sendiri
        ];

        return view('xplorea/profile', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('xplorea/profile')->with('error', 'Not logged in');
        }

        $userModel = new \App\Models\UserModel();

        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'username' => $this->request->getPost('username'),
            'artist_bio' => $this->request->getPost('bio'),
            'email' => $this->request->getPost('email'),
            'location' => $this->request->getPost('location'),
        ];

        // Handle file upload
        $img = $this->request->getFile('profile_url');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            // Perbaiki path - gunakan FCPATH untuk memastikan path yang benar
            $uploadPath = FCPATH . 'uploads/profiles/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $img->move($uploadPath, $newName);
            $data['profile_url'] = 'uploads/profiles/' . $newName;
        }

        $userModel->update($userId, $data);

        // Update session data jika perlu
        $user = $userModel->find($userId);
        session()->set('user_data', $user);

        return redirect()->to('xplorea/profile')->with('success', 'Profile updated!');
    }

    public function menu($section = 'about')
    {
        // Validasi section, default ke 'about'
        $allowed = ['about', 'careers', 'press', 'contract'];
        if (!in_array($section, $allowed)) {
            $section = 'about';
        }
        return view('xplorea/menu', ['section' => $section]);
    }

    public function support($section = 'help')
    {
        $allowed = ['help', 'faqs', 'tutorials', 'terms'];
        if (!in_array($section, $allowed)) {
            $section = 'help';
        }
        return view('xplorea/support', ['section' => $section]);
    }
}
