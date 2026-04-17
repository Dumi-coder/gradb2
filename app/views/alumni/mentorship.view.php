<?php 
$page_title = "Mentorship";
$page_subtitle = "Connect with students seeking guidance";
$page_stylesheets = [ROOT . '/assets/css/mentorship.css?v=' . time()];
require '../app/views/partials/alumni_header.php'; 
?>

<div class="dashboard-container">
  <?php require '../app/views/partials/alumni_sidebar.php'; ?>

  <main class="main-content">
    <?php if (isset($_SESSION['success'])): ?>
      <div class="mentorship-toast mentorship-toast-success" role="status" aria-live="polite">
        <i class="fas fa-check-circle"></i>
        <span><?= esc($_SESSION['success']) ?></span>
        <button type="button" class="mentorship-toast-close" aria-label="Close message">&times;</button>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="mentorship-toast mentorship-toast-error" role="alert" aria-live="assertive">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= esc($_SESSION['error']) ?></span>
        <button type="button" class="mentorship-toast-close" aria-label="Close message">&times;</button>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php
      $mentorEnabled = (int)($profile->is_verified_mentor ?? 0) === 1;
      $mentorAvailability = strtolower(trim((string)($profile->mentorship_availability_status ?? 'available')));
      $mentorHidden = (!$mentorEnabled || $mentorAvailability === 'unavailable');
    ?>

    <section class="dashboard-section mentor-reputation-section">
      <div class="reputation-card">
        <h2 class="section-title">Your Mentor Feedback</h2>
        <p class="mentor-rating">â­ <?= number_format((float)($mentorshipData['reputation']['avg_rating'] ?? 0), 1) ?> Feedback Score</p>
        <p class="mentor-sessions">Completed Sessions: <?= (int)($mentorshipData['reputation']['total_completed_sessions'] ?? 0) ?></p>
      </div>
    </section>

    <?php if ($mentorHidden): ?>
      <section class="dashboard-section mentor-onboarding-section">
        <div class="mentor-onboarding-card">
          <h3 class="mentor-onboarding-title">
            <?= !$mentorEnabled ? 'Become a Mentor' : 'Mentorship Is Currently Paused' ?>
          </h3>
          <p class="mentor-onboarding-copy">
            <?= !$mentorEnabled
              ? 'You are not visible in the student mentor list yet. Turn on mentor mode and help students with real guidance from your experience.'
              : 'Your profile is currently hidden from students because availability is set to unavailable. Switch availability to start receiving mentorship requests again.' ?>
          </p>
          <a href="<?= ROOT ?>/alumni/profile?action=edit" class="btn btn-primary btn-sm">
            <i class="fas fa-user-edit"></i>
            Go to Edit Profile
          </a>
        </div>
      </section>
    <?php endif; ?>

    <section class="dashboard-section mentorship-requests-section">
      <h2 class="section-title">Pending Requests</h2>

      <?php if (!empty($mentorshipData['requests'])): ?>
        <div class="requests-grid">
          <?php foreach ($mentorshipData['requests'] as $request): ?>
            <article class="request-card">
              <div class="request-header">
                <h3 class="request-title"><?= esc($request['topic'] ?: 'Mentorship Request') ?></h3>
                <span class="status-badge status-pending">Pending</span>
              </div>
              <p class="request-description"><?= esc($request['request_reason']) ?></p>
              <p class="student-details">
                <small>
                  <?= esc($request['student_name']) ?> | <?= esc($request['student_id']) ?> | Year <?= esc($request['academic_year']) ?> | <?= esc($request['faculty_name']) ?>
                </small>
              </p>
              <p class="mentor-meta">Student Email: <?= esc($request['student_email']) ?></p>
              <div class="request-actions mentor-request-actions">
                <form method="POST" action="<?= ROOT ?>/Alumni/Mentorship/accept/<?= (int)$request['request_id'] ?>">
                  <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accept this request?');">
                    <i class="fas fa-check"></i> Accept
                  </button>
                </form>

                <form method="POST" action="<?= ROOT ?>/Alumni/Mentorship/reject/<?= (int)$request['request_id'] ?>" class="reject-form">
                  <textarea name="rejection_reason" class="form-textarea" rows="2" placeholder="Reason for rejection" required></textarea>
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this request?');">
                    <i class="fas fa-times"></i> Reject
                  </button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-requests-message">
          <div class="no-requests-icon"><i class="fas fa-user-graduate"></i></div>
          <h3>No pending requests</h3>
          <p>New student mentorship requests will appear here.</p>
        </div>
      <?php endif; ?>
    </section>

    <section class="dashboard-section active-mentorships-section">
      <h2 class="section-title">Accepted Mentorships</h2>

      <?php if (!empty($mentorshipData['active'])): ?>
        <div class="requests-grid">
          <?php foreach ($mentorshipData['active'] as $active): ?>
            <article class="request-card active-card">
              <div class="request-header">
                <h3 class="request-title"><?= esc($active['topic'] ?: 'Active Mentorship') ?></h3>
                <span class="status-badge status-accepted">Accepted</span>
              </div>
              <p class="request-description"><?= esc($active['request_reason']) ?></p>
                <div class="contact-reveal-card">
                  <h4 class="contact-reveal-title">Student Contact</h4>
                  <p class="student-details"><small><?= esc($active['student_name']) ?> | <?= esc($active['student_id']) ?> | Year <?= esc($active['academic_year']) ?> | <?= esc($active['faculty_name'] ?: 'Faculty N/A') ?></small></p>
                  <p class="mentor-meta">Student Email: <?= esc($active['student_email']) ?></p>
                  <?php if (!empty($active['student_mobile'])): ?>
                    <p class="mentor-meta">Mobile: +94 <?= esc($active['student_mobile']) ?></p>
                  <?php endif; ?>
                  <?php if (!empty($active['student_linkedin_url'])): ?>
                    <p class="mentor-meta">LinkedIn: <a href="<?= esc($active['student_linkedin_url']) ?>" target="_blank" rel="noopener">View profile</a></p>
                  <?php endif; ?>
                </div>
                <div class="request-actions mentor-request-actions active-mentor-actions">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm mentorship-chat-open"
                    data-thread-id="<?= (int)$active['request_id'] ?>"
                    data-thread-name="<?= esc($active['student_name']) ?>"
                  >
                    <i class="fas fa-comments"></i>
                    Chat with Student
                  </button>
                  <form method="POST" action="<?= ROOT ?>/Alumni/Mentorship/end/<?= (int)$active['request_id'] ?>">
                    <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('End this mentorship now? Student will be prompted to submit required feedback.');">
                      <i class="fas fa-flag-checkered"></i> Mark Completed
                    </button>
                  </form>
                </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state compact-empty">
          <p>No accepted mentorships yet.</p>
        </div>
      <?php endif; ?>
    </section>

    <section class="dashboard-section completed-mentorships-section">
      <h2 class="section-title">Completed Mentorships</h2>

      <?php if (!empty($mentorshipData['completed'])): ?>
        <div class="requests-grid">
          <?php foreach ($mentorshipData['completed'] as $completed): ?>
            <article class="request-card completed-card">
              <div class="request-header">
                <h3 class="request-title"><?= esc($completed['topic'] ?: 'Completed Mentorship') ?></h3>
                <span class="status-badge status-completed">Completed</span>
              </div>
              <p class="request-description"><?= esc($completed['request_reason']) ?></p>
              <p class="mentor-meta">Student: <?= esc($completed['student_name']) ?></p>
              <p class="mentor-meta">Feedback Score: â­ <?= (int)($completed['rating'] ?? 0) ?></p>
              <?php if (!empty($completed['review_comment'])): ?>
                <p class="review-note"><strong>Feedback Note:</strong> "<?= esc($completed['review_comment']) ?>"</p>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state compact-empty">
          <p>No completed mentorships yet.</p>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<div class="mentorship-chat-modal" id="mentorshipChatModal" aria-hidden="true">
  <div class="mentorship-chat-panel" role="dialog" aria-modal="true" aria-labelledby="mentorshipChatTitle">
    <div class="mentorship-chat-header">
      <div>
        <h3 id="mentorshipChatTitle" class="mentorship-chat-title">Mentorship Chat</h3>
        <p id="mentorshipChatMeta" class="mentorship-chat-meta">Chat with your mentee</p>
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

<script>
  (function () {
    const toast = document.querySelector('.mentorship-toast');
    if (!toast) return;

    const closeBtn = toast.querySelector('.mentorship-toast-close');

    const dismissToast = function () {
      toast.classList.add('is-hiding');
      setTimeout(() => {
        if (toast && toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 260);
    };

    if (closeBtn) {
      closeBtn.addEventListener('click', dismissToast);
    }

    setTimeout(dismissToast, 3200);
  })();
</script>
<script>
  window.mentorshipChatConfig = {
    baseUrl: '<?=ROOT?>/alumni/Mentorship',
    currentUserId: '<?= (int)($_SESSION['user_id'] ?? 0) ?>'
  };
</script>
<script src="<?=ROOT?>/assets/js/mentorship-chat.js?v=<?=time()?>"></script>
</body>
</html>
