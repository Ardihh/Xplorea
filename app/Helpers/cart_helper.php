<?php

use App\Models\ProductModel;

function getCartData() {
    $session = session();
    $cartItems = $session->get('cart') ?? [];
    $productModel = new ProductModel();

    $cartData = [];
    $subtotal = 0;

    foreach ($cartItems as $item) {
        $product = $productModel->find($item['product_id']);
        if ($product) {
            $itemTotal = $product['base_price'] * $item['quantity'];
            $cartData[] = [
                'id' => $product['id'],
                'image' => base_url('uploads/products/' . $product['image_url']),
                'title' => $product['title'],
                'desc' => $product['description'],
                'price' => $product['base_price'],
                'quantity' => $item['quantity'],
                'total' => $itemTotal,
            ];
            $subtotal += $itemTotal;
        }
    }

    return [
        'items' => $cartData,
        'subtotal' => $subtotal,
    ];
}
