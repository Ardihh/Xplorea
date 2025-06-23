<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Artist Details</h1>
    
    <?= $this->include('partials/admin_alerts') ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i>
            Artist Profile
            <a href="<?= base_url('admin/artists/approvals') ?>" class="btn btn-sm btn-primary float-end">
                <i class="fas fa-arrow-left"></i> Back to Approvals
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Basic Information</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Username</th>
                            <td><?= esc($artist['username']) ?></td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td><?= esc($artist['fullname']) ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= esc($artist['email']) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Artist Information</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Art Categories</th>
                            <td><?= esc($artist['art_categories']) ?></td>
                        </tr>
                        <tr>
                            <th>Portfolio Link</th>
                            <td>
                                <?php if ($artist['portfolio_link']): ?>
                                    <a href="<?= esc($artist['portfolio_link']) ?>" target="_blank">
                                        <?= esc($artist['portfolio_link']) ?>
                                    </a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ($artist['artist_profile_approved']): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <h4>Artist Bio</h4>
                    <div class="border p-3 bg-light">
                        <?= esc($artist['artist_bio']) ?>
                    </div>
                </div>
            </div>

            <?php if ($request): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Upgrade Request Details</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th width="20%">Request Date</th>
                            <td><?= date('M d, Y H:i', strtotime($request['requested_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ($request['status'] === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php elseif ($request['status'] === 'rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($request['admin_notes']): ?>
                        <tr>
                            <th>Admin Notes</th>
                            <td><?= esc($request['admin_notes']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!$artist['artist_profile_approved']): ?>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="fas fa-check"></i> Approve Artist
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Reject Artist
                    </button>
                </div>
            </div>

            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url("admin/artists/approve/{$artist['id']}") ?>" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Approve Artist</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to approve this artist?</p>
                                <div class="mb-3">
                                    <label for="approveNotes" class="form-label">Admin Notes (Optional)</label>
                                    <textarea class="form-control" id="approveNotes" name="admin_notes" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Confirm Approval</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url("admin/artists/reject/{$artist['id']}") ?>" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Artist</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to reject this artist?</p>
                                <div class="mb-3">
                                    <label for="rejectNotes" class="form-label">Reason for Rejection</label>
                                    <textarea class="form-control" id="rejectNotes" name="admin_notes" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>