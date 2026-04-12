<?php
$page_title = "Edit Profile";
$page_subtitle = "Update your account details";
require '../app/views/partials/counselor_header.php';

$user = $user ?? (object)[
    'name' => 'Counselor',
    'email' => 'N/A',
];
$flashMessage = $flashMessage ?? null;
?>

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

        <form method="POST" enctype="multipart/form-data">
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

          <div style="border-top: 1px dashed var(--border, #e5e7eb); margin: 24px 0; padding-top: 24px;">
            <h3 style="margin: 0 0 16px 0; font-size: 1rem; font-weight: 600;">Change Password (Optional)</h3>

            <div class="form-group">
              <label for="password_current">Current Password</label>
              <input
                type="password"
                id="password_current"
                name="password_current"
                placeholder="Enter current password to confirm identity"
              >
              <small>Required only when changing password</small>
            </div>

            <div class="form-group">
              <label for="password_new">New Password</label>
              <input
                type="password"
                id="password_new"
                name="password_new"
                placeholder="Leave blank to keep current password"
              >
              <small>At least 6 characters</small>
            </div>

            <div class="form-group">
              <label for="password_confirm">Confirm Password</label>
              <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                placeholder="Confirm new password"
              >
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              <span>Save Changes</span>
            </button>
            <a href="<?=ROOT?>/counselor/profile" class="btn btn-outline">
              <i class="fas fa-times"></i>
              <span>Cancel</span>
            </a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
