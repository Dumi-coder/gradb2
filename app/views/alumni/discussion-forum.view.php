<?php 
$page_title = "Discussion Forum";
$page_subtitle = "Engage with fellow alumni and share experiences";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css">

<style>
    /* Force override styles */
    .topic-card {
      background-color: white !important;
      border: 1px solid #E5E7EB !important;
      border-radius: 12px !important;
      padding: 24px !important;
      margin-bottom: 20px !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .topic-title {
      font-size: 18px !important;
      font-weight: 700 !important;
      color: #1F2937 !important;
    }
    
    .status-badge {
      padding: 4px 8px !important;
      border-radius: 6px !important;
      font-size: 12px !important;
      font-weight: 700 !important;
    }
    
    .status-active {
      background-color: #C8E6C9 !important;
      color: #2E7D32 !important;
    }
    
    .status-trending {
      background-color: #FFE0B2 !important;
      color: #F57C00 !important;
    }
    
     .join-btn {
       background-color: #000000 !important;
       color: white !important;
       border: none !important;
       border-radius: 8px !important;
       padding: 8px 16px !important;
     }
     
     /* Dropdown Styling */
     .filter-select {
       background-color: #F9FAFB !important;
       border: 1px solid #D1D5DB !important;
       border-radius: 8px !important;
       padding: 8px 12px !important;
       font-size: 14px !important;
       color: #374151 !important;
       cursor: pointer !important;
       appearance: none !important;
       background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
       background-repeat: no-repeat !important;
       background-position: right 8px center !important;
       background-size: 16px !important;
       padding-right: 32px !important;
     }
     
     .filter-select:focus {
       outline: none !important;
       border-color: #3B82F6 !important;
       box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
     }
     
     /* Forum Topics Container Boundary */
     .forum-topics-section {
       border: 2px solid #E5E7EB !important;
       border-radius: 12px !important;
       padding: 24px !important;
       background-color: white !important;
       margin-top: 20px !important;
     }
     
     .topics-container {
       margin-top: 20px !important;
     }
     
     /* Forum Statistics Container Boundary */
     .statistics-section {
       border: 2px solid #E5E7EB !important;
       border-radius: 12px !important;
       padding: 24px !important;
       background-color: white !important;
       margin-top: 20px !important;
     }
     
     /* Quick Actions Container Boundary */
     .quick-actions-section {
       border: 2px solid #E5E7EB !important;
       border-radius: 12px !important;
       padding: 24px !important;
       background-color: white !important;
       margin-top: 20px !important;
     }
     
     /* Quick Actions Buttons - Same as Student Forum */
     .section-actions {
       display: flex !important;
       gap: 12px !important;
       align-items: center !important;
     }
     
     /* Modal Styling */
     .modal {
       display: none !important;
       position: fixed !important;
       z-index: 9999 !important;
       left: 0 !important;
       top: 0 !important;
       width: 100% !important;
       height: 100% !important;
       background-color: rgba(0, 0, 0, 0.5) !important;
     }
     
     .modal.show {
       display: block !important;
     }
     
     .modal-content {
       background-color: white !important;
       margin: 5% auto !important;
       padding: 0 !important;
       border-radius: 12px !important;
       width: 90% !important;
       max-width: 600px !important;
       box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
     }
     
     .modal-header {
       display: flex !important;
       justify-content: space-between !important;
       align-items: center !important;
       padding: 20px 24px !important;
       border-bottom: 1px solid #E5E7EB !important;
     }
     
     .modal-title {
       font-size: 18px !important;
       font-weight: 600 !important;
       color: #1F2937 !important;
       margin: 0 !important;
     }
     
     .modal-close {
       background: none !important;
       border: none !important;
       font-size: 18px !important;
       color: #6B7280 !important;
       cursor: pointer !important;
       padding: 4px !important;
     }
     
     .new-post-form {
       padding: 24px !important;
     }
     
     .form-group {
       margin-bottom: 20px !important;
     }
     
     .form-group label {
       display: block !important;
       font-weight: 500 !important;
       color: #374151 !important;
       margin-bottom: 8px !important;
     }
     
     .form-group input,
     .form-group textarea,
     .form-group select {
       width: 100% !important;
       padding: 12px !important;
       border: 1px solid #D1D5DB !important;
       border-radius: 8px !important;
       font-size: 14px !important;
       color: #1F2937 !important;
       box-sizing: border-box !important;
     }
     
     .form-group input:focus,
     .form-group textarea:focus,
     .form-group select:focus {
       outline: none !important;
       border-color: #3B82F6 !important;
       box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
     }
     
     .form-actions {
       display: flex !important;
       gap: 12px !important;
       justify-content: flex-end !important;
       margin-top: 24px !important;
     }
     
     .quick-tags-content {
       padding: 24px !important;
     }
     
     .tags-section h3 {
       font-size: 16px !important;
       font-weight: 600 !important;
       color: #1F2937 !important;
       margin: 0 0 16px 0 !important;
     }
     
     .quick-tags-grid {
       display: grid !important;
       grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)) !important;
       gap: 12px !important;
     }
     
     .quick-tag {
       background-color: #F3F4F6 !important;
       border: 1px solid #D1D5DB !important;
       border-radius: 8px !important;
       padding: 8px 12px !important;
       text-align: center !important;
       cursor: pointer !important;
       font-size: 14px !important;
       color: #374151 !important;
       transition: all 0.2s ease !important;
     }
     
     .quick-tag:hover {
       background-color: #E5E7EB !important;
       border-color: #9CA3AF !important;
     }
     
     .modal-footer {
       padding: 20px 24px !important;
       border-top: 1px solid #E5E7EB !important;
       display: flex !important;
       justify-content: flex-end !important;
     }
    </style>
    
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
