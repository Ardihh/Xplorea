<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductSizeModel;
use App\Models\ProductFrameModel;
use App\Models\MasterSizeModel;
use App\Models\MasterFrameModel;
use CodeIgniter\Controller;

helper('cart_helper');

class CartController extends Controller
{
    protected $session;
    protected $productModel;
    protected $sizeModel;
    protected $frameModel;

    public function __construct()
    {
        helper(['url']);
        $this->session = session();
        $this->productModel = new ProductModel();
        $this->sizeModel = new ProductSizeModel();
        $this->frameModel = new ProductFrameModel();
    }

    // Product detail (untuk memilih size & frame)
    public function cart()
    {
        $productId = $this->request->getGet('product_id');
        $product = null;
        $sizes = [];
        $frames = [];
        $recommendedProducts = [];

        if ($productId) {
            $productModel = new ProductModel();
            $productSizesModel = new ProductSizeModel();
            $productFramesModel = new ProductFrameModel();
            $masterSizesModel = new MasterSizeModel();
            $masterFramesModel = new MasterFrameModel();

            $product = $productModel->find($productId);

            if ($product) {
                // Sizes
                $productSizes = $productSizesModel->where('product_id', $productId)->findAll();
                foreach ($productSizes as $ps) {
                    $masterSize = $masterSizesModel->find($ps['master_size_id']);
                    $sizes[] = [
                        'id' => $ps['id'],
                        'size_description' => $masterSize['size_description']
                    ];
                }

                // Frames
                $productFrames = $productFramesModel->where('product_id', $productId)->findAll();
                foreach ($productFrames as $pf) {
                    $masterFrame = $masterFramesModel->find($pf['master_frame_id']);
                    $frames[] = [
                        'id' => $pf['id'],
                        'frame_name' => $masterFrame['frame_name']
                    ];
                }

                // Recommended products from same artist
                $recommendedProducts = $this->productModel
                    ->where('artist_id', $product['artist_id'])
                    ->where('id !=', $productId)
                    ->where('is_approved', 1)
                    ->limit(6)
                    ->findAll();
            }
        }

        return view('xplorea/cart', [
            'product' => $product,
            'sizes' => $sizes,
            'frames' => $frames,
            'recommendedProducts' => $recommendedProducts
        ]);
    }

    // Menampilkan isi cart full-page (sidebar mode)
    public function show()
    {
        $cartItems = $this->session->get('cart') ?? [];
        $cartData = [];
        $recommendedProducts = [];

        foreach ($cartItems as $item) {
            $product = $this->productModel->find($item['product_id']);
            if (!$product) continue;

            // Hitung harga dasar
            $basePrice = $product['base_price'] ?? 0;
            $priceAdjustment = 0;

            // Tambah harga size jika ada
            if (!empty($item['size_id'])) {
                $productSize = $this->sizeModel->find($item['size_id']);
                if ($productSize && isset($productSize['price_adjustment'])) {
                    $priceAdjustment += (float) $productSize['price_adjustment'];
                }
            }

            // Tambah harga frame jika ada
            if (!empty($item['frame_id'])) {
                $productFrame = $this->frameModel->find($item['frame_id']);
                if ($productFrame && isset($productFrame['price_adjustment'])) {
                    $priceAdjustment += (float) $productFrame['price_adjustment'];
                }
            }

            // Hitung harga final
            $finalPrice = $basePrice + $priceAdjustment;

            $cartData[] = [
                'product_id' => $item['product_id'],
                'size_id' => $item['size_id'],
                'frame_id' => $item['frame_id'],
                'image' => base_url('uploads/products/' . $product['image_url']),
                'title' => $product['title'],
                'desc' => $product['description'],
                'base_price' => $basePrice,
                'price_adjustment' => $priceAdjustment,
                'price' => $finalPrice,
                'quantity' => $item['quantity'],
            ];

            // Get recommended products from the first product's artist
            if (empty($recommendedProducts)) {
                $recommendedProducts = $this->productModel
                    ->where('artist_id', $product['artist_id'])
                    ->where('id !=', $product['id'])
                    ->where('is_approved', 1)
                    ->limit(6)
                    ->findAll();
            }
        }

        return view('xplorea/cart', [
            'product' => null,
            'sizes' => [],
            'frames' => [],
            'cartData' => $cartData,
            'recommendedProducts' => $recommendedProducts
        ]);
    }

