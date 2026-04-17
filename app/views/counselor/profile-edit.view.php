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
<link rel="stylesheet" href="<?=ROOT?>/assets/css/counselor.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section counselor-edit-page">
      <div class="edit-form-section">
        <div class="counselor-edit-top">
          <h2 class="section-title">Edit Profile Information</h2>
          <p class="counselor-edit-subtitle">Update your basic details and password securely.</p>
        </div>

        <?php if (!empty($flashMessage) && is_array($flashMessage)): ?>
          <div class="alert <?= (($flashMessage['type'] ?? '') === 'success' ? 'alert-success' : 'alert-danger') ?>">
            <?= esc($flashMessage['text'] ?? '') ?>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" data-password-modal="true">
          <div class="profile-picture-section counselor-edit-picture">
            <div class="profile-picture-preview counselor-avatar-preview">
              <?php if (!empty($user->profile_photo_url)): ?>
                <img src="<?= esc($user->profile_photo_url) ?>" alt="Profile Picture" id="profilePreview">
              <?php else: ?>
                <span id="profileInitials" class="counselor-avatar-initial"><?= strtoupper(substr((string)($user->name ?? 'C'), 0, 2)) ?></span>
              <?php endif; ?>
            </div>

            <div class="counselor-picture-actions">
              <label for="profile_photo" class="btn btn-primary cursor-pointer">
                <i class="fas fa-camera"></i>
                <span>Change Photo</span>
              </label>
              <input
                type="file"
                id="profile_photo"
                name="profile_photo"
                accept="image/*"
                onchange="previewImage(this)"
                class="input-hidden"
              >
            </div>
            <small class="form-help">Supported formats: JPG, PNG, GIF (Max 5MB)</small>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label for="name" class="form-label">Full Name *</label>
              <input
                type="text"
                id="name"
                name="name"
                class="form-input"
                required
                placeholder="Enter your full name"
                value="<?= htmlspecialchars($user->name ?? '') ?>"
              >
            </div>

            <div class="form-group">
              <label for="email" class="form-label">Email Address *</label>
              <input
                type="email"
                id="email"
                name="email"
                class="form-input"
                required
                placeholder="Enter your email"
                value="<?= htmlspecialchars($user->email ?? '') ?>"
              >
            </div>
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
<script>
  function previewImage(input) {
    const file = input.files && input.files[0];
    const preview = document.getElementById('profilePreview');
    const initials = document.getElementById('profileInitials');

    if (!file) {
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      if (preview) {
        preview.src = e.target.result;
      } else {
        const previewContainer = document.querySelector('.counselor-avatar-preview');
        if (!previewContainer) {
          return;
        }

        previewContainer.innerHTML = '';
        const img = document.createElement('img');
        img.id = 'profilePreview';
        img.alt = 'Profile Picture';
        img.src = e.target.result;
        previewContainer.appendChild(img);
      }

      if (initials) {
        initials.style.display = 'none';
      }
    };
    reader.readAsDataURL(file);
  }
</script>
</body>
</html>
