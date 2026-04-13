<?php require '../app/views/partials/superadmin_header.php'; ?>

<?php
if (!function_exists('renderAidSupervisionCardSuperadmin')) {
    function renderAidSupervisionCardSuperadmin($card)
    {
        $requestId = (int)($card->request_id ?? 0);
        $studentUserId = (int)($card->student_user_id ?? 0);
        $alumnusUserId = (int)($card->alumnus_user_id ?? 0);
        $status = strtolower((string)($card->status ?? 'pending_verification'));

        $statusLabel = 'Pending for Counselor';
        if ($status === 'open') {
            $statusLabel = 'Approved by Counselor';
        } elseif (in_array($status, ['approved', 'accepted'], true)) {
            $statusLabel = 'Accepted by Alumni';
        } elseif ($status === 'completed') {
            $statusLabel = 'Completed';
        }

        $studentIdDoc = trim((string)($card->student_id_pdf_path ?? ''));
        $incomeDoc = trim((string)($card->income_statement_path ?? ''));
        $gramasevaDoc = trim((string)($card->gramaseva_cert_path ?? ''));

        $studentIdUrl = $studentIdDoc !== '' ? ROOT . '/' . ltrim($studentIdDoc, '/') : '';
        $incomeDocUrl = $incomeDoc !== '' ? ROOT . '/' . ltrim($incomeDoc, '/') : '';
        $gramasevaDocUrl = $gramasevaDoc !== '' ? ROOT . '/' . ltrim($gramasevaDoc, '/') : '';
        ?>
        <article class="aid-card">
            <div class="card-head">
                <div>
                    <h3 class="student-name"><?= esc($card->student_name ?? 'Unknown Student') ?></h3>
                    <p class="student-meta"><?= esc($card->student_email ?? '-') ?></p>
                    <p class="student-meta">Faculty: <?= esc($card->faculty_name ?? 'Unknown') ?> · Student ID: <?= esc($card->student_id ?? '-') ?></p>
                </div>
                <div class="right-meta">
                    <span class="status-badge status-<?= esc(str_replace('_', '-', $status)) ?>"><?= esc($statusLabel) ?></span>
                    <p class="request-date">Submitted: <?= !empty($card->created_at) ? date('M j, Y g:i A', strtotime($card->created_at)) : '-' ?></p>
                </div>
            </div>

            <div class="card-grid">
                <div>
                    <h4>Aid Type</h4>
                    <p><?= esc(ucfirst((string)($card->aid_type ?? 'N/A'))) ?></p>
                </div>
                <div>
                    <h4>Amount</h4>
                    <p><?= isset($card->amount) && $card->amount !== null ? 'LKR ' . esc(number_format((float)$card->amount, 2)) : 'N/A' ?></p>
                </div>
                <div>
                    <h4>Student Mobile</h4>
                    <p><?= esc($card->mobile_number ?? 'N/A') ?></p>
                </div>
            </div>

            <div class="reason-block">
                <h4>Request Reason</h4>
                <p><?= esc($card->reason ?? 'N/A') ?></p>
            </div>

            <div class="docs-block">
                <h4>Supporting Documents</h4>
                <ul class="docs-list">
                    <li>
                        <span>Student ID Document</span>
                        <?php if ($studentIdUrl !== ''): ?>
                            <a href="<?= esc($studentIdUrl) ?>" target="_blank" rel="noopener">View File</a>
                        <?php else: ?>
                            <span class="doc-missing">N/A</span>
                        <?php endif; ?>
                    </li>
                    <li>
                        <span>Income Statement</span>
                        <?php if ($incomeDocUrl !== ''): ?>
                            <a href="<?= esc($incomeDocUrl) ?>" target="_blank" rel="noopener">View File</a>
                        <?php else: ?>
                            <span class="doc-missing">N/A</span>
                        <?php endif; ?>
                    </li>
                    <li>
                        <span>Gramaseva Certificate</span>
                        <?php if ($gramasevaDocUrl !== ''): ?>
                            <a href="<?= esc($gramasevaDocUrl) ?>" target="_blank" rel="noopener">View File</a>
                        <?php else: ?>
                            <span class="doc-missing">N/A</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>

            <?php if ($alumnusUserId > 0): ?>
                <div class="alumni-block">
                    <h4>Assigned Alumni</h4>
                    <p><?= esc($card->alumnus_name ?? 'Alumni') ?> · <?= esc($card->alumnus_email ?? 'N/A') ?></p>
                </div>
            <?php endif; ?>

            <div class="card-actions">
                <?php if ($studentUserId > 0): ?>
                    <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/superadmin/Userprofile?id=<?= $studentUserId ?>">
                        <i class="fas fa-user"></i> View Student
                    </a>
                <?php endif; ?>

                <?php if ($alumnusUserId > 0): ?>
                    <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/superadmin/Userprofile?id=<?= $alumnusUserId ?>">
                        <i class="fas fa-user-graduate"></i> View Alumni
                    </a>
                <?php endif; ?>
            </div>
        </article>
        <?php
    }
}
?>

