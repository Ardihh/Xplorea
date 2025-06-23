<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="wrapper">
    <div class="header"><a href="<?= base_url('xplorea/marketplace'); ?>">Show All Artworks</a></div>
    <div class="main">

        <!-- Sidebar Kategori -->
        <div class="category">
            <p>Browse Artwork</p>

            <div class="category1">
                <ul class="list-unstyled" style="font-size: 0.9rem;">
                    <li class="judul">Original Artwork</li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/paintings'); ?>">Paintings</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/drawings'); ?>">Drawings</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/collage'); ?>">Collage</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/mixed-media'); ?>">Mixed Media</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/sculpture'); ?>">Sculpture</a></li>
                </ul>
            </div>

            <div class="category2">
                <ul class="list-unstyled" style="font-size: 0.9rem;">
                    <li class="judul">Limited Edition Prints</li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/photography'); ?>">Photography</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/printmaking'); ?>">Printmaking</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/digital'); ?>">Digital</a></li>
                </ul>
            </div>

            <div class="category3">
                <ul class="list-unstyled" style="font-size: 0.9rem;">
                    <li class="judul">Subjects</li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/abstracts'); ?>">Abstracts</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/animals-birds'); ?>">Animals & Birds</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/cityscapes'); ?>">Cityscapes</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/figurative'); ?>">Figurative</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/flowers'); ?>">Flowers</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/landscaped'); ?>">Landscaped</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/seascapes'); ?>">Seascapes</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/still-life'); ?>">Still Life</a></li>
                </ul>
            </div>

            <div class="category3">
                <ul class="list-unstyled" style="font-size: 0.9rem;">
                    <li class="judul">Price</li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/price/50000-150000'); ?>">Rp. 50.000 - Rp. 150.000</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/price/150000-500000'); ?>">Rp. 150.000 - Rp. 500.000</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/price/500000-1000000'); ?>">Rp. 500.000 - Rp. 1.000.000</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/price/1000000-1500000'); ?>">Rp. 1.000.000 - Rp. 1.500.000</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/price/1500000-plus'); ?>">Rp. 1.500.000 +</a></li>
                </ul>
            </div>

            <div class="category4">
                <ul class="list-unstyled" style="font-size: 0.9rem;">
                    <li class="judul">Size</li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/size/small'); ?>">Small (&lt; 16")</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/size/medium'); ?>">Medium (16 - 30")</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/size/large'); ?>">Large (30 - 48")</a></li>
                    <li class="ms-4"><a href="<?= base_url('xplorea/size/extra-large'); ?>">Extra Large (&gt; 48")</a></li>
                </ul>
            </div>
        </div>

        <!-- Content -->
        <div class="content">

            <div class="menu-content">
                <ul>
                    <li><a href="<?= base_url('xplorea/marketplace'); ?>" class="buttonm <?= ($current == 'marketplace') ? 'active' : '' ?>">Shop new art <i class="bi bi-chevron-right"></i></a></li>
                    <li><a href="<?= base_url('xplorea/paintings'); ?>" class="buttonm <?= ($current == 'paintings') ? 'active' : '' ?>">All Paintings <i class="bi bi-chevron-right"></i></a></li>
                    <li><a href="<?= base_url('xplorea/drawings'); ?>" class="buttonm <?= ($current == 'drawings') ? 'active' : '' ?>">All Drawings <i class="bi bi-chevron-right"></i></a></li>
                    <li><a href="<?= base_url('xplorea/digital'); ?>" class="buttonm <?= ($current == 'digital') ? 'active' : '' ?>">All Digital Art <i class="bi bi-chevron-right"></i></a></li>
                </ul>
            </div>

            <!-- Product Display -->
            <?php if (!empty($products)): ?>
                <div class="container">
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-4">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <a href="<?= base_url('cart?product_id=' . $product['id']) ?>" class="text-decoration-none text-center d-block">
                    <div class="product-item">
                        <div class="product-image mb-2" style="width: 100%; aspect-ratio: 4/5; overflow: hidden;">
                            <img
                                src="<?= esc($product['image_url']) ?>"
                                alt="<?= esc($product['title']) ?>"
                                class="w-100 h-100"
                                style="object-fit: scale-down;">
                        </div>
                        <div class="product-info">
                            <h6 class="fw-semibold" style="font-size: 0.9rem; color: #1d2a34;">
                                <?= esc($product['title']) ?>
                            </h6>
                            <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                from Rp.<?= number_format($product['base_price'], 2) ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

            <?php else: ?>
                <div class="container">
                    <p>No products found.</p>
                </div>
            <?php endif; ?>

            <!-- Discount Offer -->
            <div class="discount d-flex justify-content-end me-5 rounded-5 shadow-sm p-5 my-5" style="background-image: url('https://wallpaperaccess.com/full/53929.jpg'); background-size: cover; background-position: center; position: relative;">
                <div style="position: absolute; inset: 0; background: rgba(255,255,255,0.3); border-radius: 2rem; z-index: 1;"></div>
                <a href="<?= base_url('xplorea/discount'); ?>" class="btn btn-light border rounded-pill px-4 d-flex flex-row justify-content-between" style="width: 13vw; position: relative; z-index: 2;">View offer <i class="bi bi-chevron-right"></i></a>
            </div>

        </div>
    </div>
</div>

<style>
.product-link {
    display: block;
    transition: transform 0.2s ease;
}

.product-link:hover {
    transform: translateY(-5px);
}

.product-item {
    text-align: center;
}

.product-image {
    overflow: hidden;
}

.product-image img {
    transition: transform 0.3s ease;
}

.product-link:hover .product-image img {
    transform: scale(1.05);
}

.product-title {
    line-height: 1.2;
    min-height: 2.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    margin-top: 0.5rem;
}
</style>

<?= $this->endSection(); ?>