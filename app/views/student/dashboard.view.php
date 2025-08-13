<!-- Student Dashboard View: /app/views/student/dashboard.view.php -->
<?php require '../app/views/partials/header.php'; ?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/style.css">

<main class="dashboard-content">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Student Dashboard</h1>
            <p>Welcome back, <?= esc($user->name) ?>!</p>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?= $total_alumni ?? 0 ?></h3>
                <p>Total Alumni</p>
            </div>
            <div class="stat-card">
                <h3><?= $faculty_alumni ?? 0 ?></h3>
                <p>Alumni in Your Faculty</p>
            </div>
        </div>
        
        <div class="dashboard-actions">
            <a href="<?=ROOT?>/student/alumni_network" class="action-btn">Browse Alumni Network</a>
            <a href="<?=ROOT?>/student/messages" class="action-btn">Messages</a>
            <a href="<?=ROOT?>/student/career_guidance" class="action-btn">Career Guidance</a>
            <a href="<?=ROOT?>/auth/profile" class="action-btn">Edit Profile</a>
        </div>
        
        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <p>No recent activity yet. Start connecting with alumni!</p>
        </div>
    </div>
</main>

<?php require '../app/views/partials/footer.php'; ?>

<!-- =============================================== -->

<!-- Alumni Dashboard View: /app/views/alumni/dashboard.view.php -->
<?php require '../app/views/partials/header.php'; ?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/style.css">

<main class="dashboard-content">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Alumni Dashboard</h1>
            <p>Welcome back, <?= esc($user->name) ?>!</p>
            <p class="user-info">Class of <?= esc($user->graduation_year) ?> | <?= esc($user->faculty) ?></p>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?= $total_students ?? 0 ?></h3>
                <p>Total Students</p>
            </div>
            <div class="stat-card">
                <h3><?= $faculty_students ?? 0 ?></h3>
                <p>Students in Your Faculty</p>
            </div>
        </div>
        
        <div class="dashboard-actions">
            <a href="<?=ROOT?>/alumni/student_network" class="action-btn">Connect with Students</a>
            <a href="<?=ROOT?>/alumni/mentorship" class="action-btn">Mentorship</a>
            <a href="<?=ROOT?>/alumni/job_opportunities" class="action-btn">Post Job Opportunities</a>
            <a href="<?=ROOT?>/alumni/messages" class="action-btn">Messages</a>
            <a href="<?=ROOT?>/auth/profile" class="action-btn">Edit Profile</a>
        </div>
        
        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <p>No recent activity yet. Start mentoring students!</p>
        </div>
    </div>
</main>

<?php require '../app/views/partials/footer.php'; ?>

<!-- =============================================== -->

<!-- Profile View: /app/views/auth/profile.view.php -->
<?php require '../app/views/partials/header.php'; ?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/login&signup.css">

<main class="main-content">
    <div class="login-container">
        <h1 class="welcome-title">Edit <span class="back-text">Profile</span></h1>
        
        <form method="POST" class="login-form">
            
            <?php if(!empty($errors)):?>
            <div class="alert alert-danger">       
                <?= implode("<br>",$errors)?> 
            </div>
            <?php endif;?>
            
            <?php if(!empty($success)):?>
            <div class="alert alert-success">       
                <?= $success ?> 
            </div>
            <?php endif;?>

            <div class="form-group">
                <input 
                    type="text" 
                    name="name" 
                    class="form-input" 
                    placeholder="Full Name" 
                    value="<?= esc($user->name) ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <input 
                    type="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="Email Address" 
                    value="<?= esc($user->email) ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <input 
                    type="text" 
                    name="faculty" 
                    class="form-input" 
                    placeholder="Faculty" 
                    value="<?= esc($user->faculty) ?>"
                    required
                >
            </div>
            
            <?php if($user_type === 'alumni'): ?>
            <div class="form-group">
                <input 
                    type="number" 
                    name="graduation_year" 
                    class="form-input" 
                    placeholder="Graduation Year" 
                    value="<?= esc($user->graduation_year) ?>"
                    required
                >
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <input 
                    type="password" 
                    name="new_password" 
                    class="form-input" 
                    placeholder="New Password (leave blank to keep current)" 
                >
            </div>

            <div class="form-group">
                <input 
                    type="password" 
                    name="confirm_password" 
                    class="form-input" 
                    placeholder="Confirm New Password" 
                >
            </div>
            
            <button type="submit" class="login-btn">Update Profile</button>
        </form>
        
        <div class="signup-link">
            <a href="<?=ROOT?>/<?= $user_type ?>/dashboard">Back to Dashboard</a>
        </div>
    </div>
</main>

<?php require '../app/views/partials/footer.php'; ?>