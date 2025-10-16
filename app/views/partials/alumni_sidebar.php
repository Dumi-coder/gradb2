<!-- Alumni Sidebar Navigation Partial -->
<aside class="sidebar">
    <nav class="sidebar-nav">
        <div class="nav-section">
            <h3 class="nav-section-title">Main</h3>
            <a href="<?=ROOT?>/alumni/dashboard" class="nav-item <?= (isset($current_page) && $current_page === 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard Home</span>
            </a>
            <a href="<?=ROOT?>/alumni/mentorshiprequests" class="nav-item <?= (isset($current_page) && $current_page === 'mentorship') ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Mentorship</span>
            </a>
            <a href="<?=ROOT?>/alumni/aidrequests" class="nav-item <?= (isset($current_page) && $current_page === 'aidrequests') ? 'active' : '' ?>">
                <i class="fas fa-hands-helping"></i>
                <span>Aid Requests</span>
            </a>
            <a href="<?=ROOT?>/alumni/events" class="nav-item <?= (isset($current_page) && $current_page === 'events') ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            <a href="<?=ROOT?>/alumni/fundraisers" class="nav-item <?= (isset($current_page) && $current_page === 'fundraisers') ? 'active' : '' ?>">
                <i class="fas fa-heart"></i>
                <span>Fundraisers</span>
            </a>
        </div>
        <div class="nav-section">
            <h3 class="nav-section-title">Resources</h3>
            <a href="<?=ROOT?>/alumni/resources/index" class="nav-item <?= (isset($current_page) && $current_page === 'resources') ? 'active' : '' ?>">
                <i class="fas fa-folder-open"></i>
                <span>Shared Resources</span>
            </a>
        </div>
        <div class="nav-section">
            <h3 class="nav-section-title">Account</h3>
            <a href="<?=ROOT?>/alumni/profile" class="nav-item <?= (isset($current_page) && $current_page === 'profile') ? 'active' : '' ?>">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>
            <a href="<?=ROOT?>/logout" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>
