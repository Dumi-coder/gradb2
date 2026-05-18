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
                <h2 class="section-title fae-56">Resource Moderation</h2>
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
                                    <?php
                                      $reportCount = (int)($resource['report_count'] ?? 0);
                                      $isPermanentlyReported = !empty($resource['permanently_reported']) || $reportCount >= 5;
                                    ?>
                                    <span class="report-reason"><?= $isPermanentlyReported ? 'Reported' : ('Reports : ' . $reportCount) ?></span>
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
                              <?php if ((int)($resource['user_is_suspended'] ?? 0) === 1): ?>
                              <span class="suspended-user-label">
                                <i class="fas fa-user-lock"></i>
                                User Suspended
                              </span>
                              <?php elseif (in_array($resource['uploader_role'] ?? '', ['faculty_admin', 'super_admin'])): ?>
                              <button class="btn btn-outline btn-sm notify-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-bell"></i>
                                Notify User
                              </button>
                              <?php else: ?>
                              <button class="btn btn-warning btn-sm hide-btn" data-resource-id="<?= $resource['id'] ?>">
                                <i class="fas fa-user-slash"></i>
                                Suspend User
                              </button>
                              <?php endif; ?>
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
                        <div class="empty-state fae-57">
                            <i class="fas fa-check-circle fae-58"></i>
                            <h4 class="fae-59">No Reported Resources</h4>
                            <p class="fae-60">All resources for your faculty are in good standing.</p>
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
                <?php
                  $myReportCount = (int)($res->report_count ?? 0);
                  $myIsPermanentlyReported = ((int)($res->permanently_reported ?? 0) === 1) || $myReportCount >= 5;
                  $myIsLink = strtolower((string)($res->file_type ?? '')) === 'link';
                  $myCountLabel = $myIsLink ? 'visits' : 'downloads';
                  $myActionLabel = $myIsLink ? 'Open' : 'Download';
                  $myActionIcon = $myIsLink ? 'fa-external-link-alt' : 'fa-download';
                ?>
                <div class="my-resource-card fae-61" 
                    
                     data-id="<?= $res->resource_id ?? '' ?>"
                     data-title="<?= htmlspecialchars($res->title ?? '', ENT_QUOTES) ?>"
                     data-description="<?= htmlspecialchars($res->description ?? '', ENT_QUOTES) ?>"
                     data-category="<?= htmlspecialchars($res->category ?? '', ENT_QUOTES) ?>"
                     data-file-path="<?= htmlspecialchars($res->file_path ?? '', ENT_QUOTES) ?>"
                     data-file-size="<?= (int)($res->file_size ?? 0) ?>"
                     data-created-at="<?= htmlspecialchars($res->created_at ?? '', ENT_QUOTES) ?>">
                  <?php if ($myIsPermanentlyReported): ?>
                    <span class="resource-status fae-62">Reported</span>
                  <?php elseif ($myReportCount > 0): ?>
                    <span class="resource-status fae-62">Reports : <?= $myReportCount ?></span>
                  <?php endif; ?>
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
                    <span class="resource-downloads"><i class="fas <?= $myIsLink ? 'fa-eye' : 'fa-download' ?>"></i> <?= (int)($res->downloads ?? 0) ?> <?= $myCountLabel ?></span>
                  </div>
                  <div class="resource-actions">
                    <button class="btn btn-primary btn-sm" data-action="edit" data-id="<?= $res->resource_id ?? '' ?>">
                      <i class="fas fa-edit"></i>
                      <span>Edit</span>
                    </button>
                    <a class="btn btn-outline btn-sm" href="<?= htmlspecialchars($res->file_path ?? '#') ?>" target="_blank" rel="noopener">
                      <i class="fas <?= $myActionIcon ?>"></i>
                      <span><?= $myActionLabel ?></span>
                    </a>
                    <button class="btn btn-danger btn-sm" data-action="delete" data-id="<?= $res->resource_id ?? '' ?>">
                      <i class="fas fa-trash"></i>
                      <span>Delete</span>
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div id="my-resources-empty" class="my-resource-card fae-63">
                <h3 class="resource-title">No resources yet</h3>
                <p class="resource-description">Upload your first resource to see it here.</p>
              </div>
            <?php endif; ?>
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
                <?php
                  $recentIsLink = strtolower((string)($resource->file_type ?? '')) === 'link';
                  $recentCountLabel = $recentIsLink ? 'visits' : 'downloads';
                  $recentActionLabel = $recentIsLink ? 'Open' : 'Download';
                  $recentActionIcon = $recentIsLink ? 'fa-external-link-alt' : 'fa-download';
                ?>
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
                      <span class="resource-downloads"><i class="fas <?= $recentIsLink ? 'fa-eye' : 'fa-download' ?>"></i> <?= (int)($resource->downloads ?? 0) ?> <?= $recentCountLabel ?></span>
                    </div>
                  </div>
                  <div class="resource-actions">
                    <a class="btn btn-outline btn-sm" href="<?=ROOT?>/admin/resourcemoderation/download?id=<?= $resource->resource_id ?? '' ?>" target="_blank" rel="noopener">
                      <i class="fas <?= $recentActionIcon ?>"></i>
                      <span><?= $recentActionLabel ?></span>
                    </a>
                    <button class="btn btn-outline btn-sm fae-report-btn" onclick="openReportModal(<?= $resource->resource_id ?? 0 ?>, '<?= htmlspecialchars(addslashes($resource->title ?? ''), ENT_QUOTES) ?>')">
                      <i class="fas fa-flag"></i>
                      <span>Report</span>
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="resource-item resources-empty-state fae-64">
                <div class="resource-content fae-65">
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
      <div class="modal-content fae-11">
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
              <input type="file" id="resourceFile" name="resourceFile" accept=".pdf,.doc,.docx,.txt,.zip,.rar,.ppt,.pptx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,image/*" class="fae-10" required>
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
      <div class="modal-content modal-small fae-11">
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
    <div id="reportModal" class="modal fae-10">
      <div class="modal-content fae-11">
        <div class="modal-header">
          <h2 class="modal-title">
            <i class="fas fa-flag fae-12"></i>
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
            <p id="reportResourceTitle" class="fae-13"></p>
          </div>

          <div class="form-group">
            <label for="reportReason">Reason for Reporting *</label>
            <textarea id="reportReason" name="reportReason" rows="4" placeholder="Please describe why you are reporting this resource..." required></textarea>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeReportModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn fae-14">
              <i class="fas fa-flag"></i>
              <span>Submit Report</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success/Error Modal -->
    <div id="messageModal" class="modal fae-10">
      <div class="modal-content fae-15">
        <div class="modal-header fae-16">
          <button class="modal-close" onclick="closeMessageModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="fae-17">
          <div id="messageIcon" class="fae-18"></div>
          <h3 id="messageTitle" class="fae-19"></h3>
          <p id="messageText" class="fae-20"></p>
          <button class="btn btn-primary fae-21" onclick="closeMessageModal()">
            <span>OK</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Action Confirmation Modal (Reported Resources) -->
    <div id="actionConfirmModal" class="modal fae-10">
      <div class="modal-content fae-66">
        <div class="modal-header fae-16">
          <h2 class="modal-title" id="actionConfirmTitle">Confirm Action</h2>
          <button class="modal-close" onclick="closeActionConfirmModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body fae-67">
          <p id="actionConfirmText" class="fae-68"></p>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-outline" onclick="closeActionConfirmModal()">
            <span>Cancel</span>
          </button>
          <button type="button" class="btn btn-primary" id="actionConfirmBtn">
            <span>Confirm</span>
          </button>
        </div>
      </div>
    </div>



