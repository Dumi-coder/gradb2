<?php require '../app/views/partials/admin_header.php'; ?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Resource Moderation Section -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title" style="font-size: 2rem; font-weight: 700;">Resource Moderation</h2>
                <div class="section-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= $resourceData['stats']['total_resources'] ?></span>
                        <span class="stat-label">Total Resources</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $resourceData['stats']['reported_resources'] ?></span>
                        <span class="stat-label">Reported</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= number_format($resourceData['stats']['total_downloads']) ?></span>
                        <span class="stat-label">Total Downloads</span>
                    </div>
                </div>
            </div>
            
            <!-- Reported Resources Section -->
            <div class="reported-resources-section">
                <h3 class="subsection-title">Reported Resources</h3>
                <div class="reported-resources-container">
                    <?php if (!empty($resourceData['reported_resources'])): ?>
                        <?php foreach ($resourceData['reported_resources'] as $resource): ?>
                        <div class="reported-resource-card">
                            <div class="resource-header">
                                <div class="resource-info">
                                    <h4 class="resource-title"><?= esc($resource['title']) ?></h4>
                                    <p class="resource-author">By: <?= esc($resource['author']) ?> (<?= esc($resource['author_email']) ?>) - <?= esc($resource['user_role']) ?></p>
                                    <p class="resource-type"><?= esc($resource['resource_type']) ?> • <?= esc($resource['file_size']) ?></p>
                                </div>
                                <div class="resource-meta">
                                    <span class="report-reason">Reported</span>
                                </div>
                            </div>
                            
                            <div class="resource-content">
                                <p><strong>Description:</strong> <?= esc($resource['description']) ?></p>
                            </div>
                            
                            <div class="resource-stats">
                                <span class="stat"><i class="fas fa-download"></i> <?= $resource['downloads'] ?> downloads</span>
                            </div>
                            
                            <div class="report-details">
                                <p><strong>Reported on:</strong> <?= date('M j, Y', strtotime($resource['reported_date'])) ?></p>
                                <p><strong>Feedback:</strong> <?= esc($resource['report_reason']) ?></p>
                            </div>
                            
                            <div class="resource-actions">
                                <button class="btn btn-success btn-sm approve-btn" data-resource-id="<?= $resource['id'] ?>">
                                    <i class="fas fa-flag"></i>
                                    Remove Flag
                                </button>
                                <button class="btn btn-warning btn-sm hide-btn" data-resource-id="<?= $resource['id'] ?>">
                                    <i class="fas fa-user-slash"></i>
                                    Suspend User
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-resource-id="<?= $resource['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                    Delete Resource
                                </button>
                                <button class="btn btn-outline btn-sm download-btn" data-resource-id="<?= $resource['id'] ?>">
                                    <i class="fas fa-download"></i>
                                    Download
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state" style="text-align: center; padding: 40px; opacity: 0.7;">
                            <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 15px;"></i>
                            <h4 style="margin-bottom: 10px;">No Reported Resources</h4>
                            <p style="color: #6b7280;">All resources for your faculty are in good standing.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

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
            <p>Share study materials, lecture notes, and helpful resources with your faculty community.</p>
          </div>
        </section>

        <!-- My Resources -->
        <section class="dashboard-section my-resources-section">
          <div class="section-header">
            <h2 class="card-title">
              <i class="fas fa-folder"></i>
              My Resources
            </h2>
            <span class="resource-count"><span id="my-resources-count"><?= isset($resourceData['my_resources']) && is_array($resourceData['my_resources']) ? count($resourceData['my_resources']) : 0 ?></span> resources</span>
          </div>
          
          <div class="my-resources-grid" id="my-resources-grid">
            <?php if(isset($resourceData['my_resources']) && is_array($resourceData['my_resources']) && count($resourceData['my_resources'])): ?>
              <?php foreach($resourceData['my_resources'] as $res): ?>
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
            <div class="category-card" onclick="window.location.href='<?=ROOT?>/admin/resourcemoderation/browse?category=lecture-notes'">
              <div class="category-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Lecture Notes</h3>
                <p class="category-description">Class notes and study guides</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($resourceData['category_counts']['lecture-notes']) ? $resourceData['category_counts']['lecture-notes'] : 0 ?> files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="window.location.href='<?=ROOT?>/admin/resourcemoderation/browse?category=assignments'">
              <div class="category-icon">
                <i class="fas fa-tasks"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Exercises</h3>
                <p class="category-description">Sample solutions and templates</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($resourceData['category_counts']['assignments']) ? $resourceData['category_counts']['assignments'] : 0 ?> files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="window.location.href='<?=ROOT?>/admin/resourcemoderation/browse?category=textbooks'">
              <div class="category-icon">
                <i class="fas fa-book"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Textbooks</h3>
                <p class="category-description">Digital books and references</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($resourceData['category_counts']['textbooks']) ? $resourceData['category_counts']['textbooks'] : 0 ?> files</span>
                </div>
              </div>
            </div>

            <div class="category-card" onclick="window.location.href='<?=ROOT?>/admin/resourcemoderation/browse?category=software'">
              <div class="category-icon">
                <i class="fas fa-code"></i>
              </div>
              <div class="category-info">
                <h3 class="category-name">Software & Tools</h3>
                <p class="category-description">Development tools and software</p>
                <div class="category-stats">
                  <span class="stat"><i class="fas fa-file"></i> <?= isset($resourceData['category_counts']['software']) ? $resourceData['category_counts']['software'] : 0 ?> files</span>
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
              <a href="<?=ROOT?>/admin/resourcemoderation/browse" class="btn btn-outline btn-sm">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <div class="resources-list">
            <?php if(isset($resourceData['recent_resources']) && is_array($resourceData['recent_resources']) && count($resourceData['recent_resources']) > 0): ?>
              <?php foreach($resourceData['recent_resources'] as $resource): ?>
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
                    <a class="btn btn-outline btn-sm" href="<?=ROOT?>/admin/resourcemoderation/download?id=<?= $resource->resource_id ?? '' ?>" target="_blank" rel="noopener">
                      <i class="fas fa-download"></i>
                      <span>Download</span>
                    </a>
                    <button class="btn btn-outline btn-sm" onclick="openReportModal(<?= $resource->resource_id ?? 0 ?>, '<?= htmlspecialchars(addslashes($resource->title ?? ''), ENT_QUOTES) ?>')" style="color: #dc2626; border-color: #dc2626;" onmouseover="this.style.background='#dc2626'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#dc2626';">
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
                  <p class="resource-description">Resources shared by users will appear here.</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </section>
    </main>
