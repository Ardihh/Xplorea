<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container" style="margin-top: 20vh">
    <div class="row">
        <div class="col-md-8 mx-auto mb-5">
            
            <h2><?= esc($thread['title']) ?></h2>
            <p>Posted by <?= esc($thread['username'] ?? 'Unknown User') ?> in <?= esc($thread['category_name'] ?? 'Unknown Category') ?></p>
            <div class="card mb-4">
                <div class="card-body">
                    <?= nl2br(esc($thread['content'])) ?>
                </div>
                <div class="card-footer text-muted">
                    <?= date('M d, Y H:i', strtotime($thread['created_at'])) ?>
                </div>
            </div>

            <?php if (isset($replies) && !empty($replies)): ?>
                <h4>Replies (<?= count($replies) ?>)</h4>
                <?php foreach ($replies as $reply): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p><?= nl2br(esc($reply['content'])) ?></p>
                        </div>
                        <div class="card-footer text-muted">
                            By <?= esc($reply['username'] ?? 'Unknown User') ?> on <?= date('M d, Y H:i', strtotime($reply['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <h4>Replies (0)</h4>
                <p>No replies yet.</p>
            <?php endif; ?>

            <div class="mt-4">
                <h5>Post a Reply</h5>
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('xplorea/community/thread/' . $thread['id'] . '/reply') ?>" method="post">
                    <?= csrf_field() ?> <!-- Tambahkan CSRF token -->
                    <div class="form-group">
                        <textarea class="form-control" name="content" rows="4" required minlength="5"><?= old('content') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>