<script>
let actionConfirmHandler = null;

document.addEventListener('DOMContentLoaded', function() {
    // Handle remove flag button clicks
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
      const actionBtn = this;

      openActionConfirmModal({
        title: 'Remove Report Flag',
        message: 'Are you sure you want to remove the report flag for this resource?',
        confirmText: 'Remove Flag',
        confirmClass: 'btn-success',
        onConfirm: () => {
          const originalText = actionBtn.innerHTML;
          actionBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
          actionBtn.disabled = true;

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
              actionBtn.innerHTML = originalText;
              actionBtn.disabled = false;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showMessageModal('error', 'Error', 'An error occurred. Please try again.');
            actionBtn.innerHTML = originalText;
            actionBtn.disabled = false;
          });
        }
            });
        });
    });

    // Handle suspend user button clicks
    document.querySelectorAll('.hide-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            const actionBtn = this;

            openActionConfirmModal({
                title: 'Suspend User',
                message: 'Are you sure you want to suspend this user account?',
                confirmText: 'Suspend User',
                confirmClass: 'btn-warning',
                onConfirm: () => {
                    const originalText = actionBtn.innerHTML;
                    actionBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
                    actionBtn.disabled = true;

                    fetch('<?=ROOT?>/admin/resourcemoderation/suspenduser', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'resource_id=' + resourceId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessageModal('success', 'Success', data.message || 'User suspended successfully!');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showMessageModal('error', 'Error', data.message || 'Failed to suspend user');
                            actionBtn.innerHTML = originalText;
                            actionBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessageModal('error', 'Error', 'An error occurred. Please try again.');
                        actionBtn.innerHTML = originalText;
                        actionBtn.disabled = false;
                    });
                }
            });
        });
    });

    // Notify action sends warning notification
    document.querySelectorAll('.notify-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const resourceId = this.getAttribute('data-resource-id');
        const actionBtn = this;

        openActionConfirmModal({
          title: 'Notify User',
          message: 'Send a warning notification to the owner of this resource about community guidelines compliance?',
          confirmText: 'Send Warning',
          confirmClass: 'btn-warning',
          onConfirm: () => {
            const originalText = actionBtn.innerHTML;
            actionBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Notifying...</span>';
            actionBtn.disabled = true;

            fetch('<?=ROOT?>/admin/resourcemoderation/notifyuser', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: 'resource_id=' + resourceId
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                showMessageModal('success', 'Warning Sent', 'User has been notified about their resource.');
                setTimeout(() => {
                  window.location.reload();
                }, 2000);
              } else {
                actionBtn.innerHTML = originalText;
                actionBtn.disabled = false;
                showMessageModal('error', 'Error', data.message || 'Failed to notify user');
              }
            })
            .catch(error => {
              actionBtn.innerHTML = originalText;
              actionBtn.disabled = false;
              showMessageModal('error', 'Error', 'Network error: ' + error.message);
            });
          }
        });
      });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
        const actionBtn = this;

        openActionConfirmModal({
          title: 'Delete Resource',
          message: 'Are you sure you want to delete this reported resource? This action cannot be undone.',
          confirmText: 'Delete Resource',
          confirmClass: 'btn-danger',
          onConfirm: () => {
            const originalText = actionBtn.innerHTML;
            actionBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Deleting...</span>';
            actionBtn.disabled = true;

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
                actionBtn.innerHTML = originalText;
                actionBtn.disabled = false;
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showMessageModal('error', 'Error', 'An error occurred. Please try again.');
              actionBtn.innerHTML = originalText;
              actionBtn.disabled = false;
            });
          }
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

