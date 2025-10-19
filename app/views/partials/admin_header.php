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
    <!-- Admin Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="welcome-section">
                    <h1 class="welcome-text">Welcome, <span class="student-name"><?= esc($_SESSION['name'] ?? 'Admin') ?></span></h1>
                    <p class="student-role">Faculty Admin • Faculty ID: <?= $_SESSION['faculty_id'] ?? '1' ?></p>
                </div>
                
                <div class="header-actions">
                    <button class="btn btn-outline notification-btn" aria-label="Notifications">
                        <i class="fas fa-bell" style="font-size: var(--font-md);"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <a href="<?=ROOT?>/admin/logout">
                        <button class="btn btn-primary logout-btn">Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </header>
