<?php require '../app/views/partials/alumni_header.php'; ?>
<?php 
$current_page = 'forum';
require '../app/views/partials/alumni_sidebar.php'; 
?>
<!-- Main Content Area -->
<main class="main-content">
        <div class="content-wrapper">
            <!-- Header -->
            <header class="dashboard-header">
                <h1 class="dashboard-title">Discussion Forums</h1>
                <p class="dashboard-subtitle">Engage with fellow alumni in forums — ask questions, share experiences, and provide mentorship.</p>
            </header>

            <!-- Forum Actions Section -->
            <section class="forum-actions">
                <div class="action-buttons">
                    <button class="btn btn-primary btn-fixed-discussion" onclick="openNewDiscussionModal()">
                        <svg class="btn-icon" viewBox="0 0 24 24">
                            <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"/>
                        </svg>
                        Start New Discussion
                    </button>
                    <div class="forum-stats">
                        <span class="stat-item">
                            <strong>47</strong> Active Discussions
                        </span>
                        <span class="stat-item">
                            <strong>324</strong> Total Posts
                        </span>
                    </div>
                </div>
            </section>

            <!-- Forum Topics Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">Forum Topics</h2>
                    <div class="forum-filters">
                        <select class="filter-select">
                            <option value="all">All Categories</option>
                            <option value="career">Career Advice</option>
                            <option value="networking">Networking</option>
                            <option value="mentorship">Mentorship</option>
                            <option value="general">General Discussion</option>
                        </select>
                        <select class="sort-select">
                            <option value="recent">Most Recent</option>
                            <option value="popular">Most Popular</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                </div>

                <!-- Forum Cards Grid -->
                <div class="mentorship-grid">
                    <!-- Forum Card 1 - Hot Topic -->
                    <div class="mentorship-card" onclick="openThread(1)">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Career Transition Tips: From Engineering to Product Management</h3>
                                <p class="request-type">Created by Sarah Martinez</p>
                            </div>
                            <span class="card-badge urgent">Hot Topic</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Looking for advice on transitioning from software engineering to product management roles. What skills should I focus on developing? Any recommended courses or certifications?
                            </p>
                            <div class="thread-tags">
                                <span class="tag career">Career</span>
                                <span class="tag hot">Hot</span>
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Views: <strong>156</strong></div>
                                <div class="aid-type">Last Activity: <strong>2 hours ago</strong></div>
                            </div>
                        </div>
                        <div class="card-actions" style="justify-content: center;">
                            <button class="btn btn-primary">Join Discussion</button>
                        </div>
                    </div>

                    <!-- Forum Card 2 -->
                    <div class="mentorship-card" onclick="openThread(2)">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Networking Events: Best Practices for Alumni Connections</h3>
                                <p class="request-type">Created by John Davis</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                What are your go-to strategies for meaningful networking at alumni events? Share your experiences and tips for building lasting professional relationships.
                            </p>
                            <div class="thread-tags">
                                <span class="tag networking">Networking</span>
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Views: <strong>89</strong></div>
                                <div class="aid-type">Last Activity: <strong>5 hours ago</strong></div>
                            </div>
                        </div>
                        <div class="card-actions" style="justify-content: center;">
                            <button class="btn btn-primary">Join Discussion</button>
                        </div>
                    </div>

                    <!-- Forum Card 3 -->
                    <div class="mentorship-card" onclick="openThread(3)">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Mentoring New Graduates: Effective Strategies</h3>
                                <p class="request-type">Created by Alex Rodriguez</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Share your experiences and tips for mentoring recent graduates in your field. What approaches have worked best for you in guiding new professionals?
                            </p>
                            <div class="thread-tags">
                                <span class="tag mentorship">Mentorship</span>
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Views: <strong>134</strong></div>
                                <div class="aid-type">Last Activity: <strong>1 day ago</strong></div>
                            </div>
                        </div>
                        <div class="card-actions" style="justify-content: center;">
                            <button class="btn btn-primary">Join Discussion</button>
                        </div>
                    </div>

                    <!-- Forum Card 4 - Trending -->
                    <div class="mentorship-card" onclick="openThread(4)">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Industry Trends: AI and Machine Learning Impact</h3>
                                <p class="request-type">Created by Rachel Patel</p>
                            </div>
                            <span class="card-badge pending">Trending</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                Discussion about how AI/ML is reshaping different industries and career paths. How are you adapting to these technological changes in your profession?
                            </p>
                            <div class="thread-tags">
                                <span class="tag general">General</span>
                                <span class="tag trending">Trending</span>
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Views: <strong>198</strong></div>
                                <div class="aid-type">Last Activity: <strong>2 days ago</strong></div>
                            </div>
                        </div>
                        <div class="card-actions" style="justify-content: center;">
                            <button class="btn btn-primary">Join Discussion</button>
                        </div>
                    </div>

                    <!-- Forum Card 5 -->
                    <div class="mentorship-card" onclick="openThread(5)">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Work-Life Balance in Tech: Your Strategies</h3>
                                <p class="request-type">Created by Mark Thompson</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                How do you maintain a healthy work-life balance in demanding tech roles? Share your strategies and tips for managing stress and personal time.
                            </p>
                            <div class="thread-tags">
                                <span class="tag career">Career</span>
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Views: <strong>92</strong></div>
                                <div class="aid-type">Last Activity: <strong>3 days ago</strong></div>
                            </div>
                        </div>
                        <div class="card-actions" style="justify-content: center;">
                            <button class="btn btn-primary">Join Discussion</button>
                        </div>
                    </div>

                    <!-- Forum Card 6 -->
                    <div class="mentorship-card" onclick="openThread(6)">
                        <div class="card-header">
                            <div class="student-info">
                                <h3 class="card-title">Remote Work Best Practices</h3>
                                <p class="request-type">Created by Jennifer Lee</p>
                            </div>
                            <span class="card-badge active">Active</span>
                        </div>
                        <div class="card-content">
                            <p class="card-description">
                                What are your best practices for remote work productivity? Looking for tips on home office setup, communication tools, and maintaining team collaboration.
                            </p>
                            <div class="thread-tags">
                                <span class="tag career">Career</span>
                                <span class="tag general">General</span>
                            </div>
                            <div class="aid-details">
                                <div class="aid-amount">Views: <strong>67</strong></div>
                                <div class="aid-type">Last Activity: <strong>4 days ago</strong></div>
                            </div>
                        </div>
                        <div class="card-actions" style="justify-content: center;">
                            <button class="btn btn-primary">Join Discussion</button>
                        </div>
                    </div>
                </div>

                <!-- View All Link -->
                <div style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-text">View All Forum Topics</button>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- New Discussion Modal -->
