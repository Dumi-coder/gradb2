<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile - GradBridge</title>
    <meta name="description" content="View and manage your alumni profile information" />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Base styles -->
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/edit-profile.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
</head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">My Profile</h1>
            <p class="alumni-role">Manage your profile information and settings</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/alumni/logout"><button class="btn btn-primary logout-btn">
              <i class="fas fa-sign-out-alt"></i>
              Logout
            </button></a>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
      <!-- sidebar -->
      <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Profile Header Section -->
        <section class="profile-header-section">
          <div class="profile-header-content">
            <div class="profile-avatar-large">
              <?= strtoupper(substr($alumni['first_name'], 0, 1) . substr($alumni['last_name'], 0, 1)) ?>
            </div>
            <div class="profile-info">
              <h1 class="profile-name-large"><?= esc($alumni['first_name'] . ' ' . $alumni['last_name']) ?></h1>
              <p class="profile-role">Alumni</p>
              <p class="profile-faculty"><?= esc($alumni['faculty']) ?> • Class of <?= esc($alumni['graduation_year']) ?></p>
            </div>
            <div class="profile-actions">
              <a href="<?=ROOT?>/Alumni/Profile/edit" class="edit-profile-btn">
                <i class="fas fa-edit"></i>
                Edit Profile
              </a>
            </div>
          </div>
        </section>

        <!-- Profile Information Section -->
        <section class="profile-content-section">
          <h2 class="section-title">
            <i class="fas fa-user"></i>
            Profile Information
          </h2>
          
          <div class="profile-info-grid">
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="info-content">
                <p class="info-label">Email Address</p>
                <p class="info-value"><?= esc($alumni['email']) ?></p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-phone"></i>
              </div>
              <div class="info-content">
                <p class="info-label">Phone Number</p>
                <p class="info-value"><?= esc($alumni['phone'] ?? 'Not provided') ?></p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="info-content">
                <p class="info-label">Faculty</p>
                <p class="info-value"><?= esc($alumni['faculty']) ?></p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-calendar"></i>
              </div>
              <div class="info-content">
                <p class="info-label">Graduation Year</p>
                <p class="info-value"><?= esc($alumni['graduation_year']) ?></p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-briefcase"></i>
              </div>
              <div class="info-content">
                <p class="info-label">Current Position</p>
                <p class="info-value"><?= esc($alumni['current_position'] ?? 'Not specified') ?></p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-building"></i>
              </div>
              <div class="info-content">
                <p class="info-label">Company</p>
                <p class="info-value"><?= esc($alumni['company'] ?? 'Not specified') ?></p>
              </div>
            </div>
          </div>
        </section>

        <!-- Contact & Social Links Section -->
        <section class="contact-social-section">
          <h2 class="section-title">
            <i class="fas fa-link"></i>
            Contact & Social Links
          </h2>
          
          <div class="social-links-grid">
            <a href="mailto:<?= esc($alumni['email']) ?>" class="social-link">
              <div class="social-link-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="social-link-content">
                <p class="social-link-text">Email</p>
                <p class="social-link-url"><?= esc($alumni['email']) ?></p>
              </div>
            </a>
            
            <?php if (!empty($alumni['linkedin'])): ?>
            <a href="<?= esc($alumni['linkedin']) ?>" class="social-link" target="_blank">
              <div class="social-link-icon">
                <i class="fab fa-linkedin"></i>
              </div>
              <div class="social-link-content">
                <p class="social-link-text">LinkedIn</p>
                <p class="social-link-url"><?= esc($alumni['linkedin']) ?></p>
              </div>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($alumni['twitter'])): ?>
            <a href="<?= esc($alumni['twitter']) ?>" class="social-link" target="_blank">
              <div class="social-link-icon">
                <i class="fab fa-twitter"></i>
              </div>
              <div class="social-link-content">
                <p class="social-link-text">Twitter</p>
                <p class="social-link-url"><?= esc($alumni['twitter']) ?></p>
              </div>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($alumni['website'])): ?>
            <a href="<?= esc($alumni['website']) ?>" class="social-link" target="_blank">
              <div class="social-link-icon">
                <i class="fas fa-globe"></i>
              </div>
              <div class="social-link-content">
                <p class="social-link-text">Website</p>
                <p class="social-link-url"><?= esc($alumni['website']) ?></p>
              </div>
            </a>
            <?php endif; ?>
          </div>
        </section>
      </main>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>