<div class="dashboard-container">
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Pending for Counselor</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($aidRequestsData['stats']['pending_for_counselor'] ?? 0) ?></span>
                        <span class="stat-label">Awaiting Verification</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($aidRequestsData['pending_for_counselor'])): ?>
                    <?php foreach ($aidRequestsData['pending_for_counselor'] as $card): ?>
                        <?php renderAidSupervisionCardSuperadmin($card); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No requests are currently waiting for counselor verification.</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Approved by Counselor</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($aidRequestsData['stats']['approved_by_counselor'] ?? 0) ?></span>
                        <span class="stat-label">Waiting for Alumni</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($aidRequestsData['approved_by_counselor'])): ?>
                    <?php foreach ($aidRequestsData['approved_by_counselor'] as $card): ?>
                        <?php renderAidSupervisionCardSuperadmin($card); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No counselor-approved requests are waiting for alumni yet.</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Accepted by Alumni</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($aidRequestsData['stats']['accepted_by_alumni'] ?? 0) ?></span>
                        <span class="stat-label">Alumni Accepted</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($aidRequestsData['accepted_by_alumni'])): ?>
                    <?php foreach ($aidRequestsData['accepted_by_alumni'] as $card): ?>
                        <?php renderAidSupervisionCardSuperadmin($card); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No aid requests have been accepted by alumni yet.</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Completed</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($aidRequestsData['stats']['completed'] ?? 0) ?></span>
                        <span class="stat-label">Closed Cases</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($aidRequestsData['completed'])): ?>
                    <?php foreach ($aidRequestsData['completed'] as $card): ?>
                        <?php renderAidSupervisionCardSuperadmin($card); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No completed aid requests yet.</div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<style>
.main-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.cards-wrap {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    align-items: stretch;
}

.aid-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.3rem;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.aid-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.student-name {
    margin: 0 0 0.2rem 0;
    color: #1F2937;
    font-size: 1.15rem;
    font-weight: 600;
}

.student-meta,
.request-date {
    margin: 0.15rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.right-meta {
    text-align: right;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending-verification {
    background-color: #FEF3C7;
    color: #92400E;
}

.status-open {
    background-color: #E0E7FF;
    color: #3730A3;
}

.status-approved,
.status-accepted {
    background-color: #DCFCE7;
    color: #166534;
}

.status-completed {
    background-color: #DBEAFE;
    color: #1E3A8A;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.7rem;
}

.card-grid h4,
.reason-block h4,
.docs-block h4,
.alumni-block h4 {
    margin: 0 0 0.35rem 0;
    color: #374151;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.card-grid p,
.reason-block p,
.alumni-block p {
    margin: 0;
    color: #4B5563;
    line-height: 1.45;
}

.reason-block,
.docs-block,
.alumni-block {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 0.9rem;
}

.docs-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}

.docs-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.92rem;
    color: #374151;
}

.docs-list a {
    color: #0E2072;
    font-weight: 600;
    text-decoration: none;
}

.docs-list a:hover {
    text-decoration: underline;
}

.doc-missing {
    color: #9CA3AF;
}

.card-actions {
    display: flex;
    gap: 0.65rem;
    flex-wrap: wrap;
    margin-top: auto;
}

.card-actions .btn {
    min-width: 165px;
    justify-content: center;
}

.empty-state {
    grid-column: 1 / -1;
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
    padding: 1.35rem 1rem;
    color: #4B5563;
    background: #F9FAFB;
    text-align: center;
}

@media (max-width: 1080px) {
    .cards-wrap {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 720px) {
    .card-head {
        flex-direction: column;
    }

    .right-meta {
        text-align: left;
    }

    .card-grid {
        grid-template-columns: 1fr;
    }

    .docs-list li {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
