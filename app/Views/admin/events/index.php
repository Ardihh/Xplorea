<?= $this->extend('layout/admin'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Manage Events</h2>
        <a href="<?= base_url('admin/events/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Event
        </a>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover mt-4">
        <thead>
            <tr>
                <th>Title</th>
                <th>Creator</th>
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
                <td><?= esc($event['creator_name'] ?? '-') ?></td>
                <td><?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?></td>
                <td><?= date('M d, Y H:i', strtotime($event['end_datetime'])) ?></td>
                <td>
                    <span class="badge <?= $event['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $event['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </td>
                <td>
                    <a href="<?= base_url('admin/events/' . $event['id']) ?>" class="btn btn-sm btn-info">View</a>
                    <a href="<?= base_url('admin/events/toggle/' . $event['id']) ?>" class="btn btn-sm btn-warning">
                        <?= $event['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </a>
                    <a href="<?= base_url('admin/events/delete/' . $event['id']) ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this event?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?> 