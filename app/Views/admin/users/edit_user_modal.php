<?= csrf_field() ?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_username">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_username" name="username" 
                   value="<?= esc($user['username']) ?>" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="edit_email" name="email" 
                   value="<?= esc($user['email']) ?>" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_password">New Password</label>
            <input type="password" class="form-control" id="edit_password" name="password">
            <small class="form-text text-muted">Leave empty to keep current password</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_fullname">Full Name</label>
            <input type="text" class="form-control" id="edit_fullname" name="fullname" 
                   value="<?= esc($user['fullname'] ?? '') ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="edit_phone">Phone Number</label>
            <input type="text" class="form-control" id="edit_phone" name="phone" 
                   value="<?= esc($user['phone'] ?? '') ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>User Type</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="edit_is_artist" name="is_artist" value="1"
                       <?= (isset($user['is_artist']) && $user['is_artist']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="edit_is_artist">
                    <i class="fas fa-palette text-info"></i> Artist
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="edit_is_admin" name="is_admin" value="1"
                       <?= (isset($user['is_admin']) && $user['is_admin']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="edit_is_admin">
                    <i class="fas fa-user-shield text-danger"></i> Administrator
                </label>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="edit_bio">Bio</label>
    <textarea class="form-control" id="edit_bio" name="bio" rows="3"><?= esc($user['bio'] ?? '') ?></textarea>
</div> 