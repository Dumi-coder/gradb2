<?php
$page_title = "My Profile";
$page_subtitle = "Counsellor account details";
require '../app/views/partials/counsellor_header.php';

// Get flash message from session if available
$flashMessage = null;
if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // Clear it after reading
}

$profile = $profile ?? (object)[
    'name' => 'Counsellor',
    'email' => 'N/A',
    'password_display' => 'Not set',
    'role' => 'counsellor',
    'profile_photo_url' => null,
];
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/counsellor.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/counsellor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section counsellor-profile-page">
      <h2 class="section-title">My Profile</h2>

      <div class="counsellor-profile-card">

        <?php if (!empty($flashMessage) && is_array($flashMessage)): ?>
          <div class="alert <?= (($flashMessage['type'] ?? '') === 'success' ? 'alert-success' : 'alert-danger') ?>">
            <?= htmlspecialchars($flashMessage['text'] ?? '') ?>
          </div>
        <?php endif; ?>

        <div class="counsellor-profile-header">
          <div class="counsellor-profile-identity">
            <div class="counsellor-profile-avatar">
              <?php if (!empty($profile->profile_photo_url)): ?>
                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Photo">
              <?php else: ?>
                <?= strtoupper(substr((string)($profile->name ?? 'C'), 0, 1)) ?>
              <?php endif; ?>
            </div>

            <div>
              <h3 class="counsellor-profile-name"><?= esc($profile->name ?? 'Counsellor') ?></h3>
              <p class="counsellor-profile-sub">Counsellor account</p>
              <span class="counsellor-role-chip">
                <i class="fas fa-user-shield"></i>
                <?= esc(ucfirst((string)($profile->role ?? 'counsellor'))) ?>
              </span>
            </div>
          </div>

          <div class="counsellor-profile-meta">
            
          </div>
        </div>

        <div class="counsellor-profile-grid">
          <div class="counsellor-info-card">
            <p class="counsellor-info-label">Full Name</p>
            <p class="counsellor-info-value"><?= esc($profile->name ?? 'N/A') ?></p>
          </div>

          <div class="counsellor-info-card">
            <p class="counsellor-info-label">Email</p>
            <p class="counsellor-info-value"><?= esc($profile->email ?? 'N/A') ?></p>
          </div>

          <div class="counsellor-info-card">
            <p class="counsellor-info-label">Password</p>
            <p class="counsellor-info-value"><?= esc($profile->password_display ?? 'Not set') ?></p>
          </div>

          <div class="counsellor-info-card">
            <p class="counsellor-info-label">Role</p>
            <p class="counsellor-info-value"><?= esc(ucfirst((string)($profile->role ?? 'counsellor'))) ?></p>
          </div>
        </div>

        <div class="counsellor-profile-actions">
          <a id="editProfileBtn" href="<?=ROOT?>/counsellor/profile-edit" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            <span>Edit Profile</span>
          </a>
        </div>
      </div>
    </section>
  </main>
</div>

<script>
  (function () {
    const editBtn = document.getElementById('editProfileBtn');

    if (editBtn) {
      editBtn.addEventListener('click', function (event) {
        event.preventDefault();
        window.location.assign('<?=ROOT?>/counsellor/profile-edit');
      });
    }
  })();
</script>
</body>
</html>
