<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5 mb-5" style="margin-top: 20vh;">
    <div class="d-flex justify-content-between align-items-center" style="margin-top: 20vh;">
        <h2>Edit Event</h2>
        <a href="<?= base_url('artist/events'); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Events
        </a>
    </div>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-3">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="<?= base_url('artist/events/update/' . $event['id']) ?>" method="POST" enctype="multipart/form-data" class="mt-4">
        <?= csrf_field() ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= esc($event['title']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?= esc($event['description']) ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= esc($event['location']) ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_datetime" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($event['start_datetime'])) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_datetime" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($event['end_datetime'])) ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="max_attendees" class="form-label">Max Attendees (Optional)</label>
                    <input type="number" class="form-control" id="max_attendees" name="max_attendees" 
                           value="<?= $event['max_attendees'] ?>" min="1">
                </div>
                
                <div class="mb-3">
                    <label for="image_url" class="form-label">Event Image</label>
                    <?php if ($event['image_url']): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/events/' . $event['image_url']) ?>" 
                                 alt="Current Image" style="max-width: 200px; height: auto;" class="img-thumbnail">
                            <p class="text-muted small">Current image</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
                    <small class="text-muted">Leave empty to keep current image</small>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Current Tickets</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tickets)): ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <div class="border-bottom pb-2 mb-2">
                                    <strong><?= esc($ticket['ticket_type']) ?></strong><br>
                                    <small class="text-muted">
                                        Price: Rp <?= number_format($ticket['price'], 0, ',', '.') ?><br>
                                        Available: <?= $ticket['quantity_available'] ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                            <p class="text-muted small">Note: Ticket details cannot be edited after creation</p>
                        <?php else: ?>
                            <p class="text-muted">No tickets available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="<?= base_url('artist/events') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?= $this->endSection(); ?> 