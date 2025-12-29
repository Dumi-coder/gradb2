<?php 
$page_title = "Browse Resources";
$page_subtitle = "Discover study materials and resources";
require '../app/views/partials/admin_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">

<div class="dashboard-container">
    <!-- sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>
      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Header Section -->
        <section class="dashboard-section">
          <div class="section-header" style="margin-bottom: 20px;">
            <h2 class="card-title">
              <?php 
                if (!empty($category)) {
                  echo ucwords(str_replace('-', ' ', $category));
                } else {
                  echo 'All Resources';
                }
              ?>
            </h2>
          </div>

          <!-- Category Navigation -->
          <div class="categories-nav" style="margin-bottom: 25px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="<?=ROOT?>/admin/resourcemoderation/browse<?= !empty($search) ? '?search=' . urlencode($search) : '' ?>" 
               class="btn <?= empty($category) ? 'btn-primary' : 'btn-outline' ?> btn-sm">
              <i class="fas fa-th"></i>
              <span>All</span>
            </a>
            <a href="<?=ROOT?>/admin/resourcemoderation/browse?category=lecture-notes<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
               class="btn <?= $category === 'lecture-notes' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
              <i class="fas fa-file-alt"></i>
              <span>Lecture Notes</span>
            </a>
            <a href="<?=ROOT?>/admin/resourcemoderation/browse?category=assignments<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
               class="btn <?= $category === 'assignments' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
              <i class="fas fa-tasks"></i>
              <span>Exercises</span>
            </a>
            <a href="<?=ROOT?>/admin/resourcemoderation/browse?category=textbooks<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
               class="btn <?= $category === 'textbooks' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
              <i class="fas fa-book"></i>
              <span>Textbooks</span>
            </a>
            <a href="<?=ROOT?>/admin/resourcemoderation/browse?category=software<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
               class="btn <?= $category === 'software' ? 'btn-primary' : 'btn-outline' ?> btn-sm">
              <i class="fas fa-code"></i>
              <span>Software & Tools</span>
            </a>
          </div>

          <!-- Search Bar -->
          <div class="search-bar-container" style="margin-bottom: 30px;">
            <form method="GET" action="<?=ROOT?>/admin/resourcemoderation/browse" style="display: flex; gap: 10px; max-width: 100%;">
              <?php if (!empty($category)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
              <?php endif; ?>
              <div style="flex: 1; position: relative; max-width: 600px;">
                <input 
                  type="text" 
                  name="search" 
                  class="input" 
                  placeholder="Search resources by title or description..." 
                  value="<?= htmlspecialchars($search ?? '') ?>"
                  style="width: 100%; padding-left: 40px;"
                >
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                <span>Search</span>
              </button>
              <a href="<?=ROOT?>/admin/resourcemoderation/index" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Resources</span>
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
              <div class="resource-item" style="opacity:.7; text-align: center;">
                <div class="resource-content" style="width: 100%;">
                  <i class="fas fa-folder-open" style="font-size: 3rem; color: #d1d5db; margin-bottom: 15px;"></i>
                  <h3 class="resource-title">No resources found</h3>
                  <p class="resource-description">
                    <?php if (!empty($search)): ?>
                      Try adjusting your search terms or browse all resources.
                    <?php else: ?>
                      There are no resources available in this category yet.
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

    <!-- JS -->
    <script>
      window.APP_ROOT = '<?=ROOT?>';

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

      // Close modals when clicking outside
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
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
  </body>
</html>
