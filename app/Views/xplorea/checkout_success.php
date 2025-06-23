<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<script>
    // Redirect ke order history dengan flash message
    window.location.href = '<?= base_url('order-history') ?>';
</script>

<?= $this->endSection(); ?>
