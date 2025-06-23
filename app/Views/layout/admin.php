<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin Dashboard">
    <meta name="author" content="">

    <title><?= $this->renderSection('title') ?> - Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
    
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .main-content {
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebarMenu">
                <div class="position-sticky pt-3">
                    <!-- Brand -->
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-crown"></i> Admin Panel
                        </h4>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>" href="<?= base_url('admin/users') ?>">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(uri_string(), 'admin/artists') !== false ? 'active' : '' ?>" href="<?= base_url('admin/artists') ?>">
                                <i class="fas fa-palette"></i> Artists
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(uri_string(), 'admin/artists/approvals') !== false ? 'active' : '' ?>" href="<?= base_url('admin/artists/approvals') ?>">
                                <i class="fas fa-user-check"></i> Artist Approvals
                                <?php if (isset($pending_approvals) && $pending_approvals > 0): ?>
                                    <span class="badge bg-warning text-dark ms-2"><?= $pending_approvals ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <hr class="sidebar-divider">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/settings') ?>">
                                <i class="fas fa-cog"></i>
                                <span>System Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/reports') ?>">
                                <i class="fas fa-file-alt"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/analytics') ?>">
                                <i class="fas fa-chart-line"></i>
                                <span>Analytics</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="window.adminDashboard.printDashboard()">
                                <i class="fas fa-print"></i>
                                <span>Print Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-item mt-4">
                            <hr class="sidebar-divider">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/') ?>">
                                <i class="fas fa-home"></i> Back to Site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to logout?')">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg topbar mb-4 static-top">
                    <div class="container-fluid">
                        <!-- Sidebar toggle (for mobile) -->
                        <button class="btn btn-link d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <!-- Page Info -->
                        <div class="d-none d-sm-inline-block text-muted">
                            <small>
                                <i class="fas fa-user"></i> Welcome, <?= session('username') ?? 'Admin' ?>
                            </small>
                        </div>

                        <!-- User Info -->
                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle fa-fw"></i>
                                    <?= session('username') ?? 'Admin' ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>">
                                        <i class="fas fa-user fa-sm fa-fw me-2"></i> Profile
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>">
                                        <i class="fas fa-cogs fa-sm fa-fw me-2"></i> Settings
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to logout?')">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i> Logout
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="main-content">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- atau untuk Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Confirm delete actions
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-delete') || e.target.closest('.btn-delete')) {
                if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>