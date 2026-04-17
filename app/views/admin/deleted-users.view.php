<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Deleted Users Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Deleted Users</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= count($deletedUsers) ?></span>
                        <span class="stat-label">Deleted Accounts</span>
                    </div>
                </div>
            </div>
            
            <!-- User Search and Filters -->
            <div class="user-filters-enhanced">
                <div class="filters-container">
                    <div class="search-box-enhanced">
                        <i class="fas fa-search search-icon-enhanced"></i>
                        <input type="text" id="user-search" placeholder="Search by name or deleted date..." class="search-input-enhanced">
                        <div class="search-ripple"></div>
                    </div>
                    <div class="filter-options-enhanced">
                        <div class="filter-item">
                            <i class="fas fa-filter filter-icon"></i>
                            <select id="role-filter" class="filter-select-enhanced">
                                <option value="">All Roles</option>
                                <option value="student">Students</option>
                                <option value="alumni">Alumni</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Deleted Users List -->
            <div class="users-container" id="deleted-users-container">
                <?php if (empty($deletedUsers)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-check fae-24"></i>
                    <h3>No Deleted Users</h3>
                    <p>There are no deleted user accounts from your faculty.</p>
                </div>
                <?php else: ?>
                <?php foreach ($deletedUsers as $user): ?>
                <div class="user-card deleted-user-card" data-role="<?= $user['role'] ?>" data-name="<?= strtolower($user['name']) ?>" data-date="<?= $user['deleted_date'] ?>">
                    <div class="user-header">
                        <div class="user-info">
                            <h4 class="user-name"><?= esc($user['name']) ?></h4>
                            <p class="user-email"><?= esc($user['email']) ?></p>
                            <p class="user-role"><?= ucfirst($user['role']) ?></p>
                        </div>
                        <div class="user-meta">
                            <span class="status-badge status-deleted">Deleted</span>
                        </div>
                    </div>
                    
                    <div class="user-details">
                        <div class="detail-item">
                            <i class="fas fa-university"></i>
                            <span><strong>Faculty:</strong> <?= esc($user['faculty_name']) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Year/Graduated:</strong> <?= esc($user['academic_year']) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-trash"></i>
                            <span><strong>Deleted:</strong> <?= date('M j, Y', strtotime($user['deleted_date'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="user-actions">
                        <button class="btn btn-success btn-sm recover-btn" data-user-id="<?= $user['user_id'] ?>" data-user-name="<?= esc($user['name']) ?>" data-role="<?= $user['role'] ?>">
                            <i class="fas fa-undo"></i>
                            Recover Account
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<!-- Recovery Confirmation Modal -->
<div id="recoveryModal" class="custom-modal fae-10">
    <div class="modal-content">
        <div class="modal-icon modal-icon-success">
            <i class="fas fa-undo"></i>
        </div>
        <h3 class="fae-25">Recover User Account</h3>
        <p id="recovery-message" class="fae-26">Are you sure you want to recover this account?</p>
        <div class="modal-buttons">
            <button onclick="confirmRecovery()" class="btn btn-success">
                <i class="fas fa-check"></i> Yes, Recover
            </button>
            <button onclick="closeRecoveryModal()" class="btn btn-outline">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </div>
</div>

<!-- Success/Error Alert Modal -->
<div id="alertModal" class="custom-modal fae-10">
    <div class="modal-content">
        <div class="modal-icon" id="alert-icon">
            <i class="fas fa-check"></i>
        </div>
        <h3 id="alert-title" class="fae-25">Success</h3>
        <p id="alert-message" class="fae-26"></p>
        <div class="modal-buttons">
            <button onclick="closeAlertModal()" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>



<script>
let selectedUserId = null;
let selectedUserRole = null;
let selectedUserName = '';

// Search functionality
document.getElementById('user-search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const roleFilter = document.getElementById('role-filter').value;
    filterUsers(searchTerm, roleFilter);
});

document.getElementById('role-filter').addEventListener('change', function() {
    const searchTerm = document.getElementById('user-search').value.toLowerCase();
    const roleFilter = this.value;
    filterUsers(searchTerm, roleFilter);
});

function filterUsers(searchTerm, roleFilter) {
    const userCards = document.querySelectorAll('.user-card');
    
    userCards.forEach(card => {
        const name = card.getAttribute('data-name');
        const date = card.getAttribute('data-date');
        const role = card.getAttribute('data-role');
        
        const matchesSearch = name.includes(searchTerm) || date.includes(searchTerm);
        const matchesRole = !roleFilter || role === roleFilter;
        
        if (matchesSearch && matchesRole) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Recover button click handlers
document.querySelectorAll('.recover-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        selectedUserId = this.getAttribute('data-user-id');
        selectedUserRole = this.getAttribute('data-role');
        selectedUserName = this.getAttribute('data-user-name');
        
        document.getElementById('recovery-message').textContent = 
            `Are you sure you want to recover the account for "${selectedUserName}"? This will restore their access to the system.`;
        
        document.getElementById('recoveryModal').style.display = 'flex';
    });
});

function closeRecoveryModal() {
    document.getElementById('recoveryModal').style.display = 'none';
    selectedUserId = null;
    selectedUserRole = null;
    selectedUserName = '';
}

async function confirmRecovery() {
    if (!selectedUserId || !selectedUserRole) return;
    
    try {
        const formData = new FormData();
        formData.append('action', 'recover');
        formData.append('user_id', selectedUserId);
        formData.append('role', selectedUserRole);
        
        const response = await fetch('<?=ROOT?>/admin/DeletedUsers', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        closeRecoveryModal();
        
        if (result.success) {
            showAlert('Success', result.message, 'success');
            // Reload page after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('Error', result.message, 'error');
        }
    } catch (error) {
        closeRecoveryModal();
        showAlert('Error', 'An error occurred while recovering the account.', 'error');
    }
}

function showAlert(title, message, type) {
    const alertIcon = document.getElementById('alert-icon');
    const alertTitle = document.getElementById('alert-title');
    const alertMessage = document.getElementById('alert-message');
    
    alertTitle.textContent = title;
    alertMessage.textContent = message;
    
    if (type === 'success') {
        alertIcon.className = 'modal-icon modal-icon-success';
        alertIcon.innerHTML = '<i class="fas fa-check"></i>';
    } else {
        alertIcon.className = 'modal-icon modal-icon-error';
        alertIcon.innerHTML = '<i class="fas fa-times"></i>';
    }
    
    document.getElementById('alertModal').style.display = 'flex';
}

function closeAlertModal() {
    document.getElementById('alertModal').style.display = 'none';
}
</script>
