<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php $product = $product ?? null; ?>

<div class="container mb-5" style="margin-top: 20vh;">
    <?php if ($product): ?>
        <!-- Back to Marketplace Button -->
        <div class="mb-4">
            <a href="<?= base_url('xplorea/marketplace'); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Marketplace
            </a>
        </div>

        <form id="addToCartForm" action="<?= base_url('cart/add/' . $product['id']) ?>" method="post">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="redirect_to" value="<?= current_url() ?>?product_id=<?= $product['id'] ?>&open_cart=1">

            <div class="row">
                <div class="col-5">
                    <img src="<?= base_url('uploads/products/' . $product['image_url']) ?>" alt="banner" class="img-fluid">
                </div>

                <div class="col-7">
                    <div class="atas">
                        <p class="fs-2 fw-bold"><?= esc($product['title']) ?></p>
                        <p>Rp <?= number_format($product['base_price'], 0, ',', '.') ?></p>
                    </div>

                    <div class="tengah">
                        <!-- SIZE -->
                        <div class="size">
                            <p class="my-2">SIZE</p>
                            <div class="size-btn mb-3" style="gap:2;">
                                <?php foreach ($sizes as $index => $size): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="size_id" id="size_<?= $size['id'] ?>" value="<?= $size['id'] ?>" <?= $index == 0 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="size_<?= $size['id'] ?>">
                                            <?= $size['size_description'] ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- FRAME -->
                        <div class="frame">
                            <p class="my-2">FRAME</p>
                            <div class="size-btn mb-3">
                                <?php foreach ($frames as $index => $frame): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="frame_id" id="frame_<?= $frame['id'] ?>" value="<?= $frame['id'] ?>" <?= $index == 0 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="frame_<?= $frame['id'] ?>">
                                            <?= $frame['frame_name'] ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                        <!-- QUANTITY -->
                        <div class="quantity">
                            <p class="my-2">QUANTITY</p>
                            <input type="number" name="quantity" id="quantityInput" class="form-control mb-3" value="1" min="1" style="width: 150px;">
                        </div>
                    </div>

                    <div class="button">
                        <div class="d-grid gap-2">
                            <?php if ($product['artist_id'] != session()->get('user_id')): ?>
                                <button type="submit" id="addToCartBtn" class="btn btn-dark rounded-0 fw-semibold">ADD TO CART</button>
                            <?php else: ?>
                                <div class="alert alert-warning mt-3">You cannot order your own product.</div>
                            <?php endif; ?>
                        </div>
                        <div class="another mt-1 text-center">
                            <a href="#" class="text-decoration-underline text-dark">More payment option</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Recommended Artworks Section -->
        <?php if (!empty($recommendedProducts)): ?>
        <div class="mt-5 pt-5 border-top">
            <h4 class="mb-4 text-center">More from this Artist</h4>
            <div class="row g-4 justify-content-center">
                <?php foreach ($recommendedProducts as $recProduct): ?>
                    <div class="col-md-3 col-lg-2">
                        <a href="<?= base_url('cart?product_id=' . $recProduct['id']) ?>" class="text-decoration-none product-link">
                            <div class="product-item">
                                <div class="product-image">
                                    <img
                                        src="<?= base_url('uploads/products/' . $recProduct['image_url']) ?>"
                                        class="img-fluid"
                                        alt="<?= esc($recProduct['title']) ?>"
                                        style="max-width: 100%; max-height: 150px; width: auto; height: auto; object-fit: contain;">
                                </div>
                                <div class="product-info mt-2">
                                    <h6 class="product-title fw-bold mb-1" style="color: #1D2A34; font-size: 0.9rem;">
                                        <?= esc($recProduct['title']) ?>
                                    </h6>
                                    <p class="product-price mb-0" style="color: #1D2A34;  font-size: 0.85rem;">
                                        Rp <?= number_format($recProduct['base_price'], 0, ',', '.') ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-center mt-5">No product selected.</p>
        <div class="text-center">
            <a href="<?= base_url('xplorea/marketplace'); ?>" class="btn btn-primary">Browse Products</a>
        </div>
    <?php endif; ?>
</div>

<style>
.product-link {
    display: block;
    transition: transform 0.2s ease;
}

.product-link:hover {
    transform: translateY(-3px);
}

.product-item {
    text-align: center;
}

.product-image {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 150px;
    overflow: hidden;
}

.product-image img {
    transition: transform 0.3s ease;
    max-width: 100%;
    max-height: 150px;
    width: auto;
    height: auto;
    object-fit: contain;
}

.product-link:hover .product-image img {
    transform: scale(1.05);
}

.product-title {
    line-height: 1.2;
    height: 2.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    margin-top: 0.25rem;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check URL parameter to open cart sidebar
        const urlParams = new URLSearchParams(window.location.search);
        const openCart = urlParams.get('open_cart');

        if (openCart === '1') {
            openCartSidebar();
        }

        // Handle the ADD TO CART form submission
        const addToCartForm = document.getElementById('addToCartForm');
        const addToCartBtn = document.getElementById('addToCartBtn');

        if (addToCartForm && addToCartBtn) {
            addToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'ADDING...';

                // Submit form normally (will cause page refresh)
                this.submit();
            });
        }
    });

    function openCartSidebar() {
        const cartExpand = document.getElementById('cartExpand');
        const overlay = document.getElementById('overlay');

        if (cartExpand) {
            cartExpand.classList.remove('hidden');
            cartExpand.style.transform = 'translateX(0)';
        }

        if (overlay) {
            overlay.classList.remove('hidden');
        }

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
</script>

<?= $this->endSection(); ?>