<div id="newDiscussionModal" class="modal-overlay" onclick="closeNewDiscussionModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Start New Discussion</h3>
            <button class="modal-close" onclick="closeNewDiscussionModal()">&times;</button>
        </div>
        <form class="new-discussion-form" onsubmit="submitNewDiscussion(event)">
            <div class="form-group">
                <label for="discussionTitle">Discussion Title</label>
                <input type="text" id="discussionTitle" name="title" placeholder="Enter discussion title..." required>
            </div>
            <div class="form-group">
                <label for="discussionCategory">Category</label>
                <select id="discussionCategory" name="category" required>
                    <option value="">Select a category</option>
                    <option value="career">Career Advice</option>
                    <option value="networking">Networking</option>
                    <option value="mentorship">Mentorship</option>
                    <option value="general">General Discussion</option>
                </select>
            </div>
            <div class="form-group">
                <label for="discussionDescription">Description</label>
                <textarea id="discussionDescription" name="description" placeholder="Describe your discussion topic in detail..." rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary btn-fixed-discussion" onclick="closeNewDiscussionModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-fixed-discussion">Create Discussion</button>
            </div>
        </form>
    </div>
</div>

<!-- Thread View Modal -->
<div id="threadModal" class="modal-overlay" onclick="closeThreadModal()">
    <div class="modal-content thread-modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="thread-header-info">
                <h3 id="threadTitle">Loading...</h3>
                <div class="thread-meta">
                    <span id="threadAuthor">Loading...</span>
                    <span id="threadDate">Loading...</span>
                    <div class="thread-stats">
                        <span id="threadViews">0 views</span>
                        <span id="threadReplies">0 replies</span>
                    </div>
                </div>
            </div>
            <button class="modal-close" onclick="closeThreadModal()">&times;</button>
        </div>
        
        <div class="thread-content">
            <!-- Original Post -->
            <div class="original-post">
                <div class="post-content">
                    <p id="threadDescription">Loading thread content...</p>
                </div>
            </div>

            <!-- Replies Section -->
            <div class="replies-section">
                <h4>Replies (<span id="replyCount">0</span>)</h4>
                <div id="repliesList" class="replies-list">
                    <!-- Replies will be loaded here -->
                </div>
            </div>

            <!-- Reply Form -->
            <div class="reply-form-section">
                <h4>Write a Reply</h4>
                <form class="reply-form" onsubmit="submitReply(event)">
                    <div class="form-group">
                        <textarea id="replyText" name="reply" placeholder="Write your reply..." rows="4" required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Post Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CSS Files -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni_mentorship.css">

