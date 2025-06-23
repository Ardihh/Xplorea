<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Manage Artworks
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Artworks</h1>
    
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

    <!-- Filter Dropdown -->
    <div class="mb-3">
        <form method="get" class="d-flex align-items-center">
            <label for="status" class="me-2 mb-0">Sort by Type:</label>
            <select name="status" id="status" class="form-select w-auto me-2" onchange="this.form.submit()">
                <option value="" <?= empty($status) ? 'selected' : '' ?>>All</option>
                <option value="pending" <?= (isset($status) && $status === 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?= (isset($status) && $status === 'approved') ? 'selected' : '' ?>>Approved</option>
                <option value="rejected" <?= (isset($status) && $status === 'rejected') ? 'selected' : '' ?>>Rejected</option>
            </select>
        </form>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Artworks</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Artist</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($artworks as $artwork): ?>
                        <tr>
                            <td><?= esc($artwork['id']) ?></td>
                            <td>
                                <?php if (!empty($artwork['image_url'])): ?>
                                    <img src="<?= base_url('uploads/products/' . $artwork['image_url']) ?>" alt="Artwork" style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($artwork['title']) ?></td>
                            <td><?= esc($artwork['artist_name']) ?></td>
                            <td>Rp. <?= number_format($artwork['base_price'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($artwork['is_approved'] == 1): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php elseif ($artwork['is_approved'] == 0): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($artwork['created_at'])) ?></td>
                            <td>
                                <?php if ($artwork['is_approved'] == 0): ?>
                                    <a href="<?= base_url('admin/artworks/approve/' . $artwork['id']) ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this artwork?')">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                    <a href="<?= base_url('admin/artworks/reject/' . $artwork['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this artwork?')">
                                        <i class="fas fa-times"></i> Reject
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/artworks/edit/' . $artwork['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 