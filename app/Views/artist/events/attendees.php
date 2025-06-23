<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20vh;">
        <h2>Event Attendees</h2>
        <a href="<?= base_url('artist/events'); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Events
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?= esc($event['title']) ?></h5>
            <small class="text-muted"><?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?> - <?= esc($event['location']) ?></small>
        </div>
        <div class="card-body">
            <?php if (!empty($attendees)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Ticket Type</th>
                                <th>Price</th>
                                <th>Booked Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendees as $index => $attendee): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($attendee['fullname'] ?? $attendee['username']) ?></td>
                                    <td><?= esc($attendee['email'] ?? '-') ?></td>
                                    <td><?= esc($attendee['ticket_type']) ?></td>
                                    <td>Rp <?= number_format($attendee['price'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($attendee['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <strong>Total Attendees: <?= count($attendees) ?></strong>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-people fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No Attendees Yet</h5>
                    <p class="text-muted">This event doesn't have any attendees yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>

<?= $this->endSection(); ?> 