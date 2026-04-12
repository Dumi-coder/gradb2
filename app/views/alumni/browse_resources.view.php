<?php 
$page_title = "Browse Resources";
$page_subtitle = "Discover study materials and resources";
require '../app/views/partials/alumni_header.php'; 

?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">

<div class="dashboard-container">
    <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>
      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Header Section -->
        <section class="dashboard-section">
          <div class="section-header" style="margin-bottom: 20px;">
            <h2 class="card-title">
              All Resources
            </h2>
          </div>

          <!-- Search Bar -->
          <div class="search-bar-container" style="margin-bottom: 30px;">
            <form method="GET" action="<?=ROOT?>/alumni/resources/browse" style="display: flex; gap: 10px; max-width: 100%;">
              <div style="flex: 1; position: relative; max-width: 600px;">
                <input 
                  type="text" 
                  name="search" 
                  class="input" 
                  placeholder="Search by keyword or category..." 
                  value="<?= htmlspecialchars($search ?? '') ?>"
                  style="width: 100%; padding-left: 40px;"
                >
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                <span>Search</span>
              </button>
              <a href="<?=ROOT?>/alumni/resources/index" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
              </a>
            </form>
          </div>
        </section>

        <!-- Resources List -->
        <section class="dashboard-section">
          <div class="section-header" style="margin-bottom: 20px;">
            <h3 class="card-title" style="font-size: 1.1rem;">
              <?= count($resources ?? []) ?> Resource<?= count($resources ?? []) !== 1 ? 's' : '' ?> Found
            </h3>
          </div>

          <div class="resources-list">
            <?php if(isset($resources) && is_array($resources) && count($resources) > 0): ?>
              <?php foreach($resources as $resource): ?>
                <?php
                  $browseIsLink = strtolower((string)($resource->file_type ?? '')) === 'link';
                  $browseCountLabel = $browseIsLink ? 'visits' : 'downloads';
                  $browseActionLabel = $browseIsLink ? 'Open' : 'Download';
                  $browseActionIcon = $browseIsLink ? 'fa-external-link-alt' : 'fa-download';
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
                      <span class="resource-size"><?= isset($resource->file_size) ? number_format(($resource->file_size/1024/1024), 1) . ' MB' : '' ?></span>
                    </div>
                    <div class="resource-details">
                      <span class="resource-downloads"><i class="fas <?= $browseIsLink ? 'fa-eye' : 'fa-download' ?>"></i> <?= (int)($resource->downloads ?? 0) ?> <?= $browseCountLabel ?></span>
                    </div>
                  </div>
                  <div class="resource-actions">
                    <a class="btn btn-outline btn-sm" href="<?=ROOT?>/alumni/resources/download?id=<?= $resource->resource_id ?? '' ?>" target="_blank" rel="noopener">
                      <i class="fas <?= $browseActionIcon ?>"></i>
                      <span><?= $browseActionLabel ?></span>
                    </a>
                    <?php if ((int)($resource->user_id ?? 0) !== (int)($_SESSION['user_id'] ?? 0)): ?>
                      <button class="btn btn-outline btn-sm" style="transition: all 0.3s;" onmouseover="this.style.borderColor='#dc2626'; this.style.color='#dc2626'" onmouseout="this.style.borderColor=''; this.style.color=''" onclick="openReportModal(<?= $resource->resource_id ?? 0 ?>, '<?= htmlspecialchars($resource->title ?? '', ENT_QUOTES) ?>')">
                        <i class="fas fa-flag"></i>
                        <span>Report</span>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="resource-item resources-empty-state" style="opacity:.7; text-align: center;">
                <div class="resource-content" style="width: 100%;">
                  <i class="fas fa-folder-open" style="font-size: 3rem; color: #d1d5db; margin-bottom: 15px;"></i>
                  <h3 class="resource-title">No resources found</h3>
                  <p class="resource-description">
                    <?php if (!empty($search)): ?>
                      Try adjusting your search terms or browse all resources.
                    <?php else: ?>
                      There are no resources shared yet.
                    <?php endif; ?>
                  </p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </section>
      </main>
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
    </script>
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
  </body>
</html>
