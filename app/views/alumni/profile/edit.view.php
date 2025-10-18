<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile - GradBridge</title>
    <meta name="description" content="Edit your alumni profile information" />
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
            <h1 class="welcome-text">Edit Profile</h1>
            <p class="alumni-role">Update your profile information and settings</p>
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
        <!-- Edit Form Section -->
        <section class="edit-form-section">
          <h2 class="section-title">
            <i class="fas fa-edit"></i>
            Profile Information
          </h2>
          
          <div class="profile-avatar">
            <?= strtoupper(substr($alumni['first_name'], 0, 1) . substr($alumni['last_name'], 0, 1)) ?>
          </div>
          
          <form method="POST" action="<?=ROOT?>/Alumni/Profile/handleUpdate" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="<?= esc($alumni['first_name']) ?>" required>
              </div>
              
              <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="<?= esc($alumni['last_name']) ?>" required>
              </div>
            </div>
            
            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" value="<?= esc($alumni['email']) ?>" required>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?= esc($alumni['phone'] ?? '') ?>">
              </div>
              
              <div class="form-group">
                <label for="faculty">Faculty *</label>
                <select id="faculty" name="faculty" required>
                  <option value="">Select Faculty</option>
                  <option value="Engineering" <?= $alumni['faculty'] == 'Engineering' ? 'selected' : '' ?>>Engineering</option>
                  <option value="Business" <?= $alumni['faculty'] == 'Business' ? 'selected' : '' ?>>Business</option>
                  <option value="Medicine" <?= $alumni['faculty'] == 'Medicine' ? 'selected' : '' ?>>Medicine</option>
                  <option value="Arts" <?= $alumni['faculty'] == 'Arts' ? 'selected' : '' ?>>Arts</option>
                  <option value="Science" <?= $alumni['faculty'] == 'Science' ? 'selected' : '' ?>>Science</option>
                </select>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="graduation_year">Graduation Year *</label>
                <input type="number" id="graduation_year" name="graduation_year" value="<?= esc($alumni['graduation_year']) ?>" min="1950" max="2030" required>
              </div>
              
              <div class="form-group">
                <label for="current_position">Current Position</label>
                <input type="text" id="current_position" name="current_position" value="<?= esc($alumni['current_position'] ?? '') ?>">
              </div>
            </div>
            
            <div class="form-group">
              <label for="company">Company</label>
              <input type="text" id="company" name="company" value="<?= esc($alumni['company'] ?? '') ?>">
            </div>
            
            <div class="form-group">
              <label for="bio">Bio</label>
              <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."><?= esc($alumni['bio'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
              <label for="linkedin">LinkedIn Profile</label>
              <input type="url" id="linkedin" name="linkedin" value="<?= esc($alumni['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/yourprofile">
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="twitter">Twitter Profile</label>
                <input type="url" id="twitter" name="twitter" value="<?= esc($alumni['twitter'] ?? '') ?>" placeholder="https://twitter.com/yourhandle">
              </div>
              
              <div class="form-group">
                <label for="website">Website</label>
                <input type="url" id="website" name="website" value="<?= esc($alumni['website'] ?? '') ?>" placeholder="https://yourwebsite.com">
              </div>
            </div>
            
            <div class="form-actions">
              <a href="<?=ROOT?>/Alumni/Profile" class="btn btn-outline">
                <i class="fas fa-times"></i>
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

    <script src="<?=ROOT?>/assets/js/main.js"></script>
  </body>
</html>