<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php
// Get user data from session or database
$user = $user ?? [];
$isArtist = isset($user['is_artist']) && $user['is_artist'];
$isOwnProfile = $isOwnProfile ?? true; // Default ke true jika tidak diset
?>

<div class="container-fluid p-0">
    <!-- Profile Header -->
    <div class="profile-header position-relative pt-5">
        <!-- Cover Photo -->
        <div class="cover-photo" style="height: 50vh; background: #1D2A34 url('<?= base_url('assets/logo1.png') ?>') no-repeat center center; background-size: auto;">
        </div>
        
        <!-- Profile Info -->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="profile-info position-relative">
                        <!-- Profile Picture -->
                        <div class="profile-picture position-absolute" style="top: -55px; left: 20px;">
                            <?php if (!empty($user['profile_url'])): ?>
                                <img src="<?= base_url($user['profile_url']) ?>" alt="Profile" class="rounded-circle border border-4 border-white" style="width: 140px; height: 140px; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= base_url('assets/logo1.png') ?>" alt="Profile" class="rounded-circle border border-4 border-white" style="width: 100px; height: 100px; object-fit: cover;">
                                <small class="d-block mt-1">No profile image</small>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <?php if ($isOwnProfile): ?>
                        <div class="action-buttons position-absolute" style="top: 20px; right: 20px;">
                            <?php if (!$isArtist): ?>
                                <a href="<?= base_url('xplorea/becomeartist') ?>" class="btn btn-primary me-2">
                                    <i class="bi bi-palette"></i> Become Artist
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </button>
                        </div>
                        <?php endif; ?>
                        
                        <!-- User Info -->
                        <div class="user-info mt-5 pt-4">
                            <h2 class="fw-bold mb-1"><?= esc($user['fullname'] ?? 'User Name') ?></h2>
                            <p class="text-muted mb-2">@<?= esc($user['username'] ?? 'username') ?></p>
                            <p class="mb-3"><?= esc($user['artist_bio'] ?? $user['bio'] ?? 'No bio available') ?></p>
                            
                            <!-- Stats -->
                            <div class="d-flex gap-4 mb-3">
                                <?php if ($isArtist): ?>
                                    <div class="text-center">
                                        <h5 class="fw-bold mb-0"><?= count($artistProducts ?? []) ?></h5>
                                        <small class="text-muted">Artworks</small>
                                    </div>
                                    <div class="text-center">
                                        <h5 class="fw-bold mb-0"><?= count($artistEvents ?? []) ?></h5>
                                        <small class="text-muted">Events</small>
                                    </div>
                                    <div class="text-center">
                                        <h5 class="fw-bold mb-0"><?= $salesStats['totalTicketsSold'] ?? 0 ?></h5>
                                        <small class="text-muted">Tickets Sold</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Tabs -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs border-0" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border-0 fw-bold" id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab" aria-controls="likes" aria-selected="true">
                            <i class="bi bi-heart"></i> Likes (Topics)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 fw-bold" id="saved-tab" data-bs-toggle="tab" data-bs-target="#saved" type="button" role="tab" aria-controls="saved" aria-selected="false">
                            <i class="bi bi-bookmark"></i> Saved (Video Tutorials)
                        </button>
                    </li>
                    <?php if ($isArtist): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 fw-bold" id="myproducts-tab" data-bs-toggle="tab" data-bs-target="#myproducts" type="button" role="tab" aria-controls="myproducts" aria-selected="false">
                            <i class="bi bi-image"></i> My Products
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 fw-bold" id="myevents-tab" data-bs-toggle="tab" data-bs-target="#myevents" type="button" role="tab" aria-controls="myevents" aria-selected="false">
                            <i class="bi bi-calendar-event"></i> My Events
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="tab-content" id="profileTabsContent">
                    <!-- Likes Tab -->
                    <div class="tab-pane fade show active" id="likes" role="tabpanel" aria-labelledby="likes-tab">
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Liked Topics</h5>
                                <div class="liked-topics">
                                    <!-- Sample liked topics -->
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Abstract Art</h6>
                                            <p class="card-text text-muted">Contemporary abstract expressions</p>
                                            <small class="text-muted">Liked 2 days ago</small>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">Digital Art</h6>
                                            <p class="card-text text-muted">Modern digital creations</p>
                                            <small class="text-muted">Liked 1 week ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Saved Tab (Video Tutorials) -->
                    <div class="tab-pane fade" id="saved" role="tabpanel" aria-labelledby="saved-tab">
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Saved Video Tutorials</h5>
                                <div class="saved-tutorials">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img src="https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg" class="card-img-top" alt="Tutorial Thumbnail" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6 class="card-title">How to Paint Abstract Art</h6>
                                                    <p class="card-text text-muted">Learn the basics of abstract painting in this step-by-step tutorial.</p>
                                                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-play-circle"></i> Watch Video
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img src="https://img.youtube.com/vi/3GwjfUFyY6M/hqdefault.jpg" class="card-img-top" alt="Tutorial Thumbnail" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6 class="card-title">Digital Art for Beginners</h6>
                                                    <p class="card-text text-muted">A beginner's guide to creating digital art using free tools.</p>
                                                    <a href="https://www.youtube.com/watch?v=3GwjfUFyY6M" target="_blank" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-play-circle"></i> Watch Video
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img src="https://img.youtube.com/vi/sample3/hqdefault.jpg" class="card-img-top" alt="Tutorial Thumbnail" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6 class="card-title">Watercolor Techniques</h6>
                                                    <p class="card-text text-muted">Master basic watercolor painting techniques.</p>
                                                    <a href="#" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-play-circle"></i> Watch Video
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($isArtist): ?>
                    <!-- My Products Tab -->
                    <div class="tab-pane fade" id="myproducts" role="tabpanel" aria-labelledby="myproducts-tab">
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">My Products</h5>
                                <div class="row">
                                    <?php if (!empty($artistProducts)): ?>
                                        <?php foreach ($artistProducts as $product): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <?php if (!empty($product['image_url'])): ?>
                                                        <img src="<?= base_url('uploads/products/' . $product['image_url']) ?>" class="card-img-top" alt="<?= esc($product['title']) ?>" style="height: 200px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div class="card-body">
                                                        <h6 class="card-title"><?= esc($product['title']) ?></h6>
                                                        <p class="card-text">Rp. <?= number_format($product['base_price'], 0, ',', '.') ?></p>
                                                        <a href="<?= base_url('artist/product/' . $product['id']); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <div class="alert alert-info">No products found.</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- My Events Tab -->
                    <div class="tab-pane fade" id="myevents" role="tabpanel" aria-labelledby="myevents-tab">
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">My Events</h5>
                                <div class="row">
                                    <?php if (!empty($artistEvents)): ?>
                                        <?php foreach ($artistEvents as $event): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                    <?php if (!empty($event['image_url'])): ?>
                                                        <img src="<?= base_url('uploads/events/' . $event['image_url']) ?>" class="card-img-top" alt="<?= esc($event['title']) ?>" style="height: 180px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div class="card-body">
                                                        <h6 class="card-title"><?= esc($event['title']) ?></h6>
                                                        <p class="card-text"><?= esc($event['location']) ?> | <?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?></p>
                                                        <a href="<?= base_url('events/' . $event['id']); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <div class="alert alert-info">No events found.</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= base_url('xplorea/profile/update') ?>" enctype="multipart/form-data" id="editProfileForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?= esc($user['fullname'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= esc($user['username'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"><?= esc($user['artist_bio'] ?? $user['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?= esc($user['location'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="profile_url" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="profile_url" name="profile_url" accept="image/*">
                        <?php if (!empty($user['profile_url'])): ?>
                            <div class="mt-2">
                                <img src="<?= base_url($user['profile_url']) ?>" alt="Current Profile" class="rounded-circle me-2" style="width: 60px; height: 60px; object-fit: cover;">
                                <small class="text-muted">Current profile image</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveProfileBtn">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.profile-header {
    background: #fff;
    border-bottom: 1px solid #e1e8ed;
}

.cover-photo {
    background-size: cover;
    background-position: center;
}

.profile-info {
    padding: 20px 0;
}

.action-buttons {
    z-index: 10;
}

.stats .stat {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.nav-tabs .nav-link {
    color: #657786;
    border: none;
    padding: 15px 20px;
}

.nav-tabs .nav-link.active {
    color: #1da1f2;
    border-bottom: 2px solid #1da1f2;
    background: none;
}

.nav-tabs .nav-link:hover {
    border: none;
    color: #1da1f2;
}

.card {
    border: 1px solid #e1e8ed;
    border-radius: 15px;
    transition: box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .action-buttons {
        position: static !important;
        margin-top: 20px;
    }
    
    .profile-picture {
        position: static !important;
        margin-bottom: 20px;
    }
}
</style>

<script>
// JavaScript untuk debugging dan memastikan fungsionalitas
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing components...');
    
    // Test modal functionality
    const editProfileModal = document.getElementById('editProfileModal');
    if (editProfileModal) {
        editProfileModal.addEventListener('show.bs.modal', function (event) {
            console.log('Modal is about to show');
        });
        
        editProfileModal.addEventListener('shown.bs.modal', function (event) {
            console.log('Modal is now visible');
        });
    }
    
    // Test tab functionality
    const tabButtons = document.querySelectorAll('#profileTabs button[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Tab clicked:', this.getAttribute('data-bs-target'));
        });
        
        button.addEventListener('shown.bs.tab', function (event) {
            console.log('Tab shown:', event.target.getAttribute('data-bs-target'));
        });
    });
    
    // Form validation
    const editForm = document.getElementById('editProfileForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            console.log('Form submitted');
            // Add form validation here if needed
        });
    }
});

// Additional function to manually trigger modal (for debugging)
function openEditModal() {
    const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    modal.show();
}

// Additional function to manually switch tabs (for debugging)
function switchToTab(tabId) {
    const tab = new bootstrap.Tab(document.querySelector(`#${tabId}-tab`));
    tab.show();
}
</script>

<?= $this->endSection(); ?>