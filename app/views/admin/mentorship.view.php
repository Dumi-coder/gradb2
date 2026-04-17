<?php require '../app/views/partials/admin_header.php'; ?>

<?php
if (!function_exists('renderMentorshipSectionCard')) {
    function renderMentorshipSectionCard($card, $routeBase)
    {
        $requestId = (int)($card['request_id'] ?? 0);
        $studentUserId = (int)($card['student_user_id'] ?? 0);
        $mentorUserId = (int)($card['alumnus_user_id'] ?? 0);
        $status = (string)($card['status'] ?? 'pending');
        $statusClass = strtolower(str_replace('_', '-', $status));
        ?>
        <article class="mentorship-card">
            <div class="card-head">
                <div>
                    <h3 class="student-name"><?= esc($card['student_name'] ?? 'Unknown Student') ?></h3>
                    <p class="student-meta"><?= esc($card['student_email'] ?? '-') ?></p>
                    <p class="student-meta">Faculty: <?= esc($card['faculty_name'] ?? 'Unknown') ?></p>
                </div>
                <div class="right-meta">
                    <span class="status-badge status-<?= esc($statusClass) ?>"><?= esc(ucfirst(str_replace('_', ' ', $status))) ?></span>
                </div>
            </div>

            <div class="card-grid">
                <div>
                    <h4>Mentor</h4>
                    <p><?= esc($card['mentor_name'] ?? 'Not accepted yet') ?></p>
                    <p class="muted"><?= esc($card['mentor_email'] ?? '-') ?></p>
                </div>
                <div>
                    <h4>Topic</h4>
                    <p><?= esc($card['topic'] ?? '-') ?></p>
                </div>
            </div>

            <div class="reason-block">
                <h4>Request Reason</h4>
                <p><?= esc($card['request_reason'] ?? '-') ?></p>
            </div>

            <?php if ($status === 'completed'): ?>
                <div class="reason-block completed-block">
                    <h4>Student Feedback</h4>
                    <p>Rating: <strong><?= isset($card['rating']) && $card['rating'] !== null ? (int)$card['rating'] . '/5' : 'N/A' ?></strong></p>
                    <p><?= esc($card['review_comment'] ?? 'No written feedback.') ?></p>
                </div>
            <?php endif; ?>

            <div class="card-actions">
                <?php if ($studentUserId > 0): ?>
                    <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/<?= $routeBase ?>/Userprofile?id=<?= $studentUserId ?>">
                        <i class="fas fa-user"></i> View Student
                    </a>
                <?php endif; ?>

                <?php if ($mentorUserId > 0): ?>
                    <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/<?= $routeBase ?>/Userprofile?id=<?= $mentorUserId ?>">
                        <i class="fas fa-user-tie"></i> View Mentor
                    </a>
                <?php endif; ?>

                <?php if (!empty($card['student_email'])): ?>
                    <a class="btn btn-primary btn-sm" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($card['student_email']) ?>&su=<?= urlencode('Mentorship Request #' . $requestId) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-envelope"></i> Email Student
                    </a>
                <?php endif; ?>

                <?php if (!empty($card['mentor_email'])): ?>
                    <a class="btn btn-primary btn-sm" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($card['mentor_email']) ?>&su=<?= urlencode('Mentorship Request #' . $requestId) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-envelope"></i> Email Mentor
                    </a>
                <?php endif; ?>
            </div>

            <p class="request-date request-date-bottom">Requested: <?= !empty($card['created_at']) ? date('M j, Y g:i A', strtotime($card['created_at'])) : '-' ?></p>
        </article>
        <?php
    }
}
?>

<div class="dashboard-container">
    <?php require '../app/views/partials/admin_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">New Requests</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($mentorshipData['stats']['pending'] ?? 0) ?></span>
                        <span class="stat-label">Not Accepted Yet</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($mentorshipData['pending'])): ?>
                    <?php foreach ($mentorshipData['pending'] as $card): ?>
                        <?php renderMentorshipSectionCard($card, 'admin'); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">Nothing pending right now. New mentorship requests for your faculty will appear here.</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Active Mentorships</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($mentorshipData['stats']['ongoing'] ?? 0) ?></span>
                        <span class="stat-label">Accepted Sessions</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($mentorshipData['ongoing'])): ?>
                    <?php foreach ($mentorshipData['ongoing'] as $card): ?>
                        <?php renderMentorshipSectionCard($card, 'admin'); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No ongoing mentorship sessions at the moment for your faculty.</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Closed Mentorships</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($mentorshipData['stats']['completed'] ?? 0) ?></span>
                        <span class="stat-label">Completed Sessions</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($mentorshipData['completed'])): ?>
                    <?php foreach ($mentorshipData['completed'] as $card): ?>
                        <?php renderMentorshipSectionCard($card, 'admin'); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No completed mentorship sessions yet. Completed sessions for your faculty will show here.</div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>


