<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container" style="margin-top: 20vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Products</h2>
        <a href="<?= base_url('artist/create-product'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?= base_url('uploads/products/' . $product['image_url']) ?>" class="card-img-top" alt="<?= esc($product['title']) ?>" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($product['title']) ?></h5>
                            <p class="card-text">Rp. <?= number_format($product['base_price'], 0, ',', '.') ?></p>
                            
                            <!-- Status Badge -->
                            <?php if ($product['is_approved'] == 1): ?>
                                <span class="badge bg-success mb-2">Approved</span>
                            <?php elseif ($product['is_approved'] == 0): ?>
                                <span class="badge bg-warning mb-2">Pending Approval</span>
                            <?php else: ?>
                                <span class="badge bg-danger mb-2">Rejected</span>
                            <?php endif; ?>
                            
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('artist/product/' . $product['id']); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="<?= base_url('artist/product/' . $product['id'] . '/edit'); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                
                                <!-- Changed to form with POST method -->
                                <form action="<?= base_url('artist/product/' . $product['id'] . '/delete') ?>" method="POST" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No products found. <a href="<?= base_url('artist/create-product'); ?>">Create your first product</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>