function openActionConfirmModal(options) {
  const modal = document.getElementById('actionConfirmModal');
  const titleEl = document.getElementById('actionConfirmTitle');
  const textEl = document.getElementById('actionConfirmText');
  const confirmBtn = document.getElementById('actionConfirmBtn');

  titleEl.textContent = options.title || 'Confirm Action';
  textEl.textContent = options.message || 'Are you sure you want to continue?';
  confirmBtn.innerHTML = '<span>' + (options.confirmText || 'Confirm') + '</span>';
  confirmBtn.className = 'btn ' + (options.confirmClass || 'btn-primary');
  actionConfirmHandler = options.onConfirm || null;
  modal.style.display = 'flex';
  modal.classList.add('show');
}

function closeActionConfirmModal() {
  const modal = document.getElementById('actionConfirmModal');
  modal.classList.remove('show');
  modal.style.display = 'none';
  actionConfirmHandler = null;
}

// Close modal when clicking outside
window.onclick = function(event) {
    const uploadModal = document.getElementById('uploadModal');
    const deleteModal = document.getElementById('deleteModal');
  const actionConfirmModal = document.getElementById('actionConfirmModal');
    if (event.target === uploadModal) {
        closeUploadModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
  if (event.target === actionConfirmModal) {
    closeActionConfirmModal();
  }
}

document.getElementById('actionConfirmBtn').addEventListener('click', function() {
  const action = actionConfirmHandler;
  closeActionConfirmModal();
  if (typeof action === 'function') {
    action();
  }
});

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
        icon.innerHTML = '<i class="fas fa-check-circle fae-22"></i>';
    } else {
        icon.innerHTML = '<i class="fas fa-times-circle fae-23"></i>';
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
  const actionConfirmModal = document.getElementById('actionConfirmModal');
    if (event.target === reportModal) {
        closeReportModal();
    }
    if (event.target === messageModal) {
        closeMessageModal();
    }
  if (event.target === actionConfirmModal) {
    closeActionConfirmModal();
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
