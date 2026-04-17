<?php
$page_title = 'Faculty Announcements';
$page_subtitle = 'Latest updates for your faculty and university';
require '../app/views/partials/student_header.php';
?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/faculty-announcements.css">

<div class="dashboard-container">
    <?php require '../app/views/partials/student_sidebar.php'; ?>

    <main class="main-content faculty-announcements-page">
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
            <div id="viewAnnouncementLoading" class="view-loading-state is-hidden">
                <div class="loading-spinner"></div>
                <p>Loading announcement...</p>
            </div>

            <div class="announcement-details modal-announcement-details">
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

            openViewAnnouncementModal();
            setViewAnnouncementLoading(true);

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
});

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

window.onclick = function(event) {
    const viewModal = document.getElementById('viewAnnouncementModal');
    if (event.target === viewModal) {
        closeViewAnnouncementModal();
    }
};
</script>

</body>
</html>
