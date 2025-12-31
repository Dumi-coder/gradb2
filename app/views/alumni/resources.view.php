<?php 
$page_title = "Resources";
$page_subtitle = "Share and access study materials";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">

<div class="dashboard-container">
    <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>
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
                    <?php if (isset($res->is_reported) && $res->is_reported == 1): ?>
                      <span class="resource-status" style="background: #dc2626; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Reported</span>
                    <?php endif; ?>
                  </div>
                  <p class="resource-description"><?= htmlspecialchars($res->description ?? '') ?></p>
                  <div class="resource-details">
                    <span class="upload-date">Uploaded: <?= isset($res->created_at) ? date('M j, Y', strtotime($res->created_at)) : '' ?></span>
                    <span class="resource-downloads"><i class="fas fa-download"></i> <?= (int)($res->downloads ?? 0) ?> downloads</span>
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
            <div class="category-card" onclick="window.location.href='<?=ROOT?>/alumni/resources/browse?category=lecture-notes'">
              <div class="category-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Lecture Notes</h3>
                <p class="category-description">Class notes and study guides</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($category_counts['lecture-notes']) ? $category_counts['lecture-notes'] : 0 ?> files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="window.location.href='<?=ROOT?>/alumni/resources/browse?category=assignments'">
              <div class="category-icon">
                <i class="fas fa-tasks"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Exercises</h3>
                <p class="category-description">Sample solutions and templates</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($category_counts['assignments']) ? $category_counts['assignments'] : 0 ?> files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="window.location.href='<?=ROOT?>/alumni/resources/browse?category=textbooks'">
              <div class="category-icon">
                <i class="fas fa-book"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Textbooks</h3>
                <p class="category-description">Digital books and references</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($category_counts['textbooks']) ? $category_counts['textbooks'] : 0 ?> files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="window.location.href='<?=ROOT?>/alumni/resources/browse?category=software'">
              <div class="category-icon">
                <i class="fas fa-code"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Software & Tools</h3>
                <p class="category-description">Development tools and software</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($category_counts['software']) ? $category_counts['software'] : 0 ?> files</span>
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
              <a href="<?=ROOT?>/alumni/resources/browse" class="btn btn-outline btn-sm">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <div class="resources-list">
            <?php if(isset($recent_resources) && is_array($recent_resources) && count($recent_resources) > 0): ?>
              <?php foreach($recent_resources as $resource): ?>
                <div class="resource-item">
                  <div class="resource-icon">
                    <?php
                      $iconClass = 'fa-file';
                      $fileType = strtolower($resource->file_type ?? '');
                      if ($fileType == 'pdf') $iconClass = 'fa-file-pdf';
                      elseif (in_array($fileType, ['doc', 'docx'])) $iconClass = 'fa-file-word';
                      elseif (in_array($fileType, ['xls', 'xlsx'])) $iconClass = 'fa-file-excel';
                      elseif (in_array($fileType, ['ppt', 'pptx'])) $iconClass = 'fa-file-powerpoint';
                      elseif (in_array($fileType, ['zip', 'rar'])) $iconClass = 'fa-file-archive';
                      elseif (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) $iconClass = 'fa-file-image';
                      elseif ($fileType == 'txt') $iconClass = 'fa-file-alt';
                      elseif (in_array($fileType, ['py', 'js', 'php', 'java', 'cpp', 'c'])) $iconClass = 'fa-file-code';
                    ?>
                    <i class="fas <?= $iconClass ?>"></i>
                  </div>
                  <div class="resource-content">
                    <h3 class="resource-title"><?= htmlspecialchars($resource->title ?? '') ?></h3>
                    <p class="resource-description"><?= htmlspecialchars($resource->description ?? '') ?></p>
                    <div class="resource-meta">
                      <?php 
                        $cat = isset($resource->category) ? ucwords(str_replace('-', ' ', $resource->category)) : ''; 
                      ?>
                      <span class="resource-category"><?= htmlspecialchars($cat) ?></span>
                      <span class="resource-author">by <?= htmlspecialchars($resource->author_name ?? 'Unknown') ?></span>
                      <?php
                        $timeAgo = '';
                        if (isset($resource->created_at)) {
                          $timestamp = strtotime($resource->created_at);
                          $diff = time() - $timestamp;
                          if ($diff < 3600) {
                            $timeAgo = floor($diff / 60) . ' minutes ago';
                          } elseif ($diff < 86400) {
                            $timeAgo = floor($diff / 3600) . ' hours ago';
                          } elseif ($diff < 604800) {
                            $timeAgo = floor($diff / 86400) . ' days ago';
                          } else {
                            $timeAgo = date('M j, Y', $timestamp);
                          }
                        }
                      ?>
                      <span class="resource-date"><?= $timeAgo ?></span>
                      <span class="resource-downloads"><i class="fas fa-download"></i> <?= (int)($resource->downloads ?? 0) ?> downloads</span>
                    </div>
                  </div>
                  <div class="resource-actions">
                    <a class="btn btn-outline btn-sm" href="<?=ROOT?>/alumni/resources/download?id=<?= $resource->resource_id ?? '' ?>" target="_blank" rel="noopener">
                      <i class="fas fa-download"></i>
                      <span>Download</span>
                    </a>
                    <button class="btn btn-outline btn-sm" style="transition: all 0.3s;" onmouseover="this.style.borderColor='#dc2626'; this.style.color='#dc2626'" onmouseout="this.style.borderColor=''; this.style.color=''" onclick="openReportModal(<?= $resource->resource_id ?? 0 ?>, '<?= htmlspecialchars($resource->title ?? '', ENT_QUOTES) ?>')">
                      <i class="fas fa-flag"></i>
                      <span>Report</span>
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="resource-item" style="opacity:.7">
                <div class="resource-content" style="text-align: center; width: 100%;">
                  <h3 class="resource-title">No recent resources available</h3>
                  <p class="resource-description">Resources shared by other users will appear here.</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Resource Activity Statistics Section -->
        <section class="dashboard-section resource-stats-section">
          <div class="section-header">
            <h2 class="section-title">
              <i class="fas fa-chart-bar" style="color: #0E2072;"></i>
              Resource Activity
            </h2>
          </div>
          
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-folder-open"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= number_format($stats['total_resources'] ?? 0) ?></h3>
                <p class="stat-label">Total Number of Resources</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-upload"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= number_format($stats['my_resources'] ?? 0) ?></h3>
                <p class="stat-label">Number of My Resources</p>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-download"></i>
              </div>
              <div class="stat-content">
                <h3 class="stat-number"><?= number_format($stats['my_downloads'] ?? 0) ?></h3>
                <p class="stat-label">Total My Resource Downloads</p>
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
            <label for="resourceFaculty">Visibility *</label>
            <select id="resourceFaculty" name="resourceFaculty" required>
              <option value="">Select Faculty</option>
              <option value="all-faculties">All Faculties</option>
              <option value="UCSC">UCSC</option>
              <option value="FOA">FOA</option>
              <option value="FOS">FOS</option>
              <option value="FOM">FOM</option>
              <option value="FOMF">FOMF</option>
              <option value="FOL">FOL</option>
              <option value="FOE">FOE</option>
              <option value="FOT">FOT</option>
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

    <!-- Report Modal -->
    <div id="reportModal" class="modal" style="display: none;">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">
            <i class="fas fa-flag" style="color: #dc2626;"></i>
            Report Resource
          </h2>
          <button class="modal-close" onclick="closeReportModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form id="reportForm" class="upload-form">
          <input type="hidden" id="reportResourceId" name="reportResourceId">
          
          <div class="form-group">
            <label>Resource</label>
            <p id="reportResourceTitle" style="color: #6b7280; margin-top: 5px;"></p>
          </div>

          <div class="form-group">
            <label for="reportReason">Reason for Reporting *</label>
            <textarea id="reportReason" name="reportReason" rows="4" placeholder="Please describe why you are reporting this resource..." required></textarea>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeReportModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn" style="background: #dc2626; color: white;">
              <i class="fas fa-flag"></i>
              <span>Submit Report</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success/Error Modal -->
    <div id="messageModal" class="modal" style="display: none;">
      <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
          <button class="modal-close" onclick="closeMessageModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div style="text-align: center; padding: 20px;">
          <div id="messageIcon" style="font-size: 48px; margin-bottom: 20px;"></div>
          <h3 id="messageTitle" style="margin-bottom: 10px; font-size: 20px;"></h3>
          <p id="messageText" style="color: #6b7280; margin-bottom: 25px;"></p>
          <button class="btn btn-primary" onclick="closeMessageModal()" style="min-width: 120px;">
            <span>OK</span>
          </button>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script>
      window.APP_ROOT = '<?=ROOT?>';
      
      function openReportModal(resourceId, resourceTitle) {
        document.getElementById('reportResourceId').value = resourceId;
        document.getElementById('reportResourceTitle').textContent = resourceTitle;
        document.getElementById('reportReason').value = '';
        document.getElementById('reportModal').style.display = 'flex';
      }

      function closeReportModal() {
        document.getElementById('reportModal').style.display = 'none';
      }

      function showMessage(type, title, message, reloadOnClose = false) {
        const modal = document.getElementById('messageModal');
        const icon = document.getElementById('messageIcon');
        const titleEl = document.getElementById('messageTitle');
        const textEl = document.getElementById('messageText');
        
        if (type === 'success') {
          icon.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i>';
        } else {
          icon.innerHTML = '<i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>';
        }
        
        titleEl.textContent = title;
        textEl.textContent = message;
        modal.style.display = 'flex';
        modal.dataset.reloadOnClose = reloadOnClose;
      }

      function closeMessageModal() {
        const modal = document.getElementById('messageModal');
        modal.style.display = 'none';
        if (modal.dataset.reloadOnClose === 'true') {
          location.reload();
        }
      }

      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('reportForm').addEventListener('submit', function(e) {
          e.preventDefault();
          
          const resourceId = document.getElementById('reportResourceId').value;
          const reason = document.getElementById('reportReason').value.trim();

          if (!reason) {
            showMessage('error', 'Missing Information', 'Please provide a reason for reporting this resource.');
            return;
          }

          const formData = new FormData();
          formData.append('resourceId', resourceId);
          formData.append('reason', reason);

          fetch('<?=ROOT?>/alumni/resources/report', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              closeReportModal();
              showMessage('success', 'Report Submitted', 'Thank you for helping maintain quality. Your report has been submitted successfully.', true);
            } else {
              showMessage('error', 'Submission Failed', data.message || 'Failed to submit report. Please try again.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'Error Occurred', 'An unexpected error occurred while submitting the report. Please try again.');
          });
        });

        // Close report modal when clicking outside
        document.getElementById('reportModal').addEventListener('click', function(e) {
          if (e.target === this) {
            closeReportModal();
          }
        });

        // Close message modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
          if (e.target === this) {
            closeMessageModal();
          }
        });
      });
    </script>
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/resources.js"></script>
  </body>
</html>
