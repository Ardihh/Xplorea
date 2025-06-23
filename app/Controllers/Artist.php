<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductSizeModel;
use App\Models\ProductFrameModel;
use App\Models\MasterSizeModel;
use App\Models\MasterFrameModel;

class Artist extends BaseController
{
    protected $productModel;
    protected $sizeModel;
    protected $frameModel;
    protected $masterSizeModel;
    protected $masterFrameModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->sizeModel = new ProductSizeModel();
        $this->frameModel = new ProductFrameModel();
        $this->masterSizeModel = new MasterSizeModel();
        $this->masterFrameModel = new MasterFrameModel();
    }

    public function products()
    {
        $artistId = session()->get('id');

        $products = $this->productModel->where('artist_id', $artistId)->findAll();

        return view('artist/products', [
            'products' => $products
        ]);
    }

    public function createProduct()
    {
        $data['sizes'] = $this->masterSizeModel->findAll();
        $data['frames'] = $this->masterFrameModel->findAll();

        return view('artist/create_product', $data);
    }

    public function saveProduct()
    {
        $artistId = session()->get('id');
        $imageFile = $this->request->getFile('image_url');

        $imageName = null;
        if ($imageFile && $imageFile->isValid()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads/products', $imageName);
        }

        $productData = [
            'artist_id' => $artistId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'base_price' => $this->request->getPost('base_price'),
            'image_url' => $imageName,
            'is_approved' => 0, // Set status pending
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->productModel->insert($productData);
        
        if ($result) {
            $productId = $this->productModel->insertID();

            // Size
            $sizeIds = $this->request->getPost('size_ids');
            if ($sizeIds) {
                foreach ($sizeIds as $sizeId) {
                    $this->sizeModel->insert([
                        'product_id' => $productId,
                        'master_size_id' => $sizeId
                    ]);
                }
            }

            // Frame
            $frameIds = $this->request->getPost('frame_ids');
            if ($frameIds) {
                foreach ($frameIds as $frameId) {
                    $this->frameModel->insert([
                        'product_id' => $productId,
                        'master_frame_id' => $frameId
                    ]);
                }
            }

            return redirect()->to(base_url('artist/products'))->with('success', 'Product submitted successfully. Waiting for admin approval.');
        } else {
            return redirect()->back()->with('error', 'Failed to save product. Please try again.');
        }
    }

    public function viewProduct($id)
    {
        $artistId = session()->get('id');
        $product = $this->productModel->where(['id' => $id, 'artist_id' => $artistId])->first();

        if (!$product) {
            return redirect()->to(base_url('artist/products'))->with('error', 'Product not found.');
        }

        $sizes = $this->sizeModel->where('product_id', $id)->findAll();
        $frames = $this->frameModel->where('product_id', $id)->findAll();

        $sizeNames = [];
        foreach ($sizes as $size) {
            $masterSize = $this->masterSizeModel->find($size['master_size_id']);
            if ($masterSize) {
                $sizeNames[] = $masterSize['size_description'];
            }
        }

        $frameNames = [];
        foreach ($frames as $frame) {
            $masterFrame = $this->masterFrameModel->find($frame['master_frame_id']);
            if ($masterFrame) {
                $frameNames[] = $masterFrame['frame_name'];
            }
        }

        $data['product'] = $product;
        $data['sizeNames'] = $sizeNames;
        $data['frameNames'] = $frameNames;

        return view('artist/view_product', $data);
    }

    public function editProduct($id)
    {
        $artistId = session()->get('id');
        $product = $this->productModel->where(['id' => $id, 'artist_id' => $artistId])->first();

        if (!$product) {
            return redirect()->to(base_url('artist/products'))->with('error', 'Product not found.');
        }

        $data['product'] = $product;
        $data['sizes'] = $this->masterSizeModel->findAll();
        $data['frames'] = $this->masterFrameModel->findAll();
        $data['selectedSizes'] = array_column($this->sizeModel->where('product_id', $id)->findAll(), 'master_size_id');
        $data['selectedFrames'] = array_column($this->frameModel->where('product_id', $id)->findAll(), 'master_frame_id');

        return view('artist/edit_product', $data);
    }

    public function updateProduct($id)
    {
        $artistId = session()->get('id');
        $product = $this->productModel->where(['id' => $id, 'artist_id' => $artistId])->first();

        if (!$product) {
            return redirect()->to(base_url('artist/products'))->with('error', 'Product not found.');
        }

        $productData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'base_price' => $this->request->getPost('base_price')
        ];

        $imageFile = $this->request->getFile('image_url');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads/products', $imageName);
            $productData['image_url'] = $imageName;
        }

        $this->productModel->update($id, $productData);

        // Optional: update size & frame juga kalau mau

        return redirect()->to(base_url('artist/products'))->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $artistId = session()->get('id');
        $product = $this->productModel->where(['id' => $id, 'artist_id' => $artistId])->first();

        if (!$product) {
            return redirect()->to(base_url('artist/products'))->with('error', 'Product not found.');
        }

        // Check if product has orders
        $db = \Config\Database::connect();
        $orderItems = $db->table('order_items')->where('product_id', $id)->countAllResults();
        
        if ($orderItems > 0) {
            return redirect()->to(base_url('artist/products'))->with('error', 'Cannot delete product because it has existing orders. Consider deactivating it instead.');
        }

        // Delete related records first
        $this->sizeModel->where('product_id', $id)->delete();
        $this->frameModel->where('product_id', $id)->delete();
        
        // Then delete the product
        $result = $this->productModel->delete($id);
        
        if ($result) {
            return redirect()->to(base_url('artist/products'))->with('success', 'Product deleted successfully.');
        } else {
            return redirect()->to(base_url('artist/products'))->with('error', 'Failed to delete product. Please try again.');
        }
    }
}
