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

<style>
  .profile-wrap {
    max-width: 980px;
    margin: 0 auto;
    padding: 16px;
  }

  .profile-card {
    background: var(--card, #fff);
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 12px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    min-height: 560px;
  }

  .profile-heading {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--foreground);
  }

  .profile-heading-sub {
    margin: 6px 0 16px 0;
    color: var(--muted-foreground, #64748b);
    font-size: .92rem;
  }

  .profile-top {
    display: flex;
    gap: 16px;
    align-items: center;
    justify-content: flex-start;
    padding: 16px;
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 10px;
    background: var(--background, #fafafa);
    margin-bottom: 18px;
  }

  .profile-top-left {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
  }

  .avatar {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background: var(--secondary, #f1f5f9);
    border: 1px solid var(--border, #e5e7eb);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    color: var(--primary, #2563eb);
    font-size: 1.4rem;
    font-weight: 700;
  }

  .avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .name {
    margin: 0;
    font-size: 1.25rem;
    color: var(--foreground);
  }

  .subtext {
    margin-top: 6px;
    margin-bottom: 0;
    color: var(--muted-foreground, #64748b);
    font-size: 0.9rem;
  }

  .role-chip {
    margin-top: 8px;
    display: inline-block;
    font-size: .76rem;
    font-weight: 600;
    border-radius: 999px;
    padding: .28rem .62rem;
    background: var(--secondary, #f1f5f9);
    color: var(--muted-foreground, #64748b);
  }

  .profile-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
  }

  .info-item {
    border: 1px solid var(--border, #e5e7eb);
    border-radius: 10px;
    padding: 14px;
    background: var(--background, #fff);
    min-height: 96px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .info-label {
    margin: 0 0 6px 0;
    font-size: .75rem;
    font-weight: 600;
    color: var(--muted-foreground, #64748b);
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .info-value {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.45;
    word-break: break-word;
    color: var(--foreground);
  }

  .profile-actions {
    margin-top: auto;
    padding-top: 16px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    border-top: 1px solid var(--border, #e5e7eb);
  }

  .profile-actions a {
    text-decoration: none;
  }

  @media (max-width: 768px) {
    .profile-card {
      padding: 18px;
      min-height: auto;
    }

    .profile-grid {
      grid-template-columns: 1fr;
    }

    .profile-top {
      align-items: flex-start;
      flex-direction: column;
    }

    .profile-actions {
      justify-content: stretch;
      flex-direction: column;
    }
    .profile-actions a {
      width: 100%;
      justify-content: center;
    }
  }
</style>

<div class="dashboard-container">
  <?php require '../app/views/partials/counselor_sidebar.php'; ?>

  <main class="main-content">
    <section class="dashboard-section profile-wrap">
      <div class="profile-card">
        <h2 class="profile-heading">Profile Information</h2>
        <p class="profile-heading-sub"></p>

        <?php if (!empty($flashMessage) && is_array($flashMessage)): ?>
          <div style="padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 0.95rem; background: <?= ($flashMessage['type'] === 'success' ? '#d1fae5' : '#fee2e2') ?>; color: <?= ($flashMessage['type'] === 'success' ? '#065f46' : '#991b1b') ?>; border: 1px solid <?= ($flashMessage['type'] === 'success' ? '#a7f3d0' : '#fecaca') ?>;">
            <?= htmlspecialchars($flashMessage['text'] ?? '') ?>
          </div>
        <?php endif; ?>
        
        <div class="profile-top">
          <div class="profile-top-left">
            <div class="avatar">
              <?php if (!empty($profile->profile_photo_url)): ?>
                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Photo">
              <?php else: ?>
                <?= strtoupper(substr((string)($profile->name ?? 'C'), 0, 1)) ?>
              <?php endif; ?>
            </div>

            <div>
              <h2 class="name"><?= esc($profile->name ?? 'Counselor') ?></h2>
              <p class="subtext">Counselor account</p>
              <span class="role-chip"><?= esc(ucfirst((string)($profile->role ?? 'counselor'))) ?></span>
            </div>
          </div>
        </div>

        <div class="profile-grid">
          <div class="info-item">
            <p class="info-label">Full Name</p>
            <p class="info-value"><?= esc($profile->name ?? 'N/A') ?></p>
          </div>

          <div class="info-item">
            <p class="info-label">Email</p>
            <p class="info-value"><?= esc($profile->email ?? 'N/A') ?></p>
          </div>

          <div class="info-item">
            <p class="info-label">Password</p>
            <p class="info-value"><?= esc($profile->password_display ?? 'Not set') ?></p>
          </div>

          <div class="info-item">
            <p class="info-label">Role</p>
            <p class="info-value"><?= esc(ucfirst((string)($profile->role ?? 'counselor'))) ?></p>
          </div>
        </div>

        <div class="profile-actions">
          <a id="editProfileBtn" href="<?=ROOT?>/counselor/profile-edit" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            <span>Edit Profile</span>
          </a>
          <!-- <a id="backDashboardBtn" href="<?=ROOT?>/counselor/dashboard" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
          </a> -->
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