    public function add($productId)
    {
        $userId = session()->get('user_id');
        $productModel = new \App\Models\ProductModel();
        $product = $productModel->find($productId);

        // Cek apakah produk ditemukan
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Cek apakah user mencoba memesan produk sendiri
        if ($product['artist_id'] == $userId) {
            return redirect()->back()->with('error', 'You cannot order your own product.');
        }

        $quantity = (int) ($this->request->getPost('quantity') ?? 1);
        $size_id = $this->request->getPost('size_id') ?? null;
        $frame_id = $this->request->getPost('frame_id') ?? null;

        $cartItems = $this->session->get('cart') ?? [];

        $found = false;
        foreach ($cartItems as $index => &$item) {
            if ($item['product_id'] == $productId && $item['size_id'] == $size_id && $item['frame_id'] == $frame_id) {
                $item['quantity'] += $quantity;

                if ($item['quantity'] <= 0) {
                    unset($cartItems[$index]);
                }
                $found = true;
                break;
            }
        }

        if (!$found && $quantity > 0) {
            // Calculate price with adjustments
            $basePrice = $product['base_price'] ?? 0;
            $priceAdjustment = 0;

            // Add size price adjustment
            if (!empty($size_id)) {
                $size = $this->sizeModel->find($size_id);
                if ($size && isset($size['price_adjustment'])) {
                    $priceAdjustment += (float) $size['price_adjustment'];
                }
            }

            // Add frame price adjustment
            if (!empty($frame_id)) {
                $frame = $this->frameModel->find($frame_id);
                if ($frame && isset($frame['price_adjustment'])) {
                    $priceAdjustment += (float) $frame['price_adjustment'];
                }
            }

            $cartItems[] = [
                'product_id' => $productId,
                'size_id' => $size_id,
                'frame_id' => $frame_id,
                'quantity' => $quantity,
                'unit_price' => $basePrice + $priceAdjustment,
                'price_adjustment' => $priceAdjustment
            ];
        }

        $this->session->set('cart', array_values($cartItems));
        $this->session->set('selected_product', $productId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Product added to cart',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return redirect()->to($this->request->getPost('redirect_to'));
    }

    private function getCartCount()
    {
        $cartItems = $this->session->get('cart') ?? [];
        $count = 0;
        foreach ($cartItems as $item) {
            $count += (int) ($item['quantity'] ?? 0);
        }
        return $count;
    }

    public function remove($product_id)
    {
        $cartItems = $this->session->get('cart') ?? [];

        // Hapus dari cart
        $cartItems = array_filter($cartItems, function ($item) use ($product_id) {
            return $item['product_id'] != $product_id;
        });

        $this->session->set('cart', array_values($cartItems));

        // Jika product yang dihapus sama dengan selected_product, hapus selected_product
        $selectedProductId = $this->session->get('selected_product');
        if ($selectedProductId == $product_id) {
            $this->session->remove('selected_product');
        }

        // Tidak perlu redirect ke show lagi, cukup kembali ke halaman sebelumnya
        return redirect()->back();
    }

    public function checkout()
    {
        $cartItems = $this->session->get('cart') ?? [];

        foreach ($cartItems as &$item) {
            $product = $this->productModel->find($item['product_id']);

            $item['product_title'] = $product['title'] ?? 'Unknown Product';

            // Get size description
            if (!empty($item['size_id'])) {
                $productSize = $this->sizeModel->find($item['size_id']);
                if ($productSize) {
                    $masterSize = (new \App\Models\MasterSizeModel())->find($productSize['master_size_id']);
                    $item['size_description'] = $masterSize['size_description'] ?? 'Unknown Size';
                }
            } else {
                $item['size_description'] = 'No Size';
            }

            // Get frame name
            if (!empty($item['frame_id'])) {
                $productFrame = $this->frameModel->find($item['frame_id']);
                if ($productFrame) {
                    $masterFrame = (new \App\Models\MasterFrameModel())->find($productFrame['master_frame_id']);
                    $item['frame_name'] = $masterFrame['frame_name'] ?? 'Unknown Frame';
                }
            } else {
                $item['frame_name'] = 'No Frame';
            }

            // Calculate unit price
            $unit_price = $product['base_price'] ?? 0;

            if (!empty($item['size_id'])) {
                $productSize = $this->sizeModel->find($item['size_id']);
                if ($productSize && isset($productSize['price']) && $productSize['price'] > 0) {
                    $unit_price = $productSize['price'];
                }
            }

            if (!empty($item['frame_id'])) {
                $productFrame = $this->frameModel->find($item['frame_id']);
                if ($productFrame && isset($productFrame['price']) && $productFrame['price'] > 0) {
                    $unit_price += $productFrame['price'];
                }
            }

            $item['unit_price'] = $unit_price;
        }

        $data['cartItems'] = $cartItems;

        return view('xplorea/checkout', $data);
    }

    public function update($product_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $delta = (int) $this->request->getPost('delta');
        $size_id = $this->request->getPost('size_id') ?? null;
        $frame_id = $this->request->getPost('frame_id') ?? null;

        $cartItems = $this->session->get('cart') ?? [];
        foreach ($cartItems as $index => &$item) {
            if (
                $item['product_id'] == $product_id &&
                ($item['size_id'] ?? null) == $size_id &&
                ($item['frame_id'] ?? null) == $frame_id
            ) {
                $item['quantity'] += $delta;
                if ($item['quantity'] <= 0) {
                    unset($cartItems[$index]);
                }
                break;
            }
        }
        $this->session->set('cart', array_values($cartItems));

        // Hitung ulang total cart
        $cart_count = 0;
        foreach ($cartItems as $item) {
            $cart_count += (int) ($item['quantity'] ?? 0);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'cart_count' => $cart_count,
        ]);
    }

