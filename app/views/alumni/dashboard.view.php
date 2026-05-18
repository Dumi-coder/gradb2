<?php
$page_title = 'Welcome, ' . esc($profile->name);
$page_subtitle = esc($profile->faculty) . ' • Class of ' . esc((string)$profile->graduated_year);
require '../app/views/partials/alumni_header.php';

$profileSnapshot = is_array($profileSnapshot ?? null) ? $profileSnapshot : [
  'active_mentorships' => 0,
  'completed_mentorships' => 0,
  'aid_facilitated' => 0,
  'resources_shared' => 0,
  'forum_contributions' => 0,
  'donations_made' => 0,
  'donations_amount' => 0,
];

$engagementChartData = is_array($engagementChartData ?? null) ? $engagementChartData : [
  'activity_timeline' => [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    'values' => [0, 0, 0, 0, 0, 0],
  ],
  'units_mix' => [
    'labels' => ['Mentorships Given', 'Aid Facilitated', 'Resources Shared', 'Forum Contributions', 'Donations'],
    'values' => [0, 0, 0, 0, 0],
  ],
  'community_total' => 0,
  'impact_total' => 0,
];

$quickLinks = is_array($quickLinks ?? null) ? $quickLinks : [];
$mentorshipData = is_array($mentorshipData ?? null) ? $mentorshipData : [];
$pendingMentorshipRequests = is_array($mentorshipData['requests'] ?? null) ? $mentorshipData['requests'] : [];
$activeMentorships = is_array($mentorshipData['active'] ?? null) ? $mentorshipData['active'] : [];
$completedMentorships = is_array($mentorshipData['completed'] ?? null) ? $mentorshipData['completed'] : [];
$aidPreview = is_array($aidPreview ?? null) ? $aidPreview : [];
$fundraiserPreview = is_array($fundraiserPreview ?? null) ? $fundraiserPreview : [];
$eventPreview = is_array($eventPreview ?? null) ? $eventPreview : [];
$forumPreview = is_array($forumPreview ?? null) ? $forumPreview : [];
$notificationPreview = is_array($notificationPreview ?? null) ? $notificationPreview : [];
$badges = is_array($badges ?? null) ? $badges : [];

$resolveBadgeTier = function ($badge) {
  $progress = (int)($badge['progress_pct'] ?? 0);
  $earned = !empty($badge['is_earned']);

  if ($earned || $progress >= 100) return 'tier-legend';
  if ($progress >= 70) return 'tier-gold';
  if ($progress >= 35) return 'tier-silver';
  return 'tier-bronze';
};

$alumniBio = trim((string)($profile->bio ?? ''));
$alumniLinkedIn = trim((string)($profile->linkedin_url ?? $profile->LinkedIn ?? ''));
$alumniGitHub = trim((string)($profile->github_url ?? $profile->GitHub ?? ''));
$alumniTwitter = trim((string)($profile->twitter_url ?? ''));

$mentorshipStatusClass = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'accepted') return 'status-approved';
  if ($status === 'completed') return 'status-completed';
  if ($status === 'rejected') return 'status-rejected';
  return 'status-pending';
};

$mentorshipStatusLabel = function ($status) {
  $status = strtolower((string)$status);
  return ucfirst($status ?: 'pending');
};

$aidStatusClass = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'completed') return 'status-completed';
  if (in_array($status, ['approved', 'accepted'], true)) return 'status-approved';
  if ($status === 'rejected') return 'status-rejected';
  return 'status-pending';
};

$aidStatusLabel = function ($status) {
  $status = strtolower((string)$status);
  if ($status === 'approved') return 'Approved';
  if ($status === 'accepted') return 'Accepted';
  return ucfirst($status ?: 'pending');
};
?>

<link rel="stylesheet" href="<?= ROOT ?>/assets/css/profile.css">
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/student-dashboard-page.css?v=<?= time() ?>">
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/alumni-badges.css?v=<?= time() ?>">

