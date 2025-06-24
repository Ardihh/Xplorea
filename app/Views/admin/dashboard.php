<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>
Admin Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-clock"></i> Last updated: <?= date('d M Y, H:i') ?>
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

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_users ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Artworks Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Artworks
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($total_artworks ?? 0) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-image fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artworks') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-image"></i> Manage Artworks
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Artists Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Artists
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($total_artists) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-palette fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artists') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-palette"></i> Manage Artists
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Approvals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($pending_approvals) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artists/approvals') ?>" class="btn btn-warning btn-sm text-white">
                            <i class="fas fa-eye"></i> Review Pending
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Artists Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Approved Artists
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($approved_artists) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artists?status=approved') ?>" class="btn btn-info btn-sm text-white">
                            <i class="fas fa-check-circle"></i> View Approved
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Events Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Events
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($total_events ?? 0) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/events') ?>" class="btn btn-danger btn-sm">
                            <i class="fas fa-calendar-alt"></i> Manage Events
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Artworks Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved Artworks
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $approved_artworks ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paint-brush fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artworks?status=approved') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-paint-brush"></i> View Approved
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Artworks Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Artworks
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pending_artworks ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artworks?status=pending') ?>" class="btn btn-warning btn-sm text-white">
                            <i class="fas fa-hourglass-half"></i> Review Pending
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Artworks Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected Artworks
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $rejected_artworks ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin/artworks?status=rejected') ?>" class="btn btn-danger btn-sm">
                            <i class="fas fa-times-circle"></i> View Rejected
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row">
        <!-- Conversion Rate Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Artist Conversion Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($conversion_rate, 1) ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Artworks per Artist -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Avg Artworks/Artist
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($avg_artworks, 1) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Rate Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Artwork Approval Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($artwork_approval_rate, 1) ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Users This Month -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                New Users This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $new_users_month ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2) !important;
}

.btn-block {
    display: block;
    width: 100%;
}

.progress {
    background-color: #f8f9fc;
}

.card-footer {
    padding: 0.75rem 1.25rem;
    background-color: rgba(0, 0, 0, 0.03);
    border-top: 1px solid rgba(0, 0, 0, 0.125);
}

.chart-area {
    position: relative;
    height: 20rem;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 15rem;
    width: 100%;
}

.dropdown-menu {
    font-size: 0.875rem;
}

.dropdown-header {
    font-weight: bold;
    color: #5a5c69;
}

/* Animation for cards */
.card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger animation for cards */
.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
.card:nth-child(5) { animation-delay: 0.5s; }
.card:nth-child(6) { animation-delay: 0.6s; }
.card:nth-child(7) { animation-delay: 0.7s; }
.card:nth-child(8) { animation-delay: 0.8s; }

/* Chart container improvements */
.chart-area, .chart-pie {
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    border-radius: 8px;
    padding: 10px;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .chart-area {
        height: 15rem;
    }
    
    .chart-pie {
        height: 12rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Button hover effects */
.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover,
.btn-outline-info:hover,
.btn-outline-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Loading animation */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Print styles */
@media print {
    .btn, .dropdown, .card-footer {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .chart-area, .chart-pie {
        height: 300px !important;
    }
}

/* Notification styles */
.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74a3b;
    color: white;
    border-radius: 50%;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    min-width: 1.5rem;
    text-align: center;
}

/* Card hover effects for better UX */
.card:hover .card-header {
    background-color: rgba(78, 115, 223, 0.05);
    transition: background-color 0.3s ease;
}

/* Responsive chart improvements */
@media (max-width: 576px) {
    .chart-area {
        height: 12rem;
    }
    
    .chart-pie {
        height: 10rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .h5 {
        font-size: 1.1rem;
    }
    
    .text-xs {
        font-size: 0.7rem;
    }
}
</style>

<?= $this->endSection() ?>