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
                                    <label for="password">Password</label>
                                    <input id="password" name="password" type="password" class="input" required>
                                </div>

                                <div class="form-row">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input id="confirm_password" name="confirm_password" type="password" class="input" required>
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



<script>
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
