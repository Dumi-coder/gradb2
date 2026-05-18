<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Faculty Announcements Section -->
        <section class="dashboard-section announcement-section faculty-section">
            <div class="section-header">
                <h2 class="section-title">Faculty Announcements</h2>
                <div class="section-sort">
                    <select id="faculty-sort" class="filter-select">
                        <option value="time">Time (Newest First)</option>
                        <option value="priority">Priority (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="create-announcement-section">
                <button class="btn btn-primary create-btn" data-announcement-type="0">
                    <i class="fas fa-plus"></i>
                    New Announcement
                </button>
            </div>
            
            <!-- Announcements List -->
            <div class="announcements-container">
                <?php if (!empty($announcementsData['announcements'])): ?>
                <?php foreach ($announcementsData['announcements'] as $announcement): ?>
                <div class="announcement-card <?= ($announcement['status'] ?? '') === 'published' ? '' : 'is-unpublished' ?>" data-priority="<?= $announcement['priority'] ?>" data-status="<?= $announcement['status'] ?>" data-created="<?= strtotime($announcement['created_date']) ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['author']) ?> (<?= esc($announcement['author_email']) ?>)</p>
                        </div>
                        <div class="announcement-meta">
                            <span class="priority-badge priority-<?= $announcement['priority'] ?>"><?= ucfirst($announcement['priority']) ?> Priority</span>
                        </div>
                    </div>
                    
                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>
                    
                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($announcement['created_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <span class="announcement-view-count"><?= $announcement['views'] ?></span></span>
                        </div>
                    </div>
                    
                    <div class="announcement-actions">
                        <?php if ($announcement['status'] === 'draft'): ?>
                        <button class="btn btn-success btn-sm publish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-paper-plane"></i>
                            Publish
                        </button>
                        <?php else: ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php endif; ?>

                        <button class="btn btn-primary btn-sm edit-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-announcement-state">
                    <i class="fas fa-bell-slash"></i>
                    <h4>No faculty announcements yet</h4>
                    <p>New faculty updates will appear here once they are published.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- University Announcements Section -->
        <section class="dashboard-section announcement-section university-section">
            <div class="section-header">
                <h2 class="section-title">University Announcements</h2>
                <div class="section-sort">
                    <select id="university-sort" class="filter-select">
                        <option value="time">Time (Newest First)</option>
                        <option value="priority">Priority (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="create-announcement-section">
                <button class="btn btn-primary create-btn" data-announcement-type="1">
                    <i class="fas fa-plus"></i>
                    New Announcement
                </button>
            </div>

            <div class="announcements-container">
                <?php if (!empty($universityAnnouncementsData['announcements'])): ?>
                <?php foreach ($universityAnnouncementsData['announcements'] as $announcement): ?>
                <div class="announcement-card <?= ($announcement['status'] ?? '') === 'published' ? '' : 'is-unpublished' ?>" data-priority="<?= $announcement['priority'] ?>" data-status="<?= $announcement['status'] ?>" data-created="<?= strtotime($announcement['created_date']) ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['author']) ?> (<?= esc($announcement['author_email']) ?>)</p>
                        </div>
                        <div class="announcement-meta">
                            <span class="priority-badge priority-<?= $announcement['priority'] ?>"><?= ucfirst($announcement['priority']) ?> Priority</span>
                        </div>
                    </div>

                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>

                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($announcement['created_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <span class="announcement-view-count"><?= $announcement['views'] ?></span></span>
                        </div>
                    </div>

                    <div class="announcement-actions">
                        <?php if ($announcement['status'] === 'draft'): ?>
                        <button class="btn btn-success btn-sm publish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-paper-plane"></i>
                            Publish
                        </button>
                        <?php else: ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php endif; ?>

                        <button class="btn btn-primary btn-sm edit-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-announcement-state">
                    <i class="fas fa-university"></i>
                    <h4>No university announcements yet</h4>
                    <p>University-wide notices will show up here when available.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Admin Announcements Section -->
        <section class="dashboard-section announcement-section admin-section">
            <div class="section-header">
                <h2 class="section-title">Admin Announcements</h2>
                <div class="section-sort">
                    <select id="admin-sort" class="filter-select">
                        <option value="time">Time (Newest First)</option>
                        <option value="priority">Priority (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="create-announcement-section">
                <button class="btn btn-primary create-btn" data-announcement-type="2">
                    <i class="fas fa-plus"></i>
                    New Announcement
                </button>
            </div>

            <div class="announcements-container">
                <?php if (!empty($adminAnnouncementsData['announcements'])): ?>
                <?php foreach ($adminAnnouncementsData['announcements'] as $announcement): ?>
                <div class="announcement-card <?= ($announcement['status'] ?? '') === 'published' ? '' : 'is-unpublished' ?>" data-priority="<?= $announcement['priority'] ?>" data-status="<?= $announcement['status'] ?>" data-created="<?= strtotime($announcement['created_date']) ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['author']) ?> (<?= esc($announcement['author_email']) ?>)</p>
                        </div>
                        <div class="announcement-meta">
                            <span class="priority-badge priority-<?= $announcement['priority'] ?>"><?= ucfirst($announcement['priority']) ?> Priority</span>
                        </div>
                    </div>

                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>

                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($announcement['created_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <span class="announcement-view-count"><?= $announcement['views'] ?></span></span>
                        </div>
                    </div>

                    <div class="announcement-actions">
                        <?php if ($announcement['status'] === 'draft'): ?>
                        <button class="btn btn-success btn-sm publish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-paper-plane"></i>
                            Publish
                        </button>
                        <?php else: ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php endif; ?>

                        <button class="btn btn-primary btn-sm edit-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-announcement-id="<?= $announcement['id'] ?>">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-announcement-state">
                    <i class="fas fa-user-shield"></i>
                    <h4>No admin announcements yet</h4>
                    <p>Admin-only notices will appear here when they are posted.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<!-- View Announcement Modal -->
<div id="viewAnnouncementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="viewAnnouncementTitle">Announcement</h2>
            <button class="modal-close" onclick="closeViewAnnouncementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="announcement-form">
            <div id="viewAnnouncementLoading" class="view-loading-state sae-27">
                <div class="loading-spinner"></div>
                <p>Loading announcement...</p>
            </div>

            <div class="announcement-details sae-29">
                <div class="detail-item">
                    <i class="fas fa-user"></i>
                    <span id="viewAnnouncementAuthor">-</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar"></i>
                    <span id="viewAnnouncementCreated">-</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-eye"></i>
                    <span id="viewAnnouncementViews">0</span>
                </div>
            </div>

            <div class="announcement-content">
                <p id="viewAnnouncementContent"></p>
            </div>
        </div>
    </div>
</div>

<!-- Create Announcement Modal -->
<div id="createAnnouncementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create New Announcement</h2>
            <button class="modal-close" onclick="closeCreateAnnouncementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="announcement-form" id="announcementForm">
            <div class="form-group">
                <label for="announcementTitle">Announcement Title *</label>
                <input type="text" id="announcementTitle" name="announcementTitle" placeholder="Enter announcement title" required>
            </div>

            <div class="form-group">
                <label for="announcementContent">Content *</label>
                <textarea id="announcementContent" name="announcementContent" rows="6" placeholder="Enter announcement content..." required></textarea>
            </div>

            <div class="form-group">
                <label for="announcementPriority">Priority *</label>
                <select id="announcementPriority" name="announcementPriority" required>
                    <option value="">Select priority</option>
                    <option value="2">High</option>
                    <option value="1">Medium</option>
                    <option value="0">Low</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeCreateAnnouncementModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    <span>Publish Announcement</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div id="editAnnouncementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Announcement</h2>
            <button class="modal-close" onclick="closeEditAnnouncementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="announcement-form" id="editAnnouncementForm">
            <input type="hidden" id="editAnnouncementId">

            <div class="form-group">
                <label for="editAnnouncementTitle">Announcement Title *</label>
                <input type="text" id="editAnnouncementTitle" placeholder="Enter announcement title" required>
            </div>

            <div class="form-group">
                <label for="editAnnouncementContent">Content *</label>
                <textarea id="editAnnouncementContent" rows="6" placeholder="Enter announcement content..." required></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeEditAnnouncementModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Confirm</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Announcement Confirmation Modal -->
<div id="deleteAnnouncementModal" class="modal">
    <div class="modal-content modal-content-sm">
        <div class="modal-header">
            <h2 class="modal-title">Confirm Delete</h2>
            <button class="modal-close" onclick="closeDeleteAnnouncementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="announcement-form">
            <p class="sae-30">Are you sure you want to delete this announcement?</p>
            <p class="sae-31">This will hide the announcement from the system.</p>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeDeleteAnnouncementModal()">
                    <span>Cancel</span>
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAnnouncementBtn">
                    <i class="fas fa-trash"></i>
                    <span>Confirm Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    window.pendingDeleteAnnouncementId = null;

    const postAction = (payload) => {
        const formData = new FormData();
        Object.keys(payload).forEach((key) => formData.append(key, payload[key]));

        return fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then((response) => response.json());
    };

    // Handle create announcement button
        let selectedAnnouncementType = 0;
    document.querySelectorAll('.create-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedAnnouncementType = parseInt(this.getAttribute('data-announcement-type') || '0', 10);
            openCreateAnnouncementModal();
        });
    });

    const createAnnouncementForm = document.getElementById('announcementForm');
    if (createAnnouncementForm) {
        createAnnouncementForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const title = document.getElementById('announcementTitle').value.trim();
            const content = document.getElementById('announcementContent').value.trim();
            const priority = document.getElementById('announcementPriority').value;

            if (!title || !content || priority === '') {
                alert('Title, content and priority are required');
                return;
            }

            postAction({
                action: 'add',
                title,
                content,
                priority,
                type: selectedAnnouncementType
            })
                .then((data) => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to publish announcement');
                    }
                    closeCreateAnnouncementModal();
                    window.location.reload();
                })
                .catch((err) => {
                    alert(err.message || 'Failed to publish announcement');
                });
        });
    }

    // Handle publish button clicks
    document.querySelectorAll('.publish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            postAction({ action: 'publish', announcement_id: announcementId })
                .then((data) => {
                    if (!data.success) throw new Error();
                    window.location.reload();
                })
                .catch(() => alert('Failed to publish announcement'));
        });
    });

    // Handle unpublish button clicks
    document.querySelectorAll('.unpublish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            postAction({ action: 'unpublish', announcement_id: announcementId })
                .then((data) => {
                    if (!data.success) throw new Error();
                    window.location.reload();
                })
                .catch(() => alert('Failed to unpublish announcement'));
        });
    });

    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            const card = this.closest('.announcement-card');
            const currentTitle = card?.querySelector('.announcement-title')?.textContent?.trim() || '';
            const currentContent = card?.querySelector('.announcement-content p')?.textContent?.trim() || '';

            document.getElementById('editAnnouncementId').value = announcementId;
            document.getElementById('editAnnouncementTitle').value = currentTitle;
            document.getElementById('editAnnouncementContent').value = currentContent;
            openEditAnnouncementModal();
        });
    });

    const editAnnouncementForm = document.getElementById('editAnnouncementForm');
    if (editAnnouncementForm) {
        editAnnouncementForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const announcementId = document.getElementById('editAnnouncementId').value;
            const title = document.getElementById('editAnnouncementTitle').value.trim();
            const content = document.getElementById('editAnnouncementContent').value.trim();

            if (!announcementId || !title || !content) {
                alert('Title and content are required');
                return;
            }

            postAction({ action: 'edit', announcement_id: announcementId, title, content })
                .then((data) => {
                    if (!data.success) throw new Error(data.message || 'Failed to edit');
                    closeEditAnnouncementModal();
                    window.location.reload();
                })
                .catch((err) => alert(err.message || 'Failed to edit announcement'));
        });
    }

    // Handle view button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            const card = this.closest('.announcement-card');

            openViewAnnouncementModal();
            setViewAnnouncementLoading(true);

            postAction({ action: 'view', announcement_id: announcementId })
                .then((data) => {
                    if (!data.success || !data.announcement) throw new Error(data.message || 'Failed to load announcement');

                    const item = data.announcement;
                    document.getElementById('viewAnnouncementTitle').textContent = item.title || 'Announcement';
                    document.getElementById('viewAnnouncementAuthor').textContent =
                        `By: ${item.creator_email || 'unknown@unknown.com'} (${item.creator_faculty_label || 'Unknown Faculty'})`;
                    document.getElementById('viewAnnouncementCreated').textContent =
                        `Created: ${new Date(item.created_at).toLocaleString()}`;
                    document.getElementById('viewAnnouncementViews').textContent = `Views: ${item.view_count || 0}`;
                    document.getElementById('viewAnnouncementContent').textContent = item.content || '';

                    if (card) {
                        card.setAttribute('data-views', String(item.view_count || 0));
                        const countEl = card.querySelector('.announcement-view-count');
                        if (countEl) countEl.textContent = String(item.view_count || 0);
                    }

                    setViewAnnouncementLoading(false);
                })
                .catch((err) => {
                    document.getElementById('viewAnnouncementTitle').textContent = 'Announcement';
                    document.getElementById('viewAnnouncementAuthor').textContent = '-';
                    document.getElementById('viewAnnouncementCreated').textContent = '-';
                    document.getElementById('viewAnnouncementViews').textContent = 'Views: 0';
                    document.getElementById('viewAnnouncementContent').textContent = err.message || 'Failed to load announcement';
                    setViewAnnouncementLoading(false);
                });
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            window.pendingDeleteAnnouncementId = this.getAttribute('data-announcement-id');
            openDeleteAnnouncementModal();
        });
    });

    const confirmDeleteBtn = document.getElementById('confirmDeleteAnnouncementBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (!window.pendingDeleteAnnouncementId) {
                closeDeleteAnnouncementModal();
                return;
            }

            postAction({ action: 'delete', announcement_id: window.pendingDeleteAnnouncementId })
                .then((data) => {
                    if (!data.success) throw new Error();
                    closeDeleteAnnouncementModal();
                    window.location.reload();
                })
                .catch(() => alert('Failed to delete announcement'));
        });
    }

    function attachSectionSorting(sortId, sectionElement) {
        const sortSelect = document.getElementById(sortId);

        if (!sortSelect || !sectionElement) {
            return;
        }

        const container = sectionElement.querySelector('.announcements-container');
        if (!container) {
            return;
        }

        const priorityRank = {
            high: 3,
            medium: 2,
            low: 1
        };

        const applySorting = function() {
            const cards = Array.from(container.querySelectorAll('.announcement-card'));

            cards.sort((a, b) => {
                if (sortSelect.value === 'priority') {
                    const aRank = priorityRank[a.getAttribute('data-priority')] || 0;
                    const bRank = priorityRank[b.getAttribute('data-priority')] || 0;
                    return bRank - aRank;
                }

                const aTime = parseInt(a.getAttribute('data-created') || '0', 10);
                const bTime = parseInt(b.getAttribute('data-created') || '0', 10);
                return bTime - aTime;
            });
            cards.forEach(card => container.appendChild(card));
        };

        sortSelect.addEventListener('change', applySorting);
        applySorting();
    }

    const facultySort = document.getElementById('faculty-sort');
    if (facultySort) {
        attachSectionSorting('faculty-sort', facultySort.closest('.announcement-section'));
    }

    const universitySort = document.getElementById('university-sort');
    if (universitySort) {
        attachSectionSorting('university-sort', universitySort.closest('.announcement-section'));
    }

    const adminSort = document.getElementById('admin-sort');
    if (adminSort) {
        attachSectionSorting('admin-sort', adminSort.closest('.announcement-section'));
    }
});

