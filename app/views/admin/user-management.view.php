<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
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
                        <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/admin/userprofile?id=<?= (int)$user['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state fae-69" id="no-suspended-users">
                    <i class="fas fa-user-check fae-70"></i>
                    <h4 class="fae-71">No Suspended Users</h4>
                    <p class="fae-72">The suspended users list is empty for your faculty.</p>
                </div>
                <?php endif; ?>

                <div class="empty-state fae-73" id="no-search-results">
                    <i class="fas fa-search fae-74"></i>
                    <h4 class="fae-71">No Matching Users</h4>
                    <p class="fae-72">Try a different name, email, or ID.</p>
                </div>
            </div>

            <div id="reactivateConfirmModal" class="modal fae-27">
                <div class="modal-content fae-75">
                    <div class="modal-header fae-76">
                        <h2 class="modal-title">Reactivate User</h2>
                        <button class="modal-close" onclick="closeReactivateModal()"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body fae-67">
                        <p id="reactivateConfirmText" class="fae-77"></p>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeReactivateModal()">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmReactivateBtn">Reactivate User</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="dashboard-section fae-78">
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
                        <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/admin/userprofile?id=<?= (int)$user['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View Profile
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state fae-69" id="no-available-users">
                    <i class="fas fa-check-circle fae-70"></i>
                    <h4 class="fae-71">No Users To Suspend</h4>
                    <p class="fae-72">All eligible users in your faculty are currently suspended or unavailable.</p>
                </div>
                <?php endif; ?>

                <div class="empty-state fae-73" id="no-active-search-results">
                    <i class="fas fa-search fae-74"></i>
                    <h4 class="fae-71">No Matching Users</h4>
                    <p class="fae-72">Try a different name, email, or ID.</p>
                </div>
            </div>

            <div id="suspendConfirmModal" class="modal fae-27">
                <div class="modal-content fae-75">
                    <div class="modal-header fae-76">
                        <h2 class="modal-title">Suspend User</h2>
                        <button class="modal-close" onclick="closeSuspendModal()"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body fae-67">
                        <p id="suspendConfirmText" class="fae-77"></p>
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
        fetch('<?=ROOT?>/admin/usermanagement/reactivate', {
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

        fetch('<?=ROOT?>/admin/usermanagement/suspend', {
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
