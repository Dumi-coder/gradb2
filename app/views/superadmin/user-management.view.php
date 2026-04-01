<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- User Management Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Suspended Users</h2>
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
            
            <!-- Suspended User Search and Filters -->
            <div class="user-filters">
                <div class="search-box">
                    <input type="text" id="user-search" placeholder="Search suspended users by name, email, or ID..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-options">
                    <select id="role-filter" class="filter-select">
                        <option value="">All Roles</option>
                        <option value="student">Students</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>
            </div>
            
            <!-- Suspended Users List -->
            <div class="users-container">
                <?php if (!empty($userData['users'])): ?>
                <?php foreach ($userData['users'] as $user): ?>
                <div class="user-card" data-role="<?= esc($user['role']) ?>" data-name="<?= esc(strtolower($user['name'])) ?>" data-email="<?= esc(strtolower($user['email'])) ?>" data-membership-id="<?= esc(strtolower($user['membership_id'] ?? '')) ?>">
                    <div class="user-header">
                        <div class="user-info">
                            <h4 class="user-name"><?= esc($user['name']) ?></h4>
                            <p class="user-email"><?= esc($user['email']) ?></p>
                            <p class="user-role"><?= ucfirst($user['role']) ?><?php if (!empty($user['membership_id'])): ?> • <?= esc($user['membership_id']) ?><?php endif; ?></p>
                        </div>
                        <div class="user-meta">
                            <span class="status-badge status-suspended">Suspended</span>
                            <?php if (!empty($user['faculty_name'])): ?>
                            <span class="faculty-badge"><?= esc($user['faculty_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="user-actions">
                        <button class="btn btn-success btn-sm reactivate-btn" data-user-id="<?= $user['id'] ?>" data-user-name="<?= esc($user['name']) ?>">
                            <i class="fas fa-user-check"></i>
                            Reactivate User
                        </button>
                        <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/superadmin/userprofile?id=<?= (int)$user['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state" id="no-suspended-users" style="text-align: center; padding: 2.25rem; opacity: 0.8;">
                    <i class="fas fa-user-check" style="font-size: 42px; color: #10b981; margin-bottom: 0.75rem;"></i>
                    <h4 style="margin: 0 0 0.4rem;">No Suspended Users</h4>
                    <p style="margin: 0; color: #6b7280;">The suspended users list is empty.</p>
                </div>
                <?php endif; ?>

                <div class="empty-state" id="no-search-results" style="display:none; text-align: center; padding: 2rem; opacity: 0.8;">
                    <i class="fas fa-search" style="font-size: 36px; color: #6b7280; margin-bottom: 0.75rem;"></i>
                    <h4 style="margin: 0 0 0.4rem;">No Matching Users</h4>
                    <p style="margin: 0; color: #6b7280;">Try a different name, email, or ID.</p>
                </div>
            </div>

            <div id="reactivateConfirmModal" class="modal" style="display:none;">
                <div class="modal-content" style="max-width:460px; padding: 25px;">
                    <div class="modal-header" style="border-bottom:none; padding-bottom:0;">
                        <h2 class="modal-title">Reactivate User</h2>
                        <button class="modal-close" onclick="closeReactivateModal()"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body" style="padding-top: 0.5rem;">
                        <p id="reactivateConfirmText" style="margin:0; color:#4b5563;"></p>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeReactivateModal()">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmReactivateBtn">Reactivate User</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="dashboard-section" style="margin-top: 1.5rem;">
            <div class="section-header">
                <h2 class="section-title">Users You Can Suspend</h2>
            </div>

            <div class="user-filters">
                <div class="search-box">
                    <input type="text" id="active-user-search" placeholder="Search users by name, email, or ID..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-options">
                    <select id="active-role-filter" class="filter-select">
                        <option value="">All Roles</option>
                        <option value="student">Students</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>
            </div>

            <div class="users-container" id="active-users-container">
                <?php if (!empty($userData['available_users'])): ?>
                <?php foreach ($userData['available_users'] as $user): ?>
                <div class="user-card active-user-card" data-role="<?= esc($user['role']) ?>" data-name="<?= esc(strtolower($user['name'])) ?>" data-email="<?= esc(strtolower($user['email'])) ?>" data-membership-id="<?= esc(strtolower($user['membership_id'] ?? '')) ?>">
                    <div class="user-header">
                        <div class="user-info">
                            <h4 class="user-name"><?= esc($user['name']) ?></h4>
                            <p class="user-email"><?= esc($user['email']) ?></p>
                            <p class="user-role"><?= ucfirst($user['role']) ?><?php if (!empty($user['membership_id'])): ?> • <?= esc($user['membership_id']) ?><?php endif; ?></p>
                        </div>
                        <div class="user-meta">
                            <span class="status-badge status-active">Active</span>
                            <?php if (!empty($user['faculty_name'])): ?>
                            <span class="faculty-badge"><?= esc($user['faculty_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="user-actions">
                        <button class="btn btn-warning btn-sm suspend-btn" data-user-id="<?= (int)$user['id'] ?>" data-user-name="<?= esc($user['name']) ?>">
                            <i class="fas fa-user-slash"></i>
                            Suspend User
                        </button>
                        <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/superadmin/userprofile?id=<?= (int)$user['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state" id="no-available-users" style="text-align: center; padding: 2.25rem; opacity: 0.8;">
                    <i class="fas fa-check-circle" style="font-size: 42px; color: #10b981; margin-bottom: 0.75rem;"></i>
                    <h4 style="margin: 0 0 0.4rem;">No Users To Suspend</h4>
                    <p style="margin: 0; color: #6b7280;">All eligible users are currently suspended or unavailable.</p>
                </div>
                <?php endif; ?>

                <div class="empty-state" id="no-active-search-results" style="display:none; text-align: center; padding: 2rem; opacity: 0.8;">
                    <i class="fas fa-search" style="font-size: 36px; color: #6b7280; margin-bottom: 0.75rem;"></i>
                    <h4 style="margin: 0 0 0.4rem;">No Matching Users</h4>
                    <p style="margin: 0; color: #6b7280;">Try a different name, email, or ID.</p>
                </div>
            </div>

            <div id="suspendConfirmModal" class="modal" style="display:none;">
                <div class="modal-content" style="max-width:460px; padding: 25px;">
                    <div class="modal-header" style="border-bottom:none; padding-bottom:0;">
                        <h2 class="modal-title">Suspend User</h2>
                        <button class="modal-close" onclick="closeSuspendModal()"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body" style="padding-top: 0.5rem;">
                        <p id="suspendConfirmText" style="margin:0; color:#4b5563;"></p>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeSuspendModal()">Cancel</button>
                        <button type="button" class="btn btn-warning" id="confirmSuspendBtn">Suspend User</button>
                    </div>
                </div>
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

.faculty-badge {
    display: inline-block;
    background-color: #e0e7ff;
    color: #3730a3;
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

/* Button styles are now in buttons.css - removed to prevent override */

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

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal.show {
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-content {
    background-color: white;
    border-radius: 12px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #E5E7EB;
    background-color: #F9FAFB;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6B7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
}

.modal-close:hover {
    background-color: #E5E7EB;
    color: #374151;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding: 1rem 1.5rem 0;
    border-top: 1px solid #E5E7EB;
}

.action-message {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1200;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
}

.action-message.error {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}
</style>

<script>
let selectedReactivateUser = null;
let selectedSuspendUser = null;

document.addEventListener('DOMContentLoaded', function() {
    // Handle reactivate button clicks
    document.querySelectorAll('.reactivate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedReactivateUser = {
                userId: this.getAttribute('data-user-id'),
                userName: this.getAttribute('data-user-name'),
                button: this
            };
            openReactivateModal();
        });
    });

    document.getElementById('confirmReactivateBtn')?.addEventListener('click', function() {
        if (!selectedReactivateUser) return;

        const { userId, userName, button } = selectedReactivateUser;
        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reactivating...';
        button.disabled = true;

        const body = 'user_id=' + encodeURIComponent(userId);
        fetch('<?=ROOT?>/superadmin/usermanagement/reactivate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeReactivateModal();
                location.reload();
            } else {
                showActionMessage(data.message || 'Failed to reactivate user');
                button.innerHTML = original;
                button.disabled = false;
            }
        })
        .catch(() => {
            showActionMessage('An error occurred. Please try again.');
            button.innerHTML = original;
            button.disabled = false;
        });
    });

    document.querySelectorAll('.suspend-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedSuspendUser = {
                userId: this.getAttribute('data-user-id'),
                userName: this.getAttribute('data-user-name'),
                button: this
            };
            openSuspendModal();
        });
    });

    document.getElementById('confirmSuspendBtn')?.addEventListener('click', function() {
        if (!selectedSuspendUser) return;

        const { userId, button } = selectedSuspendUser;
        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suspending...';
        button.disabled = true;

        fetch('<?=ROOT?>/superadmin/usermanagement/suspend', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'user_id=' + encodeURIComponent(userId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeSuspendModal();
                location.reload();
            } else {
                showActionMessage(data.message || 'Failed to suspend user');
                button.innerHTML = original;
                button.disabled = false;
            }
        })
        .catch(() => {
            showActionMessage('An error occurred. Please try again.');
            button.innerHTML = original;
            button.disabled = false;
        });
    });

    const searchInput = document.getElementById('user-search');
    const roleFilter = document.getElementById('role-filter');
    const noSearchResults = document.getElementById('no-search-results');

    function applyFilters() {
        const searchTerm = (searchInput?.value || '').toLowerCase().trim();
        const selectedRole = roleFilter?.value || '';
        const userCards = document.querySelectorAll('.user-card');
        let visibleCount = 0;

        userCards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const email = card.getAttribute('data-email') || '';
            const membershipId = card.getAttribute('data-membership-id') || '';
            const role = card.getAttribute('data-role') || '';

            const matchesSearch =
                searchTerm === '' ||
                name.includes(searchTerm) ||
                email.includes(searchTerm) ||
                membershipId.includes(searchTerm);

            const matchesRole = selectedRole === '' || role === selectedRole;
            const shouldShow = matchesSearch && matchesRole;

            card.style.display = shouldShow ? 'block' : 'none';
            if (shouldShow) {
                visibleCount += 1;
            }
        });

        if (noSearchResults) {
            noSearchResults.style.display = (userCards.length > 0 && visibleCount === 0) ? 'block' : 'none';
        }
    }

    searchInput?.addEventListener('input', applyFilters);
    roleFilter?.addEventListener('change', applyFilters);
    applyFilters();

    const activeSearchInput = document.getElementById('active-user-search');
    const activeRoleFilter = document.getElementById('active-role-filter');
    const noActiveSearchResults = document.getElementById('no-active-search-results');

    function applyActiveFilters() {
        const searchTerm = (activeSearchInput?.value || '').toLowerCase().trim();
        const selectedRole = activeRoleFilter?.value || '';
        const userCards = document.querySelectorAll('.active-user-card');
        let visibleCount = 0;

        userCards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const email = card.getAttribute('data-email') || '';
            const membershipId = card.getAttribute('data-membership-id') || '';
            const role = card.getAttribute('data-role') || '';

            const matchesSearch =
                searchTerm === '' ||
                name.includes(searchTerm) ||
                email.includes(searchTerm) ||
                membershipId.includes(searchTerm);

            const matchesRole = selectedRole === '' || role === selectedRole;
            const shouldShow = matchesSearch && matchesRole;

            card.style.display = shouldShow ? 'block' : 'none';
            if (shouldShow) {
                visibleCount += 1;
            }
        });

        if (noActiveSearchResults) {
            noActiveSearchResults.style.display = (userCards.length > 0 && visibleCount === 0) ? 'block' : 'none';
        }
    }

    activeSearchInput?.addEventListener('input', applyActiveFilters);
    activeRoleFilter?.addEventListener('change', applyActiveFilters);
    applyActiveFilters();
});

