<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5 mb-5" style="margin-top: 20vh;">
    <h2 style="margin-top: 20vh;">My Events</h2>
    <a href="<?= base_url('artist/events/create') ?>" class="btn btn-primary mb-3">Create New Event</a>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($events)): ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= esc($event['title']) ?></td>
                <td><?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?></td>
                <td><?= date('M d, Y H:i', strtotime($event['end_datetime'])) ?></td>
                <td>
                    <span class="badge <?= $event['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $event['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </td>
                <td>
                    <a href="<?= base_url('artist/events/edit/' . $event['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $event['id'] ?>)">Delete</button>
                    <a href="<?= base_url('artist/events/attendees/' . $event['id']) ?>" class="btn btn-sm btn-info">Attendees</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="col-12">
            <div class="empty-state-container" style="
                background: url('<?= base_url('assets/logo1.png') ?>') center center no-repeat;
                background-size: contain;
                background-color: #f8f9fa;
                border-radius: 15px;
                padding: 60px 20px;
                text-align: center;
                margin: 40px 0;
                min-height: 300px;
                position: relative;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            ">
                <div style="
                    background: rgba(255, 255, 255, 0.9);
                    padding: 30px;
                    border-radius: 10px;
                    backdrop-filter: blur(5px);
                    max-width: 500px;
                    margin: 0 auto;
                ">
                    <h3 style="color: #6c757d; margin-bottom: 15px;">No Events Yet</h3>
                    <p style="color: #6c757d; margin-bottom: 25px;">Start creating amazing events to showcase your talent and connect with your audience.</p>
                    <a href="<?= base_url('artist/events/create'); ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Create Your First Event
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(eventId) {
    if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
        window.location.href = "<?= base_url('artist/events/delete/') ?>" + eventId;
    }
}
</script>

<?= $this->endSection(); ?> 