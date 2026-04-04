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

    <section class="dashboard-section mentor-reputation-section">
      <div class="reputation-card">
        <h2 class="section-title">Your Mentor Reputation</h2>
        <p class="mentor-rating">⭐ <?= number_format((float)($mentorshipData['reputation']['avg_rating'] ?? 0), 1) ?></p>
        <p class="mentor-sessions">Completed Sessions: <?= (int)($mentorshipData['reputation']['total_completed_sessions'] ?? 0) ?></p>
      </div>
    </section>

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
              <p class="student-details"><small><?= esc($active['student_name']) ?> | <?= esc($active['student_id']) ?> | Year <?= esc($active['academic_year']) ?> | <?= esc($active['faculty_name'] ?: 'Faculty N/A') ?></small></p>
              <p class="mentor-meta">Student Email: <?= esc($active['student_email']) ?></p>
              <div class="request-actions mentor-request-actions">
                <form method="POST" action="<?= ROOT ?>/Alumni/Mentorship/end/<?= (int)$active['request_id'] ?>">
                  <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('End this mentorship now? Student will be prompted to submit a required review.');">
                    <i class="fas fa-flag-checkered"></i> End Mentorship
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
              <p class="mentor-meta">Rating: ⭐ <?= (int)($completed['rating'] ?? 0) ?></p>
              <?php if (!empty($completed['review_comment'])): ?>
                <p class="review-note">"<?= esc($completed['review_comment']) ?>"</p>
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

<script src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
