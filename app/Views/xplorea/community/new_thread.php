<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container" style="margin-top: 20vh">
    <div class="row">
        <div class="col-md-8 mx-auto mb-5">
            <h2>Create New Thread</h2>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('xplorea/community/create-thread') ?>" method="post">
                <div class="form-group mb-3">
                    <label for="title">Thread Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="form-group mb-3">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="8" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Create Thread</button>
                <a href="<?= base_url('community') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>