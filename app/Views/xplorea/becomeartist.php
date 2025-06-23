<?php
// Cek session login
$session = session();
if (!$session->has('logged_in') || !$session->get('logged_in')) {
    header('Location: ' . base_url('xplorea/login'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
</head>

<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <div class="col-6 py-5 px-5" style="background-color: white;">
                <a href="<?= base_url('/'); ?>" class="btn btn-secondary mb-4">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
                <form action="<?= base_url('auth/upgradeToArtist'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <!-- input fullname -->
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" aria-describedby="fullname" required>
                    </div>
                    <!-- input bio -->
                    <div class="mb-3">
                        <label for="artist_bio" class="form-label">Bio</label>
                        <input type="text" class="form-control" id="artist_bio" name="artist_bio" aria-describedby="artist_bio" required>
                    </div>
                    <!-- input art categories -->
                    <div class="mb-3">
                        <label class="form-label">Art Categories</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="art_categories[]" id="painting" value="Painting">
                            <label class="form-check-label" for="painting">Painting</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="art_categories[]" id="digital_art" value="Digital Art">
                            <label class="form-check-label" for="digital_art">Digital Art</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="art_categories[]" id="photography" value="Photography">
                            <label class="form-check-label" for="photography">Photography</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="art_categories[]" id="sculpture" value="Sculpture">
                            <label class="form-check-label" for="sculpture">Sculpture</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="art_categories[]" id="other" value="Other">
                            <label class="form-check-label" for="other">Other</label>
                        </div>
                    </div>
                    <!-- input location -->
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" aria-describedby="location" required>
                    </div>
                    <!-- input artwork -->
                    <div class="mb-3">
                        <label for="artist_website" class="form-label">Artwork URL</label>
                        <input type="text" class="form-control" id="artist_website" name="artist_website" aria-describedby="artist_website">
                    </div>
                    <!-- input phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" aria-describedby="phone" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                By submitting information, you agree to the <a href="#">Terms of Use</a> and <a href="#">Artist Agreement</a> of Exploréa, and acknowledge our <a href="#">Privacy Policy</a>, which explains how we handle personal information and your privacy rights.
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                </form>
            </div>
            <div class="col-6 d-flex align-items-center justify-content-center"
                style="background: url('<?= base_url('assets/Vector.png'); ?>') no-repeat center center; background-size: cover;">

                <img src="<?= base_url('assets/logo1.png'); ?>" alt="Logo" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</body>

</html>