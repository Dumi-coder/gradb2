<?php
$page_title = 'Faculty Announcements';
$page_subtitle = 'Latest updates for your faculty and university';
require '../app/views/partials/alumni_header.php';
?>

<div class="dashboard-container">
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

    <main class="main-content">
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

            <div class="announcements-container">
                <?php if (!empty($facultyAnnouncements)): ?>
                    <?php foreach ($facultyAnnouncements as $announcement): ?>
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
                        <article class="announcement-card" data-created="<?= strtotime($announcement['created_at'] ?? 'now') ?>" data-priority="<?= $priorityValue ?>">
                            <div class="announcement-header">
                                <div class="announcement-info">
                                    <h3 class="announcement-title"><?= esc($announcement['title']) ?></h3>
                                    <p class="announcement-author">By: <?= esc($announcement['creator_email'] ?? 'System') ?> (<?= esc($announcement['creator_faculty_label'] ?? 'University') ?>)</p>
                                </div>
                                <div class="announcement-meta">
                                    <span class="priority-badge priority-<?= $priorityClass ?>"><?= $priorityLabel ?> Priority</span>
                                </div>
                            </div>

                            <div class="announcement-content">
                                <p><?= esc($announcement['content']) ?></p>
                            </div>

                            <div class="announcement-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><strong>Created:</strong> <?= !empty($announcement['created_at']) ? date('M j, Y g:i A', strtotime($announcement['created_at'])) : '-' ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-eye"></i>
                                    <span><strong>Views:</strong> <span class="announcement-view-count"><?= (int)($announcement['view_count'] ?? 0) ?></span></span>
                                </div>
                            </div>

                            <div class="announcement-actions">
                                <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= (int)($announcement['id'] ?? 0) ?>">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                            </div>
                        </article>
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
                <?php if (!empty($universityAnnouncements)): ?>
                    <?php foreach ($universityAnnouncements as $announcement): ?>
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
                        <article class="announcement-card" data-created="<?= strtotime($announcement['created_at'] ?? 'now') ?>" data-priority="<?= $priorityValue ?>">
                            <div class="announcement-header">
                                <div class="announcement-info">
                                    <h3 class="announcement-title"><?= esc($announcement['title']) ?></h3>
                                    <p class="announcement-author">By: <?= esc($announcement['creator_email'] ?? 'System') ?> (<?= esc($announcement['creator_faculty_label'] ?? 'University') ?>)</p>
                                </div>
                                <div class="announcement-meta">
                                    <span class="priority-badge priority-<?= $priorityClass ?>"><?= $priorityLabel ?> Priority</span>
                                </div>
                            </div>

                            <div class="announcement-content">
                                <p><?= esc($announcement['content']) ?></p>
                            </div>

                            <div class="announcement-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><strong>Created:</strong> <?= !empty($announcement['created_at']) ? date('M j, Y g:i A', strtotime($announcement['created_at'])) : '-' ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-eye"></i>
                                    <span><strong>Views:</strong> <span class="announcement-view-count"><?= (int)($announcement['view_count'] ?? 0) ?></span></span>
                                </div>
                            </div>

                            <div class="announcement-actions">
                                <button class="btn btn-outline btn-sm view-btn" data-announcement-id="<?= (int)($announcement['id'] ?? 0) ?>">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-announcement-state">
                        <i class="fas fa-university"></i>
                        <h4>No university announcements yet</h4>
                        <p>University-wide notices will show up here once they are published.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<div id="viewAnnouncementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="viewAnnouncementTitle">Announcement</h2>
            <button class="modal-close" onclick="closeViewAnnouncementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="announcement-form">
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

<style>
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

.section-sort {
    display: flex;
    align-items: center;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 0.9rem;
    background-color: white;
}

.announcements-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.announcement-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
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
    margin: 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.announcement-meta {
    text-align: right;
}

.priority-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
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
    margin-top: 1rem;
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

.announcement-form {
    padding: 1.5rem;
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

@media (max-width: 768px) {
    .announcement-section .section-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .section-sort,
    .filter-select {
        width: 100%;
    }

    .announcement-header {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const announcementId = this.getAttribute('data-announcement-id');
            const card = this.closest('.announcement-card');

            postAction({ action: 'view', announcement_id: announcementId })
                .then((data) => {
                    if (!data.success || !data.announcement) {
                        throw new Error(data.message || 'Failed to load announcement');
                    }

                    const item = data.announcement;
                    document.getElementById('viewAnnouncementTitle').textContent = item.title || 'Announcement';
                    document.getElementById('viewAnnouncementAuthor').textContent =
                        `By: ${item.creator_email || 'unknown@unknown.com'} (${item.creator_faculty_label || 'Unknown Faculty'})`;
                    document.getElementById('viewAnnouncementCreated').textContent =
                        `Created: ${new Date(item.created_at).toLocaleString()}`;
                    document.getElementById('viewAnnouncementViews').textContent = `Views: ${item.view_count || 0}`;
                    document.getElementById('viewAnnouncementContent').textContent = item.content || '';

                    if (card) {
                        const countEl = card.querySelector('.announcement-view-count');
                        if (countEl) {
                            countEl.textContent = String(item.view_count || 0);
                        }
                    }

                    openViewAnnouncementModal();
                })
                .catch((err) => alert(err.message || 'Failed to load announcement'));
        });
    });
});

function openViewAnnouncementModal() {
    document.getElementById('viewAnnouncementModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeViewAnnouncementModal() {
    document.getElementById('viewAnnouncementModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    const viewModal = document.getElementById('viewAnnouncementModal');
    if (event.target === viewModal) {
        closeViewAnnouncementModal();
    }
};
</script>

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