</div>

<!-- Add Resource Modal -->
    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
      <div class="modal-content" style="padding: 25px;">
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
      <div class="modal-content modal-small" style="padding: 25px;">
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
      <div class="modal-content" style="padding: 25px;">
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
      <div class="modal-content" style="max-width: 400px; padding: 25px;">
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

<style>
.reported-resources-section, .recent-resources-section {
    margin-bottom: 2rem;
}

.subsection-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
}

.reported-resources-container, .recent-resources-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.reported-resource-card, .recent-resource-card {
    background: white;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.reported-resource-card:hover, .recent-resource-card:hover {
    border-color: #0E2072;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.resource-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.resource-title {
    margin: 0 0 0.25rem 0;
    color: #1F2937;
    font-size: 1.1rem;
    font-weight: 600;
}

.resource-author {
    margin: 0 0 0.25rem 0;
    color: #6B7280;
    font-size: 0.9rem;
}

.resource-type {
    margin: 0;
    color: #0E2072;
    font-size: 0.9rem;
    font-weight: 500;
}

.resource-meta {
    text-align: right;
}

.report-reason {
    display: inline-block;
    background-color: #DC2626;
    color: white;
    padding: 0.35rem 0.85rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
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

.resource-content {
    margin-bottom: 1rem;
}

.resource-content p {
    margin: 0 0 0.5rem 0;
    color: #4B5563;
    line-height: 1.5;
}

.resource-stats {
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

.resource-actions {
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

.add-resource-btn {
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

.add-resource-btn:hover {
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

.resource-form {
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
    // Handle remove flag button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            const btn = this;
            
            // Show loading state
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
            btn.disabled = true;
            
            fetch('<?=ROOT?>/admin/resourcemoderation/removeflag', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'resource_id=' + resourceId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessageModal('success', 'Success', data.message || 'Report flag removed successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showMessageModal('error', 'Error', data.message || 'Failed to remove flag');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessageModal('error', 'Error', 'An error occurred. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    });

    // Handle hide button clicks
    document.querySelectorAll('.hide-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            if (confirm('Are you sure you want to hide this resource?')) {
                alert('Resource hidden successfully!');
                this.closest('.reported-resource-card').style.opacity = '0.5';
                this.disabled = true;
            }
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            const btn = this;
            const card = this.closest('.reported-resource-card');
            
            // Show loading state
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Deleting...</span>';
            btn.disabled = true;
            
            fetch('<?=ROOT?>/admin/resourcemoderation/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'resource_id=' + resourceId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessageModal('success', 'Success', data.message || 'Resource deleted successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showMessageModal('error', 'Error', data.message || 'Failed to delete resource');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessageModal('error', 'Error', 'An error occurred. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    });

    // Handle download button clicks
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            window.open('<?=ROOT?>/admin/resourcemoderation/download?id=' + resourceId, '_blank');
        });
    });

    // Handle view details button clicks
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            alert('View details functionality would be implemented here for resource ID: ' + resourceId);
        });
    });

    // Handle moderate button clicks
    document.querySelectorAll('.moderate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            alert('Moderate resource functionality would be implemented here for resource ID: ' + resourceId);
        });
    });
});

