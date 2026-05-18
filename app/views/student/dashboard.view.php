<?php 
$page_title = "Welcome, " . esc($profile->name);
$page_subtitle = esc($profile->faculty) . " • Year " . esc($profile->academic_year);
require '../app/views/partials/student_header.php'; 

$recentAidRequests = $recentAidRequests ?? [];
$aidStatusLabel = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'pending_verification') {
    return 'Pending';
  }
  if (in_array($status, ['open', 'approved', 'accepted'], true)) {
    return 'Accepted';
  }
  if ($status === 'rejected') {
    return 'Rejected';
  }
  return ucfirst($status);
};

$aidStatusClass = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'pending_verification') {
    return 'status-pending';
  }
  if (in_array($status, ['open', 'approved', 'accepted'], true)) {
    return 'status-accepted';
  }
  if ($status === 'rejected') {
    return 'status-rejected';
  }
  return 'status-open';
};

$profileSnapshot = is_array($profileSnapshot ?? null) ? $profileSnapshot : [
  'active_mentorships' => 0,
  'completed_mentorships' => 0,
  'total_aid_requests' => 0,
  'pending_aid_requests' => 0,
];

$engagementChartData = is_array($engagementChartData ?? null) ? $engagementChartData : [
  'activity_timeline' => [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    'values' => [0, 0, 0, 0, 0, 0],
  ],
  'units_mix' => [
    'labels' => ['Mentorships', 'Aids Received', 'Resource Contributions', 'Forum Contributions', 'Fund Contributions'],
    'values' => [0, 0, 0, 0, 0],
  ],
  'community_total' => 0,
  'help_total' => 0,
];

$quickLinks = is_array($quickLinks ?? null) ? $quickLinks : [];
$mentorshipPreview = is_array($mentorshipPreview ?? null) ? $mentorshipPreview : [];
$eventPreview = is_array($eventPreview ?? null) ? $eventPreview : [];
$forumPreview = is_array($forumPreview ?? null) ? $forumPreview : [];
$notificationPreview = is_array($notificationPreview ?? null) ? $notificationPreview : [];

$studentBio = trim((string)($profile->bio ?? ''));
$studentLinkedIn = trim((string)($profile->LinkedIn ?? $profile->linkedin_url ?? ''));
$studentGitHub = trim((string)($profile->GitHub ?? $profile->github_url ?? ''));
$studentInstagram = trim((string)($profile->instagram_url ?? $profile->Instagram ?? ''));

$mentorshipStatusClass = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'accepted') return 'status-approved';
  if ($status === 'completed') return 'status-completed';
  if ($status === 'rejected') return 'status-rejected';
  if ($status === 'pending_review') return 'status-waiting';
  return 'status-pending';
};

$mentorshipStatusLabel = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'pending_review') return 'Pending Review';
  return ucfirst($status ?: 'pending');
};
?>

