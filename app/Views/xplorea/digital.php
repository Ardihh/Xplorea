<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="wrapper">
    <div class="header"><a href="<?= base_url('xplorea/marketplace'); ?>">Show All Artworks</a></div>
    <div class="main">
        <div class="category">
            <p>Browse Artwork</p>
            <div class="category1">
                <ul>
                    <li class="judul">Original Artwork</li>
                    <li><a href="<?= base_url('xplorea/paintings'); ?>">Paintings</a></li>
                    <li><a href="<?= base_url('xplorea/drawings'); ?>">Drawings</a></li>
                    <li><a href="<?= base_url('xplorea/collage'); ?>">Collage</a></li>
                    <li><a href="<?= base_url('xplorea/mixed-media'); ?>">Mixed Media</a></li>
                    <li><a href="<?= base_url('xplorea/sculpture'); ?>">Sculpture</a></li>
                </ul>
            </div>
            <div class="category2">
                <ul>
                    <li class="judul">Limited Edition Prints</li>
                    <li><a href="<?= base_url('xplorea/photography'); ?>">Photography</a></li>
                    <li><a href="<?= base_url('xplorea/printmaking'); ?>">Printmaking</a></li>
                    <li><a href="<?= base_url('xplorea/digital'); ?>">Digital</a></li>
                </ul>
            </div>
            <div class="category3">
                <ul>
                    <li class="judul">Subjects</li>
                    <li><a href="<?= base_url('xplorea/abstracts'); ?>">Abstracts</a></li>
                    <li><a href="<?= base_url('xplorea/animals-birds'); ?>">Animals & Birds</a></li>
                    <li><a href="<?= base_url('xplorea/cityscapes'); ?>">Cityscapes</a></li>
                    <li><a href="<?= base_url('xplorea/figurative'); ?>">Figurative</a></li>
                    <li><a href="<?= base_url('xplorea/flowers'); ?>">Flowers</a></li>
                    <li><a href="<?= base_url('xplorea/landscaped'); ?>">Landscaped</a></li>
                    <li><a href="<?= base_url('xplorea/seascapes'); ?>">Seascapes</a></li>
                    <li><a href="<?= base_url('xplorea/still-life'); ?>">Still Life</a></li>
                </ul>
            </div>
            <div class="category3">
                <ul>
                    <li class="judul">Price</li>
                    <li><a href="<?= base_url('xplorea/price/50000-150000'); ?>">Rp. 50.000 - Rp. 150.000</a></li>
                    <li><a href="<?= base_url('xplorea/price/150000-500000'); ?>">Rp. 150.000 - Rp. 500.000</a></li>
                    <li><a href="<?= base_url('xplorea/price/500000-1000000'); ?>">Rp. 500.000 - Rp. 1.000.000</a></li>
                    <li><a href="<?= base_url('xplorea/price/1000000-1500000'); ?>">Rp. 1.000.000 - Rp. 1.500.000</a></li>
                    <li><a href="<?= base_url('xplorea/price/1500000-plus'); ?>">Rp. 1.500.000 +</a></li>
                </ul>
            </div>
            <div class="category4">
                <ul>
                    <li class="judul">Size</li>
                    <li><a href="<?= base_url('xplorea/size/small'); ?>">Small (&lt; 16")</a></li>
                    <li><a href="<?= base_url('xplorea/size/medium'); ?>">Medium (16 - 30")</a></li>
                    <li><a href="<?= base_url('xplorea/size/large'); ?>">Large (30 - 48")</a></li>
                    <li><a href="<?= base_url('xplorea/size/extra-large'); ?>">Extra Large (&gt; 48")</a></li>
                </ul>
            </div>
        </div>

        <!-- content -->
        <div class="content">
            <div class="menu-content">
                <ul>
                    <li><a href="<?= base_url('xplorea/marketplace'); ?>"
                            class="buttonm <?= ($current == 'marketplace') ? 'active' : '' ?>">Shop new art
                        <i class="bi bi-chevron-right"></i></a></li>
                    <li><a href="<?= base_url('xplorea/paintings'); ?>"
                            class="buttonm <?= ($current == 'paintings') ? 'active' : '' ?>">All Paintings
                        <i class="bi bi-chevron-right"></i></a></li>
                    <li><a href="<?= base_url('xplorea/drawings'); ?>"
                            class="buttonm <?= ($current == 'drawings') ? 'active' : '' ?>">All Drawings
                        <i class="bi bi-chevron-right"></i></a></li>
                    <li><a href="<?= base_url('xplorea/digital'); ?>"
                            class="buttonm <?= ($current == 'digital') ? 'active' : '' ?>">All Digital Art
                        <i class="bi bi-chevron-right"></i></a></li>
                </ul>
            </div>
        </div>
    </div>


<?= $this->endSection(); ?>