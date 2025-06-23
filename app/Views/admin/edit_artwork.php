<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Edit Artwork
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Edit Artwork</h1>
            <form action="<?= base_url('admin/artworks/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $artwork['id'] ?>">

                <!-- Change Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Change Artwork Title:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $artwork['title'] ?>">
                </div>

                <!-- Change Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Change Artwork Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $artwork['description'] ?></textarea>
                </div>

                <!-- Change Image -->
                <div class="mb-3">
                    <label for="image_url" class="form-label">Change Artwork Image (optional):</label>
                    <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Update Artwork</button>
                <a href="<?= base_url('admin/artworks') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 