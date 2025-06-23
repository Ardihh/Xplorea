<div class="row">
    <div class="col-md-4 text-center">
        <?php 
        $profileUrl = $user['profile_url'] ?? null;
        $hasValidProfile = !empty($profileUrl) && is_string($profileUrl) && trim($profileUrl) !== '';
        ?>
        
        <?php if ($hasValidProfile): ?>
            <img src="<?= base_url($profileUrl) ?>" 
                 alt="Profile" class="img-fluid rounded-circle mb-3" 
                 style="width: 150px; height: 150px; object-fit: cover;">
        <?php else: ?>
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                 style="width: 150px; height: 150px; color: white; font-size: 48px; font-weight: bold;">
                <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
            </div>
        <?php endif; ?>
        
        <h5><?= esc($user['username']) ?></h5>
        
        <?php if (isset($user['is_admin']) && $user['is_admin']): ?>
            <span class="badge badge-danger">
                <i class="fas fa-user-shield"></i> Administrator
            </span>
        <?php elseif (isset($user['is_artist']) && $user['is_artist']): ?>
            <span class="badge badge-info">
                <i class="fas fa-palette"></i> Artist
            </span>
        <?php else: ?>
            <span class="badge badge-secondary">
                <i class="fas fa-user"></i> User
            </span>
        <?php endif; ?>
    </div>
    
    <div class="col-md-8">
        <table class="table table-borderless">
            <tr>
                <td><strong>Email:</strong></td>
                <td><?= esc($user['email']) ?></td>
            </tr>
            <tr>
                <td><strong>Full Name:</strong></td>
                <td><?= esc($user['fullname'] ?? 'Not set') ?></td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td><?= esc($user['phone'] ?? 'Not set') ?></td>
            </tr>
            <tr>
                <td><strong>Bio:</strong></td>
                <td><?= esc($user['bio'] ?? 'No bio available') ?></td>
            </tr>
            <tr>
                <td><strong>Join Date:</strong></td>
                <td><?= date('M d, Y H:i', strtotime($user['created_at'])) ?></td>
            </tr>
            <tr>
                <td><strong>Last Login:</strong></td>
                <td>
                    <?php if (!empty($user['last_login'])): ?>
                        <?= date('M d, Y H:i', strtotime($user['last_login'])) ?>
                    <?php else: ?>
                        <span class="text-muted">Never</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <?php if (isset($user['is_active']) && $user['is_active']): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div> 