<!-- Forum Specific CSS -->
<style>
/* Forum Actions Section */
.forum-actions {
    margin-bottom: 2rem;
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.forum-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    color: #64748b;
    font-size: 0.875rem;
}

.stat-item strong {
    color: #1e293b;
    font-weight: 600;
}

/* Forum Filters */
.forum-filters {
    display: flex;
    gap: 1rem;
}

.filter-select, .sort-select {
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background: white;
}

/* Thread Tags */
.thread-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.tag {
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.tag.career {
    background-color: #dbeafe;
    color: #1e40af;
}

.tag.networking {
    background-color: #f3e8ff;
    color: #7c3aed;
}

.tag.mentorship {
    background-color: #dcfce7;
    color: #166534;
}

.tag.general {
    background-color: #f1f5f9;
    color: #475569;
}

.tag.hot {
    background-color: #fef2f2;
    color: #dc2626;
}

.tag.trending {
    background-color: #fef3c7;
    color: #92400e;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-content.thread-modal {
    max-width: 800px;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.modal-header h3 {
    margin: 0;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #64748b;
    padding: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    color: #374151;
}

/* Form Styles */
.new-discussion-form,
.reply-form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Thread Modal Specific */
.thread-header-info h3 {
    margin-bottom: 0.5rem;
}

.thread-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #64748b;
    font-size: 0.875rem;
}

.thread-stats {
    display: flex;
    gap: 1rem;
}

.thread-content {
    padding: 1.5rem;
}

.original-post {
    background: #f8fafc;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #2563eb;
}

.replies-section {
    margin-bottom: 2rem;
}

.replies-section h4 {
    margin-bottom: 1rem;
    color: #374151;
}

.replies-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reply-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 1rem;
}

.reply-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.reply-author {
    font-weight: 500;
    color: #374151;
}

.reply-date {
    color: #64748b;
    font-size: 0.75rem;
}

.reply-content {
    color: #475569;
    line-height: 1.6;
}

.reply-form-section {
    border-top: 1px solid #e2e8f0;
    padding-top: 1.5rem;
}

.reply-form-section h4 {
    margin-bottom: 1rem;
    color: #374151;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .forum-stats {
        justify-content: center;
    }
    
    .forum-filters {
        flex-wrap: wrap;
    }
}

