<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile - GradBridge</title>
    <meta name="description" content="Edit Alumni Profile for GradBridge - Update your profile information." />
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
    /* Alumni Profile Edit Specific Overrides */
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
    
    /* Form Section */
    .edit-form-section {
      background: white;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group.full-width {
      grid-column: 1 / -1;
    }
    
    .form-label {
      display: block;
      font-weight: 600;
      color: #374151;
      margin-bottom: 8px;
      font-size: 14px;
    }
    
    .form-input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #D1D5DB;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .form-input:focus {
      outline: none;
      border-color: #0E2072;
      box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1);
    }
    
    .form-textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #D1D5DB;
      border-radius: 8px;
      font-size: 16px;
      resize: vertical;
      min-height: 100px;
      transition: all 0.3s ease;
    }
    
    .form-textarea:focus {
      outline: none;
      border-color: #0E2072;
      box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1);
    }
    
    .error-message {
      color: #DC2626;
      font-size: 14px;
      margin-top: 4px;
    }
    
    .success-message {
      color: #059669;
      font-size: 14px;
      margin-top: 4px;
    }
    
    .form-actions {
      display: flex;
      gap: 16px;
      justify-content: flex-end;
      margin-top: 24px;
      padding-top: 24px;
      border-top: 1px solid #E5E7EB;
    }
    
    .profile-picture-section {
      text-align: center;
      margin-bottom: 24px;
    }
    
    .profile-picture-preview {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: linear-gradient(135deg, #0E2072, #1e40af);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      font-weight: bold;
      color: white;
      margin: 0 auto 16px;
      border: 4px solid white;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .profile-picture-preview img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .profile-picture-upload {
      display: inline-block;
      background-color: #000000;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .profile-picture-upload:hover {
      background-color: #333333;
    }
    
    .profile-picture-upload input[type="file"] {
      display: none;
    }
    
    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .form-actions {
        flex-direction: column;
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
            <h1 class="welcome-text">Edit Profile</h1>
            <p class="alumni-role">Update your profile information</p>
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
        <!-- Edit Profile Form -->
        <section class="edit-form-section">
          <h2 class="section-title">Edit Profile Information</h2>
          
          <form method="POST" enctype="multipart/form-data">
            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
              <div class="profile-picture-preview">
                <?php if (!empty($profile->profile_photo_url)): ?>
                  <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Picture" id="profilePreview">
                <?php else: ?>
                  <span id="profileInitials"><?= strtoupper(substr($profile->name, 0, 2)) ?></span>
                <?php endif; ?>
              </div>
              <label for="profile_picture" class="profile-picture-upload">
                <i class="fas fa-camera"></i>
                Change Photo
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(this)">
              </label>
              <?php if (isset($errors['profile_picture'])): ?>
                <div class="error-message"><?= esc($errors['profile_picture']) ?></div>
              <?php endif; ?>
            </div>

            <div class="form-grid">
              <!-- Name -->
              <div class="form-group">
                <label for="name" class="form-label">Full Name *</label>
                <input type="text" id="name" name="name" class="form-input" value="<?= esc($profile->name) ?>" required>
                <?php if (isset($errors['name'])): ?>
                  <div class="error-message"><?= esc($errors['name']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Email -->
              <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-input" value="<?= esc($profile->email) ?>" required>
                <?php if (isset($errors['email'])): ?>
                  <div class="error-message"><?= esc($errors['email']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Degree -->
              <div class="form-group">
                <label for="degree" class="form-label">Degree *</label>
                <input type="text" id="degree" name="degree" class="form-input" value="<?= esc($profile->degree) ?>" required>
                <?php if (isset($errors['degree'])): ?>
                  <div class="error-message"><?= esc($errors['degree']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Graduation Year -->
              <div class="form-group">
                <label for="graduation_year" class="form-label">Graduation Year *</label>
                <input type="number" id="graduation_year" name="graduation_year" class="form-input" value="<?= esc($profile->graduation_year) ?>" min="1950" max="<?= date('Y') ?>" required>
                <?php if (isset($errors['graduation_year'])): ?>
                  <div class="error-message"><?= esc($errors['graduation_year']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Current Job -->
              <div class="form-group">
                <label for="current_job" class="form-label">Current Job</label>
                <input type="text" id="current_job" name="current_job" class="form-input" value="<?= esc($profile->current_job ?? '') ?>">
                <?php if (isset($errors['current_job'])): ?>
                  <div class="error-message"><?= esc($errors['current_job']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Location -->
              <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-input" value="<?= esc($profile->location ?? '') ?>">
                <?php if (isset($errors['location'])): ?>
                  <div class="error-message"><?= esc($errors['location']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Bio -->
              <div class="form-group full-width">
                <label for="bio" class="form-label">Bio</label>
                <textarea id="bio" name="bio" class="form-textarea" placeholder="Tell us about yourself..."><?= esc($profile->bio ?? '') ?></textarea>
                <?php if (isset($errors['bio'])): ?>
                  <div class="error-message"><?= esc($errors['bio']) ?></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($errors['success'])): ?>
              <div class="success-message"><?= esc($errors['success']) ?></div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
              <div class="error-message"><?= esc($errors['general']) ?></div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div class="form-actions">
              <a href="<?= ROOT ?>/alumni/profile" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Cancel
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Save Changes
              </button>
            </div>
          </form>
        </section>
      </main>
    </div>

    <script>
    function previewImage(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('profilePreview');
          const initials = document.getElementById('profileInitials');
          
          if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          }
          if (initials) {
            initials.style.display = 'none';
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    </script>
  </body>
</html>
