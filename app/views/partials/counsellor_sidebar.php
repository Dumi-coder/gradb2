<?php
$current = strtolower($_GET['url'] ?? '');
$isActive = function(string $path) use ($current): string {
  return (strpos($current, strtolower($path)) === 0) ? ' active' : '';
};
?>
<!-- Sidebar Navigation -->
      <aside class="sidebar" id="sidebar">
        <!-- Sidebar Toggle Button -->
        <div class="sidebar-toggle">
            <button class="toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
          <div class="nav-section">
            <h3 class="nav-section-title">Main</h3>
            <a href="<?=ROOT?>/Counsellor/Dashboard" class="nav-item<?= $isActive('counsellor/dashboard') ?>">
              <i class="fas fa-tachometer-alt"></i>
              <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/Counsellor/PendingRequests" class="nav-item<?= $isActive('counsellor/pendingrequests') ?>">
              <i class="fas fa-clock"></i>
              <span>Pending</span>
            </a>
            <a href="<?=ROOT?>/Counsellor/ApprovedRequests" class="nav-item<?= $isActive('counsellor/approvedrequests') ?>">
              <i class="fas fa-check-circle"></i>
              <span>Approved</span>
            </a>
            <a href="<?=ROOT?>/Counsellor/CompletedRequests" class="nav-item<?= $isActive('counsellor/completedrequests') ?>">
              <i class="fas fa-flag-checkered"></i>
              <span>Completed</span>
            </a>
            <a href="<?=ROOT?>/Counsellor/RejectedRequests" class="nav-item<?= $isActive('counsellor/rejectedrequests') ?>">
              <i class="fas fa-times-circle"></i>
              <span>Rejected</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Tools</h3>
            <a href="<?=ROOT?>/Counsellor/Analytics" class="nav-item<?= $isActive('counsellor/analytics') ?>">
              <i class="fas fa-chart-bar"></i>
              <span>Analytics</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Account</h3>
            <a href="<?=ROOT?>/Counsellor/Profile" class="nav-item<?= $isActive('counsellor/profile') ?>">
              <i class="fas fa-user"></i>
              <span>My Profile</span>
            </a>
            <a href="<?=ROOT?>/Counsellor/Logout" class="nav-item<?= $isActive('counsellor/logout') ?>">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
            </a>
          </div>
        </nav>
      </aside>