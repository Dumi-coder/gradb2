<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard - GradBridge</title>
    <meta name="description" content="Student Dashboard for GradBridge - Manage your mentorship, aid requests, and academic journey." />
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
    
  </head>

  <body>
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <!-- <div class="welcome-section">
            <h1 class="welcome-text">Welcome, <span class="student-name">Sarah Johnson</span></h1>
            <p class="student-role">Computer Science • Year 3</p>
          </div> -->
          <div class="welcome-section">
            <h1 class="welcome-text">Welcome, <span class="student-name"><?= esc($profile->name) ?></span></h1>
            <p class="student-role">Computer Science • Year <?= esc($profile->academic_year)?></p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell" style="font-size: var(--font-md);"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/student/Logout"><button class="btn btn-primary logout-btn">Logout</button></a>

          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">

      <!-- Success message after an update -->
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert-success">Profile updated successfully!</div>
            <?php endif; ?>

        <!-- Student Profile Section -->
         <section class="dashboard-section profile-section">
          <div class="section-header">
            <h2 class="section-title">Student Profile</h2>
            <!-- <button class="btn btn-outline btn-md edit-profile-btn">
              <i class="fas fa-edit"></i>
              <span>Edit Profile</span>
            </button> -->
            <a href="<?= ROOT ?>/student/profile/?action=edit&id=<?= $profile->student_id ?>" class="btn btn-primary">
                    <!-- <i class="fas fa-edit"></i> -->
                    Edit Profile
                  </a>
          </div>
                    
          <div class="profile-card">
            <div class="profile-info">
              <div class="profile-avatar-container">
            
                  <!-- <h2 class="profile-header">Profile Information</h2> -->
                  <div class="profile-avatar" id="profileAvatar">
                      <img src="" alt="Profile Picture" id="profileImage" style="display: none;">
                      <i class="fas fa-user-graduate" id="defaultAvatar"></i>
                    </div>
                    <div class="avatar-upload-overlay">
                        <label for="profilePictureInput" class="avatar-upload-btn" title="Change Profile Picture">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="profilePictureInput" accept="image/*" style="display: none;">
                    </div>    
              </div>
              <div class="profile-details">
             <!-- <div class="card"> -->
              <div class="profile-avatar-container">
                    <div class="profile-avatar" id="profileAvatar">
                        <?php if (!empty($profile->profile_photo_url)): ?>
                        <img src="<?= esc($profile->profile_photo_url) ?>" 
                            alt="Profile Picture" 
                            id="profileImage" 
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                          <?php else: ?>
                                <i class="fas fa-user-graduate" id="defaultAvatar" style="font-size: var(--font-4xl); color: #ccc;"></i>
                          <?php endif; ?>
                    </div>
    
               </div>
                     

                <h3 class="profile-name"><?= esc($profile->name) ?></h3>
                <p class="profile-id"><strong>Student ID:</strong> <?= esc($profile->student_id) ?></p>
                <p class="profile-faculty"><strong>Faculty:</strong> <?= esc($profile->faculty) ?></p>
                <p class="profile-year"><strong>Academic Year:</strong> <?= esc($profile->academic_year) ?></p>
                <p class="profile-email"><strong>Email:</strong> <?= esc($profile->email) ?></p>
                <p class="profile-bio"><strong>NO:</strong> <?= esc($profile->mobile) ?></p>
                <p class="profile-bio"><strong>Bio:</strong> <?= esc($profile->bio) ?></p>

                <!-- <a href="<?=ROOT?>/student/profile/edit" class="btn btn-primary">Edit Profile</a> -->
                
                <div class="profile-actions">
                  <a href="<?= ROOT ?>/student/profile/?action=edit&id=<?= $profile->student_id ?>" class="btn btn-primary">
                    <!-- <i class="fas fa-edit"></i> -->
                    Edit Profile
                  </a>
                  <a href="<?= ROOT ?>/student/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                  </a>
                </div>
            </div>
          </div>

          </div>
        

        </section>
    
      </main>
    </div>

    <!-- <script src="<?=ROOT?>/assets/js/main.js"></script> -->
    <!-- <script src="<?=ROOT?>/assets/js/edit-profile.js"></script> -->
    <!-- <script src="<?=ROOT?>/assets/js/dashboard.js"></script> -->
    <!-- <script> -->
      <!-- // Initialize Edit Profile Component only when needed
      // document.addEventListener('DOMContentLoaded', function() {
        // Don't auto-initialize - only create when edit profile button is clicked
      // }); -->
    <!-- // </script> -->
  </body>
</html>