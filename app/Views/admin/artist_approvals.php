<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Artist Approvals
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Artist Approvals</h1>
            <p class="mb-0 text-muted">Review and manage artist registration requests</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-users-cog"></i>
            <?= count($artists) ?> pending approval<?= count($artists) != 1 ? 's' : '' ?>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-check me-2"></i>Pending Artist Approvals
            </h6>
            <?php if (count($artists) > 0): ?>
                <span class="badge bg-warning text-dark"><?= count($artists) ?> Pending</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($artists)): ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>All Caught Up!</h4>
                    <p class="text-muted">No pending artist approvals at the moment.</p>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            <?php else: ?>
                <!-- Artists Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Artist Info</th>
                                <th>Contact</th>
                                <th>Specialization</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($artists as $index => $artist): ?>
                            <tr>
                                <td class="align-middle">
                                    <strong>#<?= $artist['id'] ?></strong>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold"><?= esc($artist['fullname'] ?? 'N/A') ?></div>
                                            <small class="text-muted">@<?= esc($artist['username']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <i class="fas fa-envelope text-primary"></i> 
                                        <?= esc($artist['email']) ?>
                                    </div>
                                    <?php if (!empty($artist['location'])): ?>
                                    <div class="mt-1">
                                        <i class="fas fa-map-marker-alt text-success"></i> 
                                        <?= esc($artist['location']) ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <?php if (!empty($artist['art_categories'])): ?>
                                        <span class="badge bg-info"><?= esc($artist['art_categories']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Not specified</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted">
                                        <?= date('M d, Y', strtotime($artist['updated_at'] ?? $artist['created_at'])) ?><br>
                                        <?= date('H:i', strtotime($artist['updated_at'] ?? $artist['created_at'])) ?>
                                    </small>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="window.location.href='<?= base_url("admin/artists/view/{$artist['id']}") ?>'"
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="modal" data-bs-target="#approveModal<?= $artist['id'] ?>"
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal<?= $artist['id'] ?>"
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions (if needed in future) -->
                <div class="mt-3 text-muted">
                    <small>
                        <i class="fas fa-info-circle"></i> 
                        Showing <?= count($artists) ?> pending approval<?= count($artists) != 1 ? 's' : '' ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals for each artist -->
<?php if (!empty($artists)): ?>
    <?php foreach ($artists as $artist): ?>
        
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal<?= $artist['id'] ?>" tabindex="-1" 
             aria-labelledby="approveModalLabel<?= $artist['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="approveModalLabel<?= $artist['id'] ?>">
                            <i class="fas fa-check-circle"></i> Approve Artist
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url("admin/artists/approve/{$artist['id']}") ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <!-- Artist Info Card -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Artist Information</h6>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Name:</strong></div>
                                        <div class="col-sm-8"><?= esc($artist['fullname'] ?? 'N/A') ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Username:</strong></div>
                                        <div class="col-sm-8">@<?= esc($artist['username']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                        <div class="col-sm-8"><?= esc($artist['email']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Categories:</strong></div>
                                        <div class="col-sm-8"><?= esc($artist['art_categories'] ?? 'Not specified') ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Location:</strong></div>
                                        <div class="col-sm-8"><?= esc($artist['location'] ?? 'Not specified') ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="admin_notes_approve<?= $artist['id'] ?>" class="form-label">
                                    <i class="fas fa-sticky-note"></i> Admin Notes (Optional)
                                </label>
                                <textarea class="form-control" id="admin_notes_approve<?= $artist['id'] ?>" 
                                         name="admin_notes" rows="3" 
                                         placeholder="Add any notes about this approval..."></textarea>
                                <div class="form-text">These notes will be saved for administrative records.</div>
                            </div>
                            
                            <div class="alert alert-success">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Approval Action:</strong> This will grant artist privileges to the user, 
                                allowing them to create and sell artwork on the platform.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Approve Artist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal<?= $artist['id'] ?>" tabindex="-1" 
             aria-labelledby="rejectModalLabel<?= $artist['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel<?= $artist['id'] ?>">
                            <i class="fas fa-times-circle"></i> Reject Artist Application
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url("admin/artists/reject/{$artist['id']}") ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <!-- Artist Info Card -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Artist Information</h6>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Name:</strong></div>
                                        <div class="col-sm-8"><?= esc($artist['fullname'] ?? 'N/A') ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Username:</strong></div>
                                        <div class="col-sm-8">@<?= esc($artist['username']) ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                        <div class="col-sm-8"><?= esc($artist['email']) ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="admin_notes_reject<?= $artist['id'] ?>" class="form-label">
                                    <i class="fas fa-exclamation-triangle"></i> Reason for Rejection 
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="admin_notes_reject<?= $artist['id'] ?>" 
                                         name="admin_notes" rows="4" required
                                         placeholder="Please provide a clear and constructive reason for rejection..."></textarea>
                                <div class="form-text">
                                    This reason will be logged and may be communicated to the applicant.
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Warning:</strong> This action will remove artist status from the user. 
                                They will need to submit a new application to become an artist.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Reject Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.avatar {
    flex-shrink: 0;
}

.modal-header.bg-success,
.modal-header.bg-danger {
    border-bottom: none;
}

.card.bg-light {
    border: 1px solid #e3e6f0;
}

.card.bg-light .card-body {
    padding: 1rem;
}

.card.bg-light .row {
    margin-bottom: 0.5rem;
}

.card.bg-light .row:last-child {
    margin-bottom: 0;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.form-text {
    font-size: 0.8rem;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on textarea when modal opens
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const textarea = modal.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        });
    });

    // Add character counter for rejection reason
    const rejectTextareas = document.querySelectorAll('textarea[name="admin_notes"]');
    rejectTextareas.forEach(textarea => {
        if (textarea.required) {
            const maxLength = 500;
            const counter = document.createElement('small');
            counter.className = 'text-muted float-end';
            textarea.parentNode.appendChild(counter);
            
            function updateCounter() {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${textarea.value.length}/${maxLength} characters`;
                counter.className = remaining < 50 ? 'text-warning float-end' : 'text-muted float-end';
            }
            
            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }
    });

    // Add confirmation for rejection
    const rejectForms = document.querySelectorAll('form[action*="reject"]');
    rejectForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const textarea = form.querySelector('textarea[name="admin_notes"]');
            if (textarea.value.trim().length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed reason for rejection (at least 10 characters).');
                textarea.focus();
                return false;
            }
        });
    });
});
</script>
<?= $this->endSection() ?>