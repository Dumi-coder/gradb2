<?php
$current = strtolower($_GET['url'] ?? '');
$isActive = function(string $path) use ($current): string {
  return (strpos($current, strtolower($path)) === 0) ? ' active' : '';
};
?>
<!-- Admin Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle">
        <button class="toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        <!-- MAIN Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">MAIN</h3>
            <a href="<?=ROOT?>/admin/dashboard" class="nav-item<?= $isActive('admin/dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/admin/mentorship" class="nav-item<?= $isActive('admin/mentorship') ?>">
                <i class="fas fa-users"></i>
                <span>Approve Mentorships</span>
            </a>
            <a href="<?=ROOT?>/admin/AidRequests" class="nav-item<?= $isActive('admin/AidRequests') ?>">
                <i class="fas fa-hands-helping"></i>
                <span>Approve Aid Requests</span>
            </a>
            <a href="<?=ROOT?>/admin/events" class="nav-item<?= $isActive('admin/events') ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Approve Events</span>
            </a>
            <a href="<?=ROOT?>/admin/ForumModeration" class="nav-item<?= $isActive('admin/ForumModeration') ?>">
                <i class="fas fa-comments"></i>
                <span>Forum Moderation</span>
            </a>
            <a href="<?=ROOT?>/admin/ResourceModeration" class="nav-item<?= $isActive('admin/ResourceModeration') ?>">
                <i class="fas fa-folder-open"></i>
                <span>Resource Moderation</span>
            </a>
            <a href="<?=ROOT?>/admin/FundraiserModeration" class="nav-item<?= $isActive('admin/FundraiserModeration') ?>">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Moderate Fundraisers</span>
            </a>
        </div>
        
        <!-- RESOURCES Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">RESOURCES</h3>
            <a href="<?=ROOT?>/admin/UserManagement" class="nav-item<?= $isActive('admin/UserManagement') ?>">
                <i class="fas fa-user-slash"></i>
                <span>Suspend/Reactivate Users</span>
            </a>
            <a href="<?=ROOT?>/admin/AddNewStudents" class="nav-item<?= $isActive('admin/AddNewStudents') ?>">
                <i class="fas fa-user-plus"></i>
                <span>Add New Students</span>
            </a>
            <a href="<?=ROOT?>/admin/AddNewAlumnies" class="nav-item<?= $isActive('admin/AddNewAlumnies') ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Add New Alumnies</span>
            </a>
            <a href="<?=ROOT?>/admin/FaqModeration" class="nav-item<?= $isActive('admin/FaqModeration') ?>">
                <i class="fas fa-question-circle"></i>
                <span>FAQ Moderation</span>
            </a>
            <a href="<?=ROOT?>/admin/FacultyAnnouncements" class="nav-item<?= $isActive('admin/FacultyAnnouncements') ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Faculty Announcements</span>
            </a>
        </div>
        
        <!-- ACCOUNT Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">ACCOUNT</h3>
            <a href="<?=ROOT?>/admin/profile" class="nav-item<?= $isActive('admin/profile') ?>">
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
