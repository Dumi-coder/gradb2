<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Alumni Dashboard - GradBridge' ?></title>
    
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
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    
    <!-- JavaScript Files -->
    <script src="<?=ROOT?>/assets/js/sidebar-toggle.js"></script>
</head>
<body class="alumni-dashboard">
    <script>
        let notificationsMarkedAsRead = false;

        async function markNotificationsAsRead() {
            if (notificationsMarkedAsRead) return;

            const badge = document.querySelector('.notification-btn .notification-badge');
            if (!badge) {
                notificationsMarkedAsRead = true;
                return;
            }

            notificationsMarkedAsRead = true;
            try {
                await fetch('<?=ROOT?>/alumni/eventboard/marknotificationsread', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            } catch (error) {
                notificationsMarkedAsRead = false;
                return;
            }

            badge.remove();
            const unreadText = document.querySelector('#notificationDropdownMenu .dropdown-role');
            if (unreadText) {
                unreadText.textContent = '0 unread';
            }
        }

        function toggleNotificationDropdown() {
            const menu = document.getElementById('notificationDropdownMenu');
            if (!menu) return;
            const willOpen = !menu.classList.contains('show');
            menu.classList.toggle('show');
            if (willOpen) {
                markNotificationsAsRead();
            }
        }

        function logout() {
            // Create a form to submit logout request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?=ROOT?>/alumni/logout';
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

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('notificationDropdownMenu');
            const btn = document.querySelector('.notification-btn');
            if (!menu || !btn) return;
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    </script>
    <!-- Alumni Dashboard Header -->
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
                        <h1 class="welcome-text">Welcome, <span class="alumni-name"><?= esc($_SESSION['name'] ?? 'Alumni') ?></span></h1>
                        <p class="alumni-role">Alumni Member</p>
                    <?php endif; ?>
                </div>
                
                <div class="header-actions">
                    <?php
                    $notification_count = (int)($notification_count ?? 0);
                    $header_notifications = $header_notifications ?? [];
                    ?>
                    <button class="btn btn-outline notification-btn" aria-label="Notifications" onclick="toggleNotificationDropdown()">
                        <i class="fas fa-bell" style="font-size: var(--font-md);"></i>
                        <?php if ($notification_count > 0): ?>
                            <span class="notification-badge"><?= $notification_count ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="profile-dropdown-menu" id="notificationDropdownMenu" style="min-width:320px; right:120px; left:auto; top:72px;">
                        <div class="dropdown-header">
                            <span class="dropdown-name">Notifications</span>
                            <span class="dropdown-role"><?= $notification_count ?> unread</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <?php if (!empty($header_notifications)): ?>
                            <?php foreach ($header_notifications as $notif): ?>
                                <div class="dropdown-item" style="align-items:flex-start; gap:8px; cursor:default;">
                                    <i class="fas fa-info-circle"></i>
                                    <span style="white-space:normal;"><?= esc($notif['message'] ?? 'Notification') ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="dropdown-item" style="cursor:default; color:var(--muted-foreground);">No notifications</div>
                        <?php endif; ?>
                    </div>
                    
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
                                <span class="dropdown-name"><?= esc($_SESSION['name'] ?? 'Alumni') ?></span>
                                <span class="dropdown-role">Alumni Member</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="<?=ROOT?>/alumni/profile" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="<?=ROOT?>/alumni/profile/edit" class="dropdown-item">
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
