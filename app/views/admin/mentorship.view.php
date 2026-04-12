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

<style>
.main-content {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.cards-wrap {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    align-items: stretch;
}

.mentorship-card {
    background: #ffffff;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}

.mentorship-card:hover {
    border-color: #0e2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.student-name {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
    font-size: 1.2rem;
    line-height: 1.3;
    font-weight: 600;
}

.student-meta,
.request-date,
.muted {
    margin: 0.2rem 0;
    color: #6b7280;
    font-size: 0.92rem;
    word-break: break-word;
}

.right-meta {
    text-align: right;
}

.card-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.9rem;
    margin-bottom: 1rem;
}

.card-grid h4,
.reason-block h4 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 0.98rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.card-grid p,
.reason-block p {
    margin: 0;
    color: #4b5563;
    line-height: 1.5;
    font-size: 1rem;
}

.reason-block {
    margin-bottom: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1rem;
}

.completed-block {
    border-left: 3px solid #93c5fd;
}

.card-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin-top: auto;
}

.card-actions .btn {
    flex: 1 1 calc(50% - 0.3rem);
    min-width: 180px;
    justify-content: center;
}

.request-date-bottom {
    margin-top: 0.65rem;
    align-self: flex-start;
}

.empty-state {
    grid-column: 1 / -1;
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 1.5rem 1.1rem;
    color: #4b5563;
    background: #f9fafb;
    text-align: center;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-accepted {
    background: #dcfce7;
    color: #166534;
}

.status-completed {
    background: #dbeafe;
    color: #1e3a8a;
}

@media (max-width: 900px) {
    .cards-wrap {
        grid-template-columns: 1fr;
    }

    .card-head {
        flex-direction: column;
    }

    .right-meta {
        text-align: left;
    }
}

@media (max-width: 640px) {
    .card-actions {
        grid-template-columns: 1fr;
    }
}
</style>
