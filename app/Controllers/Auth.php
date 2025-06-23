<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArtistUpgradeRequestModel;

class Auth extends BaseController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session();
        }
    }

    public function signup()
    {
        $userModel = new UserModel();

        $data = [
            'email'         => $this->request->getPost('mail'),
            'username'      => $this->request->getPost('user'),
            'password_hash' => password_hash($this->request->getPost('pass'), PASSWORD_DEFAULT),
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        if ($userModel->save($data)) {
            return redirect()->to('xplorea/login')->with('success', 'Account created successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to create account. Please try again.');
        }
    }

    public function login()
    {
        $userModel = new UserModel();

        $emailOrUsername = trim($this->request->getPost('mail'));
        $password = trim($this->request->getPost('pass'));

        // Validasi input
        if (empty($emailOrUsername) || empty($password)) {
            return redirect()->back()->with('error', 'Email/username and password are required.');
        }

        // Rate limiting yang lebih fleksibel
        $currentTime = time();
        $attempts = session()->get('login_attempts') ?? 0;
        $lastAttemptTime = session()->get('last_attempt_time') ?? 0;
        
        // Reset attempts jika sudah lebih dari 15 menit
        if ($currentTime - $lastAttemptTime > 900) { // 15 menit = 900 detik
            $attempts = 0;
            session()->remove('login_attempts');
            session()->remove('last_attempt_time');
        }
        
        // Jika masih dalam batas waktu, cek jumlah attempts
        if ($attempts >= 10) { // Meningkatkan batas dari 5 ke 10
            $remainingTime = 900 - ($currentTime - $lastAttemptTime);
            $remainingMinutes = ceil($remainingTime / 60);
            
            if ($remainingTime > 0) {
                return redirect()->back()->with('error', "Too many attempts. Please wait {$remainingMinutes} minutes before trying again.");
            } else {
                // Reset jika sudah lewat waktu
                $attempts = 0;
                session()->remove('login_attempts');
                session()->remove('last_attempt_time');
            }
        }

        // Cari user
        $user = $userModel->where('email', $emailOrUsername)
                         ->orWhere('username', $emailOrUsername)
                         ->first();

        // Verifikasi user
        if ($user) {
            // Cek apakah password_hash ada
            if (empty($user['password_hash'])) {
                return redirect()->back()->with('error', 'Account setup incomplete. Please contact administrator.');
            }

            // Cek status akun
            if (isset($user['is_active']) && $user['is_active'] == false) {
                return redirect()->back()->with('error', 'Your account is deactivated.');
            }

            // Verifikasi password
            if (password_verify($password, $user['password_hash'])) {
                // Reset login attempts karena berhasil
                session()->remove('login_attempts');
                session()->remove('last_attempt_time');

                // Set session
                session()->set([
                    'user_id' => $user['id'],
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'is_admin' => $user['is_admin'] ?? 0,
                    'is_artist' => $user['is_artist'] ?? 0,
                    'artist_profile_approved' => $user['artist_profile_approved'] ?? 0,
                    'logged_in' => true,
                ]);

                // Redirect berdasarkan role
                if ($user['is_admin']) {
                    return redirect()->to('/admin/dashboard');
                }

                // Redirect ke halaman sebelumnya atau home
                $redirectUrl = session()->get('redirect_url') ?? '/';
                session()->remove('redirect_url');
                return redirect()->to($redirectUrl);
            }
        }

        // Catat percobaan gagal
        $attempts++;
        session()->set([
            'login_attempts' => $attempts,
            'last_attempt_time' => $currentTime
        ]);

        // Pesan error yang lebih informatif
        $remainingAttempts = 10 - $attempts;
        if ($remainingAttempts > 0) {
            return redirect()->back()->with('error', "Invalid email/username or password. {$remainingAttempts} attempts remaining.");
        } else {
            return redirect()->back()->with('error', 'Too many failed attempts. Please wait 15 minutes before trying again.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function upgradeToArtist()
    {
        $userModel = new UserModel();
        $artistRequestModel = new ArtistUpgradeRequestModel();
        
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->to('xplorea/login')->with('error', 'You must be logged in.');
        }

        // Cek apakah user sudah artist
        $currentUser = $userModel->find($userId);
        if ($currentUser['is_artist']) {
            return redirect()->back()->with('error', 'You are already an artist.');
        }

        // Ambil array art_categories
        $artCategories = $this->request->getPost('art_categories');
        $artCategoriesString = is_array($artCategories) ? implode(', ', $artCategories) : '';

        // Data untuk update user
        $userData = [
            'is_artist' => 1,
            'artist_profile_approved' => 0,
            'fullname' => $this->request->getPost('fullname'),
            'art_categories' => $artCategoriesString,
            'location' => $this->request->getPost('location'),
            'phone' => $this->request->getPost('phone'),
            'artist_bio' => $this->request->getPost('artist_bio'),
            'artist_website' => $this->request->getPost('artist_website'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Simpan request data untuk tracking
        $requestData = [
            'user_id' => $userId,
            'request_data' => json_encode([
                'fullname' => $this->request->getPost('fullname'),
                'art_categories' => $artCategoriesString,
                'location' => $this->request->getPost('location'),
                'phone' => $this->request->getPost('phone'),
                'artist_bio' => $this->request->getPost('artist_bio'),
                'artist_website' => $this->request->getPost('artist_website'),
            ]),
            'status' => ArtistUpgradeRequestModel::STATUS_PENDING,
            'requested_at' => date('Y-m-d H:i:s')
        ];

        // Update user dan simpan request
        if ($userModel->update($userId, $userData) && $artistRequestModel->save($requestData)) {
            // Update session
            session()->set([
                'is_artist' => 1,
                'artist_profile_approved' => 0
            ]);
            
            return redirect()->to('/')->with('success', 'Your artist upgrade request has been submitted and is pending approval.');
        } else {
            return redirect()->back()->with('error', 'Failed to submit artist upgrade request.');
        }
    }
}