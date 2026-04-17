<?php require '../app/views/partials/admin_header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/fundraiser-moderation.css">

<div class="dashboard-container">
    <?php require '../app/views/partials/admin_sidebar.php'; ?>

    <main class="main-content fundraiser-moderation-page">
        <?php if (!empty($flash)): ?>
            <?php $flashType = strtolower((string)($flash['type'] ?? 'success')) === 'error' ? 'error' : 'success'; ?>
            <div class="moderation-toast moderation-toast-<?= esc($flashType) ?>" role="status" aria-live="polite">
                <i class="fas <?= $flashType === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"></i>
                <span><?= esc($flash['text'] ?? '') ?></span>
                <button type="button" class="moderation-toast-close" aria-label="Close message">&times;</button>
            </div>
        <?php endif; ?>

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
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($fundraiserData['stats']['closed_fundraisers'] ?? 0) ?></span>
                        <span class="stat-label">Closed</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= (int)($fundraiserData['stats']['rejected_fundraisers'] ?? 0) ?></span>
                        <span class="stat-label">Rejected</span>
                    </div>
                </div>
            </div>

            <div class="reported-fundraisers-section">
                <h3 class="subsection-title">All Fundraiser Requests</h3>
                <div class="reported-fundraisers-container">
                    <?php if (!empty($fundraiserData['all_fundraisers'])): foreach ($fundraiserData['all_fundraisers'] as $fundraiser): ?>
                    <div class="reported-fundraiser-card">
                        <?php
                            $status = strtolower((string)($fundraiser->status ?? ''));
                            $statusLabel = strtoupper((string)($fundraiser->status ?? 'UNKNOWN'));
                            $statusClass = 'status-pending';
                            if ($status === 'approved') {
                                $statusClass = 'status-approved';
                                $statusLabel = 'APPROVED';
                            } elseif (in_array($status, ['closed', 'stopped'], true)) {
                                $statusClass = 'status-closed';
                                $statusLabel = 'CLOSED';
                            } elseif ($status === 'rejected') {
                                $statusClass = 'status-rejected';
                                $statusLabel = 'REJECTED';
                            } else {
                                $statusLabel = 'PENDING';
                            }
                        ?>
                        <div class="fundraiser-header">
                            <div class="fundraiser-info">
                                <h4 class="fundraiser-title"><?= esc($fundraiser->title) ?></h4>
                                <p class="fundraiser-organizer">By: <?= esc($fundraiser->creator_name) ?> (<?= esc($fundraiser->creator_email) ?>)</p>
                            </div>
                            <div class="fundraiser-meta">
                                <span class="report-reason"><?= (int)$fundraiser->document_count ?> docs</span>
                                <span class="status-badge <?= esc($statusClass) ?>"><?= esc($statusLabel) ?></span>
                            </div>
                        </div>
                        <div class="fundraiser-content">
                            <p><strong>Description:</strong> <?= esc($fundraiser->description ?: ($fundraiser->reasoning ?: '-')) ?></p>
                        </div>
                        <div class="report-details">
                            <p><strong>Target:</strong> <?= esc($fundraiser->currency) ?> <?= esc(format_money($fundraiser->target_amount)) ?></p>
                            <p><strong>Submitted:</strong> <?= esc(date('M j, Y', strtotime((string)$fundraiser->created_at))) ?></p>
                            <?php if (!empty($fundraiser->creator_faculty_name)): ?><p><strong>Faculty:</strong> <?= esc($fundraiser->creator_faculty_name) ?></p><?php endif; ?>
                            <p><strong>Documents:</strong></p>
                            <?php if (!empty($fundraiser->documents)): ?>
                                <ul>
                                    <?php foreach ($fundraiser->documents as $doc): ?>
                                        <li>
                                            <a href="<?= ROOT ?>/admin/fundraiser-moderation/download/<?= (int)$doc->document_id ?>">
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
                            <?php if ($status === 'pending'): ?>
                                <form method="POST" action="<?= ROOT ?>/admin/fundraiser-moderation/approve/<?= (int)$fundraiser->fundraiser_id ?>">
                                    <input type="text" name="admin_note" placeholder="Optional approval note">
                                    <button class="btn btn-success btn-sm" type="submit"><i class="fas fa-check"></i> Approve</button>
                                </form>
                                <form method="POST" action="<?= ROOT ?>/admin/fundraiser-moderation/reject/<?= (int)$fundraiser->fundraiser_id ?>">
                                    <input type="text" name="admin_note" placeholder="Rejection note (required)" required>
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-times"></i> Reject</button>
                                </form>
                            <?php elseif ($status === 'approved'): ?>
                                <form method="POST" action="<?= ROOT ?>/admin/fundraiser-moderation/end/<?= (int)$fundraiser->fundraiser_id ?>" data-confirm-message="End this approved fundraiser now?">
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-stop-circle"></i> End Fundraiser</button>
                                </form>
                            <?php else: ?>
                                <p><small>No actions available for this status.</small></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                        <div class="reported-fundraiser-card"><p>No fundraiser requests found.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>

<div id="moderationConfirmModal" class="moderation-confirm-modal">
    <div class="moderation-confirm-content">
        <div class="moderation-confirm-header">
            <h2 class="moderation-confirm-title">Confirm Action</h2>
            <button class="moderation-confirm-close" type="button" id="moderationConfirmClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="moderation-confirm-body">
            <p id="moderationConfirmText" class="moderation-confirm-text">Are you sure?</p>
            <div class="moderation-confirm-actions">
                <button type="button" class="btn btn-outline" id="moderationConfirmCancel">Cancel</button>
                <button type="button" class="btn btn-danger" id="moderationConfirmOk">Yes, Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const toast = document.querySelector('.moderation-toast');
        if (!toast) return;

        const closeBtn = toast.querySelector('.moderation-toast-close');

        const dismissToast = function () {
            if (toast.classList.contains('is-hiding')) return;
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

    (function () {
        const confirmModal = document.getElementById('moderationConfirmModal');
        const confirmText = document.getElementById('moderationConfirmText');
        const okBtn = document.getElementById('moderationConfirmOk');
        const cancelBtn = document.getElementById('moderationConfirmCancel');
        const closeBtn = document.getElementById('moderationConfirmClose');
        let pendingForm = null;

        const closeConfirm = function () {
            if (!confirmModal) return;
            confirmModal.classList.remove('is-open');
            pendingForm = null;
        };

        const forms = document.querySelectorAll('form[data-confirm-message]');
        forms.forEach((form) => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                pendingForm = form;
                if (confirmText) {
                    confirmText.textContent = form.dataset.confirmMessage || 'Are you sure?';
                }
                if (confirmModal) {
                    confirmModal.classList.add('is-open');
                }
            });
        });

        if (okBtn) {
            okBtn.addEventListener('click', function () {
                if (pendingForm) {
                    const formToSubmit = pendingForm;
                    pendingForm = null;
                    closeConfirm();
                    formToSubmit.submit();
                }
            });
        }

        if (cancelBtn) cancelBtn.addEventListener('click', closeConfirm);
        if (closeBtn) closeBtn.addEventListener('click', closeConfirm);
        window.addEventListener('click', function (event) {
            if (event.target === confirmModal) {
                closeConfirm();
            }
        });
    })();
</script>