    public function updateQuantity($productId)
    {
        $delta = (int) $this->request->getPost('delta');
        $sizeId = $this->request->getPost('size_id');
        $frameId = $this->request->getPost('frame_id');
        
        // Get current cart
        $cart = session()->get('cart') ?? [];
        
        // Find the item in cart
        $itemKey = null;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $productId && 
                $item['size_id'] == $sizeId && 
                $item['frame_id'] == $frameId) {
                $itemKey = $key;
                break;
            }
        }
        
        if ($itemKey === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);
        }
        
        // Store original quantity for error handling
        $originalQuantity = $cart[$itemKey]['quantity'];
        
        // Update quantity
        $newQuantity = $cart[$itemKey]['quantity'] + $delta;
        
        // Validate quantity
        if ($newQuantity <= 0) {
            // Remove item from cart if quantity becomes 0 or negative
            unset($cart[$itemKey]);
            session()->set('cart', array_values($cart));
            
            return $this->response->setJSON([
                'success' => true,
                'new_quantity' => 0,
                'cart_count' => $this->getCartCount(),
                'removed' => true
            ]);
        }
        
        // Update quantity
        $cart[$itemKey]['quantity'] = $newQuantity;
        
        // Recalculate price with size and frame adjustments
        $product = $this->productModel->find($productId);
        $basePrice = $product['base_price'] ?? 0;
        $priceAdjustment = 0;
        
        // Add size price adjustment
        if (!empty($sizeId)) {
            $size = $this->sizeModel->find($sizeId);
            if ($size && isset($size['price_adjustment'])) {
                $priceAdjustment += (float) $size['price_adjustment'];
            }
        }
        
        // Add frame price adjustment
        if (!empty($frameId)) {
            $frame = $this->frameModel->find($frameId);
            if ($frame && isset($frame['price_adjustment'])) {
                $priceAdjustment += (float) $frame['price_adjustment'];
            }
        }
        
        // Calculate final unit price
        $finalUnitPrice = $basePrice + $priceAdjustment;
        $cart[$itemKey]['unit_price'] = $finalUnitPrice;
        $cart[$itemKey]['price_adjustment'] = $priceAdjustment;
        
        session()->set('cart', $cart);
        
        // Calculate new cart count
        $cartCount = $this->getCartCount();
        
        return $this->response->setJSON([
            'success' => true,
            'new_quantity' => $newQuantity,
            'cart_count' => $cartCount,
            'original_quantity' => $originalQuantity,
            'unit_price' => $finalUnitPrice,
            'price_adjustment' => $priceAdjustment
        ]);
    }

    public function showCart()
    {
        $productId = $this->request->getGet('product_id');
        
        if ($productId) {
            // Ambil data produk
            $product = $this->productModel->find($productId);
            
            if ($product) {
                // Ambil sizes dan frames untuk produk ini
                $sizes = $this->sizeModel->where('product_id', $productId)->findAll();
                $frames = $this->frameModel->where('product_id', $productId)->findAll();
                
                // Ambil rekomendasi produk dari artist yang sama
                $recommendedProducts = $this->productModel
                    ->where('artist_id', $product['artist_id'])
                    ->where('id !=', $productId)
                    ->where('is_approved', 1)
                    ->limit(6)
                    ->findAll();
                
                return view('xplorea/cart', [
                    'product' => $product,
                    'sizes' => $sizes,
                    'frames' => $frames,
                    'recommendedProducts' => $recommendedProducts
                ]);
            }
        }
        
        return view('xplorea/cart', [
            'product' => null,
            'sizes' => [],
            'frames' => [],
            'recommendedProducts' => []
        ]);
    }
}
