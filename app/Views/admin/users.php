<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Users Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Users Management</h3>
                    <div class="card-tools d-flex align-items-center">
                        <button type="button" class="btn btn-primary mr-2 me-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-user-plus"></i> Add User
                        </button>
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="searchUser" class="form-control" placeholder="Search users...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default" onclick="filterUsersTable()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="usersTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Profile</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td>
                                                <?php 
                                                $profileUrl = $user['profile_url'] ?? null;
                                                $hasValidProfile = !empty($profileUrl) && is_string($profileUrl) && trim($profileUrl) !== '';
                                                ?>
                                                <?php if ($hasValidProfile): ?>
                                                    <img src="<?= base_url($profileUrl) ?>" 
                                                         alt="Profile" class="img-circle profile-image border" width="40" height="40">
                                                <?php else: ?>
                                                    <div class="img-circle bg-primary d-flex align-items-center justify-content-center profile-fallback border" 
                                                         style="width: 40px; height: 40px; color: white; font-size: 14px; font-weight: bold;">
                                                        <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= esc($user['username']) ?></strong>
                                                <?php if (isset($user['is_admin']) && $user['is_admin']): ?>
                                                    <span class="badge badge-danger ml-1 text-dark" title="Admin">
                                                        <i class="fas fa-shield-alt text-dark"></i>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (isset($user['is_artist']) && $user['is_artist']): ?>
                                                    <span class="badge badge-info ml-1 text-dark" title="Artist">
                                                        <i class="fas fa-palette text-dark"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td><?= esc($user['fullname'] ?? 'Not set') ?></td>
                                            <td>
                                                <?php if (isset($user['is_admin']) && $user['is_admin']): ?>
                                                    <span class="badge badge-danger text-dark">
                                                        <i class="fas fa-user-shield"></i> Admin
                                                    </span>
                                                <?php elseif (isset($user['is_artist']) && $user['is_artist']): ?>
                                                    <span class="badge badge-info text-dark">
                                                        <i class="fas fa-palette"></i> Artist
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary text-dark">
                                                        <i class="fas fa-user"></i> User
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (isset($user['is_active']) && $user['is_active']): ?>
                                                    <span class="badge badge-success text-dark">Active</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger text-dark">Inactive/Deleted</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= date('M d, Y', strtotime($user['created_at'])) ?>
                                                <br>
                                                <small class="text-muted"><?= date('H:i', strtotime($user['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <?php if (!empty($user['last_login'])): ?>
                                                    <?= date('M d, Y', strtotime($user['last_login'])) ?>
                                                    <br>
                                                    <small class="text-muted"><?= date('H:i', strtotime($user['last_login'])) ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">Never</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-info" 
                                                            onclick="viewUser(<?= $user['id'] ?>)" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                            onclick="editUser(<?= $user['id'] ?>)" title="Edit User">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if (isset($user['is_active']) && $user['is_active']): ?>
                                                        <button type="button" class="btn btn-sm btn-secondary" 
                                                                onclick="toggleUserStatus(<?= $user['id'] ?>, false, '<?= esc($user['username']) ?>')" 
                                                                title="Deactivate User">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                                onclick="toggleUserStatus(<?= $user['id'] ?>, true, '<?= esc($user['username']) ?>')" 
                                                                title="Activate User">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteUser(<?= $user['id'] ?>, '<?= esc($user['username']) ?>')" 
                                                            title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No Users Found</h5>
                                                <p class="text-muted">There are no users registered yet.</p>
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
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addUserForm" method="POST" action="<?= base_url('admin/users/add') ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <small class="form-text text-muted">Must be unique and alphanumeric</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Minimum 8 characters</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User Type</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_artist" name="is_artist" value="1">
                                    <label class="form-check-label" for="is_artist">
                                        <i class="fas fa-palette text-info"></i> Artist
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1">
                                    <label class="form-check-label" for="is_admin">
                                        <i class="fas fa-user-shield text-danger"></i> Administrator
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">
                        <i class="fas fa-user-edit me-2"></i>Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Form content will be loaded dynamically -->
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">
                    <i class="fas fa-user me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <!-- User details will be loaded dynamically -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.profile-image {
    width: 40px !important;
    height: 40px !important;
    object-fit: cover;
    object-position: center;
    border-radius: 50%;
    border: 2px solid #e9ecef;
}

.profile-fallback {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50%;
    border: 2px solid #e9ecef;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // CSRF setup - pastikan ini benar
    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';

    // Debug: cek apakah CSRF token ada
    console.log('CSRF Name:', csrfName);
    console.log('CSRF Hash:', csrfHash);

    // Search & filter
    function filterUsersTable() {
        const searchTerm = $('#searchUser').val().toLowerCase();
        $('#usersTable tbody tr').each(function() {
            const row = $(this);
            const rowText = row.text().toLowerCase();
            row.toggle(rowText.indexOf(searchTerm) > -1);
        });
    }
    $('#searchUser').on('keyup', filterUsersTable);

    // AJAX action functions
    function viewUser(userId) {
        $.get('<?= base_url("admin/users/view") ?>/' + userId, function(response) {
            if (response.success) {
                $('#viewUserModal .modal-body').html(response.html);
                $('#viewUserModal').modal('show');
            } else {
                alert('Failed to load user details');
            }
        });
    }

    function editUser(userId) {
        $.get('<?= base_url("admin/users/edit") ?>/' + userId, function(response) {
            if (response.success) {
                $('#editUserModal .modal-body').html(response.html);
                $('#editUserModal').modal('show');
                $('#editUserForm').attr('action', '<?= base_url("admin/users/update") ?>/' + userId);
            } else {
                alert('Failed to load user data');
            }
        });
    }

    function toggleUserStatus(userId, status, username) {
        const action = status ? 'activate' : 'deactivate';
        if (confirm(`Are you sure you want to ${action} user "${username}"?`)) {
            $.ajax({
                url: '<?= base_url("admin/users/toggle-status") ?>',
                type: 'POST',
                data: {
                    user_id: userId,
                    status: status,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Toggle status response:', response); // Debug
                    
                    if (response.success) {
                        alert(`User ${action}d successfully!`);
                        console.log('Debug info:', response.debug); // Debug
                        location.reload();
                    } else {
                        alert(response.message || `Failed to ${action} user`);
                        console.log('Error debug:', response.debug); // Debug
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Status:', xhr.status);
                    alert('Network error occurred. Please try again.');
                }
            });
        }
    }

    function deleteUser(userId, username) {
        if (confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
            $.ajax({
                url: '<?= base_url("admin/users/delete") ?>/' + userId,
                type: 'POST',
                data: {
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Delete response:', response); // Debug
                    
                    if (response.success) {
                        alert('User deleted successfully!');
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to delete user');
                        console.log('Delete error:', response); // Debug
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete AJAX Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Status:', xhr.status);
                    alert('Network error occurred. Please try again.');
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>