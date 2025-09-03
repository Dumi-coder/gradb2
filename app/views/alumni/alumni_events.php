<?php require '../app/views/partials/alumni_header.php'; ?>

<?php
// Sample event data - in a real application, this would come from the controller/database
$events = [
    [
        'id' => 1,
        'title' => 'Annual Alumni Networking Gala',
        'description' => 'Join us for an evening of networking, dining, and reconnecting with fellow alumni. Special guest speakers from top tech companies.',
        'date' => '2025-09-15',
        'time' => '18:00',
        'location' => 'Grand Ballroom, University Hotel',
        'organizer' => 'Alumni Association',
        'type' => 'alumni',
        'registered' => true,
        'status' => 'approved',
        'approval_status' => 'approved',
        'max_attendees' => 200,
        'current_attendees' => 156
    ],
    [
        'id' => 2,
        'title' => 'Tech Career Workshop',
        'description' => 'Learn about emerging technologies and career opportunities in AI, blockchain, and cybersecurity.',
        'date' => '2025-09-08',
        'time' => '14:00',
        'location' => 'Online - Zoom Link',
        'organizer' => 'CS Alumni Chapter',
        'type' => 'alumni',
        'registered' => false,
        'status' => 'approved',
        'approval_status' => 'approved',
        'max_attendees' => 100,
        'current_attendees' => 67
    ],
    [
        'id' => 3,
        'title' => 'Student Startup Pitch Competition',
        'description' => 'Watch current students present their innovative startup ideas. Alumni serve as judges and mentors.',
        'date' => '2025-09-22',
        'time' => '15:30',
        'location' => 'Innovation Hub, Main Campus',
        'organizer' => 'Entrepreneurship Club',
        'type' => 'student',
        'registered' => true,
        'status' => 'approved',
        'approval_status' => 'approved',
        'max_attendees' => 150,
        'current_attendees' => 89
    ],
    [
        'id' => 4,
        'title' => 'Alumni Mentorship Program Launch',
        'description' => 'Official launch of the new mentorship program connecting alumni with current students.',
        'date' => '2025-08-30',
        'time' => '16:00',
        'location' => 'University Auditorium',
        'organizer' => 'Career Services',
        'type' => 'alumni',
        'registered' => true,
        'status' => 'past',
        'approval_status' => 'approved',
        'proof_url' => '#',
        'summary' => 'Successfully launched with 150+ mentor-mentee pairs.'
    ],
    [
        'id' => 5,
        'title' => 'Engineering Alumni Reunion',
        'description' => 'Class of 2020 Engineering graduates reunion with campus tour and dinner.',
        'date' => '2025-09-12',
        'time' => '12:00',
        'location' => 'Engineering Building',
        'organizer' => 'Engineering Alumni',
        'type' => 'alumni',
        'registered' => true,
        'status' => 'approved',
        'approval_status' => 'approved',
        'max_attendees' => 75,
        'current_attendees' => 42
    ],
    [
        'id' => 6,
        'title' => 'Student Research Symposium',
        'description' => 'Annual showcase of undergraduate and graduate research projects across all departments.',
        'date' => '2025-09-18',
        'time' => '09:00',
        'location' => 'Science Center',
        'organizer' => 'Graduate School',
        'type' => 'student',
        'registered' => false,
        'status' => 'approved',
        'approval_status' => 'approved',
        'max_attendees' => 300,
        'current_attendees' => 178
    ],
    [
        'id' => 9,
        'title' => 'Digital Marketing Masterclass',
        'description' => 'Learn cutting-edge digital marketing strategies from industry experts. Covers social media marketing, SEO, content creation, and analytics.',
        'date' => '2025-09-25',
        'time' => '10:00',
        'location' => 'Business Center, Conference Hall A',
        'organizer' => 'Marketing Alumni Network',
        'type' => 'alumni',
        'registered' => false,
        'status' => 'approved',
        'approval_status' => 'approved',
        'max_attendees' => 80,
        'current_attendees' => 52
    ]
];

