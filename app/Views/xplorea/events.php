<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20vh;">
        <h2 class="mb-0">Upcoming Events</h2>
        <?php if (session()->get('is_artist')): ?>
            <a href="<?= base_url('artist/events/create') ?>" class="btn btn-primary" style="width: 15vw;">
                <i class="bi bi-plus-circle me-2"></i>Add Event
            </a>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 event-card">
                        <div class="event-image-container">
                            <?php if ($event['image_url']): ?>
                                <img src="<?= base_url('uploads/events/' . $event['image_url']) ?>" 
                                     class="card-img-top event-image" 
                                     alt="<?= esc($event['title']) ?>">
                            <?php else: ?>
                                <div class="card-img-top event-image-placeholder">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= esc($event['title']) ?></h5>
                            <p class="card-text flex-grow-1"><?= esc(substr($event['description'], 0, 80)) . (strlen($event['description']) > 80 ? '...' : '') ?></p>
                            <div class="event-details">
                                <p class="card-text mb-1"><i class="bi bi-geo-alt"></i> <?= esc($event['location']) ?></p>
                                <p class="card-text mb-3"><i class="bi bi-calendar"></i> <?= date('M d, Y H:i', strtotime($event['start_datetime'])) ?></p>
                            </div>
                            <a href="<?= base_url('events/' . $event['id']) ?>" class="btn btn-primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No upcoming events at the moment.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.event-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.event-image-container {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-image:hover {
    transform: scale(1.1);
}

.event-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.card-body {
    padding: 1.25rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.card-text {
    font-size: 0.9rem;
    color: #6c757d;
    line-height: 1.4;
}

.event-details {
    margin-top: auto;
}

.event-details .card-text {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.event-details .bi {
    margin-right: 0.5rem;
    color:rgb(0, 0, 0);
}

.btn-primary {
    width: 100%;
    margin-top: 1rem;
    background-color: #1D2A34;
    color: white;
    border: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .event-image-container {
        height: 180px;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .card-text {
        font-size: 0.85rem;
    }
}
</style>

<?= $this->endSection(); ?> 