<?php require '../app/views/partials/superadmin_header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Forum Moderation Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Forum Moderation</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $forumData['stats']['total_posts'] ?></span>
                        <span class="stat-label">Total Posts</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $forumData['stats']['reported_posts'] ?></span>
                        <span class="stat-label">Reported Posts</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $forumData['stats']['active_users'] ?></span>
                        <span class="stat-label">Active Users</span>
                    </div>
                </div>
            </div>
            
            <!-- Reported Posts Section -->
            <div class="reported-posts-section">
                <h3 class="subsection-title">Reported Posts</h3>
                <div class="reported-posts-container">
                    <?php foreach ($forumData['reported_posts'] as $post): ?>
                    <div class="reported-post-card">
                        <div class="post-header">
                            <div class="post-info">
                                <h4 class="post-title"><?= esc($post['post_title']) ?></h4>
                                <p class="post-author">By: <?= esc($post['author']) ?> (<?= esc($post['author_email']) ?>)</p>
                            </div>
                            <div class="post-meta">
                                <span class="report-reason"><?= esc($post['report_reason']) ?></span>
                                <span class="status-badge status-pending">PENDING</span>
                            </div>
                        </div>
                        
                        <div class="post-content">
                            <p><?= esc($post['content']) ?></p>
                        </div>
                        
                        <div class="report-details">
                            <p><strong>Reported by:</strong> <?= esc($post['reported_by']) ?></p>
                            <p><strong>Reported on:</strong> <?= date('M j, Y', strtotime($post['reported_date'])) ?></p>
                        </div>
                        
                        <div class="post-actions">
                            <button class="btn btn-success btn-sm approve-btn" data-post-id="<?= $post['id'] ?>">
                                <i class="fas fa-check"></i>
                                Approve Post
                            </button>
                            <button class="btn btn-warning btn-sm hide-btn" data-post-id="<?= $post['id'] ?>">
                                <i class="fas fa-eye-slash"></i>
                                Hide Post
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-post-id="<?= $post['id'] ?>">
                                <i class="fas fa-trash"></i>
                                Delete Post
                            </button>
                            <button class="btn btn-outline btn-sm warn-btn" data-post-id="<?= $post['id'] ?>">
                                <i class="fas fa-exclamation-triangle"></i>
                                Warn User
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Recent Posts Section -->
            <div class="recent-posts-section">
                <div class="section-header-with-button">
                    <h3 class="subsection-title">Recent Forum Activity</h3>
                    <button class="btn btn-primary add-post-btn" onclick="openAddPostModal()">
                        <i class="fas fa-plus"></i>
                        Add New Forum Post
                    </button>
                </div>
                <div class="recent-posts-container">
                    <?php foreach ($forumData['recent_posts'] as $post): ?>
                    <div class="recent-post-card">
                        <div class="post-header">
                            <h4 class="post-title"><?= esc($post['post_title']) ?></h4>
                            <div class="post-meta">
                                <span class="post-author"><?= esc($post['author']) ?></span>
                                <span class="post-date"><?= date('M j, Y', strtotime($post['post_date'])) ?></span>
                            </div>
                        </div>
                        
                        <div class="post-content">
                            <p><?= esc($post['content']) ?></p>
                        </div>
                        
                        <div class="post-stats">
                            <span class="stat"><i class="fas fa-comments"></i> <?= $post['replies'] ?> replies</span>
                            <span class="stat"><i class="fas fa-eye"></i> <?= $post['views'] ?> views</span>
                        </div>
                        
                        <div class="post-actions">
                            <button class="btn btn-outline btn-sm view-btn" data-post-id="<?= $post['id'] ?>">
                                <i class="fas fa-eye"></i>
                                View Post
                            </button>
                            <button class="btn btn-outline btn-sm moderate-btn" data-post-id="<?= $post['id'] ?>">
                                <i class="fas fa-gavel"></i>
                                Moderate
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Add Forum Post Modal -->
<div id="addPostModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Forum Post</h2>
            <button class="modal-close" onclick="closeAddPostModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form class="forum-post-form" id="forumPostForm">
            <div class="form-group">
                <label for="postTitle">Post Title *</label>
                <input type="text" id="postTitle" name="postTitle" placeholder="Enter post title" required>
            </div>

            <div class="form-group">
                <label for="postCategory">Category *</label>
                <select id="postCategory" name="postCategory" required>
                    <option value="">Select a category</option>
                    <option value="general">General Discussion</option>
                    <option value="career">Career Advice</option>
                    <option value="academic">Academic Support</option>
                    <option value="networking">Networking</option>
                    <option value="events">Events & Activities</option>
                    <option value="resources">Resources & Opportunities</option>
                    <option value="alumni">Alumni Stories</option>
                </select>
            </div>

            <div class="form-group">
                <label for="postContent">Post Content *</label>
                <textarea id="postContent" name="postContent" rows="6" placeholder="Write your post content here..." required></textarea>
            </div>

            <div class="form-group">
                <label for="postTags">Tags (Optional)</label>
                <input type="text" id="postTags" name="postTags" placeholder="Enter tags separated by commas (e.g., career, networking, advice)">
            </div>

            <div class="form-group">
                <label for="postPriority">Priority</label>
                <select id="postPriority" name="postPriority">
                    <option value="normal">Normal</option>
                    <option value="important">Important</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="postVisibility">Visibility</label>
                <select id="postVisibility" name="postVisibility">
                    <option value="public">Public (All Users)</option>
                    <option value="students">Students Only</option>
                    <option value="alumni">Alumni Only</option>
                    <option value="faculty">Faculty Only</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeAddPostModal()">
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    <span>Publish Post</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.reported-posts-section, .recent-posts-section {
    margin-bottom: 2rem;
}

