<?= $this->extend('layout/admin'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Event Details</h2>
        <a href="<?= base_url('admin/events'); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Events
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><?= esc($event['title']) ?></h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-geo-alt"></i> <?= esc($event['location']) ?>
                    </p>
                </div>
                <div class="card-body">
                    <?php if ($event['image_url']): ?>
                        <img src="<?= base_url('uploads/events/' . $event['image_url']) ?>" 
                             class="img-fluid mb-3" alt="<?= esc($event['title']) ?>">
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Start Date & Time:</strong><br>
                            <?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>End Date & Time:</strong><br>
                            <?= date('M d, Y H:i', strtotime($event['end_datetime'])) ?>
                        </div>
                    </div>
                    
                    <?php if ($event['max_attendees']): ?>
                        <div class="mb-3">
                            <strong>Max Attendees:</strong> <?= $event['max_attendees'] ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p><?= nl2br(esc($event['description'])) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created by:</strong> <?= esc($event['creator_name'] ?? 'Unknown') ?>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge <?= $event['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $event['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Tickets Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Tickets</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tickets)): ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <strong><?= esc($ticket['ticket_type']) ?></strong><br>
                                <span class="text-muted">Price: Rp <?= number_format($ticket['price'], 0, ',', '.') ?></span><br>
                                <span class="text-muted">Available: <?= $ticket['quantity_available'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No tickets available.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Attendees Section -->
            <div class="card">
                <div class="card-header">
                    <h5>Attendees (<?= count($attendees) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($attendees)): ?>
                        <?php foreach ($attendees as $attendee): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <strong><?= esc($attendee['username'] ?? 'Unknown') ?></strong><br>
                                <span class="text-muted">Booked: <?= date('M d, Y', strtotime($attendee['created_at'])) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No attendees yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-4">
        <a href="<?= base_url('admin/events/toggle/' . $event['id']) ?>" 
           class="btn btn-warning">
            <?= $event['is_active'] ? 'Deactivate' : 'Activate' ?> Event
        </a>
        
        <a href="<?= base_url('admin/events/delete/' . $event['id']) ?>" 
           class="btn btn-danger"
           onclick="return confirm('Are you sure you want to delete this event?')">
            Delete Event
        </a>
    </div>
</div>

<?= $this->endSection(); ?> 