// Modal Functions
function openUploadModal() {
    document.getElementById('uploadModal').style.display = 'flex';
    document.getElementById('resourceMode').value = 'create';
    document.getElementById('resourceId').value = '';
    document.querySelector('.upload-form').reset();
    document.querySelector('.modal-title').textContent = 'Upload Resource';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.querySelector('.upload-form').reset();
}

function openDeleteModal() {
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const uploadModal = document.getElementById('uploadModal');
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === uploadModal) {
        closeUploadModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}

// File upload display
document.getElementById('resourceFile').addEventListener('change', function() {
    const fileName = this.files[0]?.name;
    if (fileName) {
        const placeholder = document.querySelector('.upload-placeholder p');
        placeholder.textContent = fileName;
    }
});

// Handle upload form submission
document.querySelector('.upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const mode = document.getElementById('resourceMode').value;
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type=\"submit\"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class=\"fas fa-spinner fa-spin\"></i><span>Processing...</span>';
    submitBtn.disabled = true;
    
    fetch('<?=ROOT?>/admin/resourcemoderation/' + (mode === 'edit' ? 'update' : 'upload'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        closeUploadModal();
        if (data.success) {
            showMessageModal('success', 'Success', data.message || 'Resource saved successfully!');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showMessageModal('error', 'Error', data.message || 'Failed to save resource');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        closeUploadModal();
        showMessageModal('error', 'Error', 'An error occurred. Please try again.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle Edit Resource
document.querySelectorAll('[data-action=\"edit\"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const card = this.closest('.my-resource-card');
        const resourceId = card.dataset.id;
        const title = card.dataset.title;
        const description = card.dataset.description;
        const category = card.dataset.category;
        
        // Populate form
        document.getElementById('resourceMode').value = 'edit';
        document.getElementById('resourceId').value = resourceId;
        document.getElementById('resourceTitle').value = title;
        document.getElementById('resourceDescription').value = description;
        document.getElementById('resourceCategory').value = category;
        
        // Make file optional for edit mode
        document.getElementById('resourceFile').required = false;
        
        // Update modal title
        document.querySelector('.modal-title').textContent = 'Edit Resource';
        
        // Open modal
        openUploadModal();
    });
});

// Handle Delete Resource
document.querySelectorAll('[data-action=\"delete\"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const card = this.closest('.my-resource-card');
        const resourceId = card.dataset.id;
        const title = card.dataset.title;
        
        // Set resource name in modal
        document.getElementById('deleteResourceName').textContent = title;
        
        // Set up confirm button
        document.getElementById('confirmDeleteBtn').onclick = function() {
            // Show loading state
            this.innerHTML = '<i class=\"fas fa-spinner fa-spin\"></i><span>Deleting...</span>';
            this.disabled = true;
            
            fetch('<?=ROOT?>/admin/resourcemoderation/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'resource_id=' + resourceId
            })
            .then(response => response.json())
            .then(data => {
                closeDeleteModal();
                if (data.success) {
                    showMessageModal('success', 'Success', data.message || 'Resource deleted successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showMessageModal('error', 'Error', data.message || 'Failed to delete resource');
                    this.innerHTML = '<i class=\"fas fa-trash\"></i><span>Delete Resource</span>';
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                closeDeleteModal();
                showMessageModal('error', 'Error', 'An error occurred. Please try again.');
                this.innerHTML = '<i class=\"fas fa-trash\"></i><span>Delete Resource</span>';
                this.disabled = false;
            });
        };
        
        // Open delete modal
        openDeleteModal();
    });
});

