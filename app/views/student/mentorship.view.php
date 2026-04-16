<?php 
$page_title = "Mentorship";
$page_subtitle = "Connect with alumni mentors";
$page_stylesheets = [ROOT . '/assets/css/mentorship.css?v=' . time()];
require '../app/views/partials/student_header.php'; 
?>

<div class="dashboard-container">
  <?php require '../app/views/partials/student_sidebar.php'; ?>

  <main class="main-content">
    <div class="content-container">
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          <?= esc($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle"></i>
          <?= esc($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <section class="dashboard-section faq-first-section">
        <div class="faq-first-strip" role="note" aria-label="FAQ first guidance">
          <div class="faq-first-copy">
            <h3>FAQ First</h3>
            <p>Check the FAQ before requesting mentorship to avoid generic questions already answered there and keep mentor time for personalized guidance.</p>
          </div>
          <a href="<?=ROOT?>/student/Faq" class="btn btn-outline btn-sm faq-first-btn">
            <i class="fas fa-question-circle"></i>
            FAQ
          </a>
        </div>
      </section>

      <section class="dashboard-section mentors-section">
        <div class="section-header">
          <h2 class="section-title">Available Mentors</h2>
          <div class="section-actions mentors-actions">
            <a href="javascript:void(0)" id="mentors-toggle" class="btn btn-outline btn-sm" style="<?= (!empty($data['mentors']) && count($data['mentors']) > 3) ? '' : 'display:none;' ?>">
              <span>View All</span>
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>

        <?php if (!empty($data['mentors'])): ?>
          <div class="mentors-grid">
            <?php foreach ($data['mentors'] as $idx => $mentor): ?>
              <article class="mentor-card" style="<?= ($idx >= 3) ? 'display:none;' : '' ?>">
                <div class="mentor-avatar-col">
                  <?php
                    $mentorName = trim((string)($mentor['mentor_name'] ?? 'Mentor'));
                    $nameParts = preg_split('/\s+/', $mentorName);
                    $initials = '';
                    if (!empty($nameParts) && is_array($nameParts)) {
                      $initials .= strtoupper(substr((string)$nameParts[0], 0, 1));
                      if (isset($nameParts[1])) {
                        $initials .= strtoupper(substr((string)$nameParts[1], 0, 1));
                      }
                    }
                    if ($initials === '') {
                      $initials = 'M';
                    }
                  ?>
                  <?php if (!empty($mentor['mentor_profile_photo_url'])): ?>
                    <img class="mentor-avatar" src="<?= esc($mentor['mentor_profile_photo_url']) ?>" alt="<?= esc($mentorName) ?>" loading="lazy" decoding="async">
                  <?php else: ?>
                    <span class="mentor-avatar-fallback" aria-hidden="true"><?= esc($initials) ?></span>
                  <?php endif; ?>
                </div>
                <div class="mentor-content">
                  <div class="mentor-heading-row">
                    <h3 class="mentor-name"><?= esc($mentor['mentor_name']) ?></h3>
                    <?php if (!empty($mentor['latest_mentor_badge'])): ?>
                      <span class="mentor-latest-badge <?= esc((string)($mentor['latest_mentor_badge_class'] ?? 'mentor-badge-new')) ?>">
                        <i class="fas <?= esc((string)($mentor['latest_mentor_badge_icon'] ?? 'fa-user-plus')) ?>" aria-hidden="true"></i>
                        <?= esc((string)$mentor['latest_mentor_badge']) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                  <p class="mentor-meta mentor-quick-meta">
                    <?= esc($mentor['faculty_name'] ?: 'Faculty N/A') ?>
                    <?php if (!empty($mentor['current_job'])): ?>
                      • <?= esc($mentor['current_job']) ?>
                    <?php endif; ?>
                    <?php if (!empty($mentor['expertise_area'])): ?>
                      • <?= esc($mentor['expertise_area']) ?>
                    <?php endif; ?>
                    • <?= (int)$mentor['sessions_count'] ?> sessions completed
                  </p>
                  <p class="mentor-description"><?= esc($mentor['mentor_bio'] ?: 'Experienced alumnus available for student mentorship.') ?></p>
                </div>
                <div class="mentor-actions-col">
                  <a class="btn btn-primary btn-sm" href="<?=ROOT?>/student/Mentorship/request/<?= (int)$mentor['mentor_user_id'] ?>">
                    <i class="fas fa-paper-plane"></i>
                    Request
                  </a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-user-graduate"></i></div>
            <h3>No mentors available right now</h3>
            <p>Please check back later.</p>
          </div>
        <?php endif; ?>
      </section>

      <section class="dashboard-section active-mentorships-section">
        <h2 class="section-title">Active Mentorships</h2>

        <?php if (!empty($data['activeMentorships'])): ?>
          <div class="requests-grid">
            <?php foreach ($data['activeMentorships'] as $active): ?>
              <article class="request-card active-card">
                <div class="request-header">
                  <h3 class="request-title"><?= esc($active['topic'] ?: 'Active Mentorship') ?></h3>
                  <span class="status-badge status-accepted">Accepted</span>
                </div>
                <p class="request-description"><?= esc($active['request_reason']) ?></p>
                <div class="contact-reveal-card">
                  <h4 class="contact-reveal-title">Mentor Contact</h4>
                  <p class="mentor-meta">Name: <?= esc($active['mentor_name']) ?></p>
                  <p class="mentor-meta">Email: <?= esc($active['mentor_email']) ?></p>
                  <?php if (!empty($active['mentor_mobile'])): ?>
                    <p class="mentor-meta">Mobile: +94 <?= esc($active['mentor_mobile']) ?></p>
                  <?php endif; ?>
                  <?php if (!empty($active['mentor_linkedin_url'])): ?>
                    <p class="mentor-meta">LinkedIn: <a href="<?= esc($active['mentor_linkedin_url']) ?>" target="_blank" rel="noopener">View profile</a></p>
                  <?php endif; ?>
                  <p class="mentor-meta">Faculty: <?= esc($active['faculty_name'] ?: 'N/A') ?></p>
                  <?php if (!empty($active['current_job'])): ?>
                    <p class="mentor-meta">Current Job: <?= esc($active['current_job']) ?></p>
                  <?php endif; ?>
                  <?php if (!empty($active['current_workplace'])): ?>
                    <p class="mentor-meta">Workplace: <?= esc($active['current_workplace']) ?></p>
                  <?php endif; ?>
                </div>
                <div class="request-actions mentor-request-actions">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm mentorship-chat-open"
                    data-thread-id="<?= (int)$active['request_id'] ?>"
                    data-thread-name="<?= esc($active['mentor_name']) ?>"
                  >
                    <i class="fas fa-comments"></i>
                    Chat with Alumni
                  </button>
                </div>
                <p class="mentor-meta"><small>Your mentor will end the session when mentorship is complete. Then you can submit the required feedback.</small></p>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state compact-empty">
            <p>No active mentorships.</p>
          </div>
        <?php endif; ?>
      </section>

      <section class="dashboard-section requests-section">
        <h2 class="section-title">My Mentorship Requests</h2>

        <?php if (!empty($data['requests'])): ?>
          <div class="requests-grid">
            <?php foreach ($data['requests'] as $request): ?>
              <article class="request-card">
                <div class="request-header">
                  <h3 class="request-title"><?= esc($request['topic'] ?: 'Mentorship Request') ?></h3>
                  <span class="status-badge status-<?= esc(strtolower(str_replace('_', '-', $request['status']))) ?>">
                    <?= esc(ucfirst(str_replace('_', ' ', $request['status']))) ?>
                  </span>
                </div>
                <p class="request-description"><?= esc($request['request_reason']) ?></p>
                <p class="mentor-meta">Mentor: <?= esc($request['mentor_name'] ?: 'Not assigned') ?></p>
                <?php if (!empty($request['rejection_reason']) && $request['status'] === 'rejected'): ?>
                  <p class="rejection-reason">Rejected Reason: <?= esc($request['rejection_reason']) ?></p>
                <?php endif; ?>
                <small class="request-date">Created: <?= date('M j, Y', strtotime($request['created_at'])) ?></small>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <h3>No requests sent yet</h3>
            <p>Pick a mentor and send your first mentorship request.</p>
          </div>
        <?php endif; ?>
      </section>

      <section class="dashboard-section reviews-section">
        <h2 class="section-title">Pending Feedback (Required)</h2>

        <?php if (!empty($data['pendingReviews'])): ?>
          <div class="requests-grid">
            <?php foreach ($data['pendingReviews'] as $reviewItem): ?>
              <article class="request-card review-card">
                <div class="request-header">
                  <h3 class="request-title"><?= esc($reviewItem['topic'] ?: 'Mentorship Feedback') ?></h3>
                  <span class="status-badge status-pending-review">Pending Feedback</span>
                </div>
                <p class="mentor-meta">Mentor: <?= esc($reviewItem['mentor_name']) ?></p>
                <form method="POST" action="<?=ROOT?>/student/Mentorship/submitReview/<?= (int)$reviewItem['request_id'] ?>" class="review-form">
                  <label class="form-label" for="rating-<?= (int)$reviewItem['request_id'] ?>">Feedback Score (required)</label>
                  <select id="rating-<?= (int)$reviewItem['request_id'] ?>" name="rating" class="form-select" required>
                    <option value="">Choose feedback score</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Very Poor</option>
                  </select>

                  <label class="form-label" for="comment-<?= (int)$reviewItem['request_id'] ?>">Feedback Note (optional)</label>
                  <textarea id="comment-<?= (int)$reviewItem['request_id'] ?>" name="review_comment" class="form-textarea" rows="3" placeholder="Share feedback about your mentorship experience"></textarea>

                  <button type="submit" class="btn btn-primary btn-sm">
                    Submit Feedback & Complete
                  </button>
                </form>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state compact-empty">
            <p>No pending feedback.</p>
          </div>
        <?php endif; ?>
      </section>
    </div>
  </main>
</div>

<div class="mentorship-chat-modal" id="mentorshipChatModal" aria-hidden="true">
  <div class="mentorship-chat-panel" role="dialog" aria-modal="true" aria-labelledby="mentorshipChatTitle">
    <div class="mentorship-chat-header">
      <div>
        <h3 id="mentorshipChatTitle" class="mentorship-chat-title">Mentorship Chat</h3>
        <p id="mentorshipChatMeta" class="mentorship-chat-meta">Chat with your mentor</p>
      </div>
      <button type="button" class="mentorship-chat-close" data-chat-close aria-label="Close chat">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="mentorship-chat-status" id="mentorshipChatStatus"></div>

    <div class="mentorship-chat-body">
      <div class="mentorship-chat-empty" id="mentorshipChatEmpty">
        Start the conversation.
      </div>
      <div class="mentorship-chat-messages" id="mentorshipChatMessages"></div>
    </div>

    <form class="mentorship-chat-form" id="mentorshipChatForm">
      <textarea id="mentorshipChatInput" name="message" class="mentorship-chat-input" rows="3" placeholder="Type a short message..."></textarea>
      <button type="submit" class="btn btn-primary mentorship-chat-send">
        Send
      </button>
    </form>
  </div>
</div>

<script src="<?=ROOT?>/assets/js/main.js"></script>
<script>
  (function() {
    const toggleBtn = document.getElementById('mentors-toggle');
    const cards = Array.from(document.querySelectorAll('.mentors-section .mentor-card'));
    const previewCount = 3;
    let expanded = false;

    if (!toggleBtn || cards.length <= previewCount) return;

    toggleBtn.addEventListener('click', function() {
      expanded = !expanded;
      cards.forEach((card, index) => {
        card.style.display = (expanded || index < previewCount) ? '' : 'none';
      });

      const label = toggleBtn.querySelector('span');
      const icon = toggleBtn.querySelector('i');
      if (label) label.textContent = expanded ? 'Show Less' : 'View All';
      if (icon) icon.className = expanded ? 'fas fa-chevron-up' : 'fas fa-arrow-right';
    });
  })();

  window.mentorshipChatConfig = {
    baseUrl: '<?=ROOT?>/student/Mentorship',
    currentUserId: '<?= (int)($_SESSION['user_id'] ?? 0) ?>'
  };
</script>
<script src="<?=ROOT?>/assets/js/mentorship-chat.js?v=<?=time()?>"></script>
</body>
</html>
