<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Discussion Forum - GradBridge</title>
    <meta name="description" content="Simple student discussion forum for questions and collaboration." />
    <meta name="author" content="GradBridge" />
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Base styles -->
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css">
  
  </head>

  <body>
   <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Discussion Forum</h1>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
            <a href="<?=ROOT?>/student/Logout">
                <button class="btn btn-primary logout-btn">Logout
                </button>
            </a>
        </div>
        </div>
      </div>
    </header>

    

    <div class="dashboard-container">
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Forum Header (simple) -->
        <section class="dashboard-section forum-header-section">
          <div class="section-header">
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

        <!-- Recent Discussions (simple list) -->
        <section class="dashboard-section recent-discussions-section">
          <div class="section-header">
            <h2 class="card-title">Recent Discussions</h2>
          </div>

          <div class="discussions-list">
            <div class="discussion-item">
              <div class="discussion-avatar"><i class="fas fa-user"></i></div>
              <div class="discussion-content">
                <div class="discussion-header">
                  <h3 class="discussion-title">Tips for mastering recursion?</h3>
                  <span class="discussion-category">CS Help</span>
                </div>
                <p class="discussion-excerpt">I struggle to visualize recursive calls. Any strategies or resources you recommend?</p>
                <div class="discussion-meta">
                  <span><i class="fas fa-user"></i> Alex</span>
                  <span><i class="fas fa-clock"></i> 12 mins ago</span>
                  <span><i class="fas fa-comment"></i> 4 replies</span>
                </div>
              </div>
              <div class="discussion-actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-reply"></i><span>Reply</span></button>
              </div>
            </div>

            <div class="discussion-item">
              <div class="discussion-avatar"><i class="fas fa-user"></i></div>
              <div class="discussion-content">
                <div class="discussion-header">
                  <h3 class="discussion-title">Sharing: My study plan for finals</h3>
                  <span class="discussion-category">Study Tips</span>
                </div>
                <p class="discussion-excerpt">Here’s a simple schedule that helped me last term. Hope it helps someone!</p>
                <div class="discussion-meta">
                  <span><i class="fas fa-user"></i> Priya</span>
                  <span><i class="fas fa-clock"></i> 1 hour ago</span>
                  <span><i class="fas fa-comment"></i> 7 replies</span>
                </div>
              </div>
              <div class="discussion-actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-reply"></i><span>Reply</span></button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- New Post Modal (minimal, matches JS/CSS) -->
    <div id="newPostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Post</h2>
          <button class="modal-close" onclick="closeNewPostModal()"><i class="fas fa-times"></i></button>
        </div>

        <form class="new-post-form">
          <div class="form-group">
            <label for="postTitle">Title *</label>
            <input type="text" id="postTitle" name="postTitle" placeholder="e.g., Help with data structures" required>
            <div class="form-help">At least 10 characters.</div>
          </div>

          <div class="form-group">
            <label for="postCategory">Category *</label>
            <select id="postCategory" name="postCategory" required>
              <option value="">Select a category</option>
              <option value="cs-help">CS Help</option>
              <option value="study-tips">Study Tips</option>
              <option value="general">General</option>
            </select>
          </div>

          <div class="form-group">
            <label for="postContent">Content *</label>
            <textarea id="postContent" name="postContent" rows="5" placeholder="Write your question or post here..." required></textarea>
            <div class="form-help">At least 50 characters.</div>
          </div>

          <div class="form-group">
            <label for="postPriority">Priority</label>
            <select id="postPriority" name="postPriority">
              <option value="normal">Normal</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <div class="form-group">
            <label>Tags</label>
            <div class="tags-input-container">
              <div id="tagsDisplay" class="tags-display"></div>
              <input id="tagInput" type="text" placeholder="Type a tag and press Enter">
            </div>
            <div class="form-help">Examples: #python, #algorithms, #notes</div>
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

    <!-- Quick Tags Modal (minimal) -->
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
              <div class="quick-tag" onclick="addQuickTag('python')">#python</div>
              <div class="quick-tag" onclick="addQuickTag('algorithms')">#algorithms</div>
              <div class="quick-tag" onclick="addQuickTag('notes')">#notes</div>
              <div class="quick-tag" onclick="addQuickTag('help')">#help</div>
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

    <!-- JS -->
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/discussion-forum.js"></script>
  </body>
  
</html>


