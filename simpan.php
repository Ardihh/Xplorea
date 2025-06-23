
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-3" style="border-radius: 0 0 2rem 2rem;">
    <div class="container">
        <a class="navbar-brand fs-2" href="<?= base_url(); ?>">Xploréa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav justify-content-center w-100">
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == '' ? 'active' : '' ?>" aria-current="page" href="<?= base_url(); ?>">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == '#' ? 'active' : '' ?>" href="#" id="artistToggle">ARTISTS</a>
                    <div class="artist-expand hidden" id="artistExpand">
                        <div class="artist-expand1">
                            <ul>
                                <li id="title">ARTISTS</li>
                                <li><a href="artist-page.html?section=popular-artist">Popular Artists</a></li>
                                <li><a href="artist-page.html?section=new-artist">New Artists</a></li>
                                <li><a href="artist-page.html?section=artist-a-z">Artists A-Z</a></li>
                                <li><a href="artist-page.html?section=about-artist">About our Artists</a></li>
                            </ul>
                        </div>
                        <div class="artist-expand2">
                            <ul>
                                <li id="title">ARTISTS FEATURES</li>
                                <li><a href="">Artists of the Month</a></li>
                                <li><a href="">Featured Artists</a></li>
                                <li><a href="">Artists Interviews</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == '#projects' ? 'active' : '' ?>" href="#projects">EVENTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == 'xplorea/marketplace' ? 'active' : '' ?>" href="<?= base_url('xplorea/marketplace'); ?>">MARKETPLACE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 <?= uri_string() == 'xplorea/community' ? 'active' : '' ?>" href="<?= base_url('xplorea/community'); ?>">COMMUNITY</a>
                </li>
            </ul>
        </div>
        <div class="login d-flex justify-content-end">
            <button type="button" class="btn" id="cartToggle"><i class="bi bi-cart fs-2"></i></button>

            <?php if ($session->get('logged_in')): ?>
                <!-- Tampilan ketika user sudah login -->
<div class="dropdown">
    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle fs-2"></i>
    </button>
    <?php
                $is_artist = (int) session()->get('is_artist');
                $artist_approved = (int) session()->get('artist_profile_approved');
    ?>

    <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 p-3" aria-labelledby="userDropdown" style="min-width: 220px;">

        <?php if (!$is_artist): ?>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('xplorea/becomeartist'); ?>">
                    <i class="bi bi-brush me-2"></i> Become Artist
                </a>
            </li>
        <?php endif; ?>

        <?php if ($is_artist === 1 && $artist_approved === 1): ?>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('artist/products'); ?>">
                    <i class="bi bi-plus-square me-2 text-success"></i> Add Product
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('xplorea/profile'); ?>">
                <i class="bi bi-person-circle me-2 text-secondary"></i> Profile
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('logout'); ?>">
                <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
            </a>
        </li>
    </ul>



</div>
<?php else: ?>
    <!-- Tampilan ketika user belum login -->
    <button type="button" class="btn" id="loginToggle">
        <i class="bi bi-person-circle fs-2"></i>
    </button>
<?php endif; ?>

<!-- dan juga bagian expand login -->
<?php if (!$session->get('logged_in')): ?>
    <div class="login-expand hidden" id="loginExpand">
        <div class="login-expand0">
            <h1>Welcome to Xploréa</h1>
            <p>Join a community of artists and art lovers. Discover, share, and create amazing art.</p>
        </div>
        <div class="login-expand-cta">
            <div class="login-expand1">
                <a href="<?= base_url('xplorea/login'); ?>">Log-In
                    <i class="bi bi-person"></i>
                </a>
            </div>
            <div class="login-expand2">
                <a href="<?= base_url('xplorea/becomeartist'); ?>">Become an Artist <i class="bi bi-vector-pen"></i></a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- expand cart -->
<div class="cart-expand hidden" id="cartExpand">
    <div class="cart-expand-header">
        <h1 class="mt-2">CART</h1>
        <button class="close-cart" id="closeCart"><i class="bi bi-x"></i></button>
    </div>
    <div class="cart-expand-content"></div>
    <div class="cart-expand-bottom">
        <div class="cart-expand-bottom2">
            <div class="cart-total">
                <p>SUBTOTAL</p>
                <p class="totalPrice">Rp.</p>
            </div>
            <div class="cart-checkout">
                <p>Shipping, taxes, and discount codes calculated at checkout</p>
                <a href="<?= base_url('xplorea/checkout'); ?>" class="checkout-button">CHECK OUT</a>
            </div>
        </div>
    </div>
</div>
<!-- Overlay -->
<div class="overlay hidden" id="overlay"></div>
</div>
</nav>