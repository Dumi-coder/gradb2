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

        <form method="POST" enctype="multipart/form-data">
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

          <div class="counselor-password-block">
            <h3 class="counselor-password-title">Change Password (Optional)</h3>

            <div class="form-group">
              <label for="password_current" class="form-label">Current Password</label>
              <input
                type="password"
                id="password_current"
                name="password_current"
                class="form-input"
                placeholder="Enter current password to confirm identity"
              >
              <small class="form-help">Required only when changing password</small>
            </div>

            <div class="form-group">
              <label for="password_new" class="form-label">New Password</label>
              <input
                type="password"
                id="password_new"
                name="password_new"
                class="form-input"
                placeholder="Leave blank to keep current password"
              >
              <small class="form-help">At least 6 characters</small>
            </div>

            <div class="form-group">
              <label for="password_confirm" class="form-label">Confirm Password</label>
              <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                class="form-input"
                placeholder="Confirm new password"
              >
            </div>
          </div>

          <div class="form-actions">
            <a href="<?=ROOT?>/counselor/profile" class="btn btn-outline">
              <i class="fas fa-arrow-left"></i>
              <span>Back</span>
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              <span>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

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
