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

      <section class="dashboard-section mentors-section">
        <div class="section-header">
          <h2 class="section-title">Available Mentors</h2>
          <a href="<?=ROOT?>/student/Faq" class="btn btn-outline btn-sm faq-first-btn">
            <i class="fas fa-question-circle"></i>
            FAQ First
          </a>
        </div>

        <?php if (!empty($data['mentors'])): ?>
          <div class="mentors-grid">
            <?php foreach ($data['mentors'] as $mentor): ?>
              <article class="mentor-card">
                <div class="mentor-card-top">
                  <h3 class="mentor-name"><?= esc($mentor['mentor_name']) ?></h3>
                  <span class="status-badge status-open">Mentor</span>
                </div>
                <p class="mentor-meta">
                  <?= esc($mentor['faculty_name'] ?: 'Faculty N/A') ?>
                  <?php if (!empty($mentor['current_job'])): ?>
                    | <?= esc($mentor['current_job']) ?>
                  <?php endif; ?>
                </p>
                <p class="mentor-expertise"><?= esc($mentor['expertise_area'] ?: 'General Mentorship') ?></p>
                <p class="mentor-bio"><?= esc($mentor['mentor_bio'] ?: 'Experienced alumnus available for student mentorship.') ?></p>

                <div class="mentor-rating-row">
                  <span class="mentor-rating">⭐ <?= number_format((float)$mentor['avg_rating'], 1) ?></span>
                  <span class="mentor-sessions">(<?= (int)$mentor['sessions_count'] ?> sessions)</span>
                </div>

                <a class="btn btn-primary btn-sm" href="<?=ROOT?>/student/Mentorship/request/<?= (int)$mentor['mentor_user_id'] ?>">
                  <i class="fas fa-paper-plane"></i>
                  Request Mentorship
                </a>
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
                <p class="mentor-meta">Mentor: <?= esc($active['mentor_name']) ?></p>
                <p class="mentor-meta">Email: <?= esc($active['mentor_email']) ?></p>
                <p class="mentor-meta">Faculty: <?= esc($active['faculty_name'] ?: 'N/A') ?></p>
                <?php if (!empty($active['current_job'])): ?>
                  <p class="mentor-meta">Current Job: <?= esc($active['current_job']) ?></p>
                <?php endif; ?>
                <?php if (!empty($active['current_workplace'])): ?>
                  <p class="mentor-meta">Workplace: <?= esc($active['current_workplace']) ?></p>
                <?php endif; ?>
                <p class="mentor-meta"><small>Your mentor will end the session when mentorship is complete. Then you can submit the required review.</small></p>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state compact-empty">
            <p>No active mentorships.</p>
          </div>
        <?php endif; ?>
      </section>

      <section class="dashboard-section reviews-section">
        <h2 class="section-title">Pending Reviews (Required)</h2>

        <?php if (!empty($data['pendingReviews'])): ?>
          <div class="requests-grid">
            <?php foreach ($data['pendingReviews'] as $reviewItem): ?>
              <article class="request-card review-card">
                <div class="request-header">
                  <h3 class="request-title"><?= esc($reviewItem['topic'] ?: 'Mentorship Review') ?></h3>
                  <span class="status-badge status-pending-review">Pending Review</span>
                </div>
                <p class="mentor-meta">Mentor: <?= esc($reviewItem['mentor_name']) ?></p>
                <form method="POST" action="<?=ROOT?>/student/Mentorship/submitReview/<?= (int)$reviewItem['request_id'] ?>" class="review-form">
                  <label class="form-label" for="rating-<?= (int)$reviewItem['request_id'] ?>">Rating (required)</label>
                  <select id="rating-<?= (int)$reviewItem['request_id'] ?>" name="rating" class="form-select" required>
                    <option value="">Choose rating</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Very Poor</option>
                  </select>

                  <label class="form-label" for="comment-<?= (int)$reviewItem['request_id'] ?>">Comment (optional)</label>
                  <textarea id="comment-<?= (int)$reviewItem['request_id'] ?>" name="review_comment" class="form-textarea" rows="3" placeholder="Share your mentorship experience"></textarea>

                  <button type="submit" class="btn btn-primary btn-sm">
                    Submit Review & Complete
                  </button>
                </form>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty-state compact-empty">
            <p>No pending reviews.</p>
          </div>
        <?php endif; ?>
      </section>
    </div>
  </main>
</div>

<script src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
