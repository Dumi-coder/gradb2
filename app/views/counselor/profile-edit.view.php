<?php
$page_title = "Edit Profile";
$page_subtitle = "Update your account details";
require '../app/views/partials/counselor_header.php';

$user = $user ?? (object)[
    'name' => 'Counsellor',
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
                <p class="counselor-password-modal-subtitle">Use a strong password to keep your counselor account secure.</p>

                <div class="form-group counselor-password-group">
                  <label class="form-label" for="counselor_password_current">Current Password</label>
                  <div class="counselor-password-input-wrap">
                    <input
                      type="password"
                      id="counselor_password_current"
                      name="password_current"
                      class="form-input counselor-password-input"
                      placeholder="Enter current password"
                      autocomplete="current-password"
                    >
                    <button type="button" class="counselor-password-toggle" data-password-toggle data-target="counselor_password_current" aria-label="Show current password">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <small class="form-help">Enter your existing password to authorize this change.</small>
                </div>

                <div class="form-group counselor-password-group">
                  <label class="form-label" for="counselor_password_new">New Password</label>
                  <div class="counselor-password-input-wrap">
                    <input
                      type="password"
                      id="counselor_password_new"
                      name="password_new"
                      class="form-input counselor-password-input"
                      placeholder="Enter new password"
                      autocomplete="new-password"
                    >
                    <button type="button" class="counselor-password-toggle" data-password-toggle data-target="counselor_password_new" aria-label="Show new password">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <div class="js-password-strength-container" id="counselor-password-strength-widget"></div>
                </div>

                <div class="form-group counselor-password-group">
                  <label class="form-label" for="counselor_password_confirm">Confirm New Password</label>
                  <div class="counselor-password-input-wrap">
                    <input
                      type="password"
                      id="counselor_password_confirm"
                      name="password_confirm"
                      class="form-input counselor-password-input"
                      placeholder="Re-enter new password"
                      autocomplete="new-password"
                    >
                    <button type="button" class="counselor-password-toggle" data-password-toggle data-target="counselor_password_confirm" aria-label="Show confirm password">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <small class="form-help counselor-password-match" id="counselorPasswordMatchHint" aria-live="polite"></small>
                </div>

                <div class="counselor-password-tips" aria-hidden="true">
                  <p class="counselor-password-tips-title">Password tips</p>
                  <ul>
                    <li>Use at least 10 characters.</li>
                    <li>Include letters, numbers, and symbols.</li>
                    <li>Avoid using your name or email.</li>
                  </ul>
                </div>

                <div class="change-password-modal-footer">
                  <button type="button" class="btn btn-outline js-close-password-modal">Cancel</button>
                  <button type="button" class="btn btn-primary js-done-password-modal">Apply Password Changes</button>
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

  document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[data-password-modal="true"]');
    if (!form) return;

    const toggles = form.querySelectorAll('[data-password-toggle]');
    toggles.forEach(function (toggleBtn) {
      toggleBtn.addEventListener('click', function () {
        const targetId = toggleBtn.getAttribute('data-target');
        const input = targetId ? document.getElementById(targetId) : null;
        if (!input) return;

        const reveal = input.type === 'password';
        input.type = reveal ? 'text' : 'password';

        const icon = toggleBtn.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-eye', !reveal);
          icon.classList.toggle('fa-eye-slash', reveal);
        }

        toggleBtn.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
      });
    });

    const newPasswordField = document.getElementById('counselor_password_new');
    const confirmPasswordField = document.getElementById('counselor_password_confirm');
    const matchHint = document.getElementById('counselorPasswordMatchHint');

    function updateMatchHint() {
      if (!newPasswordField || !confirmPasswordField || !matchHint) return;

      const newPassword = newPasswordField.value;
      const confirmPassword = confirmPasswordField.value;

      matchHint.classList.remove('is-match', 'is-mismatch');

      if (confirmPassword.length === 0) {
        matchHint.textContent = '';
        return;
      }

      if (newPassword === confirmPassword) {
        matchHint.textContent = 'Passwords match.';
        matchHint.classList.add('is-match');
      } else {
        matchHint.textContent = 'Passwords do not match yet.';
        matchHint.classList.add('is-mismatch');
      }
    }

    if (newPasswordField && confirmPasswordField && matchHint) {
      newPasswordField.addEventListener('input', updateMatchHint);
      confirmPasswordField.addEventListener('input', updateMatchHint);
    }
  });
</script>
</body>
</html>
