<?php 
$page_title = "Resources";
$page_subtitle = "Study materials, documents, and academic resources";
require '../app/views/partials/student_header.php'; 
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/resources.css">

<div class="dashboard-container">
    <?php require '../app/views/partials/student_sidebar.php'; ?>
    <main class="main-content">

        <section class="dashboard-section upload-section">
          <div class="section-header">
            <h2 class="card-title">Share Resources</h2>
            <button class="btn btn-primary" onclick="openUploadModal()">
              <i class="fas fa-link"></i>
              <span>Share</span>
            </button>
          </div>
          <div class="upload-info">
            <p>Share useful resource links with your faculty and alumni network.</p>
          </div>
        </section>

        <section class="dashboard-section">
          <div class="section-header" style="margin-bottom: 16px;">
            <h2 class="card-title">Search Shared Resources</h2>
          </div>
          <form method="GET" action="<?=ROOT?>/student/resources/browse" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <div style="position:relative; flex:1; min-width:260px; max-width:700px;">
              <input type="text" name="search" class="input" placeholder="Search by keyword, tag, or category (e.g. discussion forum, AL)" style="width:100%; padding-left:40px;">
              <i class="fas fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af;"></i>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i><span>Search</span></button>
          </form>
        </section>

        <section class="dashboard-section my-resources-section">
          <div class="section-header">
            <h2 class="card-title"><i class="fas fa-folder"></i> My Resources</h2>
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
                     data-tags="<?= htmlspecialchars($res->tags ?? '', ENT_QUOTES) ?>"
                     data-faculty-id="<?= (int)($res->faculty_id ?? 999) ?>"
                     data-file-path="<?= htmlspecialchars($res->file_path ?? '', ENT_QUOTES) ?>"
                     data-file-size="<?= (int)($res->file_size ?? 0) ?>"
                     data-created-at="<?= htmlspecialchars($res->created_at ?? '', ENT_QUOTES) ?>">
                  <h3 class="resource-title"><?= htmlspecialchars($res->title ?? '') ?></h3>
                  <div class="resource-meta">
                    <span class="resource-category"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $res->category ?? ''))) ?></span>
                    <span class="resource-size"><?= isset($res->file_size) ? number_format(($res->file_size/1024/1024), 1) . ' MB' : '' ?></span>
                  </div>
                  <p class="resource-description"><?= htmlspecialchars($res->description ?? '') ?></p>
                  <div class="resource-details">
                    <span class="upload-date">Uploaded: <?= isset($res->created_at) ? date('M j, Y', strtotime($res->created_at)) : '' ?></span>
                  </div>
                  <div class="resource-actions">
                    <button class="btn btn-primary btn-sm" data-action="edit" data-id="<?= $res->resource_id ?? '' ?>"><i class="fas fa-edit"></i><span>Edit</span></button>
                    <a class="btn btn-outline btn-sm" href="<?= htmlspecialchars($res->file_path ?? '#') ?>" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i><span>Open</span></a>
                    <button class="btn btn-danger btn-sm" data-action="delete" data-id="<?= $res->resource_id ?? '' ?>"><i class="fas fa-trash"></i><span>Delete</span></button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div id="my-resources-empty" class="my-resource-card" style="opacity:.8">
                <h3 class="resource-title">No resources yet</h3>
                <p class="resource-description">Share your first resource link to see it here.</p>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <section class="dashboard-section recent-section">
          <div class="section-header">
            <h2 class="card-title">Recent Resources</h2>
            <div class="section-actions">
              <a href="<?=ROOT?>/student/resources/browse" class="btn btn-outline btn-sm">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <div class="resources-list">
            <?php if(isset($recent_resources) && is_array($recent_resources) && count($recent_resources) > 0): ?>
              <?php foreach($recent_resources as $resource): ?>
                <div class="resource-item">
                  <div class="resource-icon"><i class="fas fa-link"></i></div>
                  <div class="resource-content">
                    <h3 class="resource-title"><?= htmlspecialchars($resource->title ?? '') ?></h3>
                    <p class="resource-description"><?= htmlspecialchars($resource->description ?? '') ?></p>
                    <div class="resource-meta">
                      <span class="resource-category"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $resource->category ?? ''))) ?></span>
                      <span class="resource-author">by <?= htmlspecialchars($resource->author_name ?? 'Unknown') ?></span>
                      <span class="resource-downloads"><i class="fas fa-download"></i> <?= (int)($resource->downloads ?? 0) ?> visits</span>
                    </div>
                  </div>
                  <div class="resource-actions">
                    <a class="btn btn-outline btn-sm" href="<?=ROOT?>/student/resources/download?id=<?= $resource->resource_id ?? '' ?>" target="_blank" rel="noopener">
                      <i class="fas fa-external-link-alt"></i><span>Open</span>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="resource-item resources-empty-state" style="opacity:.7">
                <div class="resource-content" style="text-align:center; width:100%;">
                  <h3 class="resource-title">No recent resources available</h3>
                  <p class="resource-description">Resources shared by others will appear here.</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </section>

    </main>
</div>

<div id="uploadModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Share Resource</h2>
      <button class="modal-close" onclick="closeUploadModal()"><i class="fas fa-times"></i></button>
    </div>

    <form class="upload-form" method="POST" action="<?=ROOT?>/student/resources/upload" enctype="multipart/form-data">
      <input type="hidden" id="resourceId" name="resourceId" value="">
      <input type="hidden" id="resourceMode" name="resourceMode" value="create">

      <div class="form-group">
        <label for="resourceTitle">Resource Title *</label>
        <input type="text" id="resourceTitle" name="resourceTitle" placeholder="Enter a descriptive title" required>
      </div>

      <div class="form-group">
        <label for="resourceCategory">Category *</label>
        <input type="text" id="resourceCategory" name="resourceCategory" placeholder="Enter category (e.g. AL, Computational Model Theory)" required>
      </div>

      <div class="form-group">
        <label for="resourceTags">Tags</label>
        <input type="text" id="resourceTags" name="resourceTags" placeholder="Add tags separated by commas">
      </div>

      <div class="form-group">
        <label for="resourceFaculty">Visibility *</label>
        <select id="resourceFaculty" name="resourceFaculty" required>
          <option value="all-faculties" selected>All Faculties</option>
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
        <label for="resourceLink">Resource Link *</label>
        <input type="text" id="resourceLink" name="resourceLink" placeholder="https://example.com/resource" required>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-outline" onclick="closeUploadModal()"><span>Cancel</span></button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-link"></i><span>Share</span></button>
      </div>
    </form>
  </div>
</div>

<div id="deleteModal" class="modal">
  <div class="modal-content modal-small">
    <div class="modal-header">
      <h2 class="modal-title"><i class="fas fa-exclamation-circle"></i> Confirm Delete</h2>
      <button class="modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div class="delete-warning">
        <div class="warning-icon-wrapper"><i class="fas fa-trash-alt"></i></div>
        <h3>Are you sure you want to delete this resource?</h3>
        <p class="delete-resource-name" id="deleteResourceName"></p>
      </div>
    </div>
    <div class="form-actions">
      <button type="button" class="btn btn-outline btn-sm" onclick="closeDeleteModal()"><i class="fas fa-times"></i><span>Cancel</span></button>
      <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn"><i class="fas fa-trash"></i><span>Delete Resource</span></button>
    </div>
  </div>
</div>

<script>
  window.APP_ROOT = '<?=ROOT?>';
</script>
<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
<script src="<?=ROOT?>/assets/js/student-resources.js?v=2"></script>
</body>
</html>