// Sample pending events created by current alumni
$pendingEvents = [
    [
        'id' => 7,
        'title' => 'AI & Machine Learning Workshop',
        'description' => 'Hands-on workshop covering latest AI/ML technologies and their practical applications in industry.',
        'date' => '2025-10-05',
        'time' => '14:00',
        'location' => 'Tech Center, Room 205',
        'organizer' => 'Current Alumni User',
        'type' => 'alumni',
        'status' => 'pending',
        'approval_status' => 'pending',
        'submitted_date' => '2025-09-01'
    ],
    [
        'id' => 8,
        'title' => 'Entrepreneurship Bootcamp',
        'description' => 'Three-day intensive bootcamp for aspiring entrepreneurs with guest speakers from successful startups.',
        'date' => '2025-10-15',
        'time' => '09:00',
        'location' => 'Business School Auditorium',
        'organizer' => 'Current Alumni User',
        'type' => 'alumni',
        'status' => 'pending',
        'approval_status' => 'pending',
        'submitted_date' => '2025-09-02'
    ]
];

// Sample registered events for current alumni (without registration_date)
$registeredEvents = [
    [
        'id' => 1,
        'title' => 'Annual Alumni Networking Gala',
        'date' => '2025-09-15',
        'time' => '18:00',
        'location' => 'Grand Ballroom, University Hotel',
        'organizer' => 'Alumni Association',
        'type' => 'alumni'
    ],
    [
        'id' => 3,
        'title' => 'Student Startup Pitch Competition',
        'date' => '2025-09-22',
        'time' => '15:30',
        'location' => 'Innovation Hub, Main Campus',
        'organizer' => 'Entrepreneurship Club',
        'type' => 'student'
    ],
    [
        'id' => 5,
        'title' => 'Engineering Alumni Reunion',
        'date' => '2025-09-12',
        'time' => '12:00',
        'location' => 'Engineering Building',
        'organizer' => 'Engineering Alumni',
        'type' => 'alumni'
    ]
];

$current_page = 'events';
?>

