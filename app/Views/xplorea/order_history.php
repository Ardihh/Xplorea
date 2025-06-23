<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20vh;">
        <h2>Order History</h2>
        <a href="<?= base_url('xplorea/marketplace'); ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Marketplace
        </a>
    </div>

    <!-- Alert Success untuk Order Baru -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <h5 class="mt-3">No orders yet</h5>
            <p class="text-muted">Start shopping to see your order history here.</p>
            <a href="<?= base_url('xplorea/marketplace'); ?>" class="btn btn-primary">
                Browse Products
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">
                                <strong>Order #<?= $order['id'] ?></strong>
                            </h6>
                            <small class="text-muted">
                                <?= date('d M Y, H:i', strtotime($order['created_at'])) ?>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge 
                                <?= $order['status'] == 'accepted' ? 'bg-success' : 
                                    ($order['status'] == 'pending' ? 'bg-warning' : 
                                    ($order['status'] == 'rejected' ? 'bg-danger' : 'bg-secondary')) ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <h6 class="mb-0 mt-1">
                                <strong>Total: Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?= base_url('uploads/products/' . $item['image_url']) ?>" 
                                         class="rounded me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;" 
                                         alt="<?= esc($item['title']) ?>">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= esc($item['title']) ?></h6>
                                        <small class="text-muted">
                                            Qty: <?= $item['quantity'] ?> × Rp <?= number_format($item['unit_price'], 0, ',', '.') ?>
                                        </small>
                                        <?php if ($item['size_description']): ?>
                                            <br><small class="text-muted">Size: <?= $item['size_description'] ?></small>
                                        <?php endif; ?>
                                        <?php if ($item['frame_name']): ?>
                                            <br><small class="text-muted">Frame: <?= $item['frame_name'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($order['address']) || !empty($order['payment_method'])): ?>
                        <hr>
                        <div class="row">
                            <?php if (!empty($order['address'])): ?>
                                <div class="col-md-6">
                                    <small class="text-muted">Shipping Address:</small>
                                    <p class="mb-0"><?= esc($order['address']) ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($order['payment_method'])): ?>
                                <div class="col-md-6">
                                    <small class="text-muted">Payment Method:</small>
                                    <p class="mb-0"><?= esc($order['payment_method']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?> 