<?= $this->extend('/layout/template'); ?>

<?= $this->section('content'); ?>


<!-- HERO SECTION -->
     <section>
         <div id="recentEventsCarousel" class="carousel slide vh-100" data-bs-ride="carousel">
             <div class="carousel-inner h-100">
                 <?php foreach ($recentEvents as $i => $event): ?>
                     <div class="carousel-item <?= $i === 0 ? 'active' : '' ?> h-100">
                         <div class="d-flex align-items-end justify-content-start h-100"
                             style="background-image: url('<?= base_url('uploads/events/' . $event['image_url']) ?>'); background-size: cover; background-position: center;">
                             <div class="container mb-5">
                                 <div class="row">
                                     <div class="col-md-4 text-white bg-dark bg-opacity-50 p-4 rounded-3">
                                         <h4><?= esc($event['title']) ?></h4>
                                         <p><?= esc($event['location']) ?></p>
                                         <a href="<?= base_url('events/' . $event['id']) ?>" class="btn btn-light rounded-3 fs-5 px-5">Get Ticket</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 <?php endforeach; ?>
             </div>
             <?php if (count($recentEvents) > 1): ?>
                 <button class="carousel-control-prev" type="button" data-bs-target="#recentEventsCarousel" data-bs-slide="prev">
                     <span class="carousel-control-prev-icon"></span>
                 </button>
                 <button class="carousel-control-next" type="button" data-bs-target="#recentEventsCarousel" data-bs-slide="next">
                     <span class="carousel-control-next-icon"></span>
                 </button>
             <?php endif; ?>
         </div>
     </section>
        
    <!-- ABOUT SECTION -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-6 justify-content-center mb-5"></div>
                    <p class="fs-4 text-center">Discover, Support, and Celebrate Art from All Around the World.</p>
                    <ul class="list-unstyled d-flex justify-content-center gap-4 mt-4">
                        <li><a href="<?= base_url('xplorea/marketplace') ?>" class="text-decoration-none text-black">Explore Artworks</a></li>
                        <li><a href="<?= base_url('xplorea/becomeartist') ?>" class="text-decoration-none text-black">Join as Artist</a></li>
                        <li><a href="<?= base_url('xplorea/events') ?>" class="text-decoration-none text-black">Browse Event</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="trending">
        <div class="container">
            <div class="row justify-content-center align-items-center mb-5">
                <?php if (!empty($latestArtworks)): ?>
                    <?php foreach ($latestArtworks as $i => $artwork): ?>
                        <div class="col-md-<?= $i === 1 ? '4' : '3' ?> d-flex justify-content-center shadow flex-column align-items-center <?= $i === 1 ? 'mx-5' : '' ?>">
                            <!-- <div class="penilaian d-flex justify-content-start w-100">
                                <i class="bi bi-star-fill fs-<?= $i === 1 ? '2' : '3' ?> py-2 pl-2" style="color: #FFE100;"></i>
                                <p class="py-3 ms-2 fs-5" style="font-weight: 600;">
                                    <?= number_format($artwork['base_price'], 0, ',', '.') ?>
                                </p>
                            </div> -->
                            <img src="<?= $artwork['image_url'] ?: base_url('assets/logo1.png') ?>" alt="<?= esc($artwork['title']) ?>" class="img-fluid pt-4" style="max-width:80%;">
                            <div class="content text-center">
                                <h3><?= esc($artwork['title']) ?></h3>
                                <p>Rp <?= number_format($artwork['base_price'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4 d-flex justify-content-center shadow flex-column align-items-center">
                        <!-- <div class="penilaian d-flex justify-content-start w-100">
                            <i class="bi bi-star-fill fs-2 py-2 pl-2" style="color: #FFE100;"></i>
                            <p class="py-3 ms-2 fs-5" style="font-weight: 600;">150.000</p>
                        </div> -->
                        <img src="<?= base_url('assets/logo1.png') ?>" alt="Sample Artwork" class="img-fluid" style="max-width:80%;">
                        <div class="content text-center">
                            <h3>Sample Artwork</h3>
                            <p>Rp 150.000</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="upcoming-event">
        <div class="position-relative d-flex justify-content-center align-items-center mb-5" style="background-image: url('https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; height: 40vh;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5);"></div>
            <div class="container py-5 position-relative text-white" height="40vh">
                <div class="row d-flex">
                    <div class="col d-flex justify-content-center align-items-center">
                        <h2 class="text-center">Upcoming Events</h2>
                    </div>
                </div>
        
            </div>
        </div>
    </section>

    <!-- EVENT CALENDAR SECTION -->
    <section id="event-calendar" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Event Calendar</h2>
            <div class="row justify-content-center">
                <div class="col-10 mx-auto">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <button id="calendar-prev" class="btn btn-outline-secondary btn-sm">&lt; Prev</button>
                        <h5 id="calendar-month-label" class="mb-0"></h5>
                        <button id="calendar-next" class="btn btn-outline-secondary btn-sm">Next &gt;</button>
                    </div>
                    <div id="calendar-root"></div>
                </div>
            </div>
            
            <!-- Event Popup Modal -->
            <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-0 pb-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div id="eventModalContent">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="calendarEventPopup" style="display:none; position:absolute; z-index:9999; min-width:220px;"></div>
            <style>
                .calendar-grid { display: flex; flex-direction: column; gap: 2px; }
                .calendar-header, .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); }
                .calendar-day-header { text-align: center; font-weight: bold; padding: 4px 0; background: #f8f9fa; border-bottom: 1px solid #dee2e6; }
                .calendar-day { min-height: 80px; border: 1px solid #dee2e6; background: #fff; position: relative; padding: 4px; }
                .calendar-day.today { background: #e3f2fd; border: 2px solid #2196f3; }
                .calendar-day.has-events { background: #e3f2fd; border: 2px solid #2196f3; cursor: pointer; }
                .calendar-day.empty { background: transparent; border: none; }
                .day-number { font-size: 1rem; font-weight: 500; display: block; margin-bottom: 2px; }
                .event-list { font-size: 0.75rem; line-height: 1.2; }
                .event-item { margin-bottom: 1px; }
                .event-link { 
                    color: #2196f3; 
                    text-decoration: none; 
                    cursor: pointer;
                    transition: color 0.2s;
                }
                .event-link:hover { 
                    color: #1976d2;
                    text-decoration: underline; 
                }
                #calendarEventPopup {
                    pointer-events: none;
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 4px 24px rgba(0,0,0,0.15);
                    border: 1px solid #dee2e6;
                    padding: 0;
                    transition: opacity 0.1s;
                    max-width: 320px;
                }
                #calendarEventPopup img {
                    width: 100%;
                    max-height: 160px;
                    object-fit: cover;
                    border-top-left-radius: 12px;
                    border-top-right-radius: 12px;
                }
                #calendarEventPopup .card-body {
                    padding: 12px 16px;
                }
            </style>
            <script>
                window.calendarEvents = <?php
                    $jsEvents = [];
                    foreach ($calendarEvents as $event) {
                        $date = date('Y-m-d', strtotime($event['start_datetime']));
                        $jsEvents[] = [
                            'date' => $date,
                            'title' => $event['title'],
                            'id' => $event['id'],
                            'image_url' => !empty($event['image_url']) ? base_url('uploads/events/' . $event['image_url']) : '', // pastikan field image_url ada
                        ];
                    }
                    echo json_encode($jsEvents);
                ?>;

                const monthNames = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];

                let currentMonth = new Date().getMonth();
                let currentYear = new Date().getFullYear();

                function renderCalendar(month, year) {
                    const calendarRoot = document.getElementById('calendar-root');
                    const monthLabel = document.getElementById('calendar-month-label');
                    monthLabel.textContent = monthNames[month] + ' ' + year;

                    // Filter event untuk bulan ini
                    const eventsByDate = {};
                    window.calendarEvents.forEach(ev => {
                        const d = new Date(ev.date);
                        if (d.getMonth() === month && d.getFullYear() === year) {
                            if (!eventsByDate[ev.date]) eventsByDate[ev.date] = [];
                            eventsByDate[ev.date].push(ev);
                        }
                    });

                    const firstDayOfMonth = new Date(year, month, 1);
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const startDayOfWeek = firstDayOfMonth.getDay();
                    const todayStr = (new Date()).toISOString().slice(0,10);

                    let html = '';
                    html += '<div class="calendar-header mb-2">';
                    html += '</div>';
                    html += '<div class="calendar-days mb-1">';
                    ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(d => {
                        html += '<div class="calendar-day-header">'+d+'</div>';
                    });
                    html += '</div>';
                    html += '<div class="calendar-days">';
                    for (let i = 0; i < startDayOfWeek; i++) {
                        html += '<div class="calendar-day empty"></div>';
                    }
                    for (let day = 1; day <= daysInMonth; day++) {
                        const dateStr = year+'-'+String(month+1).padStart(2,'0')+'-'+String(day).padStart(2,'0');
                        const hasEvent = !!eventsByDate[dateStr];
                        const isToday = dateStr === todayStr;
                        html += '<div class="calendar-day'+(isToday ? ' today':'')+(hasEvent ? ' has-events':'')+'" data-date="'+dateStr+'">';
                        html += '<span class="day-number">'+day+'</span>';
                        if (hasEvent) {
                            html += '<div class="event-list">';
                            eventsByDate[dateStr].forEach(ev => {
                                html += '<div class="event-item">';
                                html += '<a href="' + baseUrl('events/' + ev.id) + '" class="event-link" data-event-id="'+ev.id+'" data-event-title="'+escapeHtml(ev.title)+'" data-event-image="'+ev.image_url+'">'+escapeHtml(ev.title)+'</a>';
                                html += '</div>';
                            });
                            html += '</div>';
                        }
                        html += '</div>';
                    }
                    const lastDayOfWeek = (startDayOfWeek + daysInMonth - 1) % 7;
                    for (let i = lastDayOfWeek + 1; i <= 6; i++) {
                        html += '<div class="calendar-day empty"></div>';
                    }
                    html += '</div>';
                    calendarRoot.innerHTML = html;

                    // Add event listener for popup
                    document.querySelectorAll('.calendar-day.has-events').forEach(dayEl => {
                        dayEl.addEventListener('mouseenter', function(e) {
                            const date = this.getAttribute('data-date');
                            const events = eventsByDate[date];
                            if (events && events.length > 0) {
                                let popupHtml = '';
                                events.forEach(ev => {
                                    popupHtml += `
                                        <div class="card mb-0 border-0" style="box-shadow:none;">
                                            ${ev.image_url ? `<img src="${ev.image_url}" alt="${escapeHtml(ev.title)}">` : ''}
                                            <div class="card-body py-2">
                                                <h6 class="card-title mb-0">
                                                    <a href="${baseUrl('events/' + ev.id)}" class="text-decoration-none text-dark">${escapeHtml(ev.title)}</a>
                                                </h6>
                                            </div>
                                        </div>
                                    `;
                                });
                                const popup = document.getElementById('calendarEventPopup');
                                popup.innerHTML = popupHtml;
                                popup.style.display = 'block';

                                // Posisi popup di atas mouse
                                const rect = this.getBoundingClientRect();
                                popup.style.left = (rect.left + window.scrollX + rect.width/2 - popup.offsetWidth/2) + 'px';
                                popup.style.top = (rect.top + window.scrollY - popup.offsetHeight - 10) + 'px';
                            }
                        });
                        dayEl.addEventListener('mousemove', function(e) {
                            const popup = document.getElementById('calendarEventPopup');
                            // Update posisi popup mengikuti mouse
                            popup.style.left = (e.pageX + 16) + 'px';
                            popup.style.top = (e.pageY - popup.offsetHeight - 16) + 'px';
                        });
                        dayEl.addEventListener('mouseleave', function() {
                            const popup = document.getElementById('calendarEventPopup');
                            popup.style.display = 'none';
                        });
                    });
                }

                function baseUrl(path) {
                    return '<?= base_url() ?>' + (path.startsWith('/') ? path.substr(1) : path);
                }
                function escapeHtml(text) {
                    return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                }

                document.getElementById('calendar-prev').onclick = function() {
                    if (currentMonth === 0) {
                        currentMonth = 11;
                        currentYear--;
                    } else {
                        currentMonth--;
                    }
                    renderCalendar(currentMonth, currentYear);
                };
                document.getElementById('calendar-next').onclick = function() {
                    if (currentMonth === 11) {
                        currentMonth = 0;
                        currentYear++;
                    } else {
                        currentMonth++;
                    }
                    renderCalendar(currentMonth, currentYear);
                };
                renderCalendar(currentMonth, currentYear);
            </script>
        </div>
    </section>

    <section id="cta">
        <div class="container d-flex  align-items-center" style="height: 60vh;">
            <div class="row">
                <div class="col">
                    <div class="d-flex justify-content-center align-items-start flex-column">
                        <h1 class="fs-4">Ready to Explore Art in a New Way?</h1>
                        <div class="content"></div>
                            <p class="mb-0 ps-3">Discover art you'll love, and show off your own too!</p>
                            <p class="mb-0 ps-3">Your creative adventure starts here.</p>
                            <p class="mb-0 ps-3">Scroll, support, and shine as a creator.</p>
                        </div>
                        <div class="button d-flex gap-3 mt-3" style="width: 100%;">
                            <a href="<?= base_url('xplorea/marketplace') ?>" class="btn btn-light rounded-pill shadow mt-2 " style="width: 50%;">Start Exploring <i class="bi bi-search"></i></a>
                            <a href="<?= base_url('xplorea/becomeartist') ?>" class="btn btn-light rounded-pill shadow mt-2 " style="width: 50%;">Become a Creator <i class="bi bi-vector-pen"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?= $this->endSection(); ?>