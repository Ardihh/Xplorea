<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5" style="margin-top: 20vh;">

    <?php if ($section == 'help'): ?>
        <div>
            <h2>Help Center</h2>
            <p>Welcome to our Help Center. Here you can find guides and resources to assist you.</p>
        </div>
    <?php elseif ($section == 'faqs'): ?>
        <div>
            <h2>Frequently Asked Questions (FAQs)</h2>
            <ul class="list-unstyled">
                <li><strong>General Enquiries</strong></li>
                <li>How do i find the artwork i looking for?</li>
                <li>How do I use the 'Compare' feature and what is the difference between this and a 'Wish List'?</li>
                <li>What should I do if I have a question about a specific artwork?</li>
                <li>I sent a message to an artist but have not had a reply?</li>

                <li class="mt-3"><strong>Ordering</strong></li>
                <li>How do I order?</li>
                <li>I have ordered my artwork, what next?</li>
                <li>How do I tract mu order?</li>
                <li>Can I cancel my order?</li>
                <li>What does my 'order status' mean?</li>

                <li class="mt-3"><strong>Payment Options</strong></li>
                <li>What payment methods do you accept?</li>
                <li>Is your website secure?</li>
                <li>Where can I verify your site's security?</li>

                <li class="mt-3"><strong>Artists</strong></li>
                <li>How do I become an artist?</li>
                <li>How do I list my artwork?</li>
                <li>How do I update my artwork?</li>
                <li>How do I delete my artwork?</li>
                <li>How do I add a new artwork?</li>
            </ul>
        </div>
    <?php elseif ($section == 'tutorials'): ?>
        <div>
            <h2>Tutorials</h2>
            <p>Find step-by-step tutorials for using our platform.</p>
        </div>
    <?php elseif ($section == 'terms'): ?>
        <div>
            <h2>Terms of Service</h2>
            <p>Read our terms and conditions for using this website.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>
