<?php
$current = strtolower($_GET['url'] ?? '');
$isActive = function(string $path) use ($current): string {
  return (strpos($current, strtolower($path)) === 0) ? ' active' : '';
};
?>
<!-- Super Admin Sidebar -->
<aside class="sidebar">
    <nav class="sidebar-nav">
        <!-- MAIN Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">MAIN</h3>
            <a href="<?=ROOT?>/superadmin/dashboard" class="nav-item<?= $isActive('superadmin/dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/superadmin/mentorship" class="nav-item<?= $isActive('superadmin/mentorship') ?>">
                <i class="fas fa-users"></i>
                <span>Approve Mentorships</span>
            </a>
            <a href="<?=ROOT?>/superadmin/AidRequests" class="nav-item<?= $isActive('superadmin/AidRequests') ?>">
                <i class="fas fa-hands-helping"></i>
                <span>Approve Aid Requests</span>
            </a>
            <a href="<?=ROOT?>/superadmin/events" class="nav-item<?= $isActive('superadmin/events') ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Approve Events</span>
            </a>
            <a href="<?=ROOT?>/superadmin/ForumModeration" class="nav-item<?= $isActive('superadmin/ForumModeration') ?>">
                <i class="fas fa-comments"></i>
                <span>Forum Moderation</span>
            </a>
            <a href="<?=ROOT?>/superadmin/ResourceModeration" class="nav-item<?= $isActive('superadmin/ResourceModeration') ?>">
                <i class="fas fa-folder-open"></i>
                <span>Resource Moderation</span>
            </a>
            <a href="<?=ROOT?>/superadmin/FundraiserModeration" class="nav-item<?= $isActive('superadmin/FundraiserModeration') ?>">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Moderate Fundraisers</span>
            </a>
        </div>
        
        <!-- RESOURCES Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">RESOURCES</h3>
            <a href="<?=ROOT?>/superadmin/UserManagement" class="nav-item<?= $isActive('superadmin/UserManagement') ?>">
                <i class="fas fa-user-slash"></i>
                <span>Suspend/Reactivate Users</span>
            </a>
            <a href="<?=ROOT?>/superadmin/FaqModeration" class="nav-item<?= $isActive('superadmin/FaqModeration') ?>">
                <i class="fas fa-question-circle"></i>
                <span>FAQ Moderation</span>
            </a>
            <a href="<?=ROOT?>/superadmin/FacultyAnnouncements" class="nav-item<?= $isActive('superadmin/FacultyAnnouncements') ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Faculty Announcements</span>
            </a>
        </div>
        
        <!-- ACCOUNT Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">ACCOUNT</h3>
            <a href="<?=ROOT?>/superadmin/profile" class="nav-item<?= $isActive('superadmin/profile') ?>">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="#" onclick="logout(); return false;" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>
