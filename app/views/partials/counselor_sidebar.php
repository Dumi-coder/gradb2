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
            <a href="<?=ROOT?>/Counselor/Dashboard" class="nav-item<?= $isActive('counselor/dashboard') ?>">
              <i class="fas fa-tachometer-alt"></i>
              <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/Counselor/PendingRequests" class="nav-item<?= $isActive('counselor/pendingrequests') ?>">
              <i class="fas fa-clock"></i>
              <span>Pending Requests</span>
            </a>
            <a href="<?=ROOT?>/Counselor/ApprovedRequests" class="nav-item<?= $isActive('counselor/approvedrequests') ?>">
              <i class="fas fa-check-circle"></i>
              <span>Approved Requests</span>
            </a>
            <a href="<?=ROOT?>/Counselor/RejectedRequests" class="nav-item<?= $isActive('counselor/rejectedrequests') ?>">
              <i class="fas fa-times-circle"></i>
              <span>Rejected Requests</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Tools</h3>
            <a href="<?=ROOT?>/Counselor/Analytics" class="nav-item<?= $isActive('counselor/analytics') ?>">
              <i class="fas fa-chart-bar"></i>
              <span>Analytics</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Account</h3>
            <a href="<?=ROOT?>/Counselor/Logout" class="nav-item<?= $isActive('counselor/logout') ?>">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
            </a>
          </div>
        </nav>
      </aside>