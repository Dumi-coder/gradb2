<?php require '../app/views/partials/admin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Faculty Announcements Section -->
        <section class="dashboard-section announcement-section">
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
                <button class="btn btn-primary create-btn">
                    <i class="fas fa-plus"></i>
                    New Announcement
                </button>
            </div>
            
            <!-- Announcements List -->
            <div class="announcements-container">
                <?php if (!empty($announcementsData['announcements'])): ?>
                <?php foreach ($announcementsData['announcements'] as $announcement): ?>
                <div class="announcement-card <?= (int)($announcement['is_published'] ?? 0) === 1 ? '' : 'is-unpublished' ?>" data-created="<?= strtotime($announcement['created_at']) ?>" data-priority="<?= (int)($announcement['priority'] ?? 0) ?>" data-views="<?= (int)($announcement['view_count'] ?? 0) ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['creator_email'] ?? 'unknown@unknown.com') ?> (<?= esc($announcement['creator_faculty_label'] ?? 'Unknown Faculty') ?>)</p>
                        </div>
                        <div class="announcement-meta">
                            <?php
                                $priorityValue = (int)($announcement['priority'] ?? 0);
                                $priorityClass = 'low';
                                $priorityLabel = 'Low';
                                if ($priorityValue === 2) {
                                    $priorityClass = 'high';
                                    $priorityLabel = 'High';
                                } elseif ($priorityValue === 1) {
                                    $priorityClass = 'medium';
                                    $priorityLabel = 'Medium';
                                }
                            ?>
                            <span class="priority-badge priority-<?= $priorityClass ?>"><?= $priorityLabel ?> Priority</span>
                        </div>
                    </div>
                    
                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>
                    
                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($announcement['created_at'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <span class="announcement-view-count"><?= (int)($announcement['view_count'] ?? 0) ?></span></span>
                        </div>
                    </div>

                    <div class="announcement-actions">
                        <?php if ((int)($announcement['is_published'] ?? 0) === 1): ?>
                        <button class="btn btn-warning btn-sm unpublish-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success btn-sm publish-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
                            <i class="fas fa-paper-plane"></i>
                            Publish
                        </button>
                        <?php endif; ?>

                        <button class="btn btn-primary btn-sm edit-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
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
        <section class="dashboard-section announcement-section">
            <div class="section-header">
                <h2 class="section-title">University Announcements</h2>
                <div class="section-sort">
                    <select id="university-sort" class="filter-select">
                        <option value="time">Time (Newest First)</option>
                        <option value="priority">Priority (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="announcements-container">
                <?php if (!empty($universityAnnouncementsData['announcements'])): ?>
                <?php foreach ($universityAnnouncementsData['announcements'] as $announcement): ?>
                <div class="announcement-card" data-created="<?= strtotime($announcement['created_at']) ?>" data-priority="<?= (int)($announcement['priority'] ?? 0) ?>" data-views="<?= (int)($announcement['view_count'] ?? 0) ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['creator_email'] ?? 'unknown@unknown.com') ?> (<?= esc($announcement['creator_faculty_label'] ?? 'Unknown Faculty') ?>)</p>
                        </div>
                        <div class="announcement-meta">
                            <?php
                                $priorityValue = (int)($announcement['priority'] ?? 0);
                                $priorityClass = 'low';
                                $priorityLabel = 'Low';
                                if ($priorityValue === 2) {
                                    $priorityClass = 'high';
                                    $priorityLabel = 'High';
                                } elseif ($priorityValue === 1) {
                                    $priorityClass = 'medium';
                                    $priorityLabel = 'Medium';
                                }
                            ?>
                            <span class="priority-badge priority-<?= $priorityClass ?>"><?= $priorityLabel ?> Priority</span>
                        </div>
                    </div>

                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>

                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($announcement['created_at'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <span class="announcement-view-count"><?= (int)($announcement['view_count'] ?? 0) ?></span></span>
                        </div>
                    </div>

                    <div class="announcement-actions">
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
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
        <section class="dashboard-section announcement-section">
            <div class="section-header">
                <h2 class="section-title">Admin Announcements</h2>
                <div class="section-sort">
                    <select id="admin-sort" class="filter-select">
                        <option value="time">Time (Newest First)</option>
                        <option value="priority">Priority (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="announcements-container">
                <?php if (!empty($adminAnnouncementsData['announcements'])): ?>
                <?php foreach ($adminAnnouncementsData['announcements'] as $announcement): ?>
                <div class="announcement-card" data-created="<?= strtotime($announcement['created_at']) ?>" data-priority="<?= (int)($announcement['priority'] ?? 0) ?>" data-views="<?= (int)($announcement['view_count'] ?? 0) ?>">
                    <div class="announcement-header">
                        <div class="announcement-info">
                            <h4 class="announcement-title"><?= esc($announcement['title']) ?></h4>
                            <p class="announcement-author">By: <?= esc($announcement['creator_email'] ?? 'unknown@unknown.com') ?> (<?= esc($announcement['creator_faculty_label'] ?? 'Unknown Faculty') ?>)</p>
                        </div>
                        <div class="announcement-meta">
                            <?php
                                $priorityValue = (int)($announcement['priority'] ?? 0);
                                $priorityClass = 'low';
                                $priorityLabel = 'Low';
                                if ($priorityValue === 2) {
                                    $priorityClass = 'high';
                                    $priorityLabel = 'High';
                                } elseif ($priorityValue === 1) {
                                    $priorityClass = 'medium';
                                    $priorityLabel = 'Medium';
                                }
                            ?>
                            <span class="priority-badge priority-<?= $priorityClass ?>"><?= $priorityLabel ?> Priority</span>
                        </div>
                    </div>

                    <div class="announcement-content">
                        <p><?= esc($announcement['content']) ?></p>
                    </div>

                    <div class="announcement-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($announcement['created_at'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-eye"></i>
                            <span><strong>Views:</strong> <span class="announcement-view-count"><?= (int)($announcement['view_count'] ?? 0) ?></span></span>
                        </div>
                    </div>

                    <div class="announcement-actions">
                        <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= (int)$announcement['id'] ?>">
                            <i class="fas fa-eye"></i>
                            View
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
            <div id="viewAnnouncementLoading" class="view-loading-state" style="display:none;">
                <div class="loading-spinner"></div>
                <p>Loading announcement...</p>
            </div>

            <div class="announcement-details" style="margin-bottom:1rem;">
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
            <p style="margin:0 0 1rem 0; color:#374151;">Are you sure you want to delete this announcement?</p>
            <p style="margin:0; color:#6B7280; font-size:0.9rem;">This will hide the announcement from the system.</p>

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

<style>
.create-announcement-section {
    margin-bottom: 2rem;
}

.create-btn {
    background-color: #000000;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.create-btn:hover {
    background-color: #333333;
    transform: translateY(-1px);
}

.announcements-filters {
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

.announcements-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.empty-announcement-state {
    background: #F9FAFB;
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    color: #4B5563;
}

.empty-announcement-state i {
    font-size: 1.75rem;
    color: #6B7280;
    margin-bottom: 0.75rem;
}

.empty-announcement-state h4 {
    margin: 0 0 0.4rem 0;
    color: #1F2937;
    font-size: 1.05rem;
}

.empty-announcement-state p {
    margin: 0;
}

.announcement-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.announcement-card.is-unpublished {
    background: #F3F4F6;
    border-color: #D1D5DB;
    opacity: 0.85;
}

.announcement-card.is-unpublished .announcement-title,
.announcement-card.is-unpublished .announcement-content p {
    color: #4B5563;
}

.announcement-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.announcement-title {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.announcement-author {
    margin: 0 0 0.25rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.announcement-audience {
    margin: 0;
    color: #0E2072;
    font-size: 0.9rem;
    font-weight: 500;
}

.announcement-meta {
    text-align: right;
}

.priority-badge {
    display: block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.priority-high {
    background-color: #FEE2E2;
    color: #DC2626;
}

.priority-medium {
    background-color: #FEF3C7;
    color: #D97706;
}

.priority-low {
    background-color: #D1FAE5;
    color: #065F46;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-published {
    background-color: #D1FAE5;
    color: #065F46;
}

.status-draft {
    background-color: #E5E7EB;
    color: #6B7280;
}

.announcement-content {
    margin-bottom: 1rem;
}

.announcement-content p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.announcement-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

.announcement-actions {
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

.btn-danger {
    background-color: #EF4444;
    color: white;
}

.btn-danger:hover {
    background-color: #DC2626;
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

.announcement-section .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.announcement-section .section-title {
    margin: 0;
    line-height: 1.2;
}

.announcement-section .section-sort {
    display: flex;
    align-items: center;
}

.announcement-section .section-sort .filter-select {
    margin: 0;
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

/* Modal Styles */
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

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    transition: all 0.3s ease;
}

.modal-close:hover {
    background-color: #E5E7EB;
    color: #374151;
}

.announcement-form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #0E2072;
    box-shadow: 0 0 0 3px rgba(14, 32, 114, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #E5E7EB;
}

.form-actions .btn {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

.view-loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 2rem 0.5rem;
    color: #4B5563;
}

.loading-spinner {
    width: 34px;
    height: 34px;
    border: 3px solid #E5E7EB;
    border-top-color: #0E2072;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

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
    document.querySelectorAll('.create-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            openCreateAnnouncementModal();
        });
    });

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

        const applySorting = function() {
            const cards = Array.from(container.querySelectorAll('.announcement-card'));

            cards.sort((a, b) => {
                if (sortSelect.value === 'priority') {
                    const aPriority = parseInt(a.getAttribute('data-priority') || '0', 10);
                    const bPriority = parseInt(b.getAttribute('data-priority') || '0', 10);
                    return bPriority - aPriority;
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

// Handle form submission
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('title', document.getElementById('announcementTitle').value.trim());
    formData.append('content', document.getElementById('announcementContent').value.trim());
    formData.append('priority', document.getElementById('announcementPriority').value);
    formData.append('type', '0');

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || 'Failed to save announcement');
                return;
            }

            closeCreateAnnouncementModal();
            window.location.reload();
        })
        .catch(() => {
            alert('Failed to save announcement');
        });
});
</script>
