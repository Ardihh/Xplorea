<?php

namespace App\Controllers;

use App\Models\OrderItemsModel;
use App\Models\ProductModel;

class ArtistOrderController extends BaseController
{
    public function index()
    {
        $artistId = session()->get('user_id');
        $orderItemsModel = new OrderItemsModel();

        // Ambil order items untuk produk milik artist ini
        $orders = $orderItemsModel
            ->select('order_items.*, products.title as product_title, users.fullname as buyer_name')
            ->join('products', 'products.id = order_items.product_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->where('products.artist_id', $artistId)
            ->groupBy('order_items.id')
            ->orderBy('order_items.id', 'DESC')
            ->findAll();

        return view('artist/orders', ['orders' => $orders]);
    }

    public function accept($orderItemId)
    {
        $orderItemsModel = new OrderItemsModel();
        $orderItem = $orderItemsModel->find($orderItemId);

        if (!$orderItem) {
            return redirect()->back()->with('error', 'Order item not found.');
        }

        // Cek apakah status sudah accepted
        if ($orderItem['status'] === 'accepted') {
            return redirect()->back()->with('success', 'Order already accepted.');
        }

        $result = $orderItemsModel->update($orderItemId, ['status' => 'accepted']);
        if ($result) {
            return redirect()->back()->with('success', 'Order accepted.');
        } else {
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }

    public function reject($orderItemId)
    {
        $orderItemsModel = new OrderItemsModel();
        $orderItem = $orderItemsModel->find($orderItemId);

        if (!$orderItem) {
            return redirect()->back()->with('error', 'Order item not found.');
        }

        if ($orderItem['status'] === 'rejected') {
            return redirect()->back()->with('success', 'Order already rejected.');
        }

        $result = $orderItemsModel->update($orderItemId, ['status' => 'rejected']);
        if ($result) {
            return redirect()->back()->with('success', 'Order rejected.');
        } else {
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }
}