// Report Modal Functions
function openReportModal(resourceId, resourceTitle) {
    document.getElementById('reportResourceId').value = resourceId;
    document.getElementById('reportResourceTitle').textContent = resourceTitle;
    document.getElementById('reportReason').value = '';
    document.getElementById('reportModal').style.display = 'flex';
}

function closeReportModal() {
    document.getElementById('reportModal').style.display = 'none';
}

// Message Modal Functions
function showMessageModal(type, title, message) {
    const modal = document.getElementById('messageModal');
    const icon = document.getElementById('messageIcon');
    const titleEl = document.getElementById('messageTitle');
    const textEl = document.getElementById('messageText');
    
    if (type === 'success') {
        icon.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i>';
    } else {
        icon.innerHTML = '<i class="fas fa-times-circle" style="color: #ef4444;"></i>';
    }
    
    titleEl.textContent = title;
    textEl.textContent = message;
    modal.style.display = 'flex';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}

// Close report modal when clicking outside
window.addEventListener('click', function(event) {
    const reportModal = document.getElementById('reportModal');
    const messageModal = document.getElementById('messageModal');
    if (event.target === reportModal) {
        closeReportModal();
    }
    if (event.target === messageModal) {
        closeMessageModal();
    }
});

// Handle Report Form Submission
document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const resourceId = document.getElementById('reportResourceId').value;
    const reason = document.getElementById('reportReason').value.trim();
    
    if (!reason) {
        showMessageModal('error', 'Error', 'Please provide a reason for reporting.');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Submitting...</span>';
    submitBtn.disabled = true;
    
    fetch('<?=ROOT?>/admin/resourcemoderation/report', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'resource_id=' + encodeURIComponent(resourceId) + '&reason=' + encodeURIComponent(reason)
    })
    .then(response => response.json())
    .then(data => {
        closeReportModal();
        if (data.success) {
            showMessageModal('success', 'Report Submitted', data.message || 'Thank you for reporting this resource. We will review it shortly.');
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showMessageModal('error', 'Error', data.message || 'Failed to submit report. Please try again.');
        }
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        closeReportModal();
        showMessageModal('error', 'Error', 'An error occurred. Please try again later.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

</script>
