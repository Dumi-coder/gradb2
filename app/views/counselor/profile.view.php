<?php
$page_title = "My Profile";
$page_subtitle = "Counselor account details";
require '../app/views/partials/counselor_header.php';

// Get flash message from session if available
$flashMessage = null;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // Clear it after reading
}

$profile = $profile ?? (object)[
    'name' => 'Counselor',
    'email' => 'N/A',
    'password_display' => 'Not set',
    'role' => 'counselor',
    'profile_photo_url' => null,
];
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/counselor.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section counselor-profile-page">
      <h2 class="section-title">My Profile</h2>

      <div class="counselor-profile-card">

        <?php if (!empty($flashMessage) && is_array($flashMessage)): ?>
          <div class="alert <?= (($flashMessage['type'] ?? '') === 'success' ? 'alert-success' : 'alert-danger') ?>">
            <?= htmlspecialchars($flashMessage['text'] ?? '') ?>
          </div>
        <?php endif; ?>

        <div class="counselor-profile-header">
          <div class="counselor-profile-identity">
            <div class="counselor-profile-avatar">
              <?php if (!empty($profile->profile_photo_url)): ?>
                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Photo">
              <?php else: ?>
                <?= strtoupper(substr((string)($profile->name ?? 'C'), 0, 1)) ?>
              <?php endif; ?>
            </div>

            <div>
              <h3 class="counselor-profile-name"><?= esc($profile->name ?? 'Counselor') ?></h3>
              <p class="counselor-profile-sub">Counselor account</p>
              <span class="counselor-role-chip">
                <i class="fas fa-user-shield"></i>
                <?= esc(ucfirst((string)($profile->role ?? 'counselor'))) ?>
              </span>
            </div>
          </div>

          <div class="counselor-profile-meta">
            
          </div>
        </div>

        <div class="counselor-profile-grid">
          <div class="counselor-info-card">
            <p class="counselor-info-label">Full Name</p>
            <p class="counselor-info-value"><?= esc($profile->name ?? 'N/A') ?></p>
          </div>

          <div class="counselor-info-card">
            <p class="counselor-info-label">Email</p>
            <p class="counselor-info-value"><?= esc($profile->email ?? 'N/A') ?></p>
          </div>

          <div class="counselor-info-card">
            <p class="counselor-info-label">Password</p>
            <p class="counselor-info-value"><?= esc($profile->password_display ?? 'Not set') ?></p>
          </div>

          <div class="counselor-info-card">
            <p class="counselor-info-label">Role</p>
            <p class="counselor-info-value"><?= esc(ucfirst((string)($profile->role ?? 'counselor'))) ?></p>
          </div>
        </div>

        <div class="counselor-profile-actions">
          <a id="editProfileBtn" href="<?=ROOT?>/counselor/profile-edit" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            <span>Edit Profile</span>
          </a>
        </div>
      </div>
    </section>
  </main>
</div>

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
<script>
  (function () {
    const editBtn = document.getElementById('editProfileBtn');

    if (editBtn) {
      editBtn.addEventListener('click', function (event) {
        event.preventDefault();
        window.location.assign('<?=ROOT?>/counselor/profile-edit');
      });
    }
  })();
</script>
</body>
</html>
