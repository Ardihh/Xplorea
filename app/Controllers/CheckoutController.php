<?php

namespace App\Controllers;

use App\Models\OrdersModel;
use App\Models\OrderItemsModel;
use App\Models\ProductModel;
use App\Models\ProductSizeModel;
use App\Models\ProductFrameModel;
use CodeIgniter\Controller;

class CheckoutController extends Controller
{
    protected $session;
    protected $ordersModel;
    protected $orderItemsModel;
    protected $productModel;
    protected $sizeModel;
    protected $frameModel;

    public function __construct()
    {
        $this->session = session();
        $this->ordersModel = new OrdersModel();
        $this->orderItemsModel = new OrderItemsModel();
        $this->productModel = new ProductModel();
        $this->sizeModel = new ProductSizeModel();
        $this->frameModel = new ProductFrameModel();
    }

    public function process()
    {
        // Pastikan user sudah login
        if (!$this->session->has('user_id')) {
            return redirect()->to('xplorea/login')->with('error', 'Please login to proceed with checkout');
        }
        
        $cartItems = $this->session->get('cart') ?? [];

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Cart is empty');
        }

        $totalAmount = 0;

        foreach ($cartItems as &$item) {
            // Jika unit_price belum ada, hitung ulang
            if (!isset($item['unit_price'])) {
                $product = $this->productModel->find($item['product_id']);
                $basePrice = $product['base_price'] ?? 0;
                $priceAdjustment = 0;

                if (!empty($item['size_id'])) {
                    $size = $this->sizeModel->find($item['size_id']);
                    if ($size && isset($size['price_adjustment'])) {
                        $priceAdjustment += (float) $size['price_adjustment'];
                    }
                }

                if (!empty($item['frame_id'])) {
                    $frame = $this->frameModel->find($item['frame_id']);
                    if ($frame && isset($frame['price_adjustment'])) {
                        $priceAdjustment += (float) $frame['price_adjustment'];
                    }
                }

                $item['unit_price'] = $basePrice + $priceAdjustment;
                $item['price_adjustment'] = $priceAdjustment;
            }

            $totalAmount += $item['unit_price'] * $item['quantity'];
        }

        // Dapatkan user_id dari session jika user sudah login
        $userId = null;
        if ($this->session->has('user_id')) {
            $userId = $this->session->get('user_id');
        }

        $address = $this->request->getPost('address') ?? '';
        $paymentMethod = $this->request->getPost('payment_method') ?? '';

        $orderData = [
            'user_id' => $userId,
            'session_id' => session_id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'address' => $address,
            'payment_method' => $paymentMethod,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $orderId = $this->ordersModel->insert($orderData);

        foreach ($cartItems as $item) {
            $itemData = [
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'size_id' => $item['size_id'] ?? null,
                'frame_id' => $item['frame_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'price_adjustment' => $item['price_adjustment'],
                'status' => 'pending',
            ];
            $this->orderItemsModel->insert($itemData);
        }

        $this->session->remove('cart');
        $this->session->remove('selected_product');

        // Setelah order berhasil diproses, redirect ke order history dengan flash message
        return redirect()->to('order-history')->with('success', 'Order placed successfully! Thank you for your purchase.');
    }


    public function success()
    {
        return view('xplorea/checkout_success');
    }

    public function orderHistory()
    {
        // Pastikan user sudah login
        if (!$this->session->has('user_id')) {
            return redirect()->to('xplorea/login')->with('error', 'Please login to view order history');
        }

        $userId = $this->session->get('user_id');

        // Ambil semua order user
        $orders = $this->ordersModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Ambil detail items untuk setiap order
        foreach ($orders as &$order) {
            $orderItems = $this->orderItemsModel
                ->select('order_items.*, products.title, products.image_url, master_sizes.size_description, master_frames.frame_name')
                ->join('products', 'products.id = order_items.product_id', 'left')
                ->join('master_sizes', 'master_sizes.id = (SELECT master_size_id FROM product_sizes WHERE id = order_items.size_id)', 'left')
                ->join('master_frames', 'master_frames.id = (SELECT master_frame_id FROM product_frames WHERE id = order_items.frame_id)', 'left')
                ->where('order_id', $order['id'])
                ->findAll();

            // Tentukan status order berdasarkan status item
            $statuses = array_column($orderItems, 'status');
            if (in_array('pending', $statuses)) {
                $order['status'] = 'pending';
            } elseif (in_array('accepted', $statuses)) {
                $order['status'] = 'accepted';
            } elseif (count($statuses) > 0 && count(array_unique($statuses)) === 1 && $statuses[0] === 'rejected') {
                $order['status'] = 'rejected';
            } else {
                $order['status'] = 'unknown';
            }

            $order['items'] = $orderItems;
        }

        return view('xplorea/order_history', [
            'orders' => $orders,
            'title' => 'Order History'
        ]);
    }
}
