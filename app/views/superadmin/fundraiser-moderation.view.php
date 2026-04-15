<?php require '../app/views/partials/superadmin_header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/fundraiser-moderation.css">

<div class="dashboard-container">
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    <main class="main-content fundraiser-moderation-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Fundraiser Moderation</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($fundraiserData['stats']['total_fundraisers'] ?? 0) ?></span>
                        <span class="stat-label">Total Fundraisers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($fundraiserData['stats']['pending_fundraisers'] ?? 0) ?></span>
                        <span class="stat-label">Pending</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($fundraiserData['stats']['approved_fundraisers'] ?? 0) ?></span>
                        <span class="stat-label">Approved</span>
                    </div>
                </div>
            </div>

            <?php if (!empty($flash)): ?>
                <div class="create-info"><p><?= esc($flash['text'] ?? '') ?></p></div>
            <?php endif; ?>

            <div class="reported-fundraisers-section">
                <h3 class="subsection-title">Pending Requests</h3>
                <div class="reported-fundraisers-container">
                    <?php if (!empty($fundraiserData['pending_fundraisers'])): foreach ($fundraiserData['pending_fundraisers'] as $fundraiser): ?>
                    <div class="reported-fundraiser-card">
                        <div class="fundraiser-header">
                            <div class="fundraiser-info">
                                <h4 class="fundraiser-title"><?= esc($fundraiser->title) ?></h4>
                                <p class="fundraiser-organizer">By: <?= esc($fundraiser->creator_name) ?> (<?= esc($fundraiser->creator_email) ?>)</p>
                            </div>
                            <div class="fundraiser-meta">
                                <span class="report-reason"><?= (int)$fundraiser->document_count ?> docs</span>
                                <span class="status-badge status-pending">PENDING</span>
                            </div>
                        </div>
                        <div class="fundraiser-content">
                            <p><strong>Description:</strong> <?= esc($fundraiser->description ?: ($fundraiser->reasoning ?: '-')) ?></p>
                        </div>
                        <div class="report-details">
                            <p><strong>Target:</strong> <?= esc($fundraiser->currency) ?> <?= esc(format_money($fundraiser->target_amount)) ?></p>
                            <p><strong>Submitted:</strong> <?= esc(date('M j, Y', strtotime((string)$fundraiser->created_at))) ?></p>
                            <p><strong>Documents:</strong></p>
                            <?php if (!empty($fundraiser->documents)): ?>
                                <ul>
                                    <?php foreach ($fundraiser->documents as $doc): ?>
                                        <li>
                                            <a href="<?= ROOT ?>/superadmin/fundraiser-moderation/download/<?= (int)$doc->document_id ?>">
                                                <?= esc($doc->original_name ?: ('Document #' . (int)$doc->document_id)) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No documents uploaded.</p>
                            <?php endif; ?>
                        </div>
                        <div class="fundraiser-actions">
                            <form method="POST" action="<?= ROOT ?>/superadmin/fundraiser-moderation/approve/<?= (int)$fundraiser->fundraiser_id ?>">
                                <input type="text" name="admin_note" placeholder="Optional approval note">
                                <button class="btn btn-success btn-sm" type="submit"><i class="fas fa-check"></i> Approve</button>
                            </form>
                            <form method="POST" action="<?= ROOT ?>/superadmin/fundraiser-moderation/reject/<?= (int)$fundraiser->fundraiser_id ?>">
                                <input type="text" name="admin_note" placeholder="Rejection note (required)" required>
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-times"></i> Reject</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                        <div class="reported-fundraiser-card"><p>No pending fundraiser requests.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>
