<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container" style="margin-top: 20vh">
    <div class="row">
        <div class="col-md-8 mx-auto mb-5">
            <h2>Create Video Tutorial</h2>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('artist/tutorials/store') ?>" method="post">
                <div class="form-group mb-3">
                    <label for="title">Tutorial Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?= old('description') ?></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="video_url">Video URL (YouTube/Vimeo)</label>
                    <input type="url" class="form-control" id="video_url" name="video_url" value="<?= old('video_url') ?>" placeholder="https://www.youtube.com/watch?v=..." required>
                    <small class="form-text text-muted">Paste the full URL of your video from YouTube or Vimeo</small>
                </div>

                <div class="form-group mb-3">
                    <label for="thumbnail_url">Thumbnail URL (Optional)</label>
                    <input type="url" class="form-control" id="thumbnail_url" name="thumbnail_url" value="<?= old('thumbnail_url') ?>" placeholder="https://example.com/thumbnail.jpg">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= old('category') == $key ? 'selected' : '' ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="difficulty_level">Difficulty Level</label>
                            <select class="form-control" id="difficulty_level" name="difficulty_level" required>
                                <option value="">Select difficulty</option>
                                <?php foreach ($difficulty_levels as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= old('difficulty_level') == $key ? 'selected' : '' ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="duration">Duration (Optional)</label>
                    <input type="text" class="form-control" id="duration" name="duration" value="<?= old('duration') ?>" placeholder="e.g., 15:30">
                    <small class="form-text text-muted">Format: MM:SS or HH:MM:SS</small>
                </div>

                <button type="submit" class="btn btn-primary">Create Tutorial</button>
                <a href="<?= base_url('artist/tutorials') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?> 