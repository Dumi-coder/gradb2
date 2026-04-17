<?php
$page_title = "Edit Profile";
$page_subtitle = "Update your account details";
require '../app/views/partials/counselor_header.php';

$user = $user ?? (object)[
    'name' => 'Counselor',
    'email' => 'N/A',
];
$flashMessage = $flashMessage ?? null;
$hasPasswordErrors = is_array($flashMessage) && !empty($flashMessage['text']) && stripos((string)$flashMessage['text'], 'password') !== false;
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<style>
  .edit-wrap {
    max-width: 680px;
    margin: 0 auto;
  }
  .edit-card {
    background: var(--card, #fff);
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 14px;
    padding: 24px;
    font-family: inherit;
  }
  .form-group {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .form-group label {
    font-weight: 600;
    font-size: 0.92rem;
    margin: 0;
  }
  .form-group input {
    width: 100%;
    border: 1px solid var(--border, #d1d5db);
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 0.95rem;
    font-family: inherit;
    background: #fff;
  }
  .form-group input::placeholder {
    font-family: inherit;
  }
  .edit-card .btn,
  .edit-card label,
  .edit-card small,
  .edit-card h2,
  .edit-card h3,
  .edit-card span,
  .edit-card p {
    font-family: inherit;
  }
  .form-group input:focus {
    outline: none;
    border-color: var(--primary, #2563eb);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }
  .form-group small {
    color: var(--muted-foreground, #6b7280);
    font-size: 0.8rem;
  }
  .required-mark {
    color: #dc2626;
  }
  .form-actions {
    margin-top: 24px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .form-actions .btn {
    padding: 8px 12px;
    font-size: 0.88rem;
    line-height: 1.2;
    min-width: 0;
  }
  .form-actions .btn.btn-outline {
    padding-left: 9px;
    padding-right: 9px;
  }
  .alert {
    padding: 12px 14px;
    border-radius: 10px;
    margin-bottom: 16px;
    font-size: 0.95rem;
  }
  .alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }
  .alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
  }
  @media (max-width: 768px) {
    .edit-card {
      padding: 18px;
    }
    .form-actions {
      flex-direction: column;
    }
    .form-actions .btn {
      width: 100%;
    }
  }
</style>

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section edit-wrap">
      <div class="edit-card">
        <h2 style="margin: 0 0 18px 0; font-size: 1.3rem;">Edit Profile</h2>

        <?php if (!empty($flashMessage) && is_array($flashMessage)): ?>
          <div class="alert alert-<?= esc($flashMessage['type'] ?? 'info') ?>">
            <?= esc($flashMessage['text'] ?? '') ?>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" data-password-modal="true">
          <div class="form-group">
            <label for="profile_photo">Profile Photo</label>
            <input
              type="file"
              id="profile_photo"
              name="profile_photo"
              accept="image/*"
              placeholder="Choose profile photo"
            >
            <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
          </div>

          <div class="form-group">
            <label for="name">Full Name <span class="required-mark">*</span></label>
            <input
              type="text"
              id="name"
              name="name"
              required
              placeholder="Enter your full name"
              value="<?= htmlspecialchars($user->name ?? '') ?>"
            >
          </div>

          <div class="form-group">
            <label for="email">Email Address <span class="required-mark">*</span></label>
            <input
              type="email"
              id="email"
              name="email"
              required
              placeholder="Enter your email"
              value="<?= htmlspecialchars($user->email ?? '') ?>"
            >
          </div>

          <input type="hidden" name="change_password" value="0">

          <div class="form-actions">
            <button type="button" class="btn btn-outline js-open-password-modal" aria-expanded="false">
              <i class="fas fa-key"></i>
              <span>Change Password</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              <span>Save Changes</span>
            </button>
            <a href="<?=ROOT?>/counselor/profile" class="btn btn-outline">
              <i class="fas fa-times"></i>
              <span>Cancel</span>
            </a>
          </div>

          <div class="change-password-modal" aria-hidden="true" data-has-password-errors="<?= $hasPasswordErrors ? '1' : '0' ?>">
            <div class="change-password-modal-content" role="dialog" aria-modal="true" aria-labelledby="counselorChangePasswordTitle">
              <div class="change-password-modal-header">
                <h3 class="change-password-modal-title" id="counselorChangePasswordTitle">Change Password</h3>
                <button type="button" class="btn btn-outline js-close-password-modal">Close</button>
              </div>
              <div class="change-password-modal-body">
                <div class="form-group">
                  <label for="counselor_password_current">Current Password</label>
                  <input
                    type="password"
                    id="counselor_password_current"
                    name="password_current"
                    placeholder="Enter current password"
                    autocomplete="current-password"
                  >
                </div>

                <div class="form-group">
                  <label for="counselor_password_new">New Password</label>
                  <input
                    type="password"
                    id="counselor_password_new"
                    name="password_new"
                    placeholder="Enter new password"
                    autocomplete="new-password"
                  >
                  <div class="js-password-strength-container" id="counselor-password-strength-widget"></div>
                </div>

                <div class="form-group">
                  <label for="counselor_password_confirm">Confirm New Password</label>
                  <input
                    type="password"
                    id="counselor_password_confirm"
                    name="password_confirm"
                    placeholder="Confirm new password"
                    autocomplete="new-password"
                  >
                </div>

                <div class="change-password-modal-footer">
                  <button type="button" class="btn btn-outline js-done-password-modal">Done</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

<script src="<?=ROOT?>/assets/js/password-validation.js"></script>
<script src="<?=ROOT?>/assets/js/profile-password-modal.js"></script>
<script src="<?=ROOT?>/assets/js/profile.js"></script>
<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
