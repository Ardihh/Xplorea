<?php
$session = session();

$request = \Config\Services::request();
$openSidebar = $request->getGet('sidebar') === 'open';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-3" style="border-radius: 0 0 2rem 2rem;">
    <div class="container">
        <a class="navbar-brand fs-2" href="<?= base_url(); ?>">Xploréa</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav justify-content-center w-100 gap-3">
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == '' ? 'active fw-semibold' : '' ?>" href="<?= base_url(); ?>">HOME</a>
                </li>
                <li class="nav-item dropdown position-relative">
                    <a class="nav-link fs-6 dropdown-toggle" href="#" id="artistToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ARTISTS
                    </a>
                    <ul class="dropdown-menu shadow-lg rounded-4 p-4 border-0">
                        <h6 class="dropdown-header">Explore Artists</h6>
                        <li><a class="dropdown-item" href="artist-page.html?section=popular-artist">Popular Artists</a></li>
                        <li><a class="dropdown-item" href="artist-page.html?section=new-artist">New Artists</a></li>
                        <li><a class="dropdown-item" href="artist-page.html?section=artist-a-z">Artists A-Z</a></li>
                        <li><a class="dropdown-item" href="artist-page.html?section=about-artist">About Our Artists</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <h6 class="dropdown-header">Artist Features</h6>
                        <li><a class="dropdown-item" href="#">Artists of the Month</a></li>
                        <li><a class="dropdown-item" href="#">Featured Artists</a></li>
                        <li><a class="dropdown-item" href="#">Artists Interviews</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == 'events' ? 'active fw-semibold' : '' ?>" href="<?= base_url('events'); ?>">EVENTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == 'xplorea/marketplace' ? 'active fw-semibold' : '' ?>" href="<?= base_url('xplorea/marketplace'); ?>">MARKETPLACE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == 'xplorea/community' ? 'active fw-semibold' : '' ?>" href="<?= base_url('xplorea/community'); ?>">COMMUNITY</a>
                </li>
                <!-- tampilkan jika user adalah artist -->
                <?php if (session()->get('is_artist')): ?>
                    <li class="nav-item">
                        <a class="nav-link fs-6 <?= uri_string() == 'artist/events' ? 'active fw-semibold' : '' ?>" href="<?= base_url('artist/events'); ?>">MY EVENTS</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="login d-flex justify-content-end gap-3">
            <?php
            // Hitung total quantity item di keranjang
            $cartSession = session()->get('cart') ?? [];
            $cartItems = [];
            $cartCount = 0;

            $productModel = new \App\Models\ProductModel();
            foreach ($cartSession as $item) {
                $cartCount += (int) ($item['quantity'] ?? 0);
            }
            ?>
            <button type="button" class="btn position-relative" id="cartToggle">
                <i class="bi bi-cart fs-2"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small"><?= $cartCount ?></span>
                <?php endif; ?>
            </button>

            <?php if ($session->get('logged_in')): ?>
                <!-- Profile Dropdown - Fixed Version -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle p-0 d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                        <i class="bi bi-person-circle fs-2"></i>
                    </button>

                    <?php
                    $is_artist = (int) session()->get('is_artist');
                    $artist_approved = (int) session()->get('artist_profile_approved');
                    ?>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 p-3" aria-labelledby="userDropdown" style="min-width: 230px; font-size: 0.5rem;">
                        <?php if (!$is_artist): ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('xplorea/becomeartist'); ?>" style="font-size:1.3rem;">
                                    <i class="bi bi-brush me-2"></i> Become Artist
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($is_artist === 1 && $artist_approved === 1): ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('artist/products'); ?>" style="font-size:1.3rem;">
                                    <i class="bi bi-plus-square me-2 text-success"></i> Add Product
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('artist/orders'); ?>" style="font-size:1.3rem;">
                                    <i class="bi bi-bag-check me-2 text-primary"></i> Manage Orders
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Admin Dashboard Link -->
                        <?php if (session()->get('is_admin')): ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/dashboard'); ?>" style="font-size:1.3rem;">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i> Admin Dashboard
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        <?php endif; ?>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('my-bookings'); ?>" style="font-size:1.3rem;">
                                <i class="bi bi-ticket-perforated me-2 text-primary"></i> My Bookings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('order-history'); ?>" style="font-size:1.3rem;">
                                <i class="bi bi-clock-history me-2 text-warning"></i> Order History
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('xplorea/profile'); ?>" style="font-size:1.3rem;">
                                <i class="bi bi-person-circle me-2 text-secondary"></i> Profile
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('logout'); ?>" style="font-size:1.3rem;">
                                <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <button type="button" class="btn" id="loginToggle">
                    <i class="bi bi-person-circle fs-2"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Cart Expand -->
    <?php
    $session = session();
    $cartItems = $session->get('cart') ?? [];

    $productModel = new \App\Models\ProductModel();
    ?>
    <div class="cart-expand position-fixed top-0 end-0 bg-white shadow-lg p-4 hidden" id="cartExpand"
        style="width: 420px; height: 100vh; z-index: 1050;">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4" style="width: 100%;">
            <h2 class="fw-bold mb-0">CART</h2>
            <button class="btn btn-light rounded-circle shadow-sm" id="closeCart">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <?php
        $cartSession = session()->get('cart') ?? [];
        $cartItems = [];

        $productModel = new \App\Models\ProductModel();

        foreach ($cartSession as $item) {
            $product = $productModel->find($item['product_id']);
            if ($product) {
                $cartItems[] = [
                    'id' => $product['id'],
                    'image' => base_url('uploads/products/' . $product['image_url']),
                    'title' => $product['title'],
                    'desc' => $product['description'],
                    'price' => $product['base_price'],
                    'quantity' => $item['quantity'],
                ];
            }
        }
        ?>

        <div class="cart-expand-content overflow-auto mb-4" style="height: 65vh; width:100%;" id="cart-sidebar">
            <?php if (empty($cartItems)): ?>
                <div class="text-center py-5 text-muted">
                    Your cart is empty.
                    <?php
                    $subtotal = 0;
                    ?>
                </div>
            <?php else: ?>
                <?php
                $subtotal = 0;
                foreach ($cartItems as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }
                ?>

                <?php foreach ($cartItems as $item): ?>
                    <div class="border-bottom pb-3 mb-3 d-flex cart-item">
                        <img src="<?= esc($item['image']) ?>" class="me-3 rounded" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="fw-bold mb-1"><?= esc($item['title']) ?></div>
                            <div class="text-muted small mb-2"><?= esc($item['desc']) ?></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="quantity-control d-flex align-items-center border">
                                    <button type="button" class="btn btn-decrease"
                                        data-product-id="<?= $item['id'] ?>"
                                        data-size-id="<?= $item['size_id'] ?? '' ?>"
                                        data-frame-id="<?= $item['frame_id'] ?? '' ?>">
                                        -
                                    </button>

                                    <div class="px-3" id="quantity-<?= $item['id'] ?>-<?= $item['size_id'] ?? '' ?>-<?= $item['frame_id'] ?? '' ?>"><?= esc($item['quantity']) ?></div>

                                    <button type="button" class="btn btn-increase"
                                        data-product-id="<?= $item['id'] ?>"
                                        data-size-id="<?= $item['size_id'] ?? '' ?>"
                                        data-frame-id="<?= $item['frame_id'] ?? '' ?>">
                                        +
                                    </button>
                                </div>

                                <div style="font-size: 0.8rem;">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                            </div>
                        </div>
                        <div class="ms-3">
                            <a href="/cart/remove/<?= $item['id'] ?>" class="btn btn-danger btn-sm">&times;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Subtotal & Checkout -->
        <div class="border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="small fw-bold text-uppercase">Subtotal</span>
                <span class="fw-bold fs-5">Rp <?= number_format($subtotal, 2) ?></span>
            </div>
            <p class="small text-muted mb-3">Shipping, taxes, and discount codes calculated at checkout.</p>
            <a href="<?= base_url('checkout'); ?>"
                class="btn btn-dark w-100 py-3 text-uppercase fw-bold rounded-0"
                style="letter-spacing: 2px;">Check Out</a>
        </div>
    </div>

    <?php if (!$session->get('logged_in')): ?>
        <div class="login-expand position-absolute end-0 top-100 mt-3 bg-white shadow-lg p-4 rounded-4 hidden" id="loginExpand" style="width: 35vw; z-index: 1050;">
            <div class="text-center mb-3">
                <h4 class="fw-bold">Welcome to Xploréa</h4>
                <p class="small text-muted">Join a community of artists and art lovers. Discover, share, and create amazing art.</p>
            </div>

            <div class="d-flex flex-row gap-3" style="width: 100%;">
                <a href="<?= base_url('xplorea/login'); ?>" class="btn btn-outline-dark fw-semibold w-100 rounded-pill border-2">
                    <i class="bi bi-person me-2"></i> Log In
                </a>
                <a href="<?= base_url('xplorea/becomeartist'); ?>" class="btn btn-outline-dark fw-semibold w-100 rounded-pill border-2">
                    <i class="bi bi-vector-pen me-2"></i> Become an Artist
                </a>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Overlay -->
    <div class="overlay hidden position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" id="overlay" style="z-index: 1040;"></div>

    <?php if ($openSidebar): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pastikan fungsi openCartSidebar() udah ada di JS kamu
                document.getElementById('cartExpand').classList.remove('hidden');
                document.getElementById('overlay').classList.remove('hidden');
            });
        </script>
    <?php endif; ?>
