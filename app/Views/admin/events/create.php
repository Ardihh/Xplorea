<?= $this->extend('layout/admin'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Create New Event</h2>
        <a href="<?= base_url('admin/events'); ?>" class="btn btn-secondary">
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
    
    <form action="<?= base_url('admin/events/store') ?>" method="POST" enctype="multipart/form-data" class="mt-4">
        <?= csrf_field() ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?= old('description') ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= old('location') ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_datetime" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" value="<?= old('start_datetime') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_datetime" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" value="<?= old('end_datetime') ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="max_attendees" class="form-label">Max Attendees (Optional)</label>
                    <input type="number" class="form-control" id="max_attendees" name="max_attendees" value="<?= old('max_attendees') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="image_url" class="form-label">Event Image</label>
                    <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Ticket Types</h5>
                    </div>
                    <div class="card-body">
                        <div id="ticket-container">
                            <div class="ticket-item mb-3">
                                <input type="text" class="form-control mb-2" name="ticket_types[]" placeholder="Ticket Type" required>
                                <input type="number" class="form-control mb-2" name="ticket_prices[]" placeholder="Price" required>
                                <input type="number" class="form-control mb-2" name="ticket_quantities[]" placeholder="Quantity" required>
                                <input type="datetime-local" class="form-control mb-2" name="sale_starts[]" placeholder="Sale Start">
                                <input type="datetime-local" class="form-control" name="sale_ends[]" placeholder="Sale End">
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTicket()">Add Ticket Type</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3 mb-5">
            <button type="submit" class="btn btn-primary">Create Event</button>
            <a href="<?= base_url('admin/events') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function addTicket() {
    const container = document.getElementById('ticket-container');
    const ticketItem = document.createElement('div');
    ticketItem.className = 'ticket-item mb-3';
    ticketItem.innerHTML = `
        <input type="text" class="form-control mb-2" name="ticket_types[]" placeholder="Ticket Type" required>
        <input type="number" class="form-control mb-2" name="ticket_prices[]" placeholder="Price" required>
        <input type="number" class="form-control mb-2" name="ticket_quantities[]" placeholder="Quantity" required>
        <input type="datetime-local" class="form-control mb-2" name="sale_starts[]" placeholder="Sale Start">
        <input type="datetime-local" class="form-control" name="sale_ends[]" placeholder="Sale End">
        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeTicket(this)">Remove</button>
    `;
    container.appendChild(ticketItem);
}

function removeTicket(button) {
    button.parentElement.remove();
}
</script>

<?= $this->endSection(); ?> 