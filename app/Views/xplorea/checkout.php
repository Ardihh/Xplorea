<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container my-5" >
    <h2 class="mb-4" style="margin-top: 20vh;">Checkout</h2>

    <?php if (!empty($cartItems)): ?>
        <form method="post" action="<?= base_url('checkout/process') ?>">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Frame</th>
                        <th>Quantity</th> 
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    foreach ($cartItems as $item):
                        $subtotal = $item['quantity'] * $item['unit_price'];
                        $grandTotal += $subtotal;
                    ?>
                        <tr>
                            <td><?= esc($item['product_title']) ?></td>
                            <td><?= esc($item['size_description']) ?></td>
                            <td><?= esc($item['frame_name']) ?></td>
                            <td><?= esc($item['quantity']) ?></td>
                            <td>Rp <?= number_format($item['unit_price'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total</th>
                        <th>Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address</label>
                <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="cod">Cash on Delivery</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="<?= base_url('xplorea/marketplace') ?>" class="btn btn-secondary">Back to Marketplace</a>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>