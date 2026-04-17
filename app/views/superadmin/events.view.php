<?php require '../app/views/partials/superadmin_header.php'; ?>

<?php
$events = $events ?? [];
$hostingEvents = $hostingEvents ?? [];
$attendingEvents = $attendingEvents ?? [];
$registeredEvents = $registeredEvents ?? $attendingEvents;
$registeredMap = [];
if (is_array($registeredEvents)) {
    foreach ($registeredEvents as $event) {
        $eventId = (int)($event['event_id'] ?? 0);
        if ($eventId > 0) {
            $registeredMap[$eventId] = true;
        }
    }
}

if (!function_exists('renderSuperAdminEventCard')) {
  function renderSuperAdminEventCard($event, $registeredMap, $showRegistration = true, $showModeration = true)
  {
    $eventId = (int)($event['event_id'] ?? 0);
    $isRegistered = !empty($registeredMap[$eventId]);
    $registrationStatus = (string)($event['registration_status'] ?? 'open');
    $isClosed = $registrationStatus !== 'open';
    $isFull = !empty($event['max_attendees']) && (int)($event['registered_count'] ?? 0) >= (int)$event['max_attendees'];
    ?>
    <article class="event-card" data-event-id="<?= $eventId ?>">
      <div class="event-header">
        <div class="event-info">
          <h3><?= esc($event['title'] ?? 'Untitled Event') ?></h3>
          <p class="event-organizer">By <?= esc($event['organizer_name'] ?? 'Unknown') ?> · <?= esc($event['organizer_faculty_name'] ?? 'Unknown Faculty') ?></p>
          <p class="event-type"><?= esc(ucfirst((string)($event['category'] ?? 'event'))) ?></p>
        </div>
        <div class="event-meta">
          <span class="status-badge <?= $isClosed ? 'status-closed' : 'status-open' ?>">
            <?= $isClosed ? 'REGISTRATION CLOSED' : 'REGISTRATION OPEN' ?>
          </span>
          <p class="event-date"><?= !empty($event['event_date']) ? date('M j, Y', strtotime($event['event_date'])) : 'TBD' ?></p>
        </div>
      </div>

      <div class="event-details">
        <p><?= esc($event['description'] ?? 'No description provided.') ?></p>
        <div class="event-schedule">
          <div class="schedule-item"><i class="fas fa-clock"></i> <?= !empty($event['start_time']) ? date('g:i A', strtotime($event['start_time'])) : '-' ?> - <?= !empty($event['end_time']) ? date('g:i A', strtotime($event['end_time'])) : '-' ?></div>
          <div class="schedule-item"><i class="fas fa-map-marker-alt"></i> <?= esc($event['venue'] ?? 'TBA') ?></div>
          <div class="schedule-item"><i class="fas fa-users"></i> <?= (int)($event['registered_count'] ?? 0) ?> registered<?= !empty($event['max_attendees']) ? ' / ' . (int)$event['max_attendees'] : '' ?></div>
        </div>
      </div>

      <div class="event-actions">
        <button type="button" class="btn btn-outline btn-sm details-btn" data-event-id="<?= $eventId ?>">
          <i class="fas fa-eye"></i> View Details
        </button>

        <?php if ($showRegistration): ?>
          <?php if ($isRegistered): ?>
            <button type="button" class="btn btn-outline btn-sm unregister-btn" data-event-id="<?= $eventId ?>">
              <i class="fas fa-user-minus"></i> Unregister
            </button>
          <?php else: ?>
            <button type="button" class="btn btn-primary btn-sm register-btn" data-event-id="<?= $eventId ?>" <?= ($isClosed || $isFull) ? 'disabled' : '' ?>>
              <i class="fas fa-user-plus"></i> <?= ($isClosed || $isFull) ? 'Unavailable' : 'Register' ?>
            </button>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($showModeration): ?>
          <button type="button" class="btn btn-outline btn-sm toggle-reg-btn" data-event-id="<?= $eventId ?>">
            <i class="fas fa-lock"></i> <?= $isClosed ? 'Reopen Registrations' : 'Close Registrations' ?>
          </button>

          <button type="button" class="btn btn-danger btn-sm delete-btn" data-event-id="<?= $eventId ?>">
            <i class="fas fa-trash"></i> Delete
          </button>
        <?php endif; ?>
      </div>
    </article>
    <?php
  }
}
?>

<div class="dashboard-container">
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section hosting-section">
            <div class="section-header">
          <h2 class="section-title">Events I Am Hosting</h2>
          <button type="button" class="btn btn-primary" id="openCreateEventBtn">
            <i class="fas fa-plus"></i> Create Event
          </button>
        </div>

        <div class="events-container">
          <?php if (!empty($hostingEvents)): ?>
            <?php foreach ($hostingEvents as $event): ?>
              <?php renderSuperAdminEventCard($event, $registeredMap, false, true); ?>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">You are not hosting any active events yet.</div>
          <?php endif; ?>
        </div>
      </section>

      <section class="dashboard-section attending-section">
        <div class="section-header">
          <h2 class="section-title">Events I Am Attending</h2>
        </div>

        <div class="events-container">
          <?php if (!empty($attendingEvents)): ?>
            <?php foreach ($attendingEvents as $event): ?>
              <?php renderSuperAdminEventCard($event, $registeredMap, true, false); ?>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">You are not attending any events yet.</div>
          <?php endif; ?>
        </div>
      </section>

      <section class="dashboard-section moderation-section">
        <div class="section-header">
          <h2 class="section-title">Global Moderation</h2>
            </div>

            <div class="events-container">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
              <?php renderSuperAdminEventCard($event, $registeredMap, true, true); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No active events found.</div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<div id="eventDetailsModal" class="event-modal sae-27">
    <div class="event-modal-panel">
        <div class="event-modal-head">
            <h3 id="eventDetailsTitle">Event Details</h3>
            <button type="button" class="modal-close-btn" onclick="closeEventDetailsModal()">&times;</button>
        </div>
        <div id="eventDetailsBody"></div>
    </div>