// Modal Functions
function openCreateAnnouncementModal() {
    document.getElementById('createAnnouncementModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeCreateAnnouncementModal() {
    document.getElementById('createAnnouncementModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('announcementForm').reset();
}

function openEditAnnouncementModal() {
    document.getElementById('editAnnouncementModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEditAnnouncementModal() {
    document.getElementById('editAnnouncementModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    const form = document.getElementById('editAnnouncementForm');
    if (form) form.reset();
}

function openDeleteAnnouncementModal() {
    document.getElementById('deleteAnnouncementModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDeleteAnnouncementModal() {
    document.getElementById('deleteAnnouncementModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    window.pendingDeleteAnnouncementId = null;
}

function openViewAnnouncementModal() {
    document.getElementById('viewAnnouncementModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function setViewAnnouncementLoading(isLoading) {
    const loadingEl = document.getElementById('viewAnnouncementLoading');
    const detailsEl = document.querySelector('#viewAnnouncementModal .announcement-details');
    const contentEl = document.querySelector('#viewAnnouncementModal .announcement-content');

    if (loadingEl) {
        loadingEl.style.display = isLoading ? 'flex' : 'none';
    }
    if (detailsEl) {
        detailsEl.style.display = isLoading ? 'none' : 'grid';
    }
    if (contentEl) {
        contentEl.style.display = isLoading ? 'none' : 'block';
    }
}

function closeViewAnnouncementModal() {
    document.getElementById('viewAnnouncementModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('createAnnouncementModal');
    const viewModal = document.getElementById('viewAnnouncementModal');
    const editModal = document.getElementById('editAnnouncementModal');
    const deleteModal = document.getElementById('deleteAnnouncementModal');
    if (event.target === modal) {
        closeCreateAnnouncementModal();
    }
    if (event.target === viewModal) {
        closeViewAnnouncementModal();
    }
    if (event.target === editModal) {
        closeEditAnnouncementModal();
    }
    if (event.target === deleteModal) {
        closeDeleteAnnouncementModal();
    }
}

</script>
