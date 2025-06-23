<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5" style="margin-top: 20vh;">
    <?php if ($section == 'about'): ?>
        <div>
            <h2 class="text-center mb-5">About Us</h2>
            <p>Xploréa is a digital art platform dedicated to <b>connecting creators and art enthusiasts</b> across the world. We believe that art should be accessible, appreciated, and celebrated — not limited by geography or barriers.

Born from a passion for creativity and community, Xploréa empowers artists to showcase their work, share their stories, and grow their audience in a supportive and inclusive environment. Whether you're an emerging artist looking for your first exposure, or a curious collector seeking meaningful works, Xploréa brings the art world closer to you.

Through curated exhibitions, marketplace features, artist profiles, and educational content, we aim to support artistic journeys and enrich cultural experiences. Our mission is simple: to make art exploration inspiring, collaborative, and borderless.

Join us as we build a global creative space — one artwork at a time.</p>
<p class="fw-bold">Xploréa details:</p>
<ul class="list-unstyled ps-3 mb-2">
    <li class="mb-2" >Name: Xploréa</li>
    <li class="mb-2">Address: Jl. Raya Kebon Jeruk No. 123, Jakarta</li>
    <li class="mb-2">Phone: +62 812 3456 7890</li>
    <li class="mb-2">Email: info@xplorea.com</li>
</ul>
<section id="cta">
        <div class="container d-flex  align-items-center mt-5">
            <div class="row">
                <div class="col">
                    <div class="d-flex justify-content-center align-items-start flex-column">
                        <h1 class="fs-4">Ready to Explore Art in a New Way?</h1>
                        <div class="content"></div>
                            <p class="mb-0 ps-3">Discover art you'll love, and show off your own too!</p>
                            <p class="mb-0 ps-3">Your creative adventure starts here.</p>
                            <p class="mb-0 ps-3">Scroll, support, and shine as a creator.</p>
                        </div>
                        <div class="button d-flex gap-3 mt-3" style="width: 100%;">
                            <a href="<?= base_url('xplorea/marketplace') ?>" class="btn btn-light rounded-pill shadow mt-2 " style="width: 50%;">Start Exploring <i class="bi bi-search"></i></a>
                            <a href="<?= base_url('xplorea/becomeartist') ?>" class="btn btn-light rounded-pill shadow mt-2 " style="width: 50%;">Become a Creator <i class="bi bi-vector-pen"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
    <?php elseif ($section == 'careers'): ?>
        <div>
            <h2>Careers</h2>
            <p>Lowongan dan karir di perusahaan...</p>
        </div>
    <?php elseif ($section == 'press'): ?>
        <div>
            <h2>Press</h2>
            <p>Informasi untuk media dan pers...</p>
        </div>
    <?php elseif ($section == 'contract'): ?>
        <div>
            <h2>Contract</h2>
            <p>Informasi kontrak dan kerjasama...</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?> 