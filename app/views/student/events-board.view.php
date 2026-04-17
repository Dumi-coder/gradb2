<?php 
$page_title = "Events Board";
$page_subtitle = "Discover & Join Campus Activities";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/events-board.css">

<div class="dashboard-container">
      
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>

       <!-- Main Content Area -->
      <main class="main-content">
        <!-- Events Header Section -->
        <section class="dashboard-section events-header-section">
          <div class="section-header">
            <h2 class="card-title">Upcoming Events</h2>
            <div class="header-actions">
              <!-- <button class="btn btn-outline" onclick="openFilterModal()">
                <i class="fas fa-filter"></i>
                <span>Filter Events</span>
              </button> -->
              <button class="btn btn-outline btn-sm" onclick="viewAllFeatured()">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
          
          <!-- Event Categories -->
          <!--<div class="event-categories">
            <div class="category-tab active" data-category="all">
              <i class="fas fa-calendar"></i>
              <span>All Events</span>
            </div>
            <div class="category-tab" data-category="academic">
              <i class="fas fa-graduation-cap"></i>
              <span>Academic</span>
            </div>
            <div class="category-tab" data-category="social">
              <i class="fas fa-users"></i>
              <span>Social</span>
            </div>
            <div class="category-tab" data-category="career">
              <i class="fas fa-briefcase"></i>
              <span>Career</span>
            </div>
            <div class="category-tab" data-category="workshop">
              <i class="fas fa-tools"></i>
              <span>Workshops</span>
            </div>
          </div>
        </section> -->

        <!-- Featured Events Section -->
        <section class="dashboard-section featured-events-section">
          <!-- <div class="section-header" style="display:flex; justify-content:flex-end; align-items:center;">
            <button class="btn btn-outline btn-sm" onclick="viewAllFeatured()">
              <span>View All</span>
              <i class="fas fa-arrow-right"></i>
            </button>
          </div> -->
          
          <div class="featured-events-grid">
            <?php if (!empty($upcomingEvents)): ?>
              <?php foreach ($upcomingEvents as $index => $event): ?>
                <div class="featured-event-card js-card-student-upcoming" <?= $index >= 2 ? 'style="display:none;"' : '' ?>>
                  <div class="event-image" style="background-color: #E0EBF9;">
                    <?php if (!empty($event['image_path'])): ?>
                      <img src="<?=ROOT?><?= esc($event['image_path']) ?>" alt="<?= esc($event['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                  </div>
                  <div class="event-content">
                    <div class="event-category <?= esc($event['category']) ?>"><?= ucfirst(esc($event['category'])) ?></div>
                    <h3 class="event-title"><?= esc($event['title']) ?></h3>
                    <p class="event-description"><?= esc($event['description']) ?></p>
                    <div class="event-meta">
                      <?php $modeLabel = ucfirst($event['mode'] ?? 'offline'); ?>
                      <?php if (strtolower((string)$modeLabel) === 'offline') { $modeLabel = 'Physical'; } ?>
                      <span class="event-time"><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($event['event_date'])) ?></span>
                      <span class="event-time"><i class="fas fa-clock"></i> <?= date('g:i A', strtotime($event['start_time'])) ?> - <?= date('g:i A', strtotime($event['end_time'])) ?></span>
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> <?= esc($event['venue']) ?></span>
                      <span class="event-mode-badge mode-<?= strtolower(esc($event['mode'] ?? 'offline')) ?>">
                        <i class="fas fa-video"></i> <?= esc($modeLabel) ?>
                      </span>
                    </div>
                    <?php if (!empty($event['tags'])): ?>
                      <div class="event-tags-row">
                        <?php foreach (array_filter(array_map('trim', explode(',', (string)$event['tags']))) as $tag): ?>
                          <span class="event-tag-chip">#<?= esc($tag) ?></span>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                    <div class="event-stats">
                      <?php
                      $registeredCount = (int)($event['registered_count'] ?? 0);
                      $spotsLeft = !empty($event['max_attendees']) ? max(0, ((int)$event['max_attendees'] - $registeredCount)) : 'Unlimited';
                      $isClosed = (($event['registration_status'] ?? 'open') !== 'open');
                      $isFull = !empty($event['max_attendees']) && $registeredCount >= (int)$event['max_attendees'];
                      ?>
                      <span class="attendees"><i class="fas fa-users"></i> <?= $registeredCount ?> registered</span>
                      <?php if ($event['max_attendees']): ?>
                        <span class="spots-left"><i class="fas fa-ticket-alt"></i> <?= $spotsLeft ?> spots left</span>
                      <?php endif; ?>
                      <?php if ($isClosed): ?>
                        <span class="spots-left"><i class="fas fa-lock"></i> Registrations Closed</span>
                      <?php elseif ($isFull): ?>
                        <span class="spots-left"><i class="fas fa-ban"></i> Registrations Full</span>
                      <?php endif; ?>
                    </div>
                    <div class="event-actions">
                      <?php if (!empty($event['registration_link'])): ?>
                        <a class="btn btn-outline btn-sm" href="<?= esc($event['registration_link']) ?>" target="_blank" rel="noopener noreferrer">
                          <i class="fas fa-external-link-alt"></i>
                          <span>View Details</span>
                        </a>
                      <?php endif; ?>
                      <?php if ($isClosed || $isFull): ?>
                        <button type="button" class="btn btn-primary btn-sm btn-disabled-ash" disabled>
                          <i class="fas fa-calendar-plus"></i>
                          <span><?= $isClosed ? 'Registrations Closed' : 'Registrations Full' ?></span>
                        </button>
                      <?php else: ?>
                        <button type="button" class="btn btn-primary btn-sm" data-event-id="<?= (int)$event['event_id'] ?>" data-event-title="<?= esc($event['title']) ?>" onclick='openRegisterModal(<?= (int)$event["event_id"] ?>, <?= json_encode($event["title"]) ?>)'>
                          <i class="fas fa-calendar-plus"></i>
                          <span>Register Now</span>
                        </button>
                      <?php endif; ?>
                      <button class="btn btn-outline btn-sm">
                        <i class="fas fa-heart"></i>
                        <span>Save</span>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="text-align: center; color: var(--muted-foreground); padding: 2rem;">
                No upcoming events at the moment.
              </p>
            <?php endif; ?>
          </div>
        </section>

        <!-- Upcoming Events Section -->
        <!--<section class="dashboard-section upcoming-events-section">
          <div class="section-header">
            <h2 class="section-title">This Week's Events</h2>
            <div class="section-actions">
              <button class="btn btn-outline btn-sm" onclick="viewCalendar()">
                <i class="fas fa-calendar-alt"></i>
                <span>Calendar View</span>
              </button>
              <button class="btn btn-outline btn-sm" onclick="exportEvents()">
                <i class="fas fa-download"></i>
                <span>Export</span>
              </button>
            </div>
          </div>
          
          <div class="events-timeline">
            <div class="timeline-day">
              <div class="day-header">
                <h3 class="day-title">Monday, December 16</h3>
                <span class="event-count">3 events</span>
              </div>
              
              <div class="day-events">
                <div class="timeline-event">
                  <div class="event-time">09:00 AM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Study Group: Algorithms</h4>
                      <span class="event-category academic">Academic</span>
                    </div>
                    <p class="event-description">Weekly study session focusing on algorithm design and problem-solving techniques.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Library Study Room 3</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 2 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Join Group</button>
                  </div>
                </div>
                
                <div class="timeline-event">
                  <div class="event-time">02:00 PM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Resume Writing Workshop</h4>
                      <span class="event-category career">Career</span>
                    </div>
                    <p class="event-description">Learn how to create a compelling resume that stands out to employers.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Career Center</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 1.5 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Register</button>
                  </div>
                </div>
                
                <div class="timeline-event">
                  <div class="event-time">06:00 PM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Movie Night: The Social Network</h4>
                      <span class="event-category social">Social</span>
                    </div>
                    <p class="event-description">Movie screening followed by discussion about entrepreneurship and tech innovation.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Student Lounge</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 3 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Join Event</button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="timeline-day">
              <div class="day-header">
                <h3 class="day-title">Tuesday, December 17</h3>
                <span class="event-count">2 events</span>
              </div>
              
              <div class="day-events">
                <div class="timeline-event">
                  <div class="event-time">10:00 AM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Python Programming Workshop</h4>
                      <span class="event-category workshop">Workshop</span>
                    </div>
                    <p class="event-description">Learn Python basics and build your first web application.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Computer Lab B</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 3 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">Register</button>
                  </div>
                </div>
                
                <div class="timeline-event">
                  <div class="event-time">04:00 PM</div>
                  <div class="event-dot"></div>
                  <div class="event-card">
                    <div class="event-header">
                      <h4 class="event-title">Networking Mixer</h4>
                      <span class="event-category career">Career</span>
                    </div>
                    <p class="event-description">Connect with alumni and industry professionals in a relaxed setting.</p>
                    <div class="event-meta">
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> Alumni Hall</span>
                      <span class="event-duration"><i class="fas fa-clock"></i> 2 hours</span>
                    </div>
                    <button class="btn btn-outline btn-sm">RSVP</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>-->

        <!-- My Events Section -->
        <section class="dashboard-section my-events-section">
          <div class="section-header">
            <h2 class="card-title">My Events</h2>
            <?php if (!empty($registeredEvents) && count($registeredEvents) > 2): ?>
              <button class="btn btn-outline btn-sm" id="studentMyEventsToggleBtn" onclick="toggleEventCards('student-my-events', this)">
                <span>View More</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            <?php endif; ?>
          </div>
          
          <div class="my-events-grid">
            <?php if (!empty($registeredEvents)): ?>
              <?php foreach ($registeredEvents as $index => $event): ?>
                <div class="my-event-card js-card-student-my-events" <?= $index >= 2 ? 'style="display:none;"' : '' ?>>
                  <div class="event-status registered">Registered</div>
                  <div class="event-content">
                    <h3 class="event-title"><?= esc($event['title']) ?></h3>
                    
                    <?php if (!empty($event['notifications'])): ?>
                      <div class="event-notifications" style="background-color: #FFF3CD; border-left: 4px solid #FFC107; padding: var(--spacing-sm); margin-bottom: var(--spacing-md); border-radius: var(--radius-sm);">
                        <?php foreach ($event['notifications'] as $notif): ?>
                          <div class="notification-item" style="display: flex; align-items: start; gap: var(--spacing-xs); margin-bottom: var(--spacing-xs);">
                            <i class="fas fa-info-circle" style="color: #856404; margin-top: 2px;"></i>
                            <span style="color: #856404; font-size: var(--font-sm);">
                              <?php
                              if ($notif['notification_type'] === 'venue_changed') {
                                  echo "Venue changed to: " . esc($notif['new_value']);
                              } elseif ($notif['notification_type'] === 'date_changed') {
                                  echo "Date changed to: " . date('M d, Y', strtotime($notif['new_value']));
                              } elseif ($notif['notification_type'] === 'time_changed') {
                                  echo "Time changed to: " . esc($notif['new_value']);
                              } elseif ($notif['notification_type'] === 'event_cancelled') {
                                  echo "<strong>Event Cancelled</strong>";
                              } elseif ($notif['notification_type'] === 'event_postponed') {
                                  echo "Event postponed to: " . date('M d, Y', strtotime($notif['new_value']));
                              } else {
                                  echo esc($notif['message']);
                              }
                              ?>
                            </span>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                    
                    <div class="event-meta">
                      <span class="event-time"><i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($event['event_date'])) ?>, <?= date('g:i A', strtotime($event['start_time'])) ?></span>
                      <span class="event-location"><i class="fas fa-map-marker-alt"></i> <?= esc($event['venue']) ?></span>
                    </div>
                    <div class="event-actions">
                      <?php if (!empty($event['registration_link'])): ?>
                        <a class="btn btn-outline btn-sm" href="<?= esc($event['registration_link']) ?>" target="_blank" rel="noopener noreferrer">
                          <i class="fas fa-external-link-alt"></i>
                          <span>View Details</span>
                        </a>
                      <?php endif; ?>
                      <button class="btn btn-outline btn-sm" onclick="unregisterEvent(<?= $event['event_id'] ?>)">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="text-align: center; color: var(--muted-foreground); padding: 2rem;">
                You haven't registered for any events yet.
              </p>
            <?php endif; ?>
          </div>
        </section>

        <!-- Event Statistics Section -->
        <section class="dashboard-section event-stats-section">
          <div class="section-header">
            <h2 class="card-title">Event Activity</h2>
          </div>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= (int)($eventStats['events_this_month'] ?? 0) ?></h3>
                <p class="stat-label">Events This Month</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= (int)($eventStats['total_registrations'] ?? 0) ?></h3>
                <p class="stat-label">Total Registrations</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= (int)($eventStats['upcoming_this_week'] ?? 0) ?></h3>
                <p class="stat-label">Upcoming This Week</p>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- New Event Modal -->
    <div id="newEventModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Event</h2>
          <button class="modal-close" onclick="closeNewEventModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form class="new-event-form">
          <div class="form-group">
            <label for="eventTitle">Event Title *</label>
            <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter a clear, descriptive title for your event" required>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="eventCategory">Category *</label>
              <select id="eventCategory" name="eventCategory" required>
                <option value="">Select a category</option>
                <option value="academic">Academic</option>
                <option value="social">Social</option>
                <option value="career">Career</option>
                <option value="workshop">Workshop</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="eventDate">Event Date *</label>
              <input type="date" id="eventDate" name="eventDate" required>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="startTime">Start Time *</label>
              <input type="time" id="startTime" name="startTime" required>
            </div>
            
            <div class="form-group">
              <label for="endTime">End Time *</label>
              <input type="time" id="endTime" name="endTime" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="eventLocation">Location *</label>
            <input type="text" id="eventLocation" name="eventLocation" placeholder="Enter event location" required>
          </div>
          
          <div class="form-group">
            <label for="eventDescription">Event Description *</label>
            <textarea id="eventDescription" name="eventDescription" rows="4" placeholder="Describe your event in detail. What will attendees learn or experience?" required></textarea>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="maxAttendees">Maximum Attendees</label>
              <input type="number" id="maxAttendees" name="maxAttendees" placeholder="Leave empty for unlimited" min="1">
            </div>
            
            <div class="form-group">
              <label for="eventTags">Tags</label>
              <input type="text" id="eventTags" name="eventTags" placeholder="e.g., python, networking, career">
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeNewEventModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-calendar-plus"></i>
              <span>Create Event</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Register Now Modal -->
    <div id="registerModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Register for the Event</h2>
          <button class="modal-close" onclick="closeRegisterModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form class="register-event-form" id="registerEventForm">
          <input type="hidden" id="registerEventId" name="eventId">
          <input type="hidden" name="participantName" value="<?= esc($_SESSION['name'] ?? '') ?>">
          <input type="hidden" name="participantEmail" value="<?= esc($_SESSION['email'] ?? '') ?>">
          <input type="hidden" name="participantRole" value="student">
          <div class="form-row">
            <div class="form-group">
              <label for="participantName">Full Name *</label>
              <input type="text" id="participantName" value="<?= esc($_SESSION['name'] ?? '') ?>" readonly>
            </div>

            <div class="form-group">
              <label for="participantEmail">Email *</label>
              <input type="email" id="participantEmail" value="<?= esc($_SESSION['email'] ?? '') ?>" readonly>
            </div>

            <div class="form-group">
              <label for="participantRole">Role *</label>
              <input type="text" id="participantRole" value="Student" readonly>
            </div>

            <div class="form-group">
              <label for="eventName">Event Name *</label>
              <input type="text" id="eventName" name="eventName" placeholder="Event name will appear here" readonly>
            </div>

            <div class="form-group">
              <label for="specialNotes">Special Notes</label>
              <textarea id="specialNotes" name="specialNotes" rows="3" placeholder="Any specific requests or comments?"></textarea>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeRegisterModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <span>Register Now</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    
    <!-- Filter Events Modal
    <div id="filterModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Filter Events</h2>
          <button class="modal-close" onclick="closeFilterModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="filter-content">
          <div class="filter-section">
            <h3>Categories</h3>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" value="academic" checked>
                <span>Academic</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="social" checked>
                <span>Social</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="career" checked>
                <span>Career</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="workshop" checked>
                <span>Workshops</span>
              </label>
            </div>
          </div>
          
          <div class="filter-section">
            <h3>Date Range</h3>
            <div class="filter-options">
              <label class="filter-option">
                <input type="radio" name="dateRange" value="today" checked>
                <span>Today</span>
              </label>
              <label class="filter-option">
                <input type="radio" name="dateRange" value="week">
                <span>This Week</span>
              </label>
              <label class="filter-option">
                <input type="radio" name="dateRange" value="month">
                <span>This Month</span>
              </label>
              <label class="filter-option">
                <input type="radio" name="dateRange" value="custom">
                <span>Custom Range</span>
              </label>
            </div>
          </div>
          
          <div class="filter-section">
            <h3>Time of Day</h3>
            <div class="filter-options">
              <label class="filter-option">
                <input type="checkbox" value="morning" checked>
                <span>Morning (6 AM - 12 PM)</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="afternoon" checked>
                <span>Afternoon (12 PM - 6 PM)</span>
              </label>
              <label class="filter-option">
                <input type="checkbox" value="evening" checked>
                <span>Evening (6 PM - 12 AM)</span>
              </label>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button class="btn btn-outline" onclick="resetFilters()">
            <span>Reset Filters</span>
          </button>
          <button class="btn btn-primary" onclick="applyFilters()">
            <span>Apply Filters</span>
          </button>
        </div>
      </div>
    </div> -->

    <script src="<?=ROOT?>/assets/js/events-board.js"></script>
    <script>
      let currentRegisterEventId = null;

      function toggleEventCards(sectionKey, button) {
        const cards = document.querySelectorAll('.js-card-' + sectionKey);
        if (!cards.length) return;

        const hasHidden = Array.from(cards).some(card => card.style.display === 'none');
        cards.forEach((card, index) => {
          if (hasHidden) {
            card.style.display = '';
          } else {
            card.style.display = index < 2 ? '' : 'none';
          }
        });

        const label = button.querySelector('span');
        if (label) {
          label.textContent = hasHidden ? 'View Less' : 'View More';
        }
      }

      function openRegisterModal(eventIdOrTitle, eventTitle) {
        const modal = document.getElementById('registerModal');
        const eventNameInput = document.getElementById('eventName');
        const eventIdInput = document.getElementById('registerEventId');

        let resolvedEventId = null;
        let resolvedTitle = '';

        if (typeof eventIdOrTitle === 'number') {
          resolvedEventId = eventIdOrTitle;
          resolvedTitle = eventTitle || '';
        } else {
          resolvedTitle = eventIdOrTitle || '';
          const activeBtn = document.activeElement;
          if (activeBtn && activeBtn.dataset && activeBtn.dataset.eventId) {
            resolvedEventId = parseInt(activeBtn.dataset.eventId, 10);
          }
        }

        currentRegisterEventId = resolvedEventId;
        if (eventNameInput) eventNameInput.value = resolvedTitle;
        if (eventIdInput) eventIdInput.value = resolvedEventId || '';

        if (modal) {
          modal.style.display = 'block';
          document.body.style.overflow = 'hidden';
        }
      }

      function closeRegisterModal() {
        const modal = document.getElementById('registerModal');
        const form = document.getElementById('registerEventForm');
        currentRegisterEventId = null;

        if (modal) {
          modal.style.display = 'none';
          document.body.style.overflow = 'auto';
        }

        if (form) {
          form.reset();
        }
      }

      async function unregisterEvent(eventId) {
        if (!confirm('Cancel your registration for this event?')) {
          return;
        }

        const formData = new FormData();
        formData.append('eventId', eventId);

        try {
          const response = await fetch('<?=ROOT?>/student/eventsboard/unregister', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();
          showNotification(result.message || 'Updated', result.success ? 'success' : 'error');
          if (result.success) {
            setTimeout(() => location.reload(), 700);
          }
        } catch (err) {
          console.error(err);
          showNotification('Failed to cancel registration', 'error');
        }
      }

      document.getElementById('registerEventForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const eventId = currentRegisterEventId || parseInt(document.getElementById('registerEventId')?.value || '0', 10);
        if (!eventId) {
          showNotification('Event ID is missing', 'error');
          return;
        }

        const formData = new FormData();
        formData.append('eventId', eventId);

        try {
          const response = await fetch('<?=ROOT?>/student/eventsboard/register', {
            method: 'POST',
            body: formData
          });
          const result = await response.json();
          if (!result.success && (result.message || '').toLowerCase().includes('already registered')) {
            alert('You are already registered for this event.');
          }
          showNotification(result.message || 'Updated', result.success ? 'success' : 'error');
          if (result.success) {
            closeRegisterModal();
            setTimeout(() => location.reload(), 700);
          }
        } catch (err) {
          console.error(err);
          showNotification('Failed to register', 'error');
        }
      });

      window.openRegisterModal = openRegisterModal;
      window.closeRegisterModal = closeRegisterModal;
      window.unregisterEvent = unregisterEvent;
      window.toggleEventCards = toggleEventCards;

      const studentUpcomingToggleBtn = document.querySelector('.events-header-section .btn.btn-outline.btn-sm');
      if (studentUpcomingToggleBtn) {
        const upcomingCards = document.querySelectorAll('.js-card-student-upcoming');
        if (upcomingCards.length > 2) {
          studentUpcomingToggleBtn.setAttribute('onclick', "toggleEventCards('student-upcoming', this)");
          const label = studentUpcomingToggleBtn.querySelector('span');
          if (label) label.textContent = 'View More';
        } else {
          studentUpcomingToggleBtn.style.display = 'none';
        }
      }
    </script>
  </body>
</html>

