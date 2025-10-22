<?php
$current = strtolower($_GET['url'] ?? '');
$isActive = function(string $path) use ($current): string {
  return (strpos($current, strtolower($path)) === 0) ? ' active' : '';
};
?>
<!-- Sidebar Navigation -->
      <aside class="sidebar">
        <nav class="sidebar-nav">
          <div class="nav-section">
            <h3 class="nav-section-title">Main</h3>
            <a href="<?=ROOT?>/Student/Dashboard" class="nav-item<?= $isActive('student/dashboard') ?>">
              <i class="fas fa-tachometer-alt"></i>
              <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/Student/Mentorship" class="nav-item<?= $isActive('student/mentorship') ?>">
              <i class="fas fa-users"></i>
              <span>Mentorship</span>
            </a>
            <a href="<?=ROOT?>/Student/AidRequests" class="nav-item<?= $isActive('student/aidrequests') ?>">
              <i class="fas fa-hands-helping"></i>
              <span>Aid Requests</span>
            </a>
            <a href="<?=ROOT?>/Student/DiscussionForum" class="nav-item<?= $isActive('student/discussionforum') ?>">
              <i class="fas fa-comments"></i>
              <span>Discussion Forum</span>
            </a>
            <a href="<?=ROOT?>/Student/EventsBoard" class="nav-item<?= $isActive('student/eventsboard') ?>">
              <i class="fas fa-calendar-alt"></i>
              <span>Events Board</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Resources</h3>
            <a href="<?=ROOT?>/Student/Resources" class="nav-item<?= $isActive('student/resources') ?>">
              <i class="fas fa-folder-open"></i>
              <span>Resources</span>
            </a>
            <a href="<?=ROOT?>/Student/Fundraising" class="nav-item<?= $isActive('student/fundraising') ?>">
              <i class="fas fa-hand-holding-heart"></i>
              <span>Fundraising</span>
            </a>
            <a href="<?=ROOT?>/Student/Faq" class="nav-item<?= $isActive('student/faq') ?>">
              <i class="fas fa-question-circle"></i>
              <span>FAQ</span>
            </a>
          </div>
          
          <div class="nav-section">
            <h3 class="nav-section-title">Account</h3>
            <a href="<?=ROOT?>/Student/profile/show" class="nav-item<?= $isActive('student/profile') ?>">
              <i class="fas fa-user-circle"></i>
              <span>Profile</span>
            </a>
            <a href="<?=ROOT?>/Student/logout" class="nav-item<?= $isActive('student/logout') ?>">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
            </a>
          </div>
        </nav>
      </aside>