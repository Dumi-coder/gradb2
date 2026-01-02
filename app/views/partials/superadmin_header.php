<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Super Admin Dashboard - GradBridge' ?></title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/buttons.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/badges.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard-header.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin-dashboard.css">
    
    <!-- JavaScript Files -->
    <script src="<?=ROOT?>/assets/js/sidebar-toggle.js"></script>
</head>
<body>
    <script>
        function logout() {
            // Create a form to submit logout request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?=ROOT?>/superadmin/logout';
            form.style.display = 'none';
            
            // Add a hidden input to indicate logout
            const logoutInput = document.createElement('input');
            logoutInput.type = 'hidden';
            logoutInput.name = 'logout';
            logoutInput.value = '1';
            form.appendChild(logoutInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    <!-- Super Admin Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="welcome-section">
                    <?php 
                    // Make variables available in header scope
                    $page_title = $page_title ?? null;
                    $page_subtitle = $page_subtitle ?? null;
                    ?>
                    <?php if (!empty($page_title)): ?>
                        <h1 class="welcome-text"><?= esc($page_title) ?></h1>
                        <?php if (!empty($page_subtitle)): ?>
                            <p class="header-subtitle"><?= esc($page_subtitle) ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <h1 class="welcome-text">Welcome, <span class="student-name"><?= esc($_SESSION['name'] ?? 'Super Admin') ?></span></h1>
                        <p class="student-role">Super Administrator • System-wide Access</p>
                    <?php endif; ?>
                </div>
                
                <div class="header-actions">
                    <button class="btn btn-outline notification-btn" aria-label="Notifications">
                        <i class="fas fa-bell" style="font-size: var(--font-md);"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown">
                        <button class="profile-dropdown-btn" onclick="toggleProfileDropdown()">
                            <?php 
                            $profile_pic = $_SESSION['profile_picture'] ?? null;
                            if ($profile_pic): 
                            ?>
                                <img src="<?= esc($profile_pic) ?>" alt="Profile" class="profile-avatar">
                            <?php else: ?>
                                <div class="profile-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </button>
                        <div class="profile-dropdown-menu" id="profileDropdownMenu">
                            <div class="dropdown-header">
                                <span class="dropdown-name"><?= esc($_SESSION['name'] ?? 'Super Admin') ?></span>
                                <span class="dropdown-role">Super Administrator</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="<?=ROOT?>/superadmin/profile" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="<?=ROOT?>/superadmin/profile/edit" class="dropdown-item">
                                <i class="fas fa-edit"></i>
                                <span>Edit Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-logout" onclick="logout(); return false;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