</nav>

<script>
// JavaScript untuk memastikan dropdown berfungsi dengan baik
document.addEventListener('DOMContentLoaded', function() {
    console.log('Navbar loaded, initializing dropdowns...');
    
    // Cart quantity update functionality
    function updateCartQuantity(productId, sizeId, frameId, delta) {
        console.log('Updating cart quantity:', { productId, sizeId, frameId, delta });
        
        // Show loading state
        const quantityElement = document.getElementById(`quantity-${productId}-${sizeId || ''}-${frameId || ''}`);
        if (quantityElement) {
            quantityElement.textContent = '...';
        }
        
        fetch(`<?= base_url('cart/update') ?>/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'delta': delta,
                'size_id': sizeId || '',
                'frame_id': frameId || ''
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Cart update response:', data);
            
            if (data.success) {
                // Update quantity display
                if (quantityElement) {
                    quantityElement.textContent = data.new_quantity;
                }
                
                // Update cart count in navbar
                const cartBadge = document.querySelector('#cartToggle .badge');
                if (cartBadge) {
                    if (data.cart_count > 0) {
                        cartBadge.textContent = data.cart_count;
                        cartBadge.style.display = 'block';
                    } else {
                        cartBadge.style.display = 'none';
                    }
                }
                
                // If item was removed, reload cart sidebar
                if (data.removed) {
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            } else {
                // Restore original quantity on error
                if (quantityElement) {
                    quantityElement.textContent = data.original_quantity || '1';
                }
                alert(data.message || 'Failed to update quantity');
            }
        })
        .catch(error => {
            console.error('Error updating cart quantity:', error);
            // Restore original quantity on error
            if (quantityElement) {
                quantityElement.textContent = '1';
            }
            alert('Failed to update quantity. Please try again.');
        });
    }
    
    // Add event listeners for quantity buttons using event delegation
    document.addEventListener('click', function(e) {
        console.log('Click event target:', e.target);
        console.log('Click event target classes:', e.target.className);
        
        if (e.target.classList.contains('btn-decrease')) {
            e.preventDefault();
            e.stopPropagation();
            const productId = e.target.getAttribute('data-product-id');
            const sizeId = e.target.getAttribute('data-size-id');
            const frameId = e.target.getAttribute('data-frame-id');
            console.log('Decrease clicked:', { productId, sizeId, frameId });
            updateCartQuantity(productId, sizeId, frameId, -1);
            return false;
        }
        
        if (e.target.classList.contains('btn-increase')) {
            e.preventDefault();
            e.stopPropagation();
            const productId = e.target.getAttribute('data-product-id');
            const sizeId = e.target.getAttribute('data-size-id');
            const frameId = e.target.getAttribute('data-frame-id');
            console.log('Increase clicked:', { productId, sizeId, frameId });
            updateCartQuantity(productId, sizeId, frameId, 1);
            return false;
        }
    });
    
    // Prevent form submission on button clicks
    document.addEventListener('submit', function(e) {
        if (e.target.closest('.quantity-control')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Test dropdown functionality
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        console.log('User dropdown found');
        
        // Event listener untuk debug
        userDropdown.addEventListener('click', function(e) {
            console.log('User dropdown clicked');
            e.stopPropagation();
        });
        
        // Test Bootstrap dropdown events
        userDropdown.addEventListener('show.bs.dropdown', function () {
            console.log('Dropdown is about to show');
        });
        
        userDropdown.addEventListener('shown.bs.dropdown', function () {
            console.log('Dropdown is now visible');
        });
        
        userDropdown.addEventListener('hide.bs.dropdown', function () {
            console.log('Dropdown is about to hide');
        });
    } else {
        console.log('User dropdown not found');
    }
    
    // Test artist dropdown
    const artistDropdown = document.getElementById('artistToggle');
    if (artistDropdown) {
        artistDropdown.addEventListener('click', function(e) {
            console.log('Artist dropdown clicked');
        });
    }
    
    // Manual dropdown toggle function for debugging
    window.toggleUserDropdown = function() {
        const dropdown = new bootstrap.Dropdown(document.getElementById('userDropdown'));
        dropdown.toggle();
    };
    
    // Cart functionality
    const cartToggle = document.getElementById('cartToggle');
    const cartExpand = document.getElementById('cartExpand');
    const closeCart = document.getElementById('closeCart');
    const overlay = document.getElementById('overlay');
    
    if (cartToggle && cartExpand) {
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            cartExpand.classList.remove('hidden');
            overlay.classList.remove('hidden');
            console.log('Cart opened');
        });
    }
    
    if (closeCart) {
        closeCart.addEventListener('click', function() {
            cartExpand.classList.add('hidden');
            overlay.classList.add('hidden');
            console.log('Cart closed');
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            cartExpand.classList.add('hidden');
            overlay.classList.add('hidden');
            console.log('Cart closed via overlay');
        });
    }
    
    // Login toggle functionality
    const loginToggle = document.getElementById('loginToggle');
    const loginExpand = document.getElementById('loginExpand');
    
    if (loginToggle && loginExpand) {
        loginToggle.addEventListener('click', function(e) {
            e.preventDefault();
            loginExpand.classList.toggle('hidden');
            console.log('Login panel toggled');
        });
        
        // Close login panel when clicking outside
        document.addEventListener('click', function(e) {
            if (!loginToggle.contains(e.target) && !loginExpand.contains(e.target)) {
                loginExpand.classList.add('hidden');
            }
        });
    }

    // Improved Hover dropdown functionality
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = userDropdown?.nextElementSibling;
    
    if (userDropdown && dropdownMenu) {
        let dropdownInstance = null;
        let timeoutId = null;
        let isDropdownOpen = false;
        
        // Show dropdown on hover
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
            if (!dropdownInstance) {
                dropdownInstance = new bootstrap.Dropdown(userDropdown);
            }
            if (!isDropdownOpen) {
                dropdownInstance.show();
                isDropdownOpen = true;
            }
        });
        
        // Hide dropdown when mouse leaves button with longer delay
        userDropdown.addEventListener('mouseleave', function() {
            timeoutId = setTimeout(() => {
                if (dropdownInstance && isDropdownOpen) {
                    dropdownInstance.hide();
                    isDropdownOpen = false;
                }
            }, 300); // Increased delay to 300ms
        });
        
        // Keep dropdown open when hovering over menu
        dropdownMenu.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
        });
        
        // Hide dropdown when mouse leaves menu with longer delay
        dropdownMenu.addEventListener('mouseleave', function() {
            timeoutId = setTimeout(() => {
                if (dropdownInstance && isDropdownOpen) {
                    dropdownInstance.hide();
                    isDropdownOpen = false;
                }
            }, 500); // Even longer delay when leaving menu
        });
        
        // Also support click for mobile/touch devices
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            if (!dropdownInstance) {
                dropdownInstance = new bootstrap.Dropdown(userDropdown);
            }
            dropdownInstance.toggle();
            isDropdownOpen = !isDropdownOpen;
        });
        
        // Listen for Bootstrap dropdown events to sync state
        userDropdown.addEventListener('hidden.bs.dropdown', function() {
            isDropdownOpen = false;
        });
        
        userDropdown.addEventListener('shown.bs.dropdown', function() {
            isDropdownOpen = true;
        });
        
        console.log('Improved hover dropdown initialized');
    } else {
        console.error('Dropdown elements not found');
    }
    
    // Test if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap is available');
    } else {
        console.error('Bootstrap is not available');
    }
});
</script>

<style>
/* Additional CSS untuk memastikan dropdown berfungsi dengan baik */
.dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: auto !important;
    right: 0 !important;
    z-index: 1050 !important;
    display: none;
    min-width: 10rem;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    font-size: 0.875rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.375rem;
}

.dropdown-menu.show {
    display: block !important;
}

.dropdown-toggle::after {
    display: none !important;
}

.navbar .dropdown:hover .dropdown-menu {
    display: block;
}

/* Ensure proper z-index hierarchy */
.navbar {
    z-index: 1030;
}

.dropdown-menu {
    z-index: 1050;
}

.cart-expand {
    z-index: 1055;
}

.login-expand {
    z-index: 1055;
}

.overlay {
    z-index: 1040;
}

/* Fix untuk mobile responsiveness */
@media (max-width: 991.98px) {
    .login-expand {
        width: 90vw !important;
        right: 5vw !important;
    }
}
</style>