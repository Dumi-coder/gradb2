<?php
$passwordModalId = $passwordModalId ?? 'passwordChangeModal';
$passwordModalTitle = $passwordModalTitle ?? 'Change Password';
$passwordModalDescription = $passwordModalDescription ?? '';
$passwordModalSubmitLabel = $passwordModalSubmitLabel ?? 'Done';
?>

<div class="password-change-launch">
    <button type="button" class="btn btn-outline btn-password-change" onclick="openPasswordModal('<?= esc($passwordModalId) ?>')">
        <i class="fas fa-key"></i>
        <span>Change Password</span>
    </button>
</div>

<div
    id="<?= esc($passwordModalId) ?>"
    class="password-modal-backdrop"
    data-open="<?= !empty($errors['password']) ? '1' : '0' ?>"
    aria-hidden="true"
    style="display:none;"
>
    <div class="password-modal-card" role="dialog" aria-modal="true" aria-labelledby="<?= esc($passwordModalId) ?>Title">
        <div class="password-modal-header">
            <div>
                <h3 id="<?= esc($passwordModalId) ?>Title"><?= esc($passwordModalTitle) ?></h3>
                <?php if (!empty($passwordModalDescription)): ?>
                    <p><?= esc($passwordModalDescription) ?></p>
                <?php endif; ?>
            </div>
            <button type="button" class="password-modal-close" onclick="closePasswordModal('<?= esc($passwordModalId) ?>')" aria-label="Close password dialog">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="password-modal-body">
            <?php if (!empty($errors['password'])): ?>
                <div class="alert alert-danger"><?= esc($errors['password']) ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="current_password">Current Password *</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password" minlength="8" placeholder="Enter your current password">
            </div>

            <div class="form-group">
                <label for="new_password">New Password *</label>
                <input type="password" id="new_password" name="new_password" autocomplete="new-password" minlength="8" placeholder="Enter a new password">
                <div id="new-password-strength" class="password-strength-shell"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" minlength="8" placeholder="Confirm the new password">
            </div>

        </div>

        <div class="password-modal-footer">
            <button type="button" class="btn btn-outline" onclick="closePasswordModal('<?= esc($passwordModalId) ?>')">
                <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i>
                <span><?= esc($passwordModalSubmitLabel) ?></span>
            </button>
        </div>
    </div>
</div>

<script src="<?= ROOT ?>/assets/js/password-validation.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.passwordValidator && document.getElementById('new_password')) {
            window.passwordValidator.initPasswordValidation('new_password', 'confirm_password', 'new-password-strength');
        }
    });
</script>