.subsection-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
}

.reported-posts-container, .recent-posts-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reported-post-card, .recent-post-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.reported-post-card:hover, .recent-post-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.post-title {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.post-author {
    margin: 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.post-meta {
    text-align: right;
}

.report-reason {
    display: block;
    background-color: #FEE2E2;
    color: #DC2626;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    background-color: #FEF3C7;
    color: #D97706;
}

.post-content {
    margin-bottom: 1rem;
}

.post-content p {
    margin: 0;
    color: #4B5563;
    line-height: 1.5;
}

.report-details {
    background-color: #F9FAFB;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.report-details p {
    margin: 0 0 0.25rem 0;
    color: #374151;
    font-size: 0.9rem;
}

.post-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #6B7280;
    font-size: 0.875rem;
}

.stat i {
    color: #0E2072;
}

.post-actions {
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

/* Section Header with Button */
.section-header-with-button {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.add-post-btn {
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

.add-post-btn:hover {
    background-color: #333333;
    transform: translateY(-1px);
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

.forum-post-form {
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

. {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            if (confirm('Are you sure you want to approve this post?')) {
                alert('Post approved successfully!');
                this.closest('.reported-post-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle hide button clicks
    document.querySelectorAll('.hide-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            if (confirm('Are you sure you want to hide this post?')) {
                alert('Post hidden successfully!');
                this.closest('.reported-post-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                alert('Post deleted successfully!');
                this.closest('.reported-post-card').remove();
            }
        });
    });

    // Handle warn user button clicks
    document.querySelectorAll('.warn-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            alert('Warn user functionality would be implemented here for post ID: ' + postId);
        });
    });

    // Handle view post button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            alert('View post functionality would be implemented here for post ID: ' + postId);
        });
    });

    // Handle moderate button clicks
    document.querySelectorAll('.moderate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            alert('Moderate post functionality would be implemented here for post ID: ' + postId);
        });
    });
});

// Modal Functions
function openAddPostModal() {
    document.getElementById('addPostModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddPostModal() {
    document.getElementById('addPostModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('forumPostForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addPostModal');
    if (event.target === modal) {
        closeAddPostModal();
    }
}

// Handle form submission
document.getElementById('forumPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const postData = {
        title: formData.get('postTitle'),
        category: formData.get('postCategory'),
        content: formData.get('postContent'),
        tags: formData.get('postTags'),
        priority: formData.get('postPriority'),
        visibility: formData.get('postVisibility')
    };
    
    // Here you would typically send the data to the server
    console.log('Forum Post Data:', postData);
    
    // Show success message
    alert('Forum post created successfully!');
    
    // Close modal
    closeAddPostModal();
    
    // Here you would typically refresh the forum posts list
    // or add the new post to the page dynamically
});
</script>
