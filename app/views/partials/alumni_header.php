<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/buttons.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard-header.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard-cards.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Modern Dashboard Header -->
    <header class="modern-dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <div class="welcome-section">
                    <h1 class="welcome-text">Welcome, <span class="user-name-highlight"><?= isset($_SESSION['USER']->name) ? htmlspecialchars($_SESSION['USER']->name) : 'Alumni' ?></span></h1>
                    <p class="user-details">Computer Science • Class of 2020</p>
                </div>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="btn btn-outline btn-md notification-btn" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <a href="<?=ROOT?>/logout" class="btn btn-primary btn-md logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="dashboard-container">

