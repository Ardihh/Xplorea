<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<!-- DEBUG: Tambahkan ini di paling atas -->

<div class="container" style="margin-top: 20vh">
    <div class="row d-flex justify-content-between flex-row">
        <div class="col d-flex justify-content-between flex-row">
            <div class="d-flex align-items-center fs-5">
                <i class="bi bi-people me-2 fs-1"></i>
                <p class="mb-0 fw-bold">Community</p>
            </div>
            <form class="form-inline my-2" style="width: 20vw;" id="searchForm">
                <input class="form-control mr-sm-2 rounded-pill w-100" type="search"
                    placeholder="Search threads or videos" aria-label="Search"
                    id="searchInput">
            </form>
        </div>
    </div>
    <div class="row d-flex my-2">
        <div class="col-8 d-flex flex-column">
            <div class="one fs-5">
                <div class="d-flex flex-row justify-content-between" style="width: 55vw;">
                    <p class="mb-0 me-3 fw-semibold">All Threads</p>
                    <a href="<?= base_url('xplorea/community/new-thread'); ?>" class="btn btn-dark">Start Thread</a>
                </div>
            </div>
            <div class="two d-flex flex-row justify-content-end mt-4 me-5" style="font-size: 0.8rem; color:grey; gap: 2rem;">
                <p>Replies</p>
                <p class="ms-3">Started by</p>
                <p class="ms-3">Date</p>
            </div>

            <!-- Loop through all threads -->
            <div id="threadsContainer">
                <?php if (!empty($threads)): ?>
                    <?php foreach ($threads as $thread): ?>
                        <div class="thread-item d-flex flex-row align-items-center rounded-2 mb-2" style="background-color:rgb(231, 231, 231); min-height: 15vh;">
                        <div class="d-flex flex-row justify-content-between mx-3" style="width: 70%;">
                            <a href="<?= base_url('xplorea/community/thread/' . $thread['id']); ?>" class="fw-semibold" style="text-decoration: none; color: black;">
                                <?= esc($thread['title']) ?>
                            </a>
                            <div class="d-flex flex-row align-items-center text-decoration-none" style="color: inherit;">
                                <i class="bi bi-chat-left-fill fs-4" style="color: gray"></i>
                                <p class="ms-2 mt-2"><?= $thread['reply_count'] ?? 0 ?></p>
                            </div>
                        </div>
                            <div class="d-flex flex-row" style="width: 30%;">
                                <a class="ms-5" href="<?= base_url('xplorea/profile/' . $thread['username']); ?>" style="text-decoration: none; color: black;">
                                    <?= esc($thread['username']) ?>
                                </a>
                                <p class="mb-0 ms-5"><?= date('M d, Y', strtotime($thread['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info mt-3">
                        No threads found. Be the first to <a href="<?= base_url('xplorea/community/new-thread'); ?>" class="alert-link">start a discussion</a>!
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-4">
            <p class="ms-1 fs-5 fw-semibold">Art insight</p>
            <!-- Example card - you can loop through $artInsights if available -->
            <div class="card" style="width: 18rem;">
                <img src="<?= base_url('assets/img/default-tutorial.jpg') ?>" class="card-img-top" alt="Art Insight">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <p class="card-text mb-0">5 min read</p>
                        <p class="card-text mb-0">2 days ago</p>
                    </div>
                    <a href="#" class="card-title fs-4" style="text-decoration: none;">Getting Started with Watercolor</a>
                    <div class="d-flex flex-row justify-content-between">
                        <a href="#" class="card-text" style="text-decoration: none; color: black;">ArtistName</a>
                        <a href="#" class="btn"><i class="bi bi-bookmark"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const threadsContainer = document.getElementById('threadsContainer');

    function renderThreads(threads) {
        if (!threads.length) {
            threadsContainer.innerHTML = `<div class="alert alert-info mt-3">
                No threads found. Be the first to <a href="<?= base_url('xplorea/community/new-thread'); ?>" class="alert-link">start a discussion</a>!
            </div>`;
            return;
        }
        let html = '';
        threads.forEach(thread => {
            html += `
                <div class="thread-item d-flex flex-row align-items-center rounded-2 mb-2" style="background-color:rgb(231, 231, 231); min-height: 15vh;">
                    <div class="d-flex flex-row justify-content-between mx-3" style="width: 70%;">
                        <a href="<?= base_url('xplorea/community/thread/') ?>${thread.id}" class="fw-semibold" style="text-decoration: none; color: black;">
                            ${thread.title.replace(/</g, "&lt;").replace(/>/g, "&gt;")}
                        </a>
                        <div class="d-flex flex-row align-items-center text-decoration-none" style="color: inherit;">
                            <i class="bi bi-chat-left-fill fs-4" style="color: gray"></i>
                            <p class="ms-2 mt-2">${thread.reply_count ?? 0}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-row" style="width: 30%;">
                        <a class="ms-5" href="<?= base_url('xplorea/profile/') ?>${thread.username}" style="text-decoration: none; color: black;">
                            ${thread.username}
                        </a>
                        <p class="mb-0 ms-5">${(new Date(thread.created_at)).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}</p>
                    </div>
                </div>
            `;
        });
        threadsContainer.innerHTML = html;
    }

    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = this.value.trim();
        // Optional: Only search if 2+ chars or Enter
        if (searchTerm.length > 2 || e.key === 'Enter') {
            fetch(`<?= base_url('xplorea/community/search') ?>?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    renderThreads(data);
                });
        }
        // Reset to all if input kosong
        if (searchTerm.length === 0) {
            fetch(`<?= base_url('xplorea/community/search') ?>?q=`)
                .then(response => response.json())
                .then(data => {
                    renderThreads(data);
                });
        }
    });
});
</script>

<?= $this->endSection(); ?>