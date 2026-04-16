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
  'overview_labels' => ['Mentorship Received', 'Resources Shared', 'Forum Posts', 'Forum Replies'],
  'overview_values' => [0, 0, 0, 0],
  'contribution_mix' => ['resources' => 0, 'forum_posts' => 0, 'forum_replies' => 0],
  'community_total' => 0,
];

$quickLinks = is_array($quickLinks ?? null) ? $quickLinks : [];
$mentorshipPreview = is_array($mentorshipPreview ?? null) ? $mentorshipPreview : [];
$eventPreview = is_array($eventPreview ?? null) ? $eventPreview : [];
$forumPreview = is_array($forumPreview ?? null) ? $forumPreview : [];
$notificationPreview = is_array($notificationPreview ?? null) ? $notificationPreview : [];

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
<style>
  .student-identity-panel {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 16px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    background: #ffffff;
  }

  .student-identity-main {
    display: flex;
    gap: 14px;
    align-items: center;
  }

  .student-identity-avatar {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    flex-shrink: 0;
    background: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .student-identity-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .student-identity-initials {
    color: #fff;
    font-size: 1.25rem;
    font-weight: 700;
  }

  .student-identity-name {
    margin: 0;
    font-size: 1.45rem;
    color: #0f172a;
  }

  .student-identity-subline {
    margin: 4px 0 0;
    color: #475569;
    font-size: 0.94rem;
  }

  .student-identity-chips {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .student-identity-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    padding: 4px 10px;
    font-size: 0.82rem;
    font-weight: 500;
  }

  .student-identity-chip i {
    color: #1e3a8a;
  }

  .student-snapshot {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
  }

  .student-snapshot-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    padding: 10px 12px;
  }

  .student-snapshot-value {
    margin: 0;
    color: #0f172a;
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1.1;
  }

  .student-snapshot-label {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .student-insights-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 14px;
  }

  .student-chart-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    padding: 14px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
  }

  .student-chart-title {
    margin: 0;
    color: #0f172a;
    font-size: 1rem;
    font-weight: 700;
  }

  .student-chart-subtitle {
    margin: 4px 0 10px;
    color: #64748b;
    font-size: 0.84rem;
  }

  .student-chart-box {
    min-height: 250px;
    position: relative;
  }

  .insights-profile-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 14px;
    align-items: stretch;
  }

  .insights-column {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    padding: 14px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    height: 100%;
  }

  .profile-vertical-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    padding: 16px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  .profile-vertical-head {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 10px;
  }

  .profile-vertical-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #dbeafe;
    background: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .profile-vertical-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-vertical-initials {
    color: #fff;
    font-size: 1.3rem;
    font-weight: 700;
  }

  .profile-vertical-name {
    margin: 0;
    color: #0f172a;
    font-size: 1.3rem;
    font-weight: 700;
  }

  .profile-vertical-subline {
    margin: 3px 0 0;
    color: #64748b;
    font-size: 0.9rem;
  }

  .profile-vertical-contact {
    display: grid;
    gap: 8px;
  }

  .profile-contact-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    background: #f8fafc;
    color: #475569;
    padding: 6px 10px;
    font-size: 0.83rem;
    overflow: hidden;
  }

  .profile-contact-pill span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .profile-contact-pill i {
    color: #1e3a8a;
  }

  .profile-vertical-stats {
    margin-top: auto;
    display: grid;
    gap: 8px;
  }

  .profile-vertical-stat {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 10px;
    background: #f8fafc;
  }

  .profile-vertical-stat strong {
    color: #0f172a;
    font-size: 1rem;
  }

  .profile-vertical-stat span {
    color: #64748b;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  @media (max-width: 1100px) {
    .student-identity-panel {
      grid-template-columns: 1fr;
    }

    .student-insights-grid {
      grid-template-columns: 1fr;
    }

    .insights-profile-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .student-snapshot {
      grid-template-columns: 1fr;
    }

    .student-identity-main {
      align-items: flex-start;
    }
  }

  .shortcut-grid {
    display: flex;
    flex-wrap: nowrap;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: thin;
  }

  .shortcut-card {
    flex: 0 0 220px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    border: 1px solid #e2e8f0;
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .shortcut-card:hover {
    border-color: #bfdbfe;
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
  }

  .shortcut-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eff6ff;
    color: #1e3a8a;
    font-size: 0.95rem;
    flex-shrink: 0;
  }

  .shortcut-title {
    margin: 0;
    color: #0f172a;
    font-size: 0.95rem;
    font-weight: 700;
  }

  .shortcut-hint {
    margin: 3px 0 0;
    color: #64748b;
    font-size: 0.78rem;
  }

  .compact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .compact-card {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    padding: 14px;
  }

  .compact-card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 4px;
  }

  .compact-card-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.86rem;
    flex-shrink: 0;
  }

  .compact-card-aid {
    border-color: #fcd7a0;
    background: linear-gradient(180deg, #fffaf0 0%, #ffffff 34%);
  }

  .compact-card-aid .compact-card-icon {
    background: #ffedd5;
    color: #9a3412;
  }

  .compact-card-aid .compact-item {
    background: #fff7ed;
    border-color: #ffedd5;
  }

  .compact-card-mentorship {
    border-color: #bfdbfe;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 34%);
  }

  .compact-card-mentorship .compact-card-icon {
    background: #dbeafe;
    color: #1d4ed8;
  }

  .compact-card-mentorship .compact-item {
    background: #f8fbff;
    border-color: #dbeafe;
  }

  .compact-card-events {
    border-color: #bbf7d0;
    background: linear-gradient(180deg, #f5fff8 0%, #ffffff 34%);
  }

  .compact-card-events .compact-card-icon {
    background: #dcfce7;
    color: #166534;
  }

  .compact-card-events .compact-item {
    background: #f0fdf4;
    border-color: #dcfce7;
  }

  .compact-card-forum {
    border-color: #c4b5fd;
    background: linear-gradient(180deg, #faf7ff 0%, #ffffff 34%);
  }

  .compact-card-forum .compact-card-icon {
    background: #ede9fe;
    color: #5b21b6;
  }

  .compact-card-forum .compact-item {
    background: #f9f7ff;
    border-color: #ede9fe;
  }

  .compact-card-notifications {
    border-color: #fbcfe8;
    background: linear-gradient(180deg, #fff7fb 0%, #ffffff 34%);
  }

  .compact-card-notifications .compact-card-icon {
    background: #fce7f3;
    color: #9d174d;
  }

  .compact-card-notifications .compact-item {
    background: #fff8fc;
    border-color: #fce7f3;
  }

  .compact-title {
    margin: 0;
    color: #0f172a;
    font-size: 1rem;
    font-weight: 700;
  }

  .compact-subtitle {
    margin: 4px 0 10px;
    color: #64748b;
    font-size: 0.82rem;
  }

  .compact-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .compact-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    border: 1px solid #eef2f7;
    border-radius: 10px;
    padding: 10px;
    background: #f8fafc;
  }

  .compact-item-title {
    margin: 0;
    color: #0f172a;
    font-size: 0.9rem;
    font-weight: 600;
  }

  .compact-item-meta {
    margin: 3px 0 0;
    color: #64748b;
    font-size: 0.78rem;
  }

  .compact-footer-link {
    margin-top: 10px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #1e3a8a;
    text-decoration: none;
    font-size: 0.84rem;
    font-weight: 600;
  }

  @media (max-width: 1100px) {
    .shortcut-grid {
      gap: 10px;
    }

    .compact-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .shortcut-grid {
      gap: 8px;
    }

    .shortcut-card {
      flex-basis: 200px;
    }
  }
</style>
<div class="dashboard-container">
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
          <div class="section-header">
            <h2 class="section-title">Engagement Insights</h2>
          </div>
          <div class="student-insights-grid">
            <article class="student-chart-card">
              <h3 class="student-chart-title">Mentorship & Contribution Overview</h3>
              <p class="student-chart-subtitle">How you are engaging with mentorship and the GradBridge community.</p>
              <div class="student-chart-box">
                <canvas id="studentEngagementChart"></canvas>
              </div>
            </article>

            <article class="student-chart-card">
              <h3 class="student-chart-title">Contribution Mix</h3>
              <p class="student-chart-subtitle">Total contribution units: <?= (int)($engagementChartData['community_total'] ?? 0) ?></p>
              <div class="student-chart-box">
                <canvas id="studentContributionMixChart"></canvas>
              </div>
            </article>
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

          <div class="profile-vertical-stats">
            <div class="profile-vertical-stat"><span>Active Mentorships</span><strong><?= (int)($profileSnapshot['active_mentorships'] ?? 0) ?></strong></div>
            <div class="profile-vertical-stat"><span>Mentorship Received</span><strong><?= (int)($profileSnapshot['completed_mentorships'] ?? 0) ?></strong></div>
            <div class="profile-vertical-stat"><span>Resources Shared</span><strong><?= (int)($profileSnapshot['resources_shared'] ?? 0) ?></strong></div>
            <div class="profile-vertical-stat"><span>Forum Contributions</span><strong><?= (int)($profileSnapshot['forum_contributions'] ?? 0) ?></strong></div>
          </div>
        </aside>
      </div>
    </section>

    <section class="dashboard-section">
      <div class="section-header">
        <h2 class="section-title">Quick Access</h2>
      </div>
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
    </section>

    <section class="dashboard-section">
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
        const overviewLabels = <?= json_encode($engagementChartData['overview_labels'] ?? [], JSON_UNESCAPED_SLASHES) ?>;
        const overviewValues = <?= json_encode($engagementChartData['overview_values'] ?? [], JSON_UNESCAPED_SLASHES) ?>;
        const contributionMix = <?= json_encode($engagementChartData['contribution_mix'] ?? ['resources' => 0, 'forum_posts' => 0, 'forum_replies' => 0], JSON_UNESCAPED_SLASHES) ?>;

        const overviewCanvas = document.getElementById('studentEngagementChart');
        if (overviewCanvas && window.Chart) {
          new Chart(overviewCanvas, {
            type: 'bar',
            data: {
              labels: overviewLabels,
              datasets: [{
                label: 'Total',
                data: overviewValues,
                borderColor: '#1e3a8a',
                backgroundColor: ['#1e3a8a', '#334155', '#475569', '#64748b'],
                borderWidth: 0,
                borderRadius: 8,
                maxBarThickness: 42
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
                    autoSkip: false,
                    maxRotation: 0,
                    minRotation: 0,
                    callback: function(value) {
                      const label = this.getLabelForValue(value);
                      return String(label).split(' ');
                    }
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
              labels: ['Resources', 'Forum Posts', 'Forum Replies'],
              datasets: [{
                data: [
                  Number(contributionMix.resources || 0),
                  Number(contributionMix.forum_posts || 0),
                  Number(contributionMix.forum_replies || 0)
                ],
                backgroundColor: ['#1e3a8a', '#334155', '#64748b'],
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