function openReactivateModal() {
    const modal = document.getElementById('reactivateConfirmModal');
    const text = document.getElementById('reactivateConfirmText');
    if (selectedReactivateUser) {
        text.textContent = 'Are you sure you want to reactivate ' + selectedReactivateUser.userName + '? This will allow the user to log in again.';
    }
    modal.style.display = 'flex';
    modal.classList.add('show');
}

function closeReactivateModal() {
    const modal = document.getElementById('reactivateConfirmModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    selectedReactivateUser = null;
}

function openSuspendModal() {
    const modal = document.getElementById('suspendConfirmModal');
    const text = document.getElementById('suspendConfirmText');
    if (selectedSuspendUser) {
        text.textContent = 'Are you sure you want to suspend ' + selectedSuspendUser.userName + '? The user will not be able to log in.';
    }
    modal.style.display = 'flex';
    modal.classList.add('show');
}

function closeSuspendModal() {
    const modal = document.getElementById('suspendConfirmModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    selectedSuspendUser = null;
}

function showActionMessage(message) {
    const existing = document.querySelector('.action-message');
    if (existing) {
        existing.remove();
    }
    const el = document.createElement('div');
    el.className = 'action-message error';
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(() => {
        el.remove();
    }, 3000);
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('reactivateConfirmModal');
    const suspendModal = document.getElementById('suspendConfirmModal');
    if (event.target === modal) {
        closeReactivateModal();
    }
    if (event.target === suspendModal) {
        closeSuspendModal();
    }
});
</script>
