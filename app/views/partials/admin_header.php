<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard - GradBridge' ?></title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard-header.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin-dashboard.css">
</head>
<body>
    <script>
        function logout() {
            // Create a form to submit logout request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?=ROOT?>/admin/logout';
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
    <!-- Admin Dashboard Header -->
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
                        <h1 class="welcome-text">Welcome, <span class="student-name"><?= esc($_SESSION['name'] ?? 'Admin') ?></span></h1>
                        <p class="student-role">Faculty Admin • Faculty ID: <?= $_SESSION['faculty_id'] ?? '1' ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="header-actions">
                    <button class="btn btn-outline notification-btn" aria-label="Notifications">
                        <i class="fas fa-bell" style="font-size: var(--font-md);"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="btn btn-primary logout-btn" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </header>