<!-- Dashboard-specific CSS -->
<!-- Using unified profile.css so avatar sizing and styles match the student profile view -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/student-dashboard-page.css?v=<?= time() ?>">
<div class="dashboard-container student-home-dashboard">
  <?php require '../app/views/partials/student_sidebar.php'; ?>

  <main class="main-content">
    <?php if (!empty($flashMessage) && is_array($flashMessage)): ?>
      <div class="alert alert-<?= esc($flashMessage['type'] ?? 'info') ?>" style="margin-bottom: 1rem;">
        <?= esc($flashMessage['text'] ?? '') ?>
      </div>
    <?php endif; ?>

    <section class="dashboard-section">
      <div class="insights-profile-grid">
        <article class="insights-column">
          <div class="student-insights-grid">
            <article class="student-chart-card">
              <h3 class="student-chart-title">Activity Overview (Last 30 Days)</h3>
              <p class="student-chart-subtitle">A timeline of what you contributed and what support you received over time.</p>
              <div class="student-chart-box">
                <canvas id="studentEngagementChart"></canvas>
              </div>
            </article>

            <article class="student-chart-card">
              <h3 class="student-chart-title">Contribution & Help Units Mix</h3>
              <p class="student-chart-subtitle">Contributions: <?= (int)($engagementChartData['community_total'] ?? 0) ?> • Help received: <?= (int)($engagementChartData['help_total'] ?? 0) ?></p>
              <div class="student-chart-box">
                <canvas id="studentContributionMixChart"></canvas>
              </div>
            </article>
          </div>

          <div class="quick-access-inline">
            <h3 class="quick-access-title">Quick Access</h3>
            <div class="shortcut-grid">
              <?php foreach ($quickLinks as $link): ?>
                <a class="shortcut-card" href="<?= esc((string)$link['url']) ?>">
                  <span class="shortcut-icon"><i class="fas <?= esc((string)$link['icon']) ?>"></i></span>
                  <span>
                    <p class="shortcut-title"><?= esc((string)$link['title']) ?></p>
                    <p class="shortcut-hint"><?= esc((string)$link['hint']) ?></p>
                  </span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </article>

        <aside class="profile-vertical-card">
          <div class="profile-vertical-head">
            <div class="profile-vertical-avatar">
              <?php if (!empty($profile->profile_photo_url)): ?>
                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile picture">
              <?php else: ?>
                <span class="profile-vertical-initials"><?= strtoupper(substr((string)$profile->name, 0, 2)) ?></span>
              <?php endif; ?>
            </div>
            <div>
              <h3 class="profile-vertical-name"><?= esc($profile->name) ?></h3>
              <p class="profile-vertical-subline"><?= esc($profile->faculty) ?> • Year <?= esc((string)$profile->academic_year) ?></p>
            </div>
          </div>

          <div class="profile-vertical-contact">
            <div class="profile-contact-pill"><i class="fas fa-id-card"></i><span><?= esc((string)($profile->student_id ?? 'Student')) ?></span></div>
            <div class="profile-contact-pill"><i class="fas fa-envelope"></i><span><?= esc($profile->email) ?></span></div>
            <div class="profile-contact-pill"><i class="fas fa-phone"></i><span><?= esc((string)($profile->mobile ?: 'Not set')) ?></span></div>
          </div>

          <div class="profile-vertical-about">
            <p class="profile-about-title">Description</p>
            <p class="profile-about-text"><?= esc($studentBio !== '' ? $studentBio : 'Add your bio to help mentors and peers know your background and interests.') ?></p>
          </div>

          <div class="profile-vertical-links">
            <?php if ($studentLinkedIn !== ''): ?>
              <a class="profile-social-link" href="<?= esc($studentLinkedIn) ?>" target="_blank" rel="noopener noreferrer">
                <span><i class="fab fa-linkedin"></i><span class="label">LinkedIn</span></span>
                <i class="fas fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <div class="profile-social-empty"><i class="fab fa-linkedin"></i>LinkedIn not added</div>
            <?php endif; ?>

            <?php if ($studentGitHub !== ''): ?>
              <a class="profile-social-link" href="<?= esc($studentGitHub) ?>" target="_blank" rel="noopener noreferrer">
                <span><i class="fab fa-github"></i><span class="label">GitHub</span></span>
                <i class="fas fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <div class="profile-social-empty"><i class="fab fa-github"></i>GitHub not added</div>
            <?php endif; ?>

            <?php if ($studentInstagram !== ''): ?>
              <a class="profile-social-link" href="<?= esc($studentInstagram) ?>" target="_blank" rel="noopener noreferrer">
                <span><i class="fab fa-instagram"></i><span class="label">Instagram</span></span>
                <i class="fas fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <div class="profile-social-empty"><i class="fab fa-instagram"></i>Instagram not added</div>
            <?php endif; ?>
          </div>
        </aside>
      </div>
    </section>

    <section class="dashboard-section live-snapshot-section">
      <div class="section-header">
        <h2 class="section-title">Live Snapshot</h2>
      </div>

      <div class="compact-grid">
        <article class="compact-card compact-card-aid">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-hand-holding-heart"></i></span>
            <h3 class="compact-title">Recent Aid Requests</h3>
          </div>
          <p class="compact-subtitle">Latest submissions and current statuses.</p>
          <?php if (empty($recentAidRequests)): ?>
            <p class="compact-item-meta">No aid requests yet.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach ($recentAidRequests as $aid): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc(ucfirst((string)($aid->aid_type ?? 'Aid'))) ?></p>
                    <p class="compact-item-meta"><?= esc(date('M j, Y', strtotime((string)($aid->created_at ?? 'now')))) ?></p>
                  </div>
                  <span class="status-badge <?= esc($aidStatusClass($aid->status ?? '')) ?>"><?= esc($aidStatusLabel($aid->status ?? '')) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?=ROOT?>/student/aidrequests"><i class="fas fa-arrow-right"></i>View all aid requests</a>
        </article>

        <article class="compact-card compact-card-mentorship">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-user-graduate"></i></span>
            <h3 class="compact-title">Mentorship Updates</h3>
          </div>
          <p class="compact-subtitle">Your latest mentorship request activity.</p>
          <?php if (empty($mentorshipPreview)): ?>
            <p class="compact-item-meta">No mentorship requests yet.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach ($mentorshipPreview as $request): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)$request['mentor_name']) ?></p>
                    <p class="compact-item-meta"><?= esc((string)$request['topic']) ?></p>
                  </div>
                  <span class="status-badge <?= esc($mentorshipStatusClass($request['status'])) ?>"><?= esc($mentorshipStatusLabel($request['status'])) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?=ROOT?>/student/mentorship"><i class="fas fa-arrow-right"></i>Go to mentorship</a>
        </article>
      </div>
    </section>

    <section class="dashboard-section">
      <div class="section-header">
        <h2 class="section-title">Campus Pulse</h2>
      </div>

      <div class="compact-grid">
        <article class="compact-card compact-card-events">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-calendar-check"></i></span>
            <h3 class="compact-title">Upcoming Events</h3>
          </div>
          <p class="compact-subtitle">Next opportunities you can join.</p>
          <?php if (empty($eventPreview)): ?>
            <p class="compact-item-meta">No upcoming events right now.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach ($eventPreview as $event): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($event['title'] ?? 'Event')) ?></p>
                    <p class="compact-item-meta"><?= esc(date('M j, Y', strtotime((string)($event['event_date'] ?? 'now')))) ?> • <?= esc((string)($event['mode'] ?? '')) ?></p>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?=ROOT?>/student/eventsboard"><i class="fas fa-arrow-right"></i>Open events board</a>
        </article>

        <article class="compact-card compact-card-forum">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-comments"></i></span>
            <h3 class="compact-title">Forum Highlights</h3>
          </div>
          <p class="compact-subtitle">Latest topics in your faculty feed.</p>
          <?php if (empty($forumPreview)): ?>
            <p class="compact-item-meta">No forum posts yet.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach ($forumPreview as $post): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($post->title ?? 'Forum topic')) ?></p>
                    <p class="compact-item-meta">By <?= esc((string)($post->author_name ?? 'Unknown')) ?></p>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?=ROOT?>/student/discussionforum"><i class="fas fa-arrow-right"></i>Open discussion forum</a>
        </article>
      </div>

      <article class="compact-card compact-card-notifications" style="margin-top:14px;">
        <div class="compact-card-head">
          <span class="compact-card-icon"><i class="fas fa-bell"></i></span>
          <h3 class="compact-title">Unread Notifications</h3>
        </div>
        <p class="compact-subtitle">Recent alerts that still need your attention.</p>
        <?php if (empty($notificationPreview)): ?>
          <p class="compact-item-meta">You are all caught up.</p>
        <?php else: ?>
          <ul class="compact-list">
            <?php foreach ($notificationPreview as $notification): ?>
              <li class="compact-item">
                <div>
                  <p class="compact-item-title"><?= esc((string)($notification->title ?? 'Notification')) ?></p>
                  <p class="compact-item-meta"><?= esc((string)($notification->message ?? '')) ?></p>
                </div>
                <?php if (!empty($notification->action_url)): ?>
                  <a class="compact-footer-link" style="margin-top:0;" href="<?= esc((string)$notification->action_url) ?>">
                    <?= esc((string)($notification->action_label ?: 'Open')) ?>
                  </a>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </article>
    </section>
  </main>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
      (function () {
        const activityTimeline = <?= json_encode($engagementChartData['activity_timeline'] ?? ['labels' => [], 'values' => []], JSON_UNESCAPED_SLASHES) ?>;
        const unitsMix = <?= json_encode($engagementChartData['units_mix'] ?? ['labels' => [], 'values' => []], JSON_UNESCAPED_SLASHES) ?>;

        const overviewCanvas = document.getElementById('studentEngagementChart');
        if (overviewCanvas && window.Chart) {
          new Chart(overviewCanvas, {
            type: 'line',
            data: {
              labels: activityTimeline.labels || [],
              datasets: [{
                label: 'Total Activity Units',
                data: activityTimeline.values || [],
                borderColor: '#7aa2e3',
                backgroundColor: 'rgba(122, 162, 227, 0.20)',
                tension: 0.35,
                fill: true,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3,
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: { precision: 0 }
                },
                x: {
                  grid: { display: false },
                  ticks: {
                    autoSkip: true,
                    maxTicksLimit: 8,
                    maxRotation: 0,
                    minRotation: 0,
                  }
                }
              }
            }
          });
        }

        const contributionCanvas = document.getElementById('studentContributionMixChart');
        if (contributionCanvas && window.Chart) {
          new Chart(contributionCanvas, {
            type: 'doughnut',
            data: {
              labels: (unitsMix.labels || []),
              datasets: [{
                data: (unitsMix.values || []).map(function (n) { return Number(n || 0); }),
                backgroundColor: ['#8fb6ee', '#f3bc8f', '#b8cff5', '#cedaf2', '#f8d8bd'],
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 6
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: { usePointStyle: true, boxWidth: 10 }
                }
              }
            }
          });
        }
      })();
    </script>
  </body>
</html>

