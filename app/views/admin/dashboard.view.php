<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Faculty Admin Profile Section -->
        <section class="dashboard-section profile-section">
            <h2 class="section-title">Faculty Admin Profile</h2>
            <div class="profile-card">
                <div class="profile-info">
                    <div class="profile-avatar">
                        <span class="avatar-initials"><?= strtoupper(substr($_SESSION['name'] ?? 'SA', 0, 2)) ?></span>
                    </div>
                    <div class="profile-details">
                        <h3><?= esc($_SESSION['name'] ?? 'sasa') ?></h3>
                        <p>Faculty Administrator at Faculty</p>
                        <p>Admin ID: FAC001</p>
                        <div class="profile-meta">
                            <p><strong>Faculty ID:</strong> <?= $_SESSION['faculty_id'] ?? '1' ?></p>
                            <p><strong>Status:</strong> 
                                <span class="status-badge status-active">
                                    <i class="fas fa-check"></i> Active
                                </span>
                            </p>
                            <p><strong>Email:</strong> <?= esc($_SESSION['email'] ?? 'admin@university.edu') ?></p>
                        </div>
                    </div>
                </div>
        </div>
        </section>
        
        <!-- Faculty Stats Section -->
        <section class="dashboard-section">
            <h2 class="section-title">Faculty Stats</h2>
            <div class="stats-grid">
                <!-- Left Column -->
                <div class="stats-column">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['registered_students'] ?? 156 ?></div>
                            <div class="stat-label">No of Registered Students</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['students_online'] ?? 23 ?></div>
                            <div class="stat-label">No of Students Online</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['pending_aid_requests'] ?? 8 ?></div>
                            <div class="stat-label">Pending Aid Requests</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['events_waiting_approval'] ?? 5 ?></div>
                            <div class="stat-label">Events Waiting Approval</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['password_verification_requests'] ?? 7 ?></div>
                            <div class="stat-label">No of Password Verification Requests</div>
        </div>
    </div>
</div>

                <!-- Right Column -->
                <div class="stats-column">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['registered_alumni'] ?? 89 ?></div>
                            <div class="stat-label">No of Registered Alumni</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['alumni_online'] ?? 12 ?></div>
                            <div class="stat-label">No of Alumni Online</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['pending_mentorship_requests'] ?? 15 ?></div>
                            <div class="stat-label">Pending Mentorship Requests</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['pending_complaints'] ?? 3 ?></div>
                            <div class="stat-label">No of Pending User Complaints</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?= $stats['upcoming_events'] ?? 12 ?></div>
                            <div class="stat-label">Upcoming Events</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
