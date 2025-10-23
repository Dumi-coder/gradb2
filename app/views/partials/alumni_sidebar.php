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
            <a href="<?=ROOT?>/Alumni/Dashboard" class="nav-item<?= $isActive('alumni/dashboard') ?>">
              <i class="fas fa-tachometer-alt"></i>
              <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/Alumni/Mentorship" class="nav-item<?= $isActive('alumni/mentorship') ?>">
              <i class="fas fa-users"></i>
              <span>Mentorship</span>
            </a>
            <a href="<?=ROOT?>/Alumni/AidRequests" class="nav-item<?= $isActive('alumni/aidrequests') ?>">
              <i class="fas fa-hands-helping"></i>
              <span>Aid Requests</span>
            </a>
            <a href="<?=ROOT?>/Alumni/DiscussionForum" class="nav-item<?= $isActive('alumni/discussionforum') ?>">
              <i class="fas fa-comments"></i>
              <span>Discussion Forum</span>
            </a>
            <a href="<?=ROOT?>/Alumni/EventBoard" class="nav-item<?= $isActive('alumni/eventboard') ?>">
              <i class="fas fa-calendar-alt"></i>
              <span>Events Board</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Resources</h3>
            <a href="<?=ROOT?>/Alumni/Resources" class="nav-item<?= $isActive('alumni/resources') ?>">
              <i class="fas fa-folder-open"></i>
              <span>Resources</span>
            </a>
            <a href="<?=ROOT?>/Alumni/Fundraising" class="nav-item<?= $isActive('alumni/fundraising') ?>">
              <i class="fas fa-hand-holding-heart"></i>
              <span>Fundraising</span>
            </a>
            <a href="<?=ROOT?>/Alumni/Faq" class="nav-item<?= $isActive('alumni/faq') ?>">
              <i class="fas fa-question-circle"></i>
              <span>FAQ</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Account</h3>
            <a href="<?=ROOT?>/Alumni/profile/show" class="nav-item<?= $isActive('alumni/profile') ?>">
              <i class="fas fa-user-circle"></i>
              <span>Profile</span>
            </a>
            <a href="<?=ROOT?>/alumni/logout" class="nav-item<?= $isActive('alumni/logout') ?>">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
            </a>
          </div>
        </nav>
      </aside>
