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
    <?php
    if (!isset($header_notifications) || !is_array($header_notifications)) {
        $header_notifications = [];
        if (!empty($_SESSION['user_id'])) {
            $eventNotificationModel = new EventNotification();
            $header_notifications = $eventNotificationModel->getUserNotifications($_SESSION['user_id'], 0, 50);
            if (!is_array($header_notifications)) {
                $header_notifications = [];
            }
        }
    }
    ?>
    <script>
        const headerNotifications = <?= json_encode(isset($header_notifications) ? $header_notifications : [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function getNotificationMessage(item) {
            if (!item) return '';
            if (typeof item === 'string') return item;
            return item.message || item.notification_message || item.title || item.text || '';
        }

        function openNotificationWindow() {
            let overlay = document.getElementById('notificationWindowOverlay');

            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'notificationWindowOverlay';
                overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;z-index:9999;';
                overlay.innerHTML = '' +
                    '<div role="dialog" aria-modal="true" aria-labelledby="notificationWindowTitle" style="width:min(480px,94vw);height:min(520px,85vh);background:#fff;border-radius:12px;box-shadow:0 20px 50px rgba(0,0,0,0.2);padding:18px;display:flex;flex-direction:column;">' +
                    '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">' +
                    '<h3 id="notificationWindowTitle" style="margin:0;font-size:18px;">Notifications</h3>' +
                    '<div style="display:flex;gap:10px;align-items:center;">' +
                    '<button type="button" id="markAllReadBtn" style="border:1px solid #d1d5db;background:#f3f4f6;padding:6px 12px;border-radius:6px;font-size:12px;cursor:pointer;color:#374151;font-weight:500;">Mark all as read</button>' +
                    '<button type="button" id="notificationWindowClose" aria-label="Close" style="border:none;background:transparent;font-size:22px;line-height:1;cursor:pointer;">&times;</button>' +
                    '</div>' +
                    '</div>' +
                    '<div id="notificationWindowBody" style="flex:1;overflow-y:auto;border:1px solid #e5e7eb;border-radius:10px;padding:12px;display:flex;flex-direction:column;gap:10px;"></div>' +
                    '</div>';

                document.body.appendChild(overlay);

                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        overlay.remove();
                    }
                });

                const closeBtn = document.getElementById('notificationWindowClose');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        overlay.remove();
                    });
                }

                const markAllBtn = document.getElementById('markAllReadBtn');
                if (markAllBtn) {
                    markAllBtn.addEventListener('click', function() {
                        const notificationIds = [];
                        document.querySelectorAll('.notification-mark-btn').forEach(btn => {
                            notificationIds.push(btn.getAttribute('data-notification-id'));
                        });
                        
                        if (notificationIds.length === 0) {
                            markAllBtn.style.opacity = '0.5';
                            markAllBtn.disabled = true;
                            return;
                        }

                        fetch('<?=ROOT?>/home/markNotificationsAsRead', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ notification_ids: notificationIds })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const markBtns = document.querySelectorAll('.notification-mark-btn');
                                markBtns.forEach(btn => {
                                    btn.style.background = '#10b981';
                                    btn.style.borderColor = '#10b981';
                                    btn.style.color = '#fff';
                                    btn.disabled = true;
                                });
                                markAllBtn.style.opacity = '0.5';
                                markAllBtn.disabled = true;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                }

                const body = document.getElementById('notificationWindowBody');
                if (body) {
                    fetch('<?=ROOT?>/home/getNotifications')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.notifications && Array.isArray(data.notifications)) {
                                if (data.notifications.length === 0) {
                                    body.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;">' +
                                        '<div style="text-align:center;color:#6b7280;max-width:260px;">' +
                                        '<div style="width:72px;height:72px;border-radius:50%;background:#f3f4f6;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">' +
                                        '<i class="fas fa-bell-slash" style="font-size:26px;color:#9ca3af;"></i>' +
                                        '</div>' +
                                        '<p style="margin:0;font-size:14px;line-height:1.5;">You\'re all caught up. No new notifications right now.</p>' +
                                        '</div>' +
                                        '</div>';
                                } else {
                                    body.innerHTML = data.notifications.map(function(notification) {
                                        const timestamp = new Date(notification.created_at).toLocaleString();
                                        return '<div style="padding:12px;border-radius:8px;background:#f9fafb;border:1px solid #e5e7eb;width:100%;box-sizing:border-box;display:flex;gap:10px;align-items:flex-start;">' +
                                            '<button type="button" class="notification-mark-btn" data-notification-id="' + (notification.notification_id || '') + '" style="background:#fff;border:1px solid #d1d5db;border-radius:6px;padding:6px 8px;cursor:pointer;min-width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:16px;flex-shrink:0;transition:all 0.2s;">' +
                                            '<i class="fas fa-check"></i>' +
                                            '</button>' +
                                            '<div style="flex:1;">' +
                                            '<strong style="display:block;font-size:15px;color:#1f2937;margin-bottom:6px;">' + escapeHtml(notification.title) + '</strong>' +
                                            '<p style="margin:0 0 6px 0;font-size:13px;color:#6b7280;line-height:1.4;">' + escapeHtml(notification.message) + '</p>' +
                                            '<div style="font-size:12px;color:#9ca3af;">' + timestamp + '</div>' +
                                            '</div>' +
                                            '</div>';
                                    }).join('');
                                    document.querySelectorAll('.notification-mark-btn').forEach(btn => {
                                        btn.addEventListener('click', function() {
                                            const notificationId = this.getAttribute('data-notification-id');
                                            fetch('<?=ROOT?>/home/markNotificationsAsRead', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/json' },
                                                body: JSON.stringify({ notification_ids: [notificationId] })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    this.style.background = '#10b981';
                                                    this.style.borderColor = '#10b981';
                                                    this.style.color = '#fff';
                                                    this.disabled = true;
                                                }
                                            })
                                            .catch(error => console.error('Error:', error));
                                        });
                                    });
                                }
                            } else {
                                body.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;">' +
                                    '<div style="text-align:center;color:#ef4444;max-width:260px;">' +
                                    '<p style="margin:0;font-size:14px;">Unable to load notifications</p>' +
                                    '</div>' +
                                    '</div>';
                            }
                        })
                        .catch(error => {
                            body.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;">' +
                                '<div style="text-align:center;color:#ef4444;max-width:260px;">' +
                                '<p style="margin:0;font-size:14px;">Error loading notifications</p>' +
                                '</div>' +
                                '</div>';
                        });
                }
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
                    <button class="btn btn-outline notification-btn" aria-label="Notifications" onclick="openNotificationWindow()">
                        <i class="fas fa-bell" style="font-size: var(--font-md);"></i>
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
