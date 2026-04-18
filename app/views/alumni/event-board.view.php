<?php 
$page_title = "Events Board";
$page_subtitle = "Discover & Join Campus Activities";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/events-board.css">

<div class="dashboard-container">
      
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/alumni_sidebar.php'; ?>

       <!-- Main Content Area -->
      <main class="main-content">
        <!-- Events Header Section -->
        <section class="dashboard-section events-header-section">
          <div class="section-header">
            <h2 class="card-title">Upcoming Events</h2>
            <div class="header-actions">
              <button class="btn btn-primary" onclick="openNewEventModal()">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
              </button>
              <button class="btn btn-outline btn-sm" onclick="viewAllFeatured()">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </section>

        <!-- Featured Events Section -->
        <section class="dashboard-section featured-events-section">
          <div class="featured-events-grid">
            <?php if (!empty($upcomingEvents)): ?>
              <?php foreach ($upcomingEvents as $index => $event): ?>
                <div class="featured-event-card js-card-alumni-upcoming" <?= $index >= 2 ? 'style="display:none;"' : '' ?>>
                  <div class="event-image" style="background-color: #E0EBF9;">
                    <?php if (!empty($event['image_path'])): ?>
                      <img src="<?=ROOT?><?= esc($event['image_path']) ?>" alt="<?= esc($event['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                  </div>
                  <div class="event-content">
                    <div class="event-category <?= esc($event['category']) ?>"><?= ucfirst(esc($event['category'])) ?></div>
                    <h3 class="event-title"><?= esc($event['title']) ?></h3>
                    <p class="event-caption-line">
                      <span><?= date('M d, Y', strtotime($event['event_date'])) ?></span>
                      <span class="caption-dot">&middot;</span>
                      <span><?= date('g:i A', strtotime($event['start_time'])) ?> - <?= date('g:i A', strtotime($event['end_time'])) ?></span>
                      <?php if (!empty($event['venue'])): ?>
                        <span class="caption-dot">&middot;</span>
                        <span><?= esc($event['venue']) ?></span>
                      <?php endif; ?>
                    </p>
                    <p class="event-description"><?= esc($event['description']) ?></p>
                    <?php $modeLabel = ucfirst($event['mode'] ?? 'offline'); ?>
                    <?php if (strtolower((string)$modeLabel) === 'offline') { $modeLabel = 'Physical'; } ?>
                    <span class="event-mode-badge mode-<?= strtolower(esc($event['mode'] ?? 'offline')) ?>">
                      <i class="fas fa-video"></i> <?= esc($modeLabel) ?>
                    </span>
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
                        $isOwnEvent = ((int)($event['host_alumnus_id'] ?? 0) === (int)($_SESSION['user_id'] ?? 0));
                      ?>
                      <span class="attendees"><i class="fas fa-users"></i> <?= $registeredCount ?> registered</span>
                      <?php if (!empty($event['max_attendees'])): ?>
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
                      <?php if ($isOwnEvent): ?>
                        <button type="button" class="btn btn-primary btn-sm btn-disabled-ash" disabled>
                          <i class="fas fa-user-check"></i>
                          <span>Your Event</span>
                        </button>
                      <?php elseif ($isClosed || $isFull): ?>
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
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="text-align: center; color: var(--muted-foreground); padding: 2rem;">
                No upcoming events yet. Create one to get started.
              </p>
            <?php endif; ?>
          </div>
        </section>

        <!-- Events I'm Hosting Section -->
        <section class="dashboard-section hosting-events-section">
          <div class="section-header">
            <h2 class="card-title">Events I'm Hosting</h2>
            <button class="btn btn-outline btn-sm" onclick="viewAllHostingEvents()">
              <span>Manage All</span>
              <i class="fas fa-arrow-right"></i>
            </button>
          </div>
          
          <div class="hosting-events-grid">
            <?php if (!empty($hostingEvents)): ?>
              <?php foreach ($hostingEvents as $event): ?>
                <div class="hosting-event-card">
                  <div class="event-header">
                    <h3 class="event-title"><?= esc($event['title']) ?></h3>
                    <span class="status-badge status-<?= $event['registration_status'] === 'open' ? 'active' : 'closed' ?>">
                      Registrations <?= ucfirst($event['registration_status']) ?>
                    </span>
                  </div>
                  <div class="event-meta">
                    <span class="event-time"><i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($event['event_date'])) ?>, <?= date('g:i A', strtotime($event['start_time'])) ?></span>
                    <span class="event-location"><i class="fas fa-map-marker-alt"></i> <?= esc($event['venue']) ?></span>
                  </div>
                  <div class="event-stats">
                    <span class="registrants"><i class="fas fa-users"></i> <?= $event['registered_count'] ?? 0 ?> registered</span>
                    <?php if ($event['max_attendees']): ?>
                      <span class="capacity"><i class="fas fa-ticket-alt"></i> <?= $event['max_attendees'] ?> capacity</span>
                    <?php endif; ?>
                  </div>
                  <div class="event-actions">
                    <?php if (!empty($event['registration_link'])): ?>
                      <a class="btn btn-outline btn-sm" href="<?= esc($event['registration_link']) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> View Details
                      </a>
                    <?php endif; ?>
                    <button class="btn btn-outline btn-sm js-host-edit" data-event-id="<?= (int)$event['event_id'] ?>" type="button">
                      <i class="fas fa-edit"></i> Edit Details
                    </button>
                    <?php if ($event['registration_status'] === 'open'): ?>
                      <button class="btn btn-danger btn-sm js-host-toggle" data-event-id="<?= (int)$event['event_id'] ?>" type="button">
                        <i class="fas fa-lock"></i> Close Registrations
                      </button>
                    <?php else: ?>
                      <button class="btn btn-success btn-sm js-host-toggle" data-event-id="<?= (int)$event['event_id'] ?>" type="button">
                        <i class="fas fa-unlock"></i> Reopen Registrations
                      </button>
                    <?php endif; ?>
                    <button class="btn btn-danger btn-sm js-host-delete" data-event-id="<?= (int)$event['event_id'] ?>" type="button">
                      <i class="fas fa-trash"></i> Delete
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="text-align: center; color: var(--muted-foreground); padding: 2rem;">
                You haven't created any events yet. Click "Create Event" to get started!
              </p>
            <?php endif; ?>
          </div>
        </section>

        <!-- My Registered Events Section -->
        <section class="dashboard-section my-events-section">
          <div class="section-header">
            <h2 class="card-title">Events I'm Attending</h2>
            <?php if (!empty($attendingEvents) && count($attendingEvents) > 2): ?>
              <button class="btn btn-outline btn-sm" id="alumniAttendingToggleBtn" onclick="toggleEventCards('alumni-attending', this)">
                <span>View More</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            <?php endif; ?>
          </div>
          
          <div class="my-events-grid">
            <?php if (!empty($attendingEvents)): ?>
              <?php foreach ($attendingEvents as $index => $event): ?>
                <div class="my-event-card js-card-alumni-attending" <?= $index >= 2 ? 'style="display:none;"' : '' ?>>
                  <div class="my-event-image" style="background-color: #E0EBF9;">
                    <?php if (!empty($event['image_path'])): ?>
                      <img src="<?=ROOT?><?= esc($event['image_path']) ?>" alt="<?= esc($event['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                  </div>
                  <div class="event-content">
                    <div class="my-event-topline">
                      <div class="event-status registered">Registered</div>
                    </div>
                    <h3 class="event-title"><?= esc($event['title']) ?></h3>
                    <p class="my-event-caption-line">
                      <span><?= date('M d, Y', strtotime($event['event_date'])) ?></span>
                      <span class="caption-dot">&middot;</span>
                      <span><?= date('g:i A', strtotime($event['start_time'])) ?></span>
                      <?php if (!empty($event['venue'])): ?>
                        <span class="caption-dot">&middot;</span>
                        <span><?= esc($event['venue']) ?></span>
                      <?php endif; ?>
                    </p>

                    <?php $myEventModeLabel = ucfirst($event['mode'] ?? 'offline'); ?>
                    <?php if (strtolower((string)$myEventModeLabel) === 'offline') { $myEventModeLabel = 'Physical'; } ?>
                    <span class="event-mode-badge mode-<?= strtolower(esc($event['mode'] ?? 'offline')) ?>">
                      <i class="fas fa-video"></i> <?= esc($myEventModeLabel) ?>
                    </span>

                    <div class="event-actions">
                      <?php if (!empty($event['registration_link'])): ?>
                        <a class="btn btn-outline btn-sm" href="<?= esc($event['registration_link']) ?>" target="_blank" rel="noopener noreferrer">
                          <i class="fas fa-external-link-alt"></i>
                          <span>View Details</span>
                        </a>
                      <?php endif; ?>
                      <button class="btn btn-outline btn-sm btn-unregister" onclick="unregisterEvent(<?= (int)$event['event_id'] ?>)">
                        <i class="fas fa-times"></i>
                        <span>Unregister</span>
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
        
        <form class="new-event-form" id="createEventForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="eventTitle">Event Title *</label>
            <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter a clear, descriptive title for your event" required>
          </div>
          
          <div class="form-group">
            <label for="eventImage">Event Image</label>
            <input type="file" id="eventImage" name="event_image" accept="image/*">
            <small style="color: var(--muted-foreground); font-size: var(--font-xs); display: block; margin-top: 4px;">Upload an image for your event (optional)</small>
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
              <label for="eventMode">Mode *</label>
              <select id="eventMode" name="eventMode" required>
                <option value="offline">Physical</option>
                <option value="online">Online</option>
                <option value="hybrid">Hybrid</option>
              </select>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="eventDate">Event Date *</label>
              <input type="date" id="eventDate" name="eventDate" required>
            </div>
            
            <div class="form-group">
              <label for="startTime">Start Time *</label>
              <input type="time" id="startTime" name="startTime" required>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="endTime">End Time *</label>
              <input type="time" id="endTime" name="endTime" required>
            </div>
            
            <div class="form-group">
              <label for="maxAttendees">Maximum Attendees</label>
              <input type="number" id="maxAttendees" name="maxAttendees" placeholder="Leave empty for unlimited" min="1">
            </div>
          </div>
          
          <div class="form-group">
            <label for="eventLocation">Venue/Location *</label>
            <input type="text" id="eventLocation" name="eventLocation" placeholder="Enter event venue or location" required>
          </div>

          <div class="form-group" id="createSessionLinkGroup" style="display:none;">
            <label for="externalLink">Session Link (Zoom/Meet) *</label>
            <input type="url" id="externalLink" name="externalLink" placeholder="https://zoom.us/j/...">
            <small style="color: var(--muted-foreground); font-size: var(--font-xs); display: block; margin-top: 4px;">Add the live meeting link for online events.</small>
          </div>
          
          
          <div class="form-group">
            <label for="eventDescription">Event Description *</label>
            <textarea id="eventDescription" name="eventDescription" rows="4" placeholder="Describe your event in detail. What will attendees learn or experience?" required></textarea>
          </div>
          
          <div class="form-group">
            <label for="eventTags">Tags</label>
            <input type="text" id="eventTags" name="eventTags" placeholder="e.g., python, networking, career">
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

    <!-- Edit Event Modal -->
    <div id="editEventModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Edit Event</h2>
          <button class="modal-close" onclick="closeEditEventModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form class="new-event-form" id="editEventForm" enctype="multipart/form-data">
          <input type="hidden" id="editEventId" name="eventId">
          
          <div class="form-group">
            <label for="editEventTitle">Event Title *</label>
            <input type="text" id="editEventTitle" name="eventTitle" placeholder="Enter a clear, descriptive title for your event" required>
          </div>
          
          <div class="form-group">
            <label for="editEventImage">Event Image</label>
            <input type="file" id="editEventImage" name="event_image" accept="image/*">
            <small style="color: var(--muted-foreground); font-size: var(--font-xs); display: block; margin-top: 4px;">Upload a new image to replace the current one (optional)</small>
            <div id="currentEventImage" style="margin-top: 8px;"></div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="editEventCategory">Category *</label>
              <select id="editEventCategory" name="eventCategory" required>
                <option value="">Select a category</option>
                <option value="academic">Academic</option>
                <option value="social">Social</option>
                <option value="career">Career</option>
                <option value="workshop">Workshop</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="editEventMode">Mode *</label>
              <select id="editEventMode" name="eventMode" required>
                <option value="offline">Physical</option>
                <option value="online">Online</option>
                <option value="hybrid">Hybrid</option>
              </select>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="editEventDate">Event Date *</label>
              <input type="date" id="editEventDate" name="eventDate" required>
            </div>
            
            <div class="form-group">
              <label for="editStartTime">Start Time *</label>
              <input type="time" id="editStartTime" name="startTime" required>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="editEndTime">End Time *</label>
              <input type="time" id="editEndTime" name="endTime" required>
            </div>
            
            <div class="form-group">
              <label for="editMaxAttendees">Maximum Attendees</label>
              <input type="number" id="editMaxAttendees" name="maxAttendees" placeholder="Leave empty for unlimited" min="1">
            </div>
          </div>
          
          <div class="form-group">
            <label for="editEventLocation">Venue/Location *</label>
            <input type="text" id="editEventLocation" name="eventLocation" placeholder="Enter event venue or location" required>
          </div>

          <div class="form-group">
            <label for="editExternalLink">External Details Link</label>
            <input type="url" id="editExternalLink" name="externalLink" placeholder="https://example.com/event-details">
            <small style="color: var(--muted-foreground); font-size: var(--font-xs); display: block; margin-top: 4px;">Optional link for more event details.</small>
          </div>
          
          
          <div class="form-group">
            <label for="editEventDescription">Event Description *</label>
            <textarea id="editEventDescription" name="eventDescription" rows="4" placeholder="Describe your event in detail. What will attendees learn or experience?" required></textarea>
          </div>
          
          <div class="form-group">
            <label for="editEventTags">Tags</label>
            <input type="text" id="editEventTags" name="eventTags" placeholder="e.g., python, networking, career">
          </div>
          
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeEditEventModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              <span>Update Event</span>
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
          <input type="hidden" name="participantRole" value="alumni">
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
              <input type="text" id="participantRole" value="Alumni" readonly>
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

    <!-- Confirmation Modal -->
    <div id="confirmActionModal" class="modal confirm-action-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Confirm Action</h2>
          <button class="modal-close" id="confirmActionClose" type="button">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="confirm-action-body">
          <p id="confirmActionMessage">Are you sure?</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" id="confirmActionCancel" type="button">
            <span>Cancel</span>
          </button>
          <button class="btn btn-danger" id="confirmActionOk" type="button">
            <span>Confirm</span>
          </button>
        </div>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/events-board.js"></script>
    <script>
    // Event CRUD Operations
    let currentEditingEventId = null;
    let currentRegisterEventId = null;
    let confirmActionResolver = null;

    function toggleCreateSessionLinkField() {
      const modeSelect = document.getElementById('eventMode');
      const group = document.getElementById('createSessionLinkGroup');
      const input = document.getElementById('externalLink');
      if (!modeSelect || !group || !input) return;

      const isOnline = modeSelect.value === 'online';
      group.style.display = isOnline ? 'block' : 'none';
      input.required = isOnline;

      if (!isOnline) {
        input.value = '';
      }
    }

    async function fetchJson(url, options = {}, timeoutMs = 12000) {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), timeoutMs);
      try {
        const response = await fetch(url, { ...options, signal: controller.signal });
        const raw = await response.text();

        // Some PHP endpoints return JSON with text/html content-type.
        // Parse JSON defensively based on payload, not header alone.
        try {
          return raw ? JSON.parse(raw) : {};
        } catch (parseError) {
          throw new Error(raw ? raw.slice(0, 300) : 'Non-JSON response from server');
        }
      } finally {
        clearTimeout(timeoutId);
      }
    }

    function refreshEventBoard() {
      // Use cache-busting navigation so latest server state is reflected immediately.
      window.location.replace('<?=ROOT?>/alumni/eventboard?refresh=' + Date.now());
    }

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

      if (!resolvedEventId) {
        showNotification('Unable to identify selected event. Please try again.', 'error');
        return;
      }

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

    function openConfirmActionModal(message) {
      const modal = document.getElementById('confirmActionModal');
      const messageEl = document.getElementById('confirmActionMessage');

      if (messageEl) {
        messageEl.textContent = message || 'Are you sure?';
      }

      if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
      }

      return new Promise((resolve) => {
        confirmActionResolver = resolve;
      });
    }

    function closeConfirmActionModal(confirmed) {
      const modal = document.getElementById('confirmActionModal');
      if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
      }

      if (confirmActionResolver) {
        confirmActionResolver(Boolean(confirmed));
        confirmActionResolver = null;
      }
    }

    async function unregisterEvent(eventId) {
      const confirmed = await openConfirmActionModal('Cancel your registration for this event?');
      if (!confirmed) {
        return;
      }

      const formData = new FormData();
      formData.append('eventId', eventId);

      try {
        const result = await fetchJson('<?=ROOT?>/alumni/eventboard/unregister', {
          method: 'POST',
          body: formData
        });

        showNotification(result.message || 'Updated', result.success ? 'success' : 'error');
        if (result.success) {
          refreshEventBoard();
        }
      } catch (err) {
        console.error(err);
        showNotification('Failed to cancel registration', 'error');
      }
    }

    async function submitAlumniRegistration(e) {
      e.preventDefault();

      const eventIdFromInput = parseInt(document.getElementById('registerEventId')?.value || '0', 10);
      const eventId = eventIdFromInput || currentRegisterEventId || 0;
      if (!eventId) {
        showNotification('Event ID is missing', 'error');
        return;
      }

      const submitBtn = document.querySelector('#registerEventForm button[type="submit"]');
      const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Registering...</span>';
      }

      const formData = new FormData();
      formData.append('eventId', eventId);

      try {
        const result = await fetchJson('<?=ROOT?>/alumni/eventboard/register', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: formData
        });

        if (!result.success && (result.message || '').toLowerCase().includes('already registered')) {
          alert('You are already registered for this event.');
        }

        showNotification(result.message || 'Updated', result.success ? 'success' : 'error');
        if (result.success) {
          closeRegisterModal();
          refreshEventBoard();
        }
      } catch (err) {
        console.error(err);
        showNotification('Failed to register. Please try again.', 'error');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnHtml;
        }
      }
    }

    document.getElementById('registerEventForm')?.addEventListener('submit', submitAlumniRegistration);

    // Create Event Form Handler
    document.getElementById('createEventForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const result = await fetchJson('<?=ROOT?>/alumni/eventboard/create', {
                method: 'POST',
                body: formData
            });
            
            if (result.success) {
                showNotification('Event created successfully!', 'success');
                closeNewEventModal();
                this.reset();
              refreshEventBoard();
            } else {
                showNotification(result.message || 'Failed to create event', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred while creating the event', 'error');
        }
    });

    // Edit Event Form Handler
    document.getElementById('editEventForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('eventId', currentEditingEventId);
        
        try {
            const result = await fetchJson('<?=ROOT?>/alumni/eventboard/update', {
                method: 'POST',
                body: formData
            });
            
            if (result.success) {
                showNotification('Event updated successfully! Registered students have been notified.', 'success');
                closeEditEventModal();
              refreshEventBoard();
            } else {
                showNotification(result.message || 'Failed to update event', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred while updating the event', 'error');
        }
    });

    // Open Edit Modal
    async function openEditEventModal(eventId) {
        currentEditingEventId = eventId;
        
        try {
            const event = await fetchJson(`<?=ROOT?>/alumni/eventboard/getEvent?id=${eventId}`);
            
            if (event.success) {
                const data = event.data;
                document.getElementById('editEventId').value = data.event_id;
                document.getElementById('editEventTitle').value = data.title;
                document.getElementById('editEventCategory').value = data.category;
                document.getElementById('editEventMode').value = data.mode;
                document.getElementById('editEventDate').value = data.event_date;
                document.getElementById('editStartTime').value = data.start_time;
                document.getElementById('editEndTime').value = data.end_time;
                document.getElementById('editEventLocation').value = data.venue;
                document.getElementById('editExternalLink').value = data.registration_link || '';
                document.getElementById('editMaxAttendees').value = data.max_attendees || '';
                document.getElementById('editEventDescription').value = data.description;
                document.getElementById('editEventTags').value = data.tags || '';
                
                // Show current image if exists
                const imageContainer = document.getElementById('currentEventImage');
                if (data.image_path) {
                    imageContainer.innerHTML = `<img src="<?=ROOT?>${data.image_path}" style="max-width: 200px; border-radius: 8px; margin-top: 8px;" alt="Current event image">`;
                } else {
                    imageContainer.innerHTML = '';
                }
                
                document.getElementById('editEventModal').style.display = 'block';
                document.getElementById('editEventModal').classList.add('show');
            } else {
                showNotification('Failed to load event details', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred while loading event details', 'error');
        }
    }

    // Close Edit Modal
    function closeEditEventModal() {
        document.getElementById('editEventModal').style.display = 'none';
        document.getElementById('editEventModal').classList.remove('show');
        currentEditingEventId = null;
        document.getElementById('editEventForm').reset();
    }

    // Delete Event
    async function confirmDeleteEvent(eventId) {
      const confirmed = await openConfirmActionModal('Are you sure you want to delete this event? All registered students will be notified.');
      if (!confirmed) {
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('eventId', eventId);
            
            const result = await fetchJson('<?=ROOT?>/alumni/eventboard/delete', {
                method: 'POST',
                body: formData
            });
            
            if (result.success) {
                showNotification('Event deleted successfully! Registered students have been notified.', 'success');
              setTimeout(() => {
                refreshEventBoard();
              }, 150);
            } else {
                showNotification(result.message || 'Failed to delete event', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the event', 'error');
        }
    }

    // Toggle Registration Status
    async function toggleRegistrationStatus(eventId) {
        try {
            const formData = new FormData();
            formData.append('eventId', eventId);
            
            const result = await fetchJson('<?=ROOT?>/alumni/eventboard/toggleRegistration', {
                method: 'POST',
                body: formData
            });
            
            if (result.success) {
                showNotification(`Registrations ${result.status === 'open' ? 'opened' : 'closed'} successfully!`, 'success');
              setTimeout(() => {
                refreshEventBoard();
              }, 150);
            } else {
                showNotification(result.message || 'Failed to update registration status', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        }
    }

    // Get Event Data (for edit modal)
    async function getEventData(eventId) {
        try {
            return await fetchJson(`<?=ROOT?>/alumni/eventboard/getEvent?id=${eventId}`);
        } catch (error) {
            console.error('Error:', error);
            return { success: false };
        }
    }

    function bindHostingActionButtons() {
      const editButtons = document.querySelectorAll('.js-host-edit[data-event-id]');
      const toggleButtons = document.querySelectorAll('.js-host-toggle[data-event-id]');
      const deleteButtons = document.querySelectorAll('.js-host-delete[data-event-id]');

      editButtons.forEach((button) => {
        if (button.dataset.bound === '1') return;
        button.dataset.bound = '1';
        button.addEventListener('click', () => {
          const eventId = parseInt(button.dataset.eventId || '0', 10);
          if (eventId > 0) openEditEventModal(eventId);
        });
      });

      toggleButtons.forEach((button) => {
        if (button.dataset.bound === '1') return;
        button.dataset.bound = '1';
        button.addEventListener('click', () => {
          const eventId = parseInt(button.dataset.eventId || '0', 10);
          if (eventId > 0) toggleRegistrationStatus(eventId);
        });
      });

      deleteButtons.forEach((button) => {
        if (button.dataset.bound === '1') return;
        button.dataset.bound = '1';
        button.addEventListener('click', () => {
          const eventId = parseInt(button.dataset.eventId || '0', 10);
          if (eventId > 0) confirmDeleteEvent(eventId);
        });
      });
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const modals = ['newEventModal', 'editEventModal', 'registerModal', 'confirmActionModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                if (modalId === 'newEventModal') closeNewEventModal();
                if (modalId === 'editEventModal') closeEditEventModal();
                if (modalId === 'registerModal') closeRegisterModal();
                if (modalId === 'confirmActionModal') closeConfirmActionModal(false);
            }
        });
    }

        document.getElementById('confirmActionOk')?.addEventListener('click', function() {
          closeConfirmActionModal(true);
        });
        document.getElementById('confirmActionCancel')?.addEventListener('click', function() {
          closeConfirmActionModal(false);
        });
        document.getElementById('confirmActionClose')?.addEventListener('click', function() {
          closeConfirmActionModal(false);
        });

        const alumniUpcomingToggleBtn = document.querySelector('.events-header-section .btn.btn-outline.btn-sm');
        if (alumniUpcomingToggleBtn) {
          const upcomingCards = document.querySelectorAll('.js-card-alumni-upcoming');
          if (upcomingCards.length > 2) {
            alumniUpcomingToggleBtn.setAttribute('onclick', "toggleEventCards('alumni-upcoming', this)");
            const label = alumniUpcomingToggleBtn.querySelector('span');
            if (label) label.textContent = 'View More';
          } else {
            alumniUpcomingToggleBtn.style.display = 'none';
          }
        }

        window.openRegisterModal = openRegisterModal;
        window.closeRegisterModal = closeRegisterModal;
        window.unregisterEvent = unregisterEvent;
        window.toggleEventCards = toggleEventCards;
        window.openEditEventModal = openEditEventModal;
        window.toggleRegistrationStatus = toggleRegistrationStatus;
        window.confirmDeleteEvent = confirmDeleteEvent;

        bindHostingActionButtons();

          const createModeSelect = document.getElementById('eventMode');
          if (createModeSelect) {
            createModeSelect.addEventListener('change', toggleCreateSessionLinkField);
          }

          const originalOpenNewEventModal = window.openNewEventModal;
          if (typeof originalOpenNewEventModal === 'function') {
            window.openNewEventModal = function() {
              originalOpenNewEventModal();
              toggleCreateSessionLinkField();
            };
          }

          const originalCloseNewEventModal = window.closeNewEventModal;
          if (typeof originalCloseNewEventModal === 'function') {
            window.closeNewEventModal = function() {
              originalCloseNewEventModal();
              toggleCreateSessionLinkField();
            };
          }

          toggleCreateSessionLinkField();
    </script>
  </body>
</html>