<div class="dashboard-container student-home-dashboard">
  <?php require '../app/views/partials/alumni_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section">
      <div class="insights-profile-grid">
        <article class="insights-column">
          <div class="student-insights-grid">
            <article class="student-chart-card">
              <h3 class="student-chart-title">Activity Overview (Last 30 Days)</h3>
              <p class="student-chart-subtitle">A timeline of your mentoring, aid support, resource sharing, and community contributions.</p>
              <div class="student-chart-box">
                <canvas id="alumniEngagementChart"></canvas>
              </div>
            </article>

            <article class="student-chart-card">
              <h3 class="student-chart-title">Contribution Mix</h3>
              <p class="student-chart-subtitle">Contribution units: <?= (int)($engagementChartData['community_total'] ?? 0) ?> • Direct impact units: <?= (int)($engagementChartData['impact_total'] ?? 0) ?></p>
              <div class="student-chart-box">
                <canvas id="alumniContributionMixChart"></canvas>
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
              <p class="profile-vertical-subline"><?= esc($profile->faculty) ?> • Class of <?= esc((string)$profile->graduated_year) ?></p>
            </div>
          </div>

          <div class="profile-vertical-contact">
            <div class="profile-contact-pill"><i class="fas fa-id-card"></i><span><?= esc((string)($profile->alumni_id ?? 'Alumni')) ?></span></div>
            <div class="profile-contact-pill"><i class="fas fa-envelope"></i><span><?= esc($profile->email) ?></span></div>
            <div class="profile-contact-pill"><i class="fas fa-phone"></i><span><?= esc((string)($profile->mobile ?: 'Not set')) ?></span></div>
            <div class="profile-contact-pill"><i class="fas fa-briefcase"></i><span><?= esc((string)($profile->current_workplace ?: 'Workplace not set')) ?></span></div>
          </div>

          <div class="profile-vertical-about">
            <p class="profile-about-title">Description</p>
            <p class="profile-about-text"><?= esc($alumniBio !== '' ? $alumniBio : 'Add your bio to help students understand your industry path and mentorship strengths.') ?></p>
          </div>

          <div class="profile-vertical-links">
            <?php if ($alumniLinkedIn !== ''): ?>
              <a class="profile-social-link" href="<?= esc($alumniLinkedIn) ?>" target="_blank" rel="noopener noreferrer">
                <span><i class="fab fa-linkedin"></i><span class="label">LinkedIn</span></span>
                <i class="fas fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <div class="profile-social-empty"><i class="fab fa-linkedin"></i>LinkedIn not added</div>
            <?php endif; ?>

            <?php if ($alumniGitHub !== ''): ?>
              <a class="profile-social-link" href="<?= esc($alumniGitHub) ?>" target="_blank" rel="noopener noreferrer">
                <span><i class="fab fa-github"></i><span class="label">GitHub</span></span>
                <i class="fas fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <div class="profile-social-empty"><i class="fab fa-github"></i>GitHub not added</div>
            <?php endif; ?>

            <?php if ($alumniTwitter !== ''): ?>
              <a class="profile-social-link" href="<?= esc($alumniTwitter) ?>" target="_blank" rel="noopener noreferrer">
                <span><i class="fab fa-twitter"></i><span class="label">Twitter</span></span>
                <i class="fas fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <div class="profile-social-empty"><i class="fab fa-twitter"></i>Twitter not added</div>
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
        <article class="compact-card compact-card-mentorship">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-user-graduate"></i></span>
            <h3 class="compact-title">Mentorship Pipeline</h3>
          </div>
          <p class="compact-subtitle">Pending, active, and completed mentorship sessions.</p>
          <?php if (empty($pendingMentorshipRequests) && empty($activeMentorships) && empty($completedMentorships)): ?>
            <p class="compact-item-meta">No mentorship activity yet.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach (array_slice($pendingMentorshipRequests, 0, 1) as $request): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($request['student_name'] ?? 'Student')) ?></p>
                    <p class="compact-item-meta">Pending • <?= esc((string)($request['guidance_type'] ?? 'Mentorship')) ?></p>
                  </div>
                  <span class="status-badge status-pending">Pending</span>
                </li>
              <?php endforeach; ?>

              <?php foreach (array_slice($activeMentorships, 0, 1) as $session): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($session['student_name'] ?? 'Student')) ?></p>
                    <p class="compact-item-meta"><?= esc((string)($session['topic'] ?? 'Mentorship Session')) ?></p>
                  </div>
                  <span class="status-badge status-approved">Active</span>
                </li>
              <?php endforeach; ?>

              <?php foreach (array_slice($completedMentorships, 0, 1) as $session): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($session['student_name'] ?? 'Student')) ?></p>
                    <p class="compact-item-meta"><?= esc((string)($session['topic'] ?? 'Mentorship Session')) ?></p>
                  </div>
                  <span class="status-badge status-completed">Completed</span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?= ROOT ?>/alumni/mentorship"><i class="fas fa-arrow-right"></i>Open mentorship workspace</a>
        </article>

        <article class="compact-card compact-card-aid">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-hands-helping"></i></span>
            <h3 class="compact-title">Aid Support Updates</h3>
          </div>
          <p class="compact-subtitle">Latest aid requests you have taken ownership of.</p>
          <?php if (empty($aidPreview)): ?>
            <p class="compact-item-meta">No aid requests handled yet.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach ($aidPreview as $aid): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($aid->student_name ?? 'Student')) ?> • <?= esc(ucfirst((string)($aid->aid_type ?? 'Aid'))) ?></p>
                    <p class="compact-item-meta"><?= esc(date('M j, Y', strtotime((string)($aid->created_at ?? 'now')))) ?></p>
                  </div>
                  <span class="status-badge <?= esc($aidStatusClass($aid->status ?? '')) ?>"><?= esc($aidStatusLabel($aid->status ?? '')) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?= ROOT ?>/alumni/aidrequests"><i class="fas fa-arrow-right"></i>Go to aid requests</a>
        </article>
      </div>
    </section>

    <section class="dashboard-section">
      <div class="section-header">
        <h2 class="section-title">Community Pulse</h2>
      </div>

      <div class="compact-grid">
        <article class="compact-card compact-card-events">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-calendar-check"></i></span>
            <h3 class="compact-title">Upcoming Events</h3>
          </div>
          <p class="compact-subtitle">Events where you can connect, mentor, or speak.</p>
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
          <a class="compact-footer-link" href="<?= ROOT ?>/alumni/eventboard"><i class="fas fa-arrow-right"></i>Open events board</a>
        </article>

        <article class="compact-card compact-card-forum">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-comments"></i></span>
            <h3 class="compact-title">Forum Highlights</h3>
          </div>
          <p class="compact-subtitle">Discussions where your alumni insight can help.</p>
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
          <a class="compact-footer-link" href="<?= ROOT ?>/alumni/discussionforum"><i class="fas fa-arrow-right"></i>Open discussion forum</a>
        </article>
      </div>

      <div class="compact-grid" style="margin-top: 14px;">
        <article class="compact-card compact-card-fundraising">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-hand-holding-heart"></i></span>
            <h3 class="compact-title">Fundraising Watch</h3>
          </div>
          <p class="compact-subtitle">Campaigns where your contribution can make a difference.</p>
          <?php if (empty($fundraiserPreview)): ?>
            <p class="compact-item-meta">No active campaigns right now.</p>
          <?php else: ?>
            <ul class="compact-list">
              <?php foreach ($fundraiserPreview as $fund): ?>
                <li class="compact-item">
                  <div>
                    <p class="compact-item-title"><?= esc((string)($fund->title ?? 'Campaign')) ?></p>
                    <p class="compact-item-meta"><?= esc((string)($fund->creator_name ?? 'Creator')) ?> • <?= (int)($fund->funded_percent ?? 0) ?>% funded</p>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <a class="compact-footer-link" href="<?= ROOT ?>/alumni/fundraising"><i class="fas fa-arrow-right"></i>Open fundraising</a>
        </article>

        <article class="compact-card compact-card-notifications">
          <div class="compact-card-head">
            <span class="compact-card-icon"><i class="fas fa-bell"></i></span>
            <h3 class="compact-title">Unread Notifications</h3>
          </div>
          <p class="compact-subtitle">Latest alerts that need your attention.</p>
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
                    <a class="compact-footer-link" style="margin-top: 0;" href="<?= esc((string)$notification->action_url) ?>">
                      <?= esc((string)($notification->action_label ?: 'Open')) ?>
                    </a>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </article>
      </div>
    </section>

    <section class="dashboard-section badges-section">
      <div class="section-header">
        <h2 class="section-title">Impact Badges</h2>
      </div>

      <?php if (empty($badges)): ?>
        <p class="compact-item-meta">No badges available yet.</p>
      <?php else: ?>
        <p class="compact-subtitle alumni-badges-subtitle">Level up your impact by mentoring, guiding, and giving back to the community.</p>
        <div class="alumni-badge-grid">
          <?php foreach ($badges as $badge): ?>
            <?php
              $badgeTierClass = $resolveBadgeTier($badge);
              $isEarned = !empty($badge['is_earned']);
            ?>
            <article class="alumni-badge-card <?= esc($badgeTierClass) ?> <?= $isEarned ? 'is-earned' : 'is-locked' ?>">
              <div class="alumni-badge-emblem-wrap">
                <div class="alumni-badge-emblem">
                  <div class="alumni-badge-core">
                    <i class="fas <?= esc((string)$badge['icon']) ?>"></i>
                  </div>
                  <div class="alumni-badge-ribbon"><?= esc((string)$badge['title']) ?></div>
                </div>
              </div>

              <div class="alumni-badge-content">
                <p class="alumni-badge-description"><?= esc((string)$badge['description']) ?></p>
                <p class="alumni-badge-status">
                  <?= $isEarned ? 'Earned' : 'In progress' ?>
                  <span class="alumni-badge-dot">•</span>
                  <?= is_float($badge['value']) ? number_format((float)$badge['value'], 0) : (int)$badge['value'] ?> /
                  <?= is_float($badge['target']) ? number_format((float)$badge['target'], 0) : (int)$badge['target'] ?>
                </p>

                <div class="alumni-badge-progress-track" aria-hidden="true">
                  <span class="alumni-badge-progress-fill" style="width: <?= (int)($badge['progress_pct'] ?? 0) ?>%"></span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="alumni-badge-timeline" aria-hidden="true">
          <div class="alumni-badge-timeline-line"></div>
          <div class="alumni-badge-timeline-marker marker-bronze">Starter</div>
          <div class="alumni-badge-timeline-marker marker-silver">Growing</div>
          <div class="alumni-badge-timeline-marker marker-gold">Impact</div>
          <div class="alumni-badge-timeline-marker marker-legend">Legacy</div>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
  (function () {
    const activityTimeline = <?= json_encode($engagementChartData['activity_timeline'] ?? ['labels' => [], 'values' => []], JSON_UNESCAPED_SLASHES) ?>;
    const unitsMix = <?= json_encode($engagementChartData['units_mix'] ?? ['labels' => [], 'values' => []], JSON_UNESCAPED_SLASHES) ?>;

    const overviewCanvas = document.getElementById('alumniEngagementChart');
    if (overviewCanvas && window.Chart) {
      new Chart(overviewCanvas, {
        type: 'line',
        data: {
          labels: activityTimeline.labels || [],
          datasets: [{
            label: 'Total Activity Units',
            data: activityTimeline.values || [],
            borderColor: '#5f9bcf',
            backgroundColor: 'rgba(95, 155, 207, 0.20)',
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

    const contributionCanvas = document.getElementById('alumniContributionMixChart');
    if (contributionCanvas && window.Chart) {
      new Chart(contributionCanvas, {
        type: 'doughnut',
        data: {
          labels: (unitsMix.labels || []),
          datasets: [{
            data: (unitsMix.values || []).map(function (n) { return Number(n || 0); }),
            backgroundColor: ['#74a8d8', '#e9b174', '#8bc8a4', '#80b3b6', '#f0c58a'],
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
