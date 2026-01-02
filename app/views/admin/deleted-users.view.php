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
                    <i class="fas fa-user-check" style="font-size: 48px; color: #95a5a6; margin-bottom: 16px;"></i>
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
<div id="recoveryModal" class="custom-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-icon modal-icon-success">
            <i class="fas fa-undo"></i>
        </div>
        <h3 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 24px;">Recover User Account</h3>
        <p id="recovery-message" style="color: #7f8c8d; margin: 0 0 30px 0;">Are you sure you want to recover this account?</p>
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
<div id="alertModal" class="custom-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-icon" id="alert-icon">
            <i class="fas fa-check"></i>
        </div>
        <h3 id="alert-title" style="margin: 0 0 10px 0; color: #2c3e50; font-size: 24px;">Success</h3>
        <p id="alert-message" style="color: #7f8c8d; margin: 0 0 30px 0;"></p>
        <div class="modal-buttons">
            <button onclick="closeAlertModal()" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>

<style>
/* Enhanced Search and Filters Styling */
.user-filters-enhanced {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    animation: slideIn 0.5s ease;
}

.filters-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
}

.search-box-enhanced {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-input-enhanced {
    width: 100%;
    padding: 16px 20px 16px 50px;
    border: 2px solid #e8ecf1;
    border-radius: 12px;
    font-size: 15px;
    background: #f8f9fa;
    color: #2c3e50;
    transition: all 0.3s ease;
}

.search-input-enhanced:focus {
    outline: none;
    background: white;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.search-input-enhanced::placeholder {
    color: #95a5a6;
}

.search-icon-enhanced {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 18px;
    pointer-events: none;
    transition: all 0.3s ease;
}

.search-input-enhanced:focus ~ .search-icon-enhanced {
    color: #764ba2;
    transform: translateY(-50%) scale(1.1);
}

.filter-options-enhanced {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-item {
    position: relative;
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border: 2px solid #e8ecf1;
    border-radius: 12px;
    padding: 8px 16px;
    transition: all 0.3s ease;
}

.filter-item:hover {
    background: white;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.filter-icon {
    color: #667eea;
    margin-right: 10px;
    font-size: 16px;
}

.filter-select-enhanced {
    border: none;
    background: transparent;
    color: #2c3e50;
    font-size: 15px;
    font-weight: 500;
    padding: 8px 12px;
    cursor: pointer;
    outline: none;
    min-width: 150px;
}

.filter-select-enhanced option {
    background: white;
    color: #2c3e50;
    padding: 10px;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .filters-container {
        flex-direction: column;
    }
    
    .search-box-enhanced {
        min-width: 100%;
    }
    
    .filter-options-enhanced {
        width: 100%;
    }
    
    .filter-item {
        flex: 1;
        justify-content: center;
    }
}

.deleted-user-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-left: 4px solid #e74c3c;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.deleted-user-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #D1D5DB;
}

.user-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #F3F4F6;
}

.user-info h4 {
    margin: 0 0 0.5rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.user-info p {
    margin: 0.25rem 0;
    color: #6B7280;
    font-size: 0.95rem;
}

.user-meta {
    text-align: right;
}

.status-deleted {
    display: inline-block;
    padding: 0.35rem 0.85rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    background-color: #FEE2E2;
    color: #DC2626;
}

.user-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
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
    color: #e74c3c;
    width: 20px;
    text-align: center;
}

.user-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #7f8c8d;
}

.custom-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: slideDown 0.3s ease;
}

.modal-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 36px;
}

.modal-icon-success {
    background: #d4edda;
    color: #27ae60;
}

.modal-icon-error {
    background: #f8d7da;
    color: #e74c3c;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 30px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

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
