<?php require '../app/views/partials/superadmin_header.php'; ?>

<?php
if (!function_exists('renderAidSupervisionCardSuperadmin')) {
    function renderAidSupervisionCardSuperadmin($card)
    {
        $requestId = (int)($card->request_id ?? 0);
        $studentUserId = (int)($card->student_user_id ?? 0);
        $alumnusUserId = (int)($card->alumnus_user_id ?? 0);
        $status = strtolower((string)($card->status ?? 'pending_verification'));

        $statusLabel = 'Pending for Counsellor';
        if ($status === 'open') {
            $statusLabel = 'Approved by Counsellor';
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
                <h2 class="section-title">Pending for Counsellor</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($aidRequestsData['stats']['pending_for_counsellor'] ?? 0) ?></span>
                        <span class="stat-label">Awaiting Verification</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($aidRequestsData['pending_for_counsellor'])): ?>
                    <?php foreach ($aidRequestsData['pending_for_counsellor'] as $card): ?>
                        <?php renderAidSupervisionCardSuperadmin($card); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No requests are currently waiting for counsellor verification.</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Approved by Counsellor</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($aidRequestsData['stats']['approved_by_counsellor'] ?? 0) ?></span>
                        <span class="stat-label">Waiting for Alumni</span>
                    </div>
                </div>
            </div>

            <div class="cards-wrap">
                <?php if (!empty($aidRequestsData['approved_by_counsellor'])): ?>
                    <?php foreach ($aidRequestsData['approved_by_counsellor'] as $card): ?>
                        <?php renderAidSupervisionCardSuperadmin($card); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No counsellor-approved requests are waiting for alumni yet.</div>
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


