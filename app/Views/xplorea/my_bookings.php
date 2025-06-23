<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <h2 style="margin-top: 20vh;">My Event Bookings</h2>
    <?php if (empty($bookings)): ?>
        <div class="text-center my-5 py-5" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 2px solid #2196f3; border-radius: 15px; padding: 3rem;">
            <i class="bi bi-calendar-x" style="font-size: 4rem; color: #1976d2; opacity: 0.8;"></i>
            <h4 class="mt-4 text-muted">You have no event bookings yet</h4>
            <p class="text-muted">Your booked events will appear here.<br>Start exploring and book your first event!</p>
            <a href="<?= base_url('xplorea/events') ?>" class="btn btn-primary mt-3">
                <i class="fas fa-calendar-plus"></i> Browse Events
            </a>
        </div>
    <?php else: ?>
        <div class="mb-4">
            <div class="btn-group" role="group" aria-label="Filter Bookings">
                <button type="button" class="btn btn-outline-secondary" id="filterAll">All</button>
                <button type="button" class="btn btn-outline-secondary" id="filterPaid">Paid</button>
                <button type="button" class="btn btn-outline-secondary" id="filterPending">Pending</button>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?php foreach ($bookings as $booking): ?>
                <div class="col">
                    <div class="card h-100 booking-card" data-status="<?= $booking['payment_status'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($booking['title']) ?></h5>
                            <p class="card-text">
                                <strong>Date:</strong> <?= date('M d, Y H:i', strtotime($booking['start_datetime'])) ?><br>
                                <strong>Location:</strong> <?= esc($booking['location']) ?><br>
                                <strong>Ticket:</strong> <?= esc($booking['ticket_type']) ?><br>
                                <strong>Qty:</strong> <?= $booking['quantity'] ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <span class="badge <?= $booking['payment_status'] === 'paid' ? 'bg-success' : ($booking['payment_status'] === 'pending' ? 'bg-warning' : 'bg-danger') ?>">
                                        <?= ucfirst($booking['payment_status']) ?>
                                    </span>
                                </div>
                                <div class="icon-wrapper">
                                    <i class="bi bi-ticket-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Animasi untuk ikon kosong */
@keyframes bounce {
    0%, 100% { transform: translateY(0);}
    50% { transform: translateY(-15px);}
}
.empty-state-animation i {
    animation: bounce 2s infinite;
}
.hover-lift:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    transition: all 0.2s;
}
.icon-wrapper {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.booking-card {
    transition: box-shadow 0.2s, transform 0.2s;
}
.booking-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    transform: translateY(-6px) scale(1.01);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterAll = document.getElementById('filterAll');
    const filterPaid = document.getElementById('filterPaid');
    const filterPending = document.getElementById('filterPending');
    const cards = document.querySelectorAll('.booking-card');

    function filterCards(status) {
        cards.forEach(card => {
            if (status === 'all' || card.getAttribute('data-status') === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (filterAll) filterAll.addEventListener('click', () => filterCards('all'));
    if (filterPaid) filterPaid.addEventListener('click', () => filterCards('paid'));
    if (filterPending) filterPending.addEventListener('click', () => filterCards('pending'));
});
</script>

<?= $this->endSection(); ?> 