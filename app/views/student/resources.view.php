<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resources - GradBridge</title>
    <meta name="description" content="Access shared study materials, documents, and academic resources." />
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
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">
  </head>

  <body>
    <!-- Top Navbar -->
    <header class="dashboard-header">
      <div class="container">
        <div class="header-content">
          <div class="welcome-section">
            <h1 class="welcome-text">Resources</h1>
            <p class="student-role">Study materials, documents, and academic resources</p>
          </div>
          
          <div class="header-actions">
            <button class="btn btn-outline notification-btn" aria-label="Notifications">
              <i class="fas fa-bell"></i>
              <span class="notification-badge">3</span>
            </button>
            <button class="btn btn-primary logout-btn">
              <i class="fas fa-sign-out-alt"></i>
              Logout
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
    <!-- sidebar -->
    <?php require '../app/views/partials/student_sidebar.php'; ?>
      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Upload Section -->
        <section class="dashboard-section upload-section">
          <div class="section-header">
            <h2 class="card-title">Share Resources</h2>
            <button class="btn btn-primary btn-md" onclick="openUploadModal()">
              <i class="fas fa-upload"></i>
              <span>Upload File</span>
            </button>
          </div>
          
          <div class="upload-info">
            <p>Share study materials, lecture notes, and helpful resources with the community.</p>
          </div>
        </section>

        <!-- Resource Categories -->
        <section class="dashboard-section categories-section">
          <div class="section-header">
            <h2 class="card-title">Browse by Category</h2>
          </div>
          
          <div class="categories-grid">
            <div class="category-card" onclick="filterResources('lecture-notes')">
              <div class="category-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Lecture Notes</h3>
                <p class="category-description">Class notes and study guides</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> 45 files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="filterResources('assignments')">
              <div class="category-icon">
                <i class="fas fa-tasks"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Assignments</h3>
                <p class="category-description">Sample solutions and templates</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> 32 files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="filterResources('textbooks')">
              <div class="category-icon">
                <i class="fas fa-book"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Textbooks</h3>
                <p class="category-description">Digital books and references</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> 18 files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="filterResources('software')">
              <div class="category-icon">
                <i class="fas fa-code"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Software & Tools</h3>
                <p class="category-description">Development tools and software</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> 12 files</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Recent Resources -->
        <section class="dashboard-section recent-section">
          <div class="section-header">
            <h2 class="card-title">Recent Resources</h2>
            <div class="section-actions">
              <button class="btn btn-outline btn-sm" onclick="viewAllResources()">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <div class="resources-list">
            <div class="resource-item">
              <div class="resource-icon">
                <i class="fas fa-file-pdf"></i>
              </div>
              <div class="resource-content">
                <h3 class="resource-title">Data Structures Cheat Sheet</h3>
                <p class="resource-description">Comprehensive reference for common data structures and algorithms</p>
                <div class="resource-meta">
                  <span class="resource-category">Lecture Notes</span>
                  <span class="resource-author">by Alex Chen</span>
                  <span class="resource-date">2 hours ago</span>
                </div>
              </div>
              <div class="resource-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-download"></i>
                  <span>Download</span>
                </button>
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-star"></i>
                  <span>Save</span>
                </button>
              </div>
            </div>

            <div class="resource-item">
              <div class="resource-icon">
                <i class="fas fa-file-code"></i>
              </div>
              <div class="resource-content">
                <h3 class="resource-title">Python Project Template</h3>
                <p class="resource-description">Starter template for Python web applications with Flask</p>
                <div class="resource-meta">
                  <span class="resource-category">Software & Tools</span>
                  <span class="resource-author">by Sarah Johnson</span>
                  <span class="resource-date">1 day ago</span>
                </div>
              </div>
              <div class="resource-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-download"></i>
                  <span>Download</span>
                </button>
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-star"></i>
                  <span>Save</span>
                </button>
              </div>
            </div>

            <div class="resource-item">
              <div class="resource-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="resource-content">
                <h3 class="resource-title">Database Design Guidelines</h3>
                <p class="resource-description">Best practices for designing efficient database schemas</p>
                <div class="resource-meta">
                  <span class="resource-category">Assignments</span>
                  <span class="resource-author">by Dr. Martinez</span>
                  <span class="resource-date">3 days ago</span>
                </div>
              </div>
              <div class="resource-actions">
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-download"></i>
                  <span>Download</span>
                </button>
                <button class="btn btn-outline btn-sm">
                  <i class="fas fa-star"></i>
                  <span>Save</span>
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Upload Resource</h2>
          <button class="modal-close" onclick="closeUploadModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form class="upload-form">
          <div class="form-group">
            <label for="resourceTitle">Resource Title *</label>
            <input type="text" id="resourceTitle" name="resourceTitle" placeholder="Enter a descriptive title" required>
          </div>

          <div class="form-group">
            <label for="resourceCategory">Category *</label>
            <select id="resourceCategory" name="resourceCategory" required>
              <option value="">Select a category</option>
              <option value="lecture-notes">Lecture Notes</option>
              <option value="assignments">Assignments</option>
              <option value="textbooks">Textbooks</option>
              <option value="software">Software & Tools</option>
            </select>
          </div>

          <div class="form-group">
            <label for="resourceDescription">Description</label>
            <textarea id="resourceDescription" name="resourceDescription" rows="3" placeholder="Describe what this resource contains..."></textarea>
          </div>

          <div class="form-group">
            <label for="resourceFile">File *</label>
            <div class="file-upload-area" onclick="document.getElementById('resourceFile').click()">
              <input type="file" id="resourceFile" name="resourceFile" accept=".pdf,.doc,.docx,.txt,.zip,.rar" style="display: none;" required>
              <div class="upload-placeholder">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Click to select file or drag and drop</p>
                <small>Supported: PDF, DOC, DOCX, TXT, ZIP, RAR</small>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline btn-md" onclick="closeUploadModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
              <i class="fas fa-upload"></i>
              <span>Upload</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- JS -->
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/resources.js"></script>
  </body>
</html>

