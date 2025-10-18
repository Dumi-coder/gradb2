<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Discussion Forum - GradBridge</title>
    <meta name="description" content="Engage with fellow alumni in forums — ask questions, share experiences, and provide mentorship." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/alumni-dashboard.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css">
    
    
  </head>

  <body class="alumni-dashboard">
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Discussion Forums</h1>
            <p class="header-subtitle">Engage with fellow alumni in forums — ask questions, share experiences, and provide mentorship.</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/alumni/logout" class="btn btn-primary logout-btn">Logout</a>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Quick Actions Section -->
        <section class="dashboard-section quick-actions-section">
          <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
          </div>
          
           <div class="quick-actions-container">
             <div class="section-actions">
               <button class="btn btn-outline btn-sm" onclick="openQuickTagsModal()">
                 <i class="fas fa-hashtag"></i>
                 <span>Quick Tags</span>
               </button>
               <button class="btn btn-primary btn-md" onclick="openNewPostModal()">
                 <i class="fas fa-plus"></i>
                 <span>New Post</span>
               </button>
             </div>
           </div>
        </section>

        <!-- Forum Statistics Section -->
        <section class="dashboard-section statistics-section">
          <div class="section-header">
            <h2 class="section-title">Forum Statistics</h2>
          </div>
          
          <div class="statistics-grid">
            <div class="stat-card">
              <div class="stat-content">
                <div class="stat-label">Active Discussions:</div>
                <div class="stat-number"><?= $forumData['statistics']['active_discussions'] ?></div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-content">
                <div class="stat-label">Total Posts:</div>
                <div class="stat-number"><?= $forumData['statistics']['total_posts'] ?></div>
              </div>
            </div>
          </div>
        </section>

        <!-- Forum Topics Section -->
        <section class="dashboard-section forum-topics-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="section-title">Forum Topics</h2>
              <div class="filter-controls">
                <select class="filter-select">
                  <option>All Categories</option>
                  <option>Career</option>
                  <option>Mentorship</option>
                  <option>General</option>
                </select>
                <select class="filter-select">
                  <option>Most Recent</option>
                  <option>Most Popular</option>
                  <option>Most Active</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="topics-container">
            <?php foreach ($forumData['topics'] as $topic): ?>
            <div class="topic-card">
              <div class="topic-header">
                <div class="topic-info">
                  <h3 class="topic-title"><?= esc($topic['title']) ?></h3>
                  <p class="topic-creator">Created by <?= esc($topic['creator']) ?></p>
                </div>
                <span class="status-badge status-<?= $topic['status'] ?>">
                  <?= strtoupper($topic['status']) ?>
                </span>
              </div>
              
              <div class="topic-description">
                <p><?= esc($topic['description']) ?></p>
              </div>
              
              <?php if (isset($topic['tags'])): ?>
              <div class="topic-tags">
                <?php foreach ($topic['tags'] as $tag): ?>
                <span class="topic-tag tag-<?= strtolower($tag) ?>"><?= esc($tag) ?></span>
                <?php endforeach; ?>
              </div>
              <?php else: ?>
              <div class="topic-tags">
                <span class="topic-tag tag-<?= strtolower($topic['category']) ?>"><?= esc($topic['category']) ?></span>
              </div>
              <?php endif; ?>
              
              <div class="topic-footer">
                <div class="topic-meta">
                  <div class="topic-views">Views: <strong><?= $topic['views'] ?></strong></div>
                  <div class="topic-activity">Last Activity: <?= esc($topic['last_activity']) ?></div>
                </div>
                <button class="btn btn-primary join-btn">Join Discussion</button>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>
      </main>
    </div>

    <!-- New Post Modal -->
    <div id="newPostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Post</h2>
          <button class="modal-close" onclick="closeNewPostModal()"><i class="fas fa-times"></i></button>
        </div>
        <form class="new-post-form">
          <div class="form-group">
            <label for="postTitle">Title *</label>
            <input type="text" id="postTitle" name="title" placeholder="e.g., Help with data structures" required>
            <small style="color: #6B7280; font-size: 12px;">At least 10 characters.</small>
          </div>
          <div class="form-group">
            <label for="postCategory">Category *</label>
            <select id="postCategory" name="category" required>
              <option value="">Select a category</option>
              <option value="career">Career</option>
              <option value="mentorship">Mentorship</option>
              <option value="networking">Networking</option>
              <option value="general">General</option>
            </select>
          </div>
          <div class="form-group">
            <label for="postContent">Content *</label>
            <textarea id="postContent" name="content" placeholder="Write your question or post here..." rows="6" required></textarea>
            <small style="color: #6B7280; font-size: 12px;">At least 50 characters.</small>
          </div>
          <div class="form-group">
            <label for="postPriority">Priority</label>
            <select id="postPriority" name="priority">
              <option value="normal">Normal</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
          <div class="form-group">
            <label for="postTags">Tags</label>
            <textarea id="postTags" name="tags" placeholder="Type a tag and press Enter (e.g., #python, #machine-learning)" rows="2"></textarea>
            <small style="color: #6B7280; font-size: 12px;">Examples: #python, #algorithms, #notes</small>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline btn-md" onclick="openQuickTagsModal()">
              <i class="fas fa-hashtag"></i>
              <span>Quick Tags</span>
            </button>
            <button type="button" class="btn btn-outline btn-md" onclick="closeNewPostModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
              <i class="fas fa-paper-plane"></i>
              <span>Publish</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Quick Tags Modal -->
    <div id="quickTagsModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Quick Tags</h2>
          <button class="modal-close" onclick="closeQuickTagsModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="quick-tags-content">
          <div class="tags-section">
            <h3>Popular</h3>
            <div class="quick-tags-grid">
              <div class="quick-tag" onclick="addQuickTag('career')">#career</div>
              <div class="quick-tag" onclick="addQuickTag('mentorship')">#mentorship</div>
              <div class="quick-tag" onclick="addQuickTag('networking')">#networking</div>
              <div class="quick-tag" onclick="addQuickTag('experience')">#experience</div>
              <div class="quick-tag" onclick="addQuickTag('advice')">#advice</div>
              <div class="quick-tag" onclick="addQuickTag('general')">#general</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline btn-md" onclick="closeQuickTagsModal()">
            <span>Close</span>
          </button>
        </div>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/discussion-forum.js"></script>
    
    <script>
    // Make sure functions are available globally
    window.openNewPostModal = openNewPostModal;
    window.closeNewPostModal = closeNewPostModal;
    window.openQuickTagsModal = openQuickTagsModal;
    window.closeQuickTagsModal = closeQuickTagsModal;
    window.addQuickTag = addQuickTag;
    // Debug: Check if page is loaded
    console.log('Alumni discussion forum script loaded');
    
    // Modal Functions
    function openNewPostModal() {
      console.log('Opening New Post Modal');
      const modal = document.getElementById('newPostModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
        console.log('Modal display set to block and show class added');
      } else {
        console.error('New Post Modal not found');
      }
    }
    
    function closeNewPostModal() {
      console.log('Closing New Post Modal');
      const modal = document.getElementById('newPostModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }
    
    function openQuickTagsModal() {
      console.log('Opening Quick Tags Modal');
      const modal = document.getElementById('quickTagsModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
        console.log('Quick Tags Modal display set to block and show class added');
      } else {
        console.error('Quick Tags Modal not found');
      }
    }
    
    function closeQuickTagsModal() {
      console.log('Closing Quick Tags Modal');
      const modal = document.getElementById('quickTagsModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }
    
    function addQuickTag(tag) {
      const tagsTextarea = document.getElementById('postTags');
      const currentTags = tagsTextarea.value;
      const newTag = '#' + tag;
      
      if (currentTags.includes(newTag)) {
        return; // Tag already exists
      }
      
      if (currentTags.trim() === '') {
        tagsTextarea.value = newTag;
      } else {
        tagsTextarea.value = currentTags + ' ' + newTag;
      }
      
      closeQuickTagsModal();
    }
    
    // Close modals when clicking outside
    window.onclick = function(event) {
      const newPostModal = document.getElementById('newPostModal');
      const quickTagsModal = document.getElementById('quickTagsModal');
      
      if (event.target === newPostModal) {
        closeNewPostModal();
      }
      if (event.target === quickTagsModal) {
        closeQuickTagsModal();
      }
    }
    
    // Form submission
    document.addEventListener('DOMContentLoaded', function() {
      const newPostForm = document.querySelector('.new-post-form');
      if (newPostForm) {
        newPostForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Get form data
          const formData = new FormData(this);
          const title = formData.get('title');
          const category = formData.get('category');
          const content = formData.get('content');
          const priority = formData.get('priority');
          const tags = formData.get('tags');
          
          // Basic validation
          if (title.length < 10) {
            alert('Title must be at least 10 characters long.');
            return;
          }
          
          if (content.length < 50) {
            alert('Content must be at least 50 characters long.');
            return;
          }
          
          if (!category) {
            alert('Please select a category.');
            return;
          }
          
          // Here you would typically send the data to the server
          console.log('New post data:', {
            title: title,
            category: category,
            content: content,
            priority: priority,
            tags: tags
          });
          
          alert('Post created successfully!');
          closeNewPostModal();
          this.reset();
        });
      }
    });
    </script>
  </body>
</html>
