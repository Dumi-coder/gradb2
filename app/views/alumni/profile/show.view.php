<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alumni Profile - GradBridge</title>
    <meta name="description" content="Alumni Profile for GradBridge - View and manage your profile information." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/edit-profile.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    
    <style>
    /* Alumni Profile Specific Overrides */
    .btn-primary {
      background-color: #000000 !important;
      color: white !important;
      border: none !important;
    }
    
    .btn-primary:hover {
      background-color: #333333 !important;
    }
    
    .btn-outline:hover {
      background-color: #000000 !important;
      color: white !important;
      border-color: #000000 !important;
    }
    
    /* Profile Header Section */
    .profile-header-section {
      background: linear-gradient(135deg, #0E2072, #1e40af);
      color: white;
      padding: 40px 0;
      margin-bottom: 30px;
      border-radius: 12px;
      position: relative;
    }
    
    .profile-header-content {
      display: flex;
      align-items: center;
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .profile-avatar-large {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      font-weight: bold;
      color: white;
      border: 4px solid white;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    
    .profile-avatar-large img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .profile-header-info {
      flex: 1;
    }
    
    .profile-name-large {
      font-size: 36px;
      font-weight: 700;
      margin: 0 0 8px 0;
      color: white;
    }
    
    .profile-title {
      font-size: 20px;
      color: #93c5fd;
      margin: 0 0 8px 0;
      font-weight: 500;
    }
    
    .profile-batch {
      font-size: 16px;
      color: #bfdbfe;
      margin: 0;
    }
    
    .edit-profile-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background-color: #000000;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .edit-profile-btn:hover {
      background-color: #333333;
      transform: translateY(-2px);
    }
    
    /* Profile Information Section */
    .profile-info-section {
      background: white;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
    }
    
    .profile-info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    
    .profile-info-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    
    .profile-info-label {
      font-size: 12px;
      font-weight: 600;
      color: #6B7280;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    
    .profile-info-value {
      font-size: 16px;
      font-weight: 500;
      color: #1F2937;
    }
    
    /* Bio Section */
    .bio-section {
      background: white;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
    }
    
    .bio-text {
      color: #4B5563;
      line-height: 1.6;
      margin: 0;
    }
    
    /* Achievements Section */
    .achievements-section {
      background: white;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
    }
    
    .achievements-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 16px;
      margin-top: 16px;
    }
    
    .achievement-badge {
      background: white;
      border: 1px solid #E5E7EB;
      border-radius: 8px;
      padding: 16px;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .achievement-badge:hover {
      border-color: #0E2072;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }
    
    .achievement-icon {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 8px;
      font-size: 20px;
      color: white;
    }
    
    .achievement-icon.mentor {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .achievement-icon.donor {
      background: linear-gradient(135deg, #ec4899, #db2777);
    }
    
    .achievement-icon.active {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .achievement-icon.volunteer {
      background: linear-gradient(135deg, #f97316, #ea580c);
    }
    
    .achievement-icon.leader {
      background: linear-gradient(135deg, #eab308, #ca8a04);
    }
    
    .achievement-icon.innovator {
      background: linear-gradient(135deg, #84cc16, #65a30d);
    }
    
    .achievement-label {
      font-size: 14px;
      font-weight: 600;
      color: #1F2937;
    }
    
    /* Contact & Social Links Section */
    .contact-social-section {
      background: white;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
    }
    
    .social-links-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-top: 16px;
    }
    
    .social-link-card {
      background: white;
      border: 1px solid #E5E7EB;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .social-link-card:hover {
      border-color: #0E2072;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }
    
    .social-link-icon {
      width: 50px;
      height: 50px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      font-size: 24px;
      color: white;
    }
    
    .social-link-icon.linkedin {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .social-link-icon.github {
      background: linear-gradient(135deg, #ec4899, #db2777);
    }
    
    .social-link-icon.twitter {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .social-link-icon.website {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }
    
    .social-link-label {
      font-size: 14px;
      font-weight: 600;
      color: #1F2937;
    }
    
    @media (max-width: 768px) {
      .profile-header-content {
        flex-direction: column;
        text-align: center;
      }
      
      .edit-profile-btn {
        position: static;
        margin-top: 20px;
        align-self: center;
      }
      
      .profile-info-grid {
        grid-template-columns: 1fr;
      }
      
      .achievements-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .social-links-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    </style>
</head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Welcome, <span class="alumni-name"><?= esc($profile->name) ?></span></h1>
            <p class="alumni-role"><?= esc($profile->degree) ?> • Batch <?= esc($profile->graduation_year) ?></p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell" style="font-size: 1.1rem;"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/alumni/logout"><button class="btn btn-primary logout-btn">Logout</button></a>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
      <!-- sidebar -->
      <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Success message after an update -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-success">Profile updated successfully!</div>
        <?php endif; ?>

        <!-- Profile Header Section -->
        <section class="profile-header-section">
          <div class="profile-header-content">
            <div class="profile-avatar-large">
              <?php if (!empty($profile->profile_photo_url)): ?>
                <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Picture">
              <?php else: ?>
                <?= strtoupper(substr($profile->name, 0, 2)) ?>
              <?php endif; ?>
            </div>
            <div class="profile-header-info">
              <h1 class="profile-name-large"><?= esc($profile->name) ?></h1>
              <p class="profile-title"><?= esc($profile->current_job ?? 'Software Engineer') ?></p>
              <p class="profile-batch">Batch <?= esc($profile->graduation_year) ?></p>
            </div>
          </div>
          <a href="<?= ROOT ?>/alumni/profile/?action=edit&id=<?= $profile->alumni_id ?>" class="edit-profile-btn">
            <i class="fas fa-edit"></i>
            Edit Profile
          </a>
        </section>

        <!-- Personal Information Section -->
        <section class="profile-info-section">
          <h2 class="section-title">Personal Information</h2>
          <div class="profile-info-grid">
            <div class="profile-info-item">
              <span class="profile-info-label">Full Name</span>
              <span class="profile-info-value"><?= esc($profile->name) ?></span>
            </div>
            <div class="profile-info-item">
              <span class="profile-info-label">Degree</span>
              <span class="profile-info-value"><?= esc($profile->degree) ?></span>
            </div>
            <div class="profile-info-item">
              <span class="profile-info-label">Location</span>
              <span class="profile-info-value"><?= esc($profile->location ?? 'San Francisco, CA') ?></span>
            </div>
            <div class="profile-info-item">
              <span class="profile-info-label">Graduation Year</span>
              <span class="profile-info-value"><?= esc($profile->graduation_year) ?></span>
            </div>
            <div class="profile-info-item">
              <span class="profile-info-label">Current Job</span>
              <span class="profile-info-value"><?= esc($profile->current_job ?? 'Senior Software Engineer') ?></span>
            </div>
            <div class="profile-info-item">
              <span class="profile-info-label">Email</span>
              <span class="profile-info-value"><?= esc($profile->email) ?></span>
            </div>
          </div>
        </section>

        <!-- Bio Section -->
        <section class="bio-section">
          <h2 class="section-title">Bio</h2>
          <p class="bio-text"><?= esc($profile->bio ?? 'Experienced software engineer with expertise in full-stack development. Passionate about mentoring students and contributing to the alumni community.') ?></p>
        </section>

        <!-- Achievements & Badges Section -->
        <section class="achievements-section">
          <h2 class="section-title">Achievements & Badges</h2>
          <div class="achievements-grid">
            <div class="achievement-badge">
              <div class="achievement-icon mentor">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="achievement-label">Mentor</div>
            </div>
            <div class="achievement-badge">
              <div class="achievement-icon donor">
                <i class="fas fa-heart"></i>
              </div>
              <div class="achievement-label">Donor</div>
            </div>
            <div class="achievement-badge">
              <div class="achievement-icon active">
                <i class="fas fa-star"></i>
              </div>
              <div class="achievement-label">Active</div>
            </div>
            <div class="achievement-badge">
              <div class="achievement-icon volunteer">
                <i class="fas fa-shield-alt"></i>
              </div>
              <div class="achievement-label">Volunteer</div>
            </div>
            <div class="achievement-badge">
              <div class="achievement-icon leader">
                <i class="fas fa-crown"></i>
              </div>
              <div class="achievement-label">Leader</div>
            </div>
            <div class="achievement-badge">
              <div class="achievement-icon innovator">
                <i class="fas fa-lightbulb"></i>
              </div>
              <div class="achievement-label">Innovator</div>
            </div>
          </div>
        </section>

        <!-- Contact & Social Links Section -->
        <section class="contact-social-section">
          <h2 class="section-title">Contact & Social Links</h2>
          <div class="social-links-grid">
            <div class="social-link-card">
              <div class="social-link-icon linkedin">
                <i class="fab fa-linkedin"></i>
              </div>
              <div class="social-link-label">LinkedIn</div>
            </div>
            <div class="social-link-card">
              <div class="social-link-icon github">
                <i class="fab fa-github"></i>
              </div>
              <div class="social-link-label">GitHub</div>
            </div>
            <div class="social-link-card">
              <div class="social-link-icon twitter">
                <i class="fab fa-twitter"></i>
              </div>
              <div class="social-link-label">Twitter</div>
            </div>
            <div class="social-link-card">
              <div class="social-link-icon website">
                <i class="fas fa-globe"></i>
              </div>
              <div class="social-link-label">Website</div>
            </div>
          </div>
        </section>
      </main>
    </div>
</body>
</html>