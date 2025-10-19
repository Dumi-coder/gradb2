<?php require '../app/views/partials/header.php'; ?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <?= esc($_SESSION['name']) ?>!</p>
    </div>
    
    <div class="dashboard-content">
        <div class="dashboard-card">
            <h2>System Overview</h2>
            <p>Admin panel for managing the GradBridge platform.</p>
        </div>
        
        <div class="dashboard-actions">
            <a href="logout" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</div>

<?php require '../app/views/partials/footer.php'; ?>
