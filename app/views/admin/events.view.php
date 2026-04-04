<?php require '../app/views/partials/admin_header.php'; ?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/events-board.css">

<style>
.featured-events-section .featured-events-grid {
  display: grid !important;
  grid-template-columns: repeat(2, minmax(260px, 1fr)) !important;
  gap: var(--spacing-lg) !important;
  align-items: stretch;
}

.featured-events-section .featured-events-grid .featured-event-card {
  width: auto !important;
  max-width: none !important;
  min-width: 0;
}

.featured-events-section .featured-event-card .event-title,
.featured-events-section .featured-event-card .event-description,
.featured-events-section .featured-event-card .event-meta span {
  white-space: normal;
  overflow-wrap: anywhere;
}

@media (max-width: 1200px) {
  .featured-events-section .featured-events-grid {
    grid-template-columns: repeat(2, minmax(240px, 1fr)) !important;
  }
}

@media (max-width: 768px) {
  .featured-events-section .featured-events-grid {
    grid-template-columns: 1fr !important;
  }
}
</style>

<div class="dashboard-container">
    <?php require '../app/views/partials/admin_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section events-header-section">
            <div class="section-header">
                <h2 class="card-title">Event Approval Queue</h2>
                <div class="header-actions">
                    <button class="btn btn-outline btn-sm" id="adminPendingToggleBtn" onclick="toggleEventCards('admin-pending', this)">
                        <span>View More</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <section class="dashboard-section featured-events-section">
            <div class="featured-events-grid">
                <?php if (!empty($pendingEvents)): ?>
                    <?php foreach ($pendingEvents as $index => $event): ?>
                        <div class="featured-event-card js-card-admin-pending" <?= $index >= 2 ? 'style="display:none;"' : '' ?>>
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

                                <div class="event-stats">
                                    <span class="attendees"><i class="fas fa-user"></i> By <?= esc($event['organizer_name'] ?? 'Alumni') ?></span>
                                    <span class="attendees"><i class="fas fa-users"></i> <?= (int)($event['registered_count'] ?? 0) ?> registered</span>
                                    <span class="spots-left"><i class="fas fa-hourglass-half"></i> Pending Review</span>
                                </div>

                                <div class="event-actions">
                                    <?php if (!empty($event['registration_link'])): ?>
                                        <a class="btn btn-outline btn-sm" href="<?= esc($event['registration_link']) ?>" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-external-link-alt"></i>
                                            <span>View Details</span>
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="handleAdminEventDecision(<?= (int)$event['event_id'] ?>, 'approve', this)">
                                        <i class="fas fa-check"></i>
                                        <span>Approve</span>
                                    </button>
                                    <button type="button" class="btn btn-outline btn-sm" onclick="handleAdminEventDecision(<?= (int)$event['event_id'] ?>, 'reject', this)">
                                        <i class="fas fa-times"></i>
                                        <span>Reject</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: var(--muted-foreground); padding: 2rem;">
                        No pending event approvals at the moment.
                    </p>
                <?php endif; ?>
            </div>
        </section>

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
                        <h3 class="stat-number"><?= (int)count($pendingEvents ?? []) ?></h3>
                        <p class="stat-label">Pending Approvals</p>
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

<script src="<?=ROOT?>/assets/js/main.js"></script>
<script src="<?=ROOT?>/assets/js/events-board.js"></script>
<script>
function toggleEventCards(sectionKey, button) {
    const cards = document.querySelectorAll('.js-card-' + sectionKey);
    if (!cards.length) {
        return;
    }

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

function handleAdminEventDecision(eventId, decision, button) {
    const message = decision === 'approve'
        ? 'Approve this event request?'
        : 'Reject this event request?';

    if (!confirm(message)) {
        return;
    }

    const card = button.closest('.featured-event-card');
    if (card) {
        card.style.opacity = '0.65';
    }

    const actionText = decision === 'approve' ? 'approved' : 'rejected';
    showNotification('Event request ' + actionText + ' (temporary UI action).', 'success');
}

window.toggleEventCards = toggleEventCards;

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('adminPendingToggleBtn');
    const cards = document.querySelectorAll('.js-card-admin-pending');
    if (toggleBtn && cards.length <= 2) {
        toggleBtn.style.display = 'none';
    }
});
</script>
