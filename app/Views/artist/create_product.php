<!-- View: artist/create_product.php -->
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container" style="margin-top: 20vh;">
    <h2>Create New Product</h2>
    <form action="<?= base_url('artist/save-product'); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="base_price" class="form-label">Base Price</label>
            <input type="number" class="form-control" id="base_price" name="base_price" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="image_url" class="form-label">Product Image</label>
            <input class="form-control" type="file" id="image_url" name="image_url" accept="image/*">
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

        <button type="submit" class="btn btn-primary mb-5">Save Product</button>
    </form>
</div>

<?= $this->endSection(); ?>