<?php 
$page_title = "Resources";
$page_subtitle = "Study materials, documents, and academic resources";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">

<div class="dashboard-container">
    <!-- sidebar -->
    <?php require '../app/views/partials/student_sidebar.php'; ?>
      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Upload Section -->
        <section class="dashboard-section upload-section">
          <div class="section-header">
            <h2 class="card-title">Share Resources</h2>
            <button class="btn btn-primary" onclick="openUploadModal()">
              <i class="fas fa-upload"></i>
              <span>Upload File</span>
            </button>
          </div>
          
          <div class="upload-info">
            <p>Share study materials, lecture notes, and helpful resources with the community.</p>
          </div>
        </section>

        <!-- My Resources -->
        <section class="dashboard-section my-resources-section">
          <div class="section-header">
            <h2 class="card-title">
              <i class="fas fa-folder"></i>
              My Resources
            </h2>
            <span class="resource-count"><span id="my-resources-count"><?= isset($my_resources) && is_array($my_resources) ? count($my_resources) : 0 ?></span> resources</span>
          </div>
          
          <div class="my-resources-grid" id="my-resources-grid">
            <?php if(isset($my_resources) && is_array($my_resources) && count($my_resources)): ?>
              <?php foreach($my_resources as $res): ?>
                <div class="my-resource-card" 
                     data-id="<?= $res->resource_id ?? '' ?>"
                     data-title="<?= htmlspecialchars($res->title ?? '', ENT_QUOTES) ?>"
                     data-description="<?= htmlspecialchars($res->description ?? '', ENT_QUOTES) ?>"
                     data-category="<?= htmlspecialchars($res->category ?? '', ENT_QUOTES) ?>"
                     data-file-path="<?= htmlspecialchars($res->file_path ?? '', ENT_QUOTES) ?>"
                     data-file-size="<?= (int)($res->file_size ?? 0) ?>"
                     data-created-at="<?= htmlspecialchars($res->created_at ?? '', ENT_QUOTES) ?>">
                  <h3 class="resource-title"><?= htmlspecialchars($res->title ?? '') ?></h3>
                  <div class="resource-meta">
                    <?php 
                      $cat = isset($res->category) ? ucwords(str_replace('-', ' ', $res->category)) : ''; 
                    ?>
                    <span class="resource-category"><?= htmlspecialchars($cat) ?></span>
                    <span class="resource-size"><?= isset($res->file_size) ? number_format(($res->file_size/1024/1024), 1) . ' MB' : '' ?></span>
                  </div>
                  <p class="resource-description"><?= htmlspecialchars($res->description ?? '') ?></p>
                  <div class="resource-details">
                    <span class="upload-date">Uploaded: <?= isset($res->created_at) ? date('M j, Y', strtotime($res->created_at)) : '' ?></span>
                  </div>
                  <div class="resource-actions">
                    <button class="btn btn-primary btn-sm" data-action="edit" data-id="<?= $res->resource_id ?? '' ?>">
                      <i class="fas fa-edit"></i>
                      <span>Edit</span>
                    </button>
                    <a class="btn btn-outline btn-sm" href="<?= htmlspecialchars($res->file_path ?? '#') ?>" target="_blank" rel="noopener">
                      <i class="fas fa-download"></i>
                      <span>Open</span>
                    </a>
                    <button class="btn btn-danger btn-sm" data-action="delete" data-id="<?= $res->resource_id ?? '' ?>">
                      <i class="fas fa-trash"></i>
                      <span>Delete</span>
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div id="my-resources-empty" class="my-resource-card" style="opacity:.8">
                <h3 class="resource-title">No resources yet</h3>
                <p class="resource-description">Upload your first resource to see it here.</p>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Browse by Category -->
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
                <h3 class="category-name">Exercises</h3>
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
                  <span class="resource-author">by Dumindu Hashen</span>
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
                  <span class="resource-author">by Nethmi Perera</span>
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
                  <span class="resource-category">Exercises</span>
                  <span class="resource-author">by Prasad Manoj</span>
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

        <form class="upload-form" enctype="multipart/form-data">
          <input type="hidden" id="resourceId" name="resourceId" value="">
          <input type="hidden" id="resourceMode" name="resourceMode" value="create">
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
              <input type="file" id="resourceFile" name="resourceFile" accept=".pdf,.doc,.docx,.txt,.zip,.rar,.ppt,.pptx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,image/*" style="display: none;" required>
              <div class="upload-placeholder">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Click to select file or drag and drop</p>
                <small>Supported: PDF, DOC, DOCX, TXT, ZIP, RAR, PPT, PPTX, XLS, XLSX, PNG, JPG, JPEG, GIF</small>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeUploadModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-upload"></i>
              <span>Upload</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
      <div class="modal-content modal-small">
        <div class="modal-header">
          <h2 class="modal-title">
            <i class="fas fa-exclamation-circle"></i>
            Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="modal-body">
          <div class="delete-warning">
            <div class="warning-icon-wrapper">
              <i class="fas fa-trash-alt"></i>
            </div>
            <h3>Are you sure you want to delete this resource?</h3>
            <p class="delete-resource-name" id="deleteResourceName"></p>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-outline btn-sm" onclick="closeDeleteModal()">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
            <i class="fas fa-trash"></i>
            <span>Delete Resource</span>
          </button>
        </div>

        <div class="modal-footer">
          <p class="warning-text">
            <i class="fas fa-info-circle"></i>
            This action cannot be undone. The file will be permanently deleted from the system.
          </p>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script>
      window.APP_ROOT = '<?=ROOT?>';
    </script>
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/student-resources.js"></script>
  </body>
</html>

