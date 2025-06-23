<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container my-5" style="margin-top: 15vh;">
    <h2 class="fw-bold" style="margin-top: 20vh;"><?= esc($product['title']) ?></h2>

    <div class="row">
        <div class="col-md-6">
            <img src="<?= base_url('uploads/products/' . $product['image_url']) ?>" 
                 class="img-fluid rounded"
                 alt="<?= esc($product['title']) ?>"
                 style="object-fit: cover; width: 100%; height: 400px;">
        </div>

        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center ">
            <div class="w-100">
            <p><strong>Description:</strong> <?= esc($product['description']) ?></p>
            <p><strong>Base Price:</strong> Rp <?= esc($product['base_price']) ?></p>

            <p><strong>Available Sizes:</strong> 
                <?= !empty($sizeNames) ? implode(', ', $sizeNames) : '-' ?>
            </p>

            <p><strong>Available Frames:</strong> 
                <?= !empty($frameNames) ? implode(', ', $frameNames) : '-' ?>
            </p>

            <a href="<?= base_url('artist/product/' . $product['id'] . '/edit') ?>" class="btn btn-primary">Edit Product</a>
            
            <form action="<?= base_url('artist/product/' . $product['id'] . '/delete') ?>" method="POST" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">Delete Product</button>
            </form>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>