</div>

<div id="createEventModal" class="event-modal sae-27">
  <div class="event-modal-panel">
    <div class="event-modal-head">
      <h3>Create New Event</h3>
      <button type="button" class="modal-close-btn" onclick="closeCreateEventModal()">&times;</button>
    </div>
    <form id="createEventForm" enctype="multipart/form-data">
      <div class="event-form-grid">
        <input type="text" name="eventTitle" placeholder="Event title" required>
        <input type="text" name="eventCategory" placeholder="Category" required>
        <input type="date" name="eventDate" required>
        <input type="time" name="startTime" required>
        <input type="time" name="endTime">
        <input type="text" name="eventLocation" placeholder="Venue" required>
        <select name="eventMode">
          <option value="offline">Offline</option>
          <option value="online">Online</option>
          <option value="hybrid">Hybrid</option>
        </select>
        <input type="number" name="maxAttendees" min="1" placeholder="Max attendees (optional)">
        <input type="url" name="externalLink" placeholder="Registration link (optional)">
        <input type="text" name="eventTags" placeholder="Tags (comma separated)">
        <input type="file" name="event_image" accept=".jpg,.jpeg,.png,.gif,.webp">
      </div>
      <textarea name="eventDescription" rows="4" placeholder="Event description" required></textarea>
      <div class="event-actions sae-28">
        <button type="button" class="btn btn-outline btn-sm" onclick="closeCreateEventModal()">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Create Event</button>
      </div>
    </form>
  </div>
</div>



<script>
(function() {
  const root = '<?= ROOT ?>';

  function postAction(path, eventId, onSuccess) {
    const body = new URLSearchParams();
    body.set('eventId', String(eventId));

    fetch(root + path, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body
    })
      .then(r => r.json())
      .then(data => {
        if (!data.success) {
          alert(data.message || 'Action failed');
          return;
        }
        onSuccess(data);
      })
      .catch(() => alert('Request failed'));
  }

  document.querySelectorAll('.register-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const eventId = btn.getAttribute('data-event-id');
      postAction('/superadmin/Events/register', eventId, () => location.reload());
    });
  });

  document.querySelectorAll('.unregister-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const eventId = btn.getAttribute('data-event-id');
      postAction('/superadmin/Events/unregister', eventId, () => location.reload());
    });
  });

  document.querySelectorAll('.toggle-reg-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const eventId = btn.getAttribute('data-event-id');
      postAction('/superadmin/Events/toggleRegistration', eventId, () => location.reload());
    });
  });

  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!confirm('Delete this event?')) return;
      const eventId = btn.getAttribute('data-event-id');
      postAction('/superadmin/Events/delete', eventId, () => location.reload());
    });
  });

  document.querySelectorAll('.details-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const eventId = btn.getAttribute('data-event-id');
      fetch(root + '/superadmin/Events/getEvent?id=' + encodeURIComponent(eventId), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(r => r.json())
        .then(data => {
          if (!data.success) {
            alert(data.message || 'Unable to load details');
            return;
          }

          const e = data.data || {};
          document.getElementById('eventDetailsTitle').textContent = e.title || 'Event Details';
          document.getElementById('eventDetailsBody').innerHTML = `
            <p class="detail-row"><strong>Organizer:</strong> ${e.organizer_name || '-'} (${e.organizer_email || '-'})</p>
            <p class="detail-row"><strong>Faculty:</strong> ${e.organizer_faculty_name || '-'}</p>
            <p class="detail-row"><strong>Date:</strong> ${e.event_date || '-'}</p>
            <p class="detail-row"><strong>Time:</strong> ${(e.start_time || '-') + ' - ' + (e.end_time || '-')}</p>
            <p class="detail-row"><strong>Venue:</strong> ${e.venue || '-'}</p>
            <p class="detail-row"><strong>Category:</strong> ${e.category || '-'}</p>
            <p class="detail-row"><strong>Mode:</strong> ${e.mode || '-'}</p>
            <p class="detail-row"><strong>Registrations:</strong> ${(e.registered_count || 0)}${e.max_attendees ? ' / ' + e.max_attendees : ''}</p>
            <p class="detail-row"><strong>Description:</strong> ${e.description || '-'}</p>
            ${e.registration_link ? `<p class="detail-row"><a href="${e.registration_link}" target="_blank" rel="noopener">Open Event Link</a></p>` : ''}
          `;
          document.getElementById('eventDetailsModal').style.display = 'flex';
        })
        .catch(() => alert('Unable to load details'));
    });
  });

  const openCreateBtn = document.getElementById('openCreateEventBtn');
  if (openCreateBtn) {
    openCreateBtn.addEventListener('click', () => {
      const modal = document.getElementById('createEventModal');
      if (modal) modal.style.display = 'flex';
    });
  }

  const createForm = document.getElementById('createEventForm');
  if (createForm) {
    createForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(createForm);

      fetch(root + '/superadmin/Events/create', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
        .then(r => r.json())
        .then(data => {
          if (!data.success) {
            alert(data.message || 'Failed to create event');
            return;
          }
          closeCreateEventModal();
          location.reload();
        })
        .catch(() => alert('Failed to create event'));
    });
  }
})();

function closeEventDetailsModal() {
  const modal = document.getElementById('eventDetailsModal');
  if (modal) {
    modal.style.display = 'none';
  }
}

function closeCreateEventModal() {
  const modal = document.getElementById('createEventModal');
  if (modal) {
    modal.style.display = 'none';
  }
}
</script>
