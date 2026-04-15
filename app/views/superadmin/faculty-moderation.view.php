<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Faculty Moderation</h2>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= esc($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"><?= implode('<br>', array_map('esc', $errors)) ?></div>
            <?php endif; ?>

            <div class="faculty-moderation-grid">
                <div class="faculty-list-panel">
                    <h3>Faculties</h3>
                    <div class="faculty-list">
                        <?php if (!empty($faculties)): ?>
                            <?php foreach ($faculties as $faculty): ?>
                                <?php $active = ((int)$selectedFacultyId === (int)$faculty->faculty_id) ? ' active' : ''; ?>
                                <a href="<?= ROOT ?>/superadmin/FacultyModeration?faculty_id=<?= (int)$faculty->faculty_id ?>" class="faculty-item<?= $active ?>">
                                    <span class="faculty-name"><?= esc($faculty->faculty_name) ?></span>
                                    <?php if (!empty($faculty->admin_name)): ?>
                                        <span class="faculty-admin">Admin: <?= esc($faculty->admin_name) ?></span>
                                    <?php else: ?>
                                        <span class="faculty-admin no-admin">No active admin</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-text">No faculties found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="faculty-details-panel">
                    <?php if (!empty($selectedFaculty)): ?>
                        <?php $hasActiveAdmin = !empty($selectedFaculty->faculty_admin_id); ?>
                        <h3><?= esc($selectedFaculty->faculty_name) ?></h3>

                        <div class="current-admin-card">
                            <h4>Current Faculty Admin</h4>
                            <?php if (!empty($selectedFaculty->faculty_admin_id)): ?>
                                <p><strong>Name:</strong> <?= esc($selectedFaculty->admin_name ?? 'N/A') ?></p>
                                <p><strong>Email:</strong> <?= esc($selectedFaculty->admin_email ?? 'N/A') ?></p>

                                <form method="POST" class="inline-form demote-form" data-admin-name="<?= esc($selectedFaculty->admin_name ?? 'this faculty admin') ?>">
                                    <input type="hidden" name="action" value="demote">
                                    <input type="hidden" name="faculty_id" value="<?= (int)$selectedFaculty->faculty_id ?>">
                                    <input type="hidden" name="faculty_admin_id" value="<?= esc($selectedFaculty->faculty_admin_id) ?>">
                                    <button type="submit" class="btn btn-danger">Demote Faculty Admin</button>
                                </form>
                            <?php else: ?>
                                <p class="no-admin">No active faculty admin for this faculty.</p>
                            <?php endif; ?>
                        </div>

                        <div class="appoint-card<?= $hasActiveAdmin ? ' is-disabled' : '' ?>">
                            <h4>Appoint New Faculty Admin</h4>
                            <?php if ($hasActiveAdmin): ?>
                                <p class="disabled-note">This faculty already has an admin.</p>
                            <?php endif; ?>
                            <form method="POST" class="appoint-form" <?= $hasActiveAdmin ? 'aria-disabled="true"' : '' ?>>
                                <input type="hidden" name="action" value="appoint">
                                <input type="hidden" name="faculty_id" value="<?= (int)$selectedFaculty->faculty_id ?>">

                                <fieldset <?= $hasActiveAdmin ? 'disabled' : '' ?>>

                                <div class="form-row">
                                    <label for="name">Full Name</label>
                                    <input id="name" name="name" type="text" class="input" required>
                                </div>

                                <div class="form-row">
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="email" class="input" required>
                                </div>

                                <div class="form-row">
                                    <label for="faculty-admin-password">Password</label>
                                    <input id="faculty-admin-password" name="password" type="password" class="input" minlength="8" autocomplete="new-password" required>
                                    <div id="faculty-admin-password-strength" class="password-strength-shell"></div>
                                </div>

                                <div class="form-row">
                                    <label for="faculty-admin-confirm-password">Confirm Password</label>
                                    <input id="faculty-admin-confirm-password" name="confirm_password" type="password" class="input" minlength="8" autocomplete="new-password" required>
                                </div>

                                <button type="submit" class="btn btn-primary" <?= $hasActiveAdmin ? 'disabled' : '' ?>>Create and Appoint</button>
                                </fieldset>
                            </form>
                        </div>
                    <?php else: ?>
                        <p class="empty-text">Select a faculty to view moderation details.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>

<div id="demoteConfirmModal" class="confirm-modal" aria-hidden="true">
    <div class="confirm-modal-backdrop" data-close-modal="true"></div>
    <div class="confirm-modal-content" role="dialog" aria-modal="true" aria-labelledby="demoteModalTitle">
        <h3 id="demoteModalTitle">Confirm Demotion</h3>
        <p id="demoteModalMessage">Are you sure you want to demote this faculty admin?</p>
        <div class="confirm-modal-actions">
            <button type="button" class="btn btn-outline" id="cancelDemotionBtn">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDemotionBtn">Yes, Demote</button>
        </div>
    </div>
</div>

<script src="<?= ROOT ?>/assets/js/password-validation.js"></script>

<style>
.faculty-moderation-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 1.25rem;
}