<div class="dashboard-container">
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

    <main class="main-content">
        <div class="content-wrapper">
            <!-- Header -->
            <header class="dashboard-header">
                <h1 class="dashboard-title">Events</h1>
                <p class="dashboard-subtitle">Explore alumni and student-organized events. Publish, register, and view event details.</p>
                <div class="header-actions">
                    <button id="publishEventBtn" class="btn btn-primary">+ Publish Event</button>
                </div>
            </header>

            <!-- Pending Events Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        Pending Events
                    </h2>
                    <span class="section-count"><?php echo count($pendingEvents); ?> events</span>
                </div>
                <?php if (empty($pendingEvents)): ?>
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        <h3>No Pending Events</h3>
                        <p>You don't have any events waiting for approval.</p>
                    </div>
                <?php else: ?>
                    <div class="mentorship-grid">
                        <?php foreach ($pendingEvents as $event): ?>
                            <div class="mentorship-card">
                                <div class="card-header">
                                    <div class="student-info">
                                        <h3 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                                        <p class="request-type"><?php echo htmlspecialchars($event['location']); ?></p>
                                    </div>
                                    <span class="card-badge pending">Pending</span>
                                </div>
                                <div class="card-content">
                                    <p class="card-description">
                                        <?php echo htmlspecialchars($event['description']); ?>
                                    </p>
                                    <div class="aid-details">
                                        <div class="aid-amount">Date: <strong><?php echo date('M j, Y', strtotime($event['date'])); ?></strong></div>
                                        <div class="aid-type">Time: <strong><?php echo date('g:i A', strtotime($event['time'])); ?></strong></div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-primary">Edit</button>
                                    <button class="btn btn-secondary">Delete</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Registered Events Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3l8-8"/>
                            <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.65 0 3.2.45 4.52 1.23"/>
                        </svg>
                        My Registered Events
                    </h2>
                    <span class="section-count"><?php echo count($registeredEvents); ?> events</span>
                </div>
                <div class="events-grid">
                    <?php if (empty($registeredEvents)): ?>
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3l8-8"/>
                                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.65 0 3.2.45 4.52 1.23"/>
                            </svg>
                            <h3>No Registered Events</h3>
                            <p>You haven't registered for any events yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($registeredEvents as $event): ?>
                            <div class="event-card registered-event">
                                <div class="event-card-header">
                                    <div class="event-type-badge">
                                        <span class="badge badge-<?php echo $event['type']; ?>"><?php echo ucfirst($event['type']); ?> Event</span>
                                        <span class="badge badge-registered">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 11l3 3l8-8"/>
                                                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.65 0 3.2.45 4.52 1.23"/>
                                            </svg>
                                            Registered
                                        </span>
                                    </div>
                                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                </div>
                                <div class="event-card-body">
                                    <div class="event-details">
                                        <div class="detail-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="16" y1="2" x2="16" y2="6"/>
                                                <line x1="8" y1="2" x2="8" y2="6"/>
                                                <line x1="3" y1="10" x2="21" y2="10"/>
                                            </svg>
                                            <span><?php echo date('M j, Y', strtotime($event['date'])); ?> at <?php echo date('g:i A', strtotime($event['time'])); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <span><?php echo htmlspecialchars($event['location']); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            <span>Organized by <?php echo htmlspecialchars($event['organizer']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-card-footer">
                                    <button class="btn btn-danger btn-sm" onclick="unregisterEvent(<?php echo $event['id']; ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                        Unregister
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- View Toggle and Controls -->
            <section class="events-controls">
                <div class="view-controls">
                    <button id="calendarViewBtn" class="btn btn-secondary active">Calendar View</button>
                    <button id="listViewBtn" class="btn btn-secondary">List View</button>
                </div>
                <div class="calendar-controls" id="calendarControls">
                    <button id="prevMonth" class="btn btn-text">← Previous</button>
                    <button id="todayBtn" class="btn btn-secondary">Today</button>
                    <button id="nextMonth" class="btn btn-text">Next →</button>
                </div>
            </section>

            <!-- Calendar View -->
            <section class="dashboard-section" id="calendarView">
                <div class="calendar-container">
                    <div class="calendar-header">
                        <h2 id="currentMonth">September 2025</h2>
                    </div>
                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                    <div class="calendar-legend">
                        <span class="legend-item">
                            <span class="legend-dot alumni"></span>Alumni Events
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot student"></span>Student Events
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot past"></span>Past Events
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot registered"></span>My Registered Events
                        </span>
                    </div>
                </div>
            </section>

            <!-- List View -->
            <section class="dashboard-section" id="listView" style="display: none;">
                <div class="section-header">
                    <h2 class="section-title">Upcoming Events</h2>
                </div>
                <?php 
                $upcomingEvents = array_filter($events, function($event) {
                    return $event['status'] === 'approved' && $event['date'] >= date('Y-m-d');
                });
                usort($upcomingEvents, function($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });
                ?>
                
                <?php if (!empty($upcomingEvents)): ?>
                    <div class="mentorship-grid">
                        <?php foreach ($upcomingEvents as $event): ?>
                            <div class="mentorship-card">
                                <div class="card-header">
                                    <div class="student-info">
                                        <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                                        <p class="request-type"><?= htmlspecialchars($event['location']) ?></p>
                                    </div>
                                    <span class="card-badge <?= $event['registered'] ? 'active' : 'pending' ?>"><?= $event['registered'] ? 'Registered' : 'Available' ?></span>
                                </div>
                                <div class="card-content">
                                    <p class="card-description">
                                        <?= htmlspecialchars($event['description']) ?>
                                    </p>
                                    <div class="aid-details">
                                        <div class="aid-amount">Date: <strong><?= date('M j, Y', strtotime($event['date'])) ?></strong></div>
                                        <div class="aid-type">Time: <strong><?= date('g:i A', strtotime($event['time'])) ?></strong></div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-primary" onclick="openEventModal(<?= $event['id'] ?>)">View Details</button>
                                    <?php if ($event['registered']): ?>
                                        <button class="btn btn-success">Registered</button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" onclick="registerForEvent(<?= $event['id'] ?>)">Register</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- View More Link -->
                    <div style="text-align: center; margin-top: 2rem;">
                        <button class="btn btn-text">View All Upcoming Events</button>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        <h3>No Upcoming Events</h3>
                        <p>No upcoming events available.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="modal-overlay" onclick="closeEventModal()">
    <div class="modal-content event-modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalEventTitle">Event Title</h3>
            <button class="modal-close" onclick="closeEventModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="event-modal-content">
                <div class="event-meta">
                    <span id="modalEventType" class="event-badge alumni">Alumni Event</span>
                    <span id="modalEventStatus" class="status-badge upcoming">Upcoming</span>
                </div>
                <div class="event-details-grid">
                    <div class="detail-item">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        <span id="modalEventDateTime">Date & Time</span>
                    </div>
                    <div class="detail-item">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span id="modalEventLocation">Location</span>
                    </div>
                    <div class="detail-item">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span id="modalEventOrganizer">Organizer</span>
                    </div>
                    <div class="detail-item" id="attendeeInfo">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01 1l-4.7 6.28c-.37.5-.58 1.11-.58 1.73V20h-2v-2h-2v2H4v-2.5c0-1.1.9-2 2-2h3c1.1 0 2-.9 2-2V7h4c1.1 0 2 .9 2 2v6h2v4z"/>
                        </svg>
                        <span id="modalAttendees">Attendees</span>
                    </div>
                </div>
                <div class="event-description">
                    <h4>Description</h4>
                    <p id="modalEventDescription">Event description will be displayed here.</p>
                </div>
                <div class="event-proof" id="eventProof" style="display: none;">
                    <h4>Event Summary</h4>
                    <p id="modalEventSummary">Event summary for past events.</p>
                    <a href="#" id="modalProofLink" class="btn btn-text">View Photos/Recordings</a>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="modalRegisterBtn" class="btn btn-primary" onclick="registerForEvent()">Register</button>
            <button id="modalUnregisterBtn" class="btn btn-secondary" onclick="unregisterFromEvent()" style="display: none;">Unregister</button>
            <button class="btn btn-text" onclick="closeEventModal()">Close</button>
        </div>
    </div>
</div>

<!-- Publish Event Modal -->
<div id="publishEventModal" class="modal-overlay" onclick="closePublishEventModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Publish New Event</h3>
            <button class="modal-close" onclick="closePublishEventModal()">&times;</button>
        </div>
        <div class="approval-notice">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6M12 18h.01"/>
            </svg>
            <span>Your event will be visible once approved by Admin.</span>
        </div>
        <form class="publish-event-form" onsubmit="submitNewEvent(event)">
            <div class="form-group">
                <label for="eventTitle">Event Title</label>
                <input type="text" id="eventTitle" name="title" placeholder="Enter event title..." required>
            </div>
            <div class="form-group">
                <label for="eventDescription">Description</label>
                <textarea id="eventDescription" name="description" placeholder="Describe your event..." rows="4" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="eventDate">Date</label>
                    <input type="date" id="eventDate" name="date" required>
                </div>
                <div class="form-group">
                    <label for="eventTime">Time</label>
                    <input type="time" id="eventTime" name="time" required>
                </div>
            </div>
            <div class="form-group">
                <label for="eventLocation">Location</label>
                <input type="text" id="eventLocation" name="location" placeholder="Event location or online link..." required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="eventType">Event Type</label>
                    <select id="eventType" name="type" required>
                        <option value="">Select type</option>
                        <option value="alumni">Alumni Event</option>
                        <option value="student">Student Event</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="maxAttendees">Max Attendees</label>
                    <input type="number" id="maxAttendees" name="max_attendees" min="1" placeholder="100">
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closePublishEventModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </div>
        </form>
    </div>
</div>

<!-- CSS Files -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni_mentorship.css">

<!-- Events Specific CSS -->
<style>
/* Header Actions - Lower Button Position */
.header-actions {
    margin-top: 1rem;
    position: relative;
    top: 8px;
}

/* Events Controls */
.events-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.view-controls {
    display: flex;
    gap: 0.5rem;
}

.view-controls .btn.active {
    background-color: #2563eb;
    color: white;
}

.calendar-controls {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* Calendar Styles */
.calendar-container {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.calendar-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.calendar-header h2 {
    color: #1e293b;
    font-size: 1.5rem;
    font-weight: 600;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.calendar-day-header {
    background: #f8fafc;
    padding: 0.75rem;
    text-align: center;
    font-weight: 600;
    color: #64748b;
    font-size: 0.875rem;
}

.calendar-day {
    background: white;
    padding: 0.5rem;
    min-height: 80px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.calendar-day:hover {
    background-color: #f8fafc;
}

.calendar-day.other-month {
    background: #f8fafc;
    color: #94a3b8;
}

.calendar-day.today {
    background: #dbeafe;
    border: 2px solid #2563eb;
}

.calendar-day.has-events {
    background: #fefce8;
}

.day-number {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.event-dots {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
    margin-top: 0.25rem;
}

.event-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.event-dot.alumni {
    background-color: #2563eb;
}

.event-dot.student {
    background-color: #059669;
}

.event-dot.past {
    background-color: #6b7280;
}

.event-dot.registered {
    background-color: #eab308;
    border: 2px solid #ca8a04;
}

.calendar-legend {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.legend-dot.alumni {
    background-color: #2563eb;
}

.legend-dot.student {
    background-color: #059669;
}

.legend-dot.past {
    background-color: #6b7280;
}

.legend-dot.registered {
    background-color: #eab308;
    border: 2px solid #ca8a04;
}

/* List View Styles */
.events-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.event-list-item {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.event-list-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.event-list-item.registered {
    border-left: 4px solid #eab308;
    background: #fefce8;
}

.event-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.event-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.event-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.event-badge.alumni {
    background: #dbeafe;
    color: #1e40af;
}

.event-badge.student {
    background: #dcfce7;
    color: #166534;
}

.event-details {
    display: flex;
    gap: 2rem;
    color: #64748b;
    font-size: 0.875rem;
}

.event-date, .event-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.icon {
    width: 16px;
    height: 16px;
    fill: currentColor;
}

/* Event Modal Styles */
.event-modal {
    max-width: 600px;
    width: 90%;
}

.event-modal-content {
    padding: 1.5rem;
}

.event-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.upcoming {
    background: #dcfce7;
    color: #166534;
}

.status-badge.past {
    background: #f3f4f6;
    color: #374151;
}

.event-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #64748b;
}

.event-description {
    margin-bottom: 1.5rem;
}

.event-description h4 {
    color: #1e293b;
    margin-bottom: 0.75rem;
}

.event-description p {
    color: #64748b;
    line-height: 1.6;
}

.event-proof h4 {
    color: #1e293b;
    margin-bottom: 0.75rem;
}

.event-proof p {
    color: #64748b;
    margin-bottom: 1rem;
}

/* Form Styles */
.publish-event-form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

/* Pending Events Table Styles */
.events-table-container {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.events-table {
    width: 100%;
    border-collapse: collapse;
}

.events-table th {
    background: #f8fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e2e8f0;
}

.events-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
}

.events-table tr:last-child td {
    border-bottom: none;
}

.events-table tr:hover {
    background: #f8fafc;
}

.event-title strong {
    display: block;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.event-description {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

.event-datetime strong {
    display: block;
    color: #1e293b;
    margin-bottom: 0.125rem;
}

.event-datetime span {
    font-size: 0.875rem;
    color: #64748b;
}

.event-location {
    color: #64748b;
    font-size: 0.875rem;
}

.submitted-date {
    color: #64748b;
    font-size: 0.875rem;
}

.badge-pending {
    background: #f3f4f6;
    color: #6b7280;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-pending svg {
    width: 16px;
    height: 16px;
}

/* Registered Events Grid Styles */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.registered-event {
    border: 2px solid #fbbf24;
    background: #fffbeb;
}

.registered-event:hover {
    border-color: #f59e0b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.event-card-header {
    border-bottom: 1px solid #fde68a;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
}

.event-type-badge {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.badge-registered {
    background: #fbbf24;
    color: #92400e;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.badge-registered svg {
    width: 14px;
    height: 14px;
}

.event-card h3 {
    color: #1e293b;
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
}

.event-card-body {
    margin-bottom: 1rem;
}

.detail-row {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: #64748b;
    font-size: 0.875rem;
}

.detail-row svg {
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.detail-row span {
    line-height: 1.4;
}

.event-card-footer {
    padding-top: 1rem;
    border-top: 1px solid #fde68a;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* Empty State Styles */
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #64748b;
}

.empty-state svg {
    margin: 0 auto 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 1.125rem;
}

.empty-state p {
    color: #64748b;
    margin: 0;
}

/* Section Header Enhancements */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.section-title svg {
    color: #2563eb;
}

.section-count {
    background: #e2e8f0;
    color: #64748b;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-alumni {
    background: #dbeafe;
    color: #1e40af;
}

.badge-student {
    background: #d1fae5;
    color: #065f46;
}

/* Approval Notice Styles */
.approval-notice {
    background: #dbeafe;
    border: 1px solid #bfdbfe;
    border-radius: 0.5rem;
    padding: 1rem 1.5rem;
    margin: 0 1.5rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #1e40af;
    font-size: 0.875rem;
}

.approval-notice svg {
    flex-shrink: 0;
    color: #2563eb;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 1.5rem 1.5rem 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #64748b;
    padding: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    color: #374151;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Responsive Design */
@media (max-width: 768px) {
    .events-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .calendar-controls {
        justify-content: center;
    }
    
    .calendar-day {
        min-height: 60px;
        padding: 0.25rem;
    }
    
    .event-list-item {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .event-details {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}

@media (max-width: 600px) {
    .calendar-grid {
        gap: 0;
    }
    
    .calendar-day {
        min-height: 50px;
        font-size: 0.875rem;
    }
    
    .legend-item {
        font-size: 0.75rem;
    }
}
</style>

<!-- JavaScript -->
<script src="<?=ROOT?>/assets/js/dashboard.js"></script>
<script>
// Sample events data for JavaScript
const events = <?= json_encode($events) ?>;
const registeredEvents = <?= json_encode($registeredEvents) ?>;

// Current date and calendar state
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let selectedEventId = null;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializeCalendar();
    initializeEventHandlers();
});

function initializeEventHandlers() {
    // View toggle buttons
    document.getElementById('calendarViewBtn').addEventListener('click', () => showCalendarView());
    document.getElementById('listViewBtn').addEventListener('click', () => showListView());
    
    // Calendar navigation
    document.getElementById('prevMonth').addEventListener('click', () => navigateMonth(-1));
    document.getElementById('nextMonth').addEventListener('click', () => navigateMonth(1));
    document.getElementById('todayBtn').addEventListener('click', () => goToToday());
    
    // Modal handlers
    document.getElementById('publishEventBtn').addEventListener('click', () => openPublishEventModal());
}

function showCalendarView() {
    document.getElementById('calendarView').style.display = 'block';
    document.getElementById('listView').style.display = 'none';
    document.getElementById('calendarControls').style.display = 'flex';
    
    document.getElementById('calendarViewBtn').classList.add('active');
    document.getElementById('listViewBtn').classList.remove('active');
}

function showListView() {
    document.getElementById('calendarView').style.display = 'none';
    document.getElementById('listView').style.display = 'block';
    document.getElementById('calendarControls').style.display = 'none';
    
    document.getElementById('listViewBtn').classList.add('active');
    document.getElementById('calendarViewBtn').classList.remove('active');
}

function initializeCalendar() {
    generateCalendar(currentMonth, currentYear);
}

function generateCalendar(month, year) {
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
    // Update month header
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
    
    // Get calendar grid
    const calendarGrid = document.getElementById('calendarGrid');
    calendarGrid.innerHTML = '';
    
    // Add day headers
    dayNames.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.className = 'calendar-day-header';
        dayHeader.textContent = day;
        calendarGrid.appendChild(dayHeader);
    });
    
    // Calculate calendar dates
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const today = new Date();
    const todayDate = today.getDate();
    const todayMonth = today.getMonth();
    const todayYear = today.getFullYear();
    
    // Add previous month's trailing days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        const dayElement = createCalendarDay(day, month - 1, year, true);
        calendarGrid.appendChild(dayElement);
    }
    
    // Add current month's days
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = createCalendarDay(day, month, year, false);
        
        // Check if today
        if (day === todayDate && month === todayMonth && year === todayYear) {
            dayElement.classList.add('today');
        }
        
        calendarGrid.appendChild(dayElement);
    }
    
    // Add next month's leading days
    const totalCells = calendarGrid.children.length - 7; // Subtract day headers
    const remainingCells = 42 - totalCells; // 6 weeks * 7 days
    for (let day = 1; day <= remainingCells; day++) {
        const dayElement = createCalendarDay(day, month + 1, year, true);
        calendarGrid.appendChild(dayElement);
    }
}

function createCalendarDay(day, month, year, otherMonth) {
    const dayElement = document.createElement('div');
    dayElement.className = 'calendar-day';
    if (otherMonth) {
        dayElement.classList.add('other-month');
    }
    
    const dayNumber = document.createElement('div');
    dayNumber.className = 'day-number';
    dayNumber.textContent = day;
    dayElement.appendChild(dayNumber);
    
    // Check for events on this date
    const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const dayEvents = events.filter(event => event.date === dateString);
    
    if (dayEvents.length > 0) {
        dayElement.classList.add('has-events');
        
        const eventDots = document.createElement('div');
        eventDots.className = 'event-dots';
        
        dayEvents.forEach(event => {
            const dot = document.createElement('div');
            dot.className = 'event-dot';
            
            if (event.status === 'past') {
                dot.classList.add('past');
            } else if (event.registered) {
                dot.classList.add('registered');
            } else {
                dot.classList.add(event.type);
            }
            
            eventDots.appendChild(dot);
        });
        
        dayElement.appendChild(eventDots);
    }
    
    // Add click handler
    dayElement.addEventListener('click', () => {
        if (dayEvents.length > 0) {
            openEventModal(dayEvents[0].id);
        }
    });
    
    return dayElement;
}

function navigateMonth(direction) {
    currentMonth += direction;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    generateCalendar(currentMonth, currentYear);
}

function goToToday() {
    const today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    generateCalendar(currentMonth, currentYear);
}

function openEventModal(eventId) {
    selectedEventId = eventId;
    const event = events.find(e => e.id === eventId);
    if (!event) return;
    
    // Populate modal content
    document.getElementById('modalEventTitle').textContent = event.title;
    document.getElementById('modalEventType').textContent = `${event.type.charAt(0).toUpperCase() + event.type.slice(1)} Event`;
    document.getElementById('modalEventType').className = `event-badge ${event.type}`;
    
    document.getElementById('modalEventStatus').textContent = event.status.charAt(0).toUpperCase() + event.status.slice(1);
    document.getElementById('modalEventStatus').className = `status-badge ${event.status}`;
    
    const eventDate = new Date(event.date);
    const eventTime = new Date(`2000-01-01 ${event.time}`);
    document.getElementById('modalEventDateTime').textContent = 
        `${eventDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} at ${eventTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;
    
    document.getElementById('modalEventLocation').textContent = event.location;
    document.getElementById('modalEventOrganizer').textContent = event.organizer;
    document.getElementById('modalEventDescription').textContent = event.description;
    
    // Handle attendee info for upcoming events
    const attendeeInfo = document.getElementById('attendeeInfo');
    if (event.status === 'upcoming' && event.max_attendees) {
        attendeeInfo.style.display = 'flex';
        document.getElementById('modalAttendees').textContent = `${event.current_attendees}/${event.max_attendees} registered`;
    } else {
        attendeeInfo.style.display = 'none';
    }
    
    // Handle past event proof
    const eventProof = document.getElementById('eventProof');
    if (event.status === 'past') {
        eventProof.style.display = 'block';
        if (event.summary) {
            document.getElementById('modalEventSummary').textContent = event.summary;
        }
        if (event.proof_url) {
            document.getElementById('modalProofLink').href = event.proof_url;
        }
    } else {
        eventProof.style.display = 'none';
    }
    
    // Handle registration buttons
    const registerBtn = document.getElementById('modalRegisterBtn');
    const unregisterBtn = document.getElementById('modalUnregisterBtn');
    
    if (event.status === 'upcoming') {
        if (event.registered) {
            registerBtn.style.display = 'none';
            unregisterBtn.style.display = 'inline-block';
        } else {
            registerBtn.style.display = 'inline-block';
            unregisterBtn.style.display = 'none';
        }
    } else {
        registerBtn.style.display = 'none';
        unregisterBtn.style.display = 'none';
    }
    
    // Show modal
    document.getElementById('eventModal').classList.add('active');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.remove('active');
    selectedEventId = null;
}

function registerForEvent(eventId = null) {
    const id = eventId || selectedEventId;
    if (!id) return;
    
    // Here you would make an AJAX call to register for the event
    console.log('Registering for event:', id);
    
    // Update the event in the events array
    const event = events.find(e => e.id === id);
    if (event) {
        event.registered = true;
        event.current_attendees++;
    }
    
    // Refresh the calendar and close modal
    generateCalendar(currentMonth, currentYear);
    if (selectedEventId) {
        closeEventModal();
    }
    
    // Show success message (you can implement a toast notification)
    alert('Successfully registered for the event!');
}

function unregisterFromEvent() {
    if (!selectedEventId) return;
    
    // Here you would make an AJAX call to unregister from the event
    console.log('Unregistering from event:', selectedEventId);
    
    // Update the event in the events array
    const event = events.find(e => e.id === selectedEventId);
    if (event) {
        event.registered = false;
        event.current_attendees--;
    }
    
    // Refresh the calendar and close modal
    generateCalendar(currentMonth, currentYear);
    closeEventModal();
    
    // Show success message
    alert('Successfully unregistered from the event!');
}

function openPublishEventModal() {
    document.getElementById('publishEventModal').classList.add('active');
}

function closePublishEventModal() {
    document.getElementById('publishEventModal').classList.remove('active');
    // Reset form
    document.querySelector('.publish-event-form').reset();
}

function submitNewEvent(event) {
    event.preventDefault();
    
    // Get form data
    const formData = new FormData(event.target);
    const eventData = {
        id: events.length + 1,
        title: formData.get('title'),
        description: formData.get('description'),
        date: formData.get('date'),
        time: formData.get('time'),
        location: formData.get('location'),
        organizer: 'You', // Current user
        type: formData.get('type'),
        registered: false, // Will be pending approval
        status: 'pending',
        approval_status: 'pending',
        max_attendees: parseInt(formData.get('max_attendees')) || 100,
        current_attendees: 0,
        submitted_date: new Date().toISOString().split('T')[0]
    };
    
    // Here you would make an AJAX call to save the event
    console.log('Publishing event:', eventData);
    
    // Close modal
    closePublishEventModal();
    
    // Show success message with admin approval info
    showSuccessMessage('Event submitted successfully! Your event will be visible once approved by Admin.', 'info');
}

// Unregister from event function
function unregisterEvent(eventId) {
    if (confirm('Are you sure you want to unregister from this event?')) {
        // Here you would make an AJAX call to unregister
        console.log('Unregistering from event:', eventId);
        
        // For demo, remove from registered events and refresh page
        const registeredIndex = registeredEvents.findIndex(e => e.id === eventId);
        if (registeredIndex !== -1) {
            registeredEvents.splice(registeredIndex, 1);
        }
        
        // Update main events array
        const event = events.find(e => e.id === eventId);
        if (event) {
            event.registered = false;
            event.current_attendees = Math.max(0, event.current_attendees - 1);
        }
        
        // Refresh the calendar and page
        generateCalendar(currentMonth, currentYear);
        showSuccessMessage('Successfully unregistered from the event!', 'success');
        
        // Simulate page refresh by hiding the unregistered event card
        setTimeout(() => {
            location.reload();
        }, 1500);
    }
}

// Success message function
function showSuccessMessage(message = '', type = 'success') {
    // Create success message element if it doesn't exist
    let messageDiv = document.getElementById('successMessage');
    if (!messageDiv) {
        messageDiv = document.createElement('div');
        messageDiv.id = 'successMessage';
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            z-index: 10000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        `;
        document.body.appendChild(messageDiv);
    }
    
    // Set message and style based on type
    messageDiv.textContent = message;
    if (type === 'success') {
        messageDiv.style.backgroundColor = '#059669';
    } else if (type === 'info') {
        messageDiv.style.backgroundColor = '#2563eb';
    } else if (type === 'warning') {
        messageDiv.style.backgroundColor = '#d97706';
    }
    
    // Show message
    setTimeout(() => {
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateX(0)';
    }, 100);
    
    // Hide message after 4 seconds
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateX(100%)';
    }, 4000);
}

// Sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add sidebar toggle functionality if not already initialized
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    // Function to ensure icons are visible
    function ensureIconsVisible() {
        const navIcons = document.querySelectorAll('.nav-icon');
        navIcons.forEach(icon => {
            icon.style.display = 'block';
            icon.style.visibility = 'visible';
            icon.style.opacity = '1';
        });
    }
    
    // Call on page load
    ensureIconsVisible();
    
    // Handle manual sidebar toggle
    if (sidebarToggle && sidebar && !sidebarToggle.hasAttribute('data-initialized')) {
        sidebarToggle.setAttribute('data-initialized', 'true');
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Update main content margin
            const mainContent = document.querySelector('.main-content');
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '70px';
            } else {
                mainContent.style.marginLeft = '280px';
            }
            
            // Ensure icons remain visible after toggle
            setTimeout(ensureIconsVisible, 100);
        });
    }
    
    // Handle responsive behavior
    function handleResize() {
        if (window.innerWidth <= 1024) {
            sidebar.classList.add('collapsed');
            document.querySelector('.main-content').style.marginLeft = '70px';
        } else {
            sidebar.classList.remove('collapsed');
            document.querySelector('.main-content').style.marginLeft = '280px';
        }
        // Ensure icons are visible after resize
        setTimeout(ensureIconsVisible, 100);
    }
    
    // Initial call
    handleResize();
    
    // Listen for resize events
    window.addEventListener('resize', handleResize);
    
    // Observe DOM changes to ensure icons stay visible
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                ensureIconsVisible();
            }
        });
    });
    
    if (sidebar) {
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>

<?php require '../app/views/partials/footer.php'; ?>
