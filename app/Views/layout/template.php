<?php
// Cek session login
$session = session();
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Xplorea</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
  <link href='https://fonts.googleapis.com/css?family=IBM Plex Sans' rel='stylesheet'>
  <link rel="stylesheet" href="<?= base_url('css/marketplace.css'); ?>">
  <!-- <link rel="stylesheet" href="<?= base_url('css/cart.css'); ?>"> -->
  <link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
  <?= $this->include('layout/navbar'); ?>
  <?= $this->renderSection('content'); ?>
  <footer class="bg-black text-white py-5 mt-auto" style=" width: 100%;">
    <div class="container">
      <div class="row">
        <div class="col">
          <ul class="list-unstyled">
            <li>COMPANY</li>
            <li><a href="<?= base_url('menu/about') ?>" class="text-decoration-none text-white">About Us</a></li>
            <li><a href="<?= base_url('menu/careers') ?>" class="text-decoration-none text-white">Careers</a></li>
            <li><a href="<?= base_url('menu/press') ?>" class="text-decoration-none text-white">Press</a></li>
            <li><a href="<?= base_url('menu/contract') ?>" class="text-decoration-none text-white">Contract</a></li>
          </ul>
        </div>
        <div class="col">
          <ul class="list-unstyled">
            <li>SUPPORT</li>
            <li><a href="<?= base_url('support/help') ?>" class="text-decoration-none text-white">Help Center</a></li>
            <li><a href="<?= base_url('support/faqs') ?>" class="text-decoration-none text-white">FAQs</a></li>
            <li><a href="<?= base_url('support/tutorials') ?>" class="text-decoration-none text-white">Tutorials</a></li>
            <li><a href="<?= base_url('support/terms') ?>" class="text-decoration-none text-white">Terms of Service</a></li>
          </ul>
        </div>
        <div class="col">
          <ul class="list-unstyled">
            <li>SOCIAL</li>
            <ul class="list-unstyled d-flex flex-row gap-4">
              <li><a href="#" class="fs-4 text-white"><i class="bi bi-instagram"></i></a></li>
              <li><a href="#" class="fs-4 text-white"><i class="bi bi-facebook"></i></a></li>
              <li><a href="#" class="fs-4 text-white"><i class="bi bi-envelope"></i></a></li>
            </ul>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24G7c6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="<?= base_url('js/script.js'); ?>"></script>
</body>

</html>