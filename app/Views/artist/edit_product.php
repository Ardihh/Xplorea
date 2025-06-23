<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <h2 class="mb-4">Edit Product</h2>

    <form action="<?= base_url('artist/product/' . $product['id'] . '/update') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Product Title:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= esc($product['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= esc($product['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="base_price" class="form-label">Base Price:</label>
            <input type="number" step="0.01" class="form-control" id="base_price" name="base_price" value="<?= esc($product['base_price']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Available Sizes</label><br>
            <?php foreach ($sizes as $size): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="size_ids[]" value="<?= $size['id'] ?>"
                        <?= (isset($selectedSizes) && in_array($size['id'], $selectedSizes)) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= esc($size['size_name']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Available Frames</label><br>
            <?php foreach ($frames as $frame): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="frame_ids[]" value="<?= $frame['id'] ?>"
                        <?= (isset($selectedFrames) && in_array($frame['id'], $selectedFrames)) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= esc($frame['frame_name']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
            <?php if (!empty($product['image_url'])): ?>
                <img src="<?= base_url('uploads/products/' . $product['image_url']) ?>" alt="Product Image" width="150" class="mb-2">
            <?php else: ?>
                <img src="<?= base_url('uploads/products/default.png') ?>" alt="No Image" width="150" class="mb-2">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="image_url" class="form-label">Change Product Image (optional):</label>
            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="<?= base_url('artist/products') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>