.faculty-list-panel,
.faculty-details-panel,
.current-admin-card,
.appoint-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1rem;
}

.faculty-list {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    margin-top: 0.75rem;
}

.faculty-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    text-decoration: none;
    color: #1f2937;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.75rem;
}

.faculty-item:hover,
.faculty-item.active {
    border-color: #0e2072;
    background: #f8faff;
}

.faculty-name {
    font-weight: 600;
}

.faculty-admin {
    font-size: 0.86rem;
    color: #4b5563;
}

.no-admin {
    color: #b91c1c;
}

.current-admin-card {
    margin: 1rem 0;
}

.appoint-form {
    display: grid;
    gap: 0.85rem;
}

.appoint-form fieldset {
    margin: 0;
    padding: 0;
    border: 0;
    display: grid;
    gap: 0.85rem;
}

.appoint-card.is-disabled {
    opacity: 0.65;
}

.appoint-card.is-disabled .appoint-form {
    pointer-events: none;
}

.disabled-note {
    margin: 0.25rem 0 0.9rem;
    color: #b45309;
    font-weight: 600;
}

.form-row {
    display: grid;
    gap: 0.35rem;
}

.password-strength-shell {
    margin-top: 0.1rem;
}

.password-strength-shell .password-strength-indicator {
    margin-top: 0;
    font-size: 0.78rem;
}

.password-strength-shell .strength-text {
    margin-bottom: 6px;
}

.password-strength-shell .requirements-list {
    gap: 2px;
}

.password-strength-shell .requirement {
    font-size: 0.78rem;
}

.inline-form {
    margin-top: 0.8rem;
}

.alert {
    margin-bottom: 0.9rem;
    padding: 0.7rem 0.9rem;
    border-radius: 8px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
}

.alert-danger {
    background: #fee2e2;
    color: #b91c1c;
}

.empty-text {
    color: #6b7280;
}

.confirm-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.confirm-modal.open {
    display: flex;
}

.confirm-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(17, 24, 39, 0.55);
}

.confirm-modal-content {
    position: relative;
    width: min(92vw, 460px);
    background: #ffffff;
    border-radius: 14px;
    padding: 1.1rem;
    border-top: 5px solid #b91c1c;
    box-shadow: 0 22px 50px rgba(0, 0, 0, 0.28);
    animation: modalIn 180ms ease-out;
}

.confirm-modal-content h3 {
    margin: 0 0 0.45rem;
    color: #111827;
}

.confirm-modal-content p {
    margin: 0 0 1rem;
    color: #374151;
}

.confirm-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: translateY(8px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 960px) {
    .faculty-moderation-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (window.passwordValidator) {
            window.passwordValidator.initPasswordValidation(
                'faculty-admin-password',
                'faculty-admin-confirm-password',
                'faculty-admin-password-strength'
            );
        }
    });
})();

(function () {
    const modal = document.getElementById('demoteConfirmModal');
    const message = document.getElementById('demoteModalMessage');
    const cancelBtn = document.getElementById('cancelDemotionBtn');
    const confirmBtn = document.getElementById('confirmDemotionBtn');
    const demoteForms = document.querySelectorAll('.demote-form');
    let pendingForm = null;

    if (!modal || !confirmBtn || !cancelBtn || demoteForms.length === 0) {
        return;
    }

    function openModal(form) {
        pendingForm = form;
        const adminName = form.getAttribute('data-admin-name') || 'this faculty admin';
        message.textContent = 'Are you sure you want to demote ' + adminName + '?';
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        pendingForm = null;
    }

    demoteForms.forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            openModal(form);
        });
    });

    confirmBtn.addEventListener('click', function () {
        if (pendingForm) {
            pendingForm.submit();
        }
    });

    cancelBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
        if (e.target && e.target.getAttribute('data-close-modal') === 'true') {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) {
            closeModal();
        }
    });
})();
</script>
