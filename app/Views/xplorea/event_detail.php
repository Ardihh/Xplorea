<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5" style="margin-top: 20vh;">
    <div class="row"  style="margin-top: 20vh;">
        <div class="col-md-7">
            <h2><?= esc($event['title']) ?></h2>
            <p class="text-muted"><?= esc($event['location']) ?> | <?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?></p>
            <img src="<?= base_url('uploads/events/' . $event['image_url']) ?>" class="img-fluid mb-3" alt="<?= esc($event['title']) ?>">
            <p><?= esc($event['description']) ?></p>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5>Tickets</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($tickets)): ?>
                        <p class="text-muted">No tickets available.</p>
                    <?php else: ?>
                        <form action="<?= base_url('events/book/' . $event['id']) ?>" method="POST">
                            <div class="mb-3">
                                <label for="ticket_id" class="form-label">Select Ticket</label>
                                <select name="ticket_id" id="ticket_id" class="form-select" required>
                                    <?php foreach ($tickets as $ticket): ?>
                                        <option value="<?= $ticket['id'] ?>">
                                            <?= esc($ticket['ticket_type']) ?> - Rp <?= number_format($ticket['price'], 0, ',', '.') ?> (<?= $ticket['quantity_available'] ?> left)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
                            </div>
                            <button type="submit" class="btn btn-success">Book Ticket</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?> 