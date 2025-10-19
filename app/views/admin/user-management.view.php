<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- User Management Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">User Management</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $userData['stats']['total_users'] ?></span>
                        <span class="stat-label">Total Users</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $userData['stats']['active_users'] ?></span>
                        <span class="stat-label">Active</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $userData['stats']['suspended_users'] ?></span>
                        <span class="stat-label">Suspended</span>
                    </div>
                </div>
            </div>
            
            <!-- User Search and Filters -->
            <div class="user-filters">
                <div class="search-box">
                    <input type="text" id="user-search" placeholder="Search users by name or email..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-options">
                    <select id="role-filter" class="filter-select">
                        <option value="">All Roles</option>
                        <option value="student">Students</option>
                        <option value="alumni">Alumni</option>
                    </select>
                    <select id="status-filter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>
            
            <!-- Users List -->
            <div class="users-container">
                <?php foreach ($userData['users'] as $user): ?>
                <div class="user-card" data-role="<?= $user['role'] ?>" data-status="<?= $user['status'] ?>">
                    <div class="user-header">
                        <div class="user-info">
                            <h4 class="user-name"><?= esc($user['name']) ?></h4>
                            <p class="user-email"><?= esc($user['email']) ?></p>
                            <p class="user-role"><?= ucfirst($user['role']) ?></p>
                        </div>
                        <div class="user-meta">
                            <span class="status-badge status-<?= $user['status'] ?>"><?= ucfirst($user['status']) ?></span>
                            <?php if ($user['violations'] > 0): ?>
                            <span class="violations-badge"><?= $user['violations'] ?> violations</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="user-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Last Login:</strong> <?= date('M j, Y', strtotime($user['last_login'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-user-plus"></i>
                            <span><strong>Registered:</strong> <?= date('M j, Y', strtotime($user['registration_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span><strong>Violations:</strong> <?= $user['violations'] ?></span>
                        </div>
                    </div>
                    
                    <div class="user-actions">
                        <?php if ($user['status'] === 'active'): ?>
                        <button class="btn btn-warning btn-sm suspend-btn" data-user-id="<?= $user['id'] ?>" data-user-name="<?= esc($user['name']) ?>">
                            <i class="fas fa-user-slash"></i>
                            Suspend User
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success btn-sm reactivate-btn" data-user-id="<?= $user['id'] ?>" data-user-name="<?= esc($user['name']) ?>">
                            <i class="fas fa-user-check"></i>
                            Reactivate User
                        </button>
                        <?php endif; ?>
                        <button class="btn btn-outline btn-sm view-btn" data-user-id="<?= $user['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </button>
                        <button class="btn btn-outline btn-sm warn-btn" data-user-id="<?= $user['id'] ?>">
                            <i class="fas fa-exclamation-triangle"></i>
                            Send Warning
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

<style>
.user-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #0E2072;
    box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1);
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6B7280;
}

.filter-options {
    display: flex;
    gap: 0.75rem;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 0.9rem;
    background-color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #0E2072;
}

.users-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.user-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.user-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.user-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.user-name {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.user-email {
    margin: 0 0 0.25rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.user-role {
    margin: 0;
    color: #0E2072;
    font-size: 0.9rem;
    font-weight: 500;
}

.user-meta {
    text-align: right;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.status-active {
    background-color: #D1FAE5;
    color: #065F46;
}

.status-suspended {
    background-color: #FEE2E2;
    color: #DC2626;
}

.violations-badge {
    display: block;
    background-color: #FEF3C7;
    color: #D97706;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.user-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4B5563;
    font-size: 0.9rem;
}

.detail-item i {
    color: #0E2072;
    width: 16px;
}

.user-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-success {
    background-color: #10B981;
    color: white;
}

.btn-success:hover {
    background-color: #059669;
}

.btn-warning {
    background-color: #F59E0B;
    color: white;
}

.btn-warning:hover {
    background-color: #D97706;
}

.btn-outline {
    background-color: white;
    color: #000000;
    border: 1px solid #000000;
}

.btn-outline:hover {
    background-color: #000000;
    color: white;
}

.section-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #0E2072;
}

.stat-label {
    font-size: 0.875rem;
    color: #6B7280;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle suspend button clicks
    document.querySelectorAll('.suspend-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            if (confirm(`Are you sure you want to suspend ${userName}?`)) {
                alert('User suspended successfully!');
                this.closest('.user-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle reactivate button clicks
    document.querySelectorAll('.reactivate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            if (confirm(`Are you sure you want to reactivate ${userName}?`)) {
                alert('User reactivated successfully!');
                this.closest('.user-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle view profile button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            alert('View profile functionality would be implemented here for user ID: ' + userId);
        });
    });

    // Handle send warning button clicks
    document.querySelectorAll('.warn-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            alert('Send warning functionality would be implemented here for user ID: ' + userId);
        });
    });

    // Handle search functionality
    document.getElementById('user-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const userCards = document.querySelectorAll('.user-card');
        
        userCards.forEach(card => {
            const userName = card.querySelector('.user-name').textContent.toLowerCase();
            const userEmail = card.querySelector('.user-email').textContent.toLowerCase();
            
            if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle role filter
    document.getElementById('role-filter').addEventListener('change', function() {
        const selectedRole = this.value;
        const userCards = document.querySelectorAll('.user-card');
        
        userCards.forEach(card => {
            if (selectedRole === '' || card.getAttribute('data-role') === selectedRole) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Handle status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const userCards = document.querySelectorAll('.user-card');
        
        userCards.forEach(card => {
            if (selectedStatus === '' || card.getAttribute('data-status') === selectedStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