@media (max-width: 768px) {
    .thread-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<!-- JavaScript -->
<script src="<?=ROOT?>/assets/js/dashboard.js"></script>

<!-- Forum Specific JavaScript -->
<script>
// Sample forum data
const forumData = {
    1: {
        title: "Career Transition Tips: From Engineering to Product Management",
        author: "Sarah Martinez",
        date: "September 1, 2025",
        description: "I've been working as a software engineer for 5 years and I'm interested in transitioning to product management. I'd love to hear from alumni who have made similar transitions. What skills should I focus on developing? Are there specific courses or certifications that helped you? How did you position yourself for PM roles during the application process?",
        views: 156,
        replies: [
            {
                author: "Michael Chen",
                date: "2 hours ago",
                content: "Great question, Sarah! I made a similar transition 3 years ago. The key is to start thinking like a PM while you're still in engineering. I recommend reading 'Inspired' by Marty Cagan and taking a product management course on Coursera. Also, try to get involved in product decisions at your current job."
            },
            {
                author: "Lisa Wang",
                date: "4 hours ago",
                content: "I agree with Michael. Additionally, I found it helpful to do informational interviews with PMs at different companies. It gave me great insights into the day-to-day responsibilities and helped me tailor my applications. Happy to connect if you'd like to chat more!"
            },
            {
                author: "David Kim",
                date: "6 hours ago",
                content: "One thing that really helped me was starting a side project where I acted as the product manager. It gave me concrete examples to discuss in interviews and showed my genuine interest in the field."
            }
        ]
    },
    2: {
        title: "Networking Events: Best Practices for Alumni Connections",
        author: "John Davis", 
        date: "August 30, 2025",
        description: "Alumni networking events can be overwhelming, especially for introverts like myself. What are your go-to strategies for making meaningful connections at these events? Any tips for following up afterwards?",
        views: 89,
        replies: [
            {
                author: "Lisa Wang",
                date: "5 hours ago",
                content: "I used to struggle with networking too! My advice: set a small goal like having 3 meaningful conversations rather than collecting 20 business cards. Quality over quantity always wins."
            },
            {
                author: "Alex Rodriguez",
                date: "1 day ago",
                content: "LinkedIn follow-ups within 24-48 hours work well for me. Reference something specific from your conversation to help them remember you."
            }
        ]
    },
    3: {
        title: "Mentoring New Graduates: Effective Strategies",
        author: "Alex Rodriguez",
        date: "August 29, 2025", 
        description: "I've recently started mentoring some new graduates and I want to make sure I'm providing value. What strategies have worked well for experienced alumni when mentoring newcomers to the field?",
        views: 134,
        replies: [
            {
                author: "Emily Johnson",
                date: "1 day ago",
                content: "Setting clear expectations and goals at the beginning really helps. I also schedule regular check-ins and encourage my mentees to come prepared with specific questions."
            },
            {
                author: "Rachel Patel",
                date: "1 day ago", 
                content: "I like to share real examples from my own career journey - both successes and failures. It makes the advice more relatable and actionable."
            }
        ]
    }
};

// Modal functions
function openNewDiscussionModal() {
    document.getElementById('newDiscussionModal').classList.add('active');
}

function closeNewDiscussionModal() {
    document.getElementById('newDiscussionModal').classList.remove('active');
    // Reset form
    document.querySelector('.new-discussion-form').reset();
}

function openThread(threadId) {
    const thread = forumData[threadId];
    if (!thread) return;
    
    // Populate thread modal
    document.getElementById('threadTitle').textContent = thread.title;
    document.getElementById('threadAuthor').textContent = `By ${thread.author}`;
    document.getElementById('threadDate').textContent = thread.date;
    document.getElementById('threadDescription').textContent = thread.description;
    document.getElementById('threadViews').textContent = `${thread.views} views`;
    document.getElementById('threadReplies').textContent = `${thread.replies.length} replies`;
    document.getElementById('replyCount').textContent = thread.replies.length;
    
    // Populate replies
    const repliesList = document.getElementById('repliesList');
    repliesList.innerHTML = '';
    
    thread.replies.forEach(reply => {
        const replyElement = document.createElement('div');
        replyElement.className = 'reply-item';
        replyElement.innerHTML = `
            <div class="reply-header">
                <div class="user-avatar">${reply.author.split(' ').map(n => n[0]).join('')}</div>
                <span class="reply-author">${reply.author}</span>
                <span class="reply-date">${reply.date}</span>
            </div>
            <div class="reply-content">${reply.content}</div>
        `;
        repliesList.appendChild(replyElement);
    });
    
    // Show modal
    document.getElementById('threadModal').classList.add('active');
}

function closeThreadModal() {
    document.getElementById('threadModal').classList.remove('active');
}

function submitNewDiscussion(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const title = formData.get('title');
    const category = formData.get('category');
    const description = formData.get('description');
    
    // Here you would normally send the data to your backend
    console.log('New discussion:', { title, category, description });
    
    // Show success message and close modal
    alert('Discussion created successfully!');
    closeNewDiscussionModal();
}

function submitReply(event) {
    event.preventDefault();
    
    const replyText = document.getElementById('replyText').value;
    
    // Here you would normally send the reply to your backend
    console.log('New reply:', replyText);
    
    // Add reply to current thread (for demo)
    const repliesList = document.getElementById('repliesList');
    const replyElement = document.createElement('div');
    replyElement.className = 'reply-item';
    replyElement.innerHTML = `
        <div class="reply-header">
            <div class="user-avatar">YN</div>
            <span class="reply-author">Your Name</span>
            <span class="reply-date">Just now</span>
        </div>
        <div class="reply-content">${replyText}</div>
    `;
    repliesList.appendChild(replyElement);
    
    // Update reply count
    const currentCount = parseInt(document.getElementById('replyCount').textContent);
    document.getElementById('replyCount').textContent = currentCount + 1;
    document.getElementById('threadReplies').textContent = `${currentCount + 1} replies`;
    
    // Clear form
    document.getElementById('replyText').value = '';
    
    // Show success message
    alert('Reply posted successfully!');
}

// Ensure sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle && sidebar && mainContent) {
        sidebarToggle.addEventListener('click', function() {
            // DISABLED: Sidebar toggle functionality removed
            // sidebar.classList.toggle('collapsed');
            // The sidebar now stays fixed at 280px width - no toggling
            console.log('Sidebar toggle disabled - layout stays fixed');
        });
        
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeNewDiscussionModal();
            closeThreadModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeNewDiscussionModal();
            closeThreadModal();
        }
    });
});
</script>
