<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Artists Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Artists
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($artists) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-palette fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Artists
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($artists, function($artist) { return $artist['artist_profile_approved'] == 1; })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Artists
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($artists, function($artist) { return $artist['artist_profile_approved'] == 0; })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                New This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($artists, function($artist) { return !empty($artist['created_at']) && strtotime($artist['created_at']) > strtotime('-30 days'); })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label for="statusFilter" class="mb-1">Filter by Status:</label>
                <select id="statusFilter" class="form-control">
                    <option value="">All Artists</option>
                    <option value="1">Approved</option>
                    <option value="0">Pending Approval</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label for="sortBy" class="mb-1">Sort by:</label>
                <select id="sortBy" class="form-control">
                    <option value="username">Username</option>
                    <option value="email">Email</option>
                    <option value="created_at">Join Date</option>
                    <option value="updated_at">Last Updated</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Artists Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="artistsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Profile</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Status</th>
                            <th>Join Date</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($artists)): ?>
                            <?php foreach ($artists as $artist): ?>
                                <tr>
                                    <td><?= $artist['id'] ?></td>
                                    <td>
                                        <?php if (!empty($artist['profile_picture'])): ?>
                                            <img src="<?= base_url('uploads/profiles/' . $artist['profile_picture']) ?>" 
                                                 alt="Profile" class="img-circle border" width="40" height="40" style="object-fit:cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle border" 
                                                 style="width: 40px; height: 40px; font-size: 16px;">
                                                <?= strtoupper(substr($artist['username'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= esc($artist['username']) ?></strong>
                                        <?php if ($artist['artist_profile_approved']): ?>
                                            <span class="badge badge-success text-dark ml-1" title="Approved">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning ml-1" title="Pending">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($artist['email']) ?></td>
                                    <td><?= esc($artist['fullname'] ?? 'Not set') ?></td>
                                    <td>
                                        <?php if ($artist['artist_profile_approved']): ?>
                                            <span class="badge badge-success text-dark">
                                                <i class="fas fa-check-circle"></i> Approved
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= date('M d, Y', strtotime($artist['created_at'])) ?>
                                        <br>
                                        <small class="text-muted"><?= date('H:i', strtotime($artist['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <?= date('M d, Y', strtotime($artist['updated_at'])) ?>
                                        <br>
                                        <small class="text-muted"><?= date('H:i', strtotime($artist['updated_at'])) ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/artists/view/' . $artist['id']) ?>" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <?php if (!$artist['artist_profile_approved']): ?>
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="approveArtist(<?= $artist['id'] ?>, '<?= esc($artist['username']) ?>')" 
                                                        title="Approve Artist">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="rejectArtist(<?= $artist['id'] ?>, '<?= esc($artist['username']) ?>')" 
                                                        title="Reject Artist">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-warning" 
                                                        onclick="revokeArtist(<?= $artist['id'] ?>, '<?= esc($artist['username']) ?>')" 
                                                        title="Revoke Approval">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button type="button" class="btn btn-sm btn-secondary" 
                                                    onclick="viewArtistDetails(<?= $artist['id'] ?>)" 
                                                    title="More Options">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Artists Found</h5>
                                        <p class="text-muted">There are no artists registered yet.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Artist</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve <strong id="approveUsername"></strong> as an artist?</p>
                    <div class="form-group">
                        <label for="approveNotes">Admin Notes (Optional):</label>
                        <textarea class="form-control" id="approveNotes" name="admin_notes" rows="3" 
                                  placeholder="Optional notes about the approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Artist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Artist</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject <strong id="rejectUsername"></strong> as an artist?</p>
                    <div class="form-group">
                        <label for="rejectNotes">Rejection Reason (Required):</label>
                        <textarea class="form-control" id="rejectNotes" name="admin_notes" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Artist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchArtist').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('artistsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(searchTerm) > -1) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const table = document.getElementById('artistsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const statusCell = row.getElementsByTagName('td')[5];
        
        if (status === '') {
            row.style.display = '';
        } else if (status === '1' && statusCell.textContent.includes('Approved')) {
            row.style.display = '';
        } else if (status === '0' && statusCell.textContent.includes('Pending')) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

// Approve artist function
function approveArtist(id, username) {
    document.getElementById('approveUsername').textContent = username;
    document.getElementById('approveForm').action = '<?= base_url('admin/artists/approve/') ?>' + id;
    $('#approveModal').modal('show');
}

// Reject artist function
function rejectArtist(id, username) {
    document.getElementById('rejectUsername').textContent = username;
    document.getElementById('rejectForm').action = '<?= base_url('admin/artists/reject/') ?>' + id;
    $('#rejectModal').modal('show');
}

// View artist details
function viewArtistDetails(id) {
    window.location.href = '<?= base_url('admin/artists/view/') ?>' + id;
}

// Revoke artist function
function revokeArtist(id, username) {
    if (confirm('Are you sure you want to revoke approval for ' + username + '?')) {
        window.location.href = '<?= base_url('admin/artists/revoke/') ?>' + id;
    }
}

// Dummy export and refresh functions
function exportArtists() {
    alert('Export feature coming soon!');
}
function refreshData() {
    location.reload();
}
</script>
<style>
/* Custom styles for better look */
.card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
}
.table-hover tbody tr:hover {
    background-color: #f1f3f6;
}
.img-circle {
    border-radius: 50%;
}
</style>
<?= $this->endSection() ?>