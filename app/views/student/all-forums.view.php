<?php 
$page_title = "All Forums";
$page_subtitle = "Browse all discussion topics";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css?v=<?=time()?>">

<style>
/* Force styles to load inline */
.dashboard-container {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 2rem;
}

.dashboard-section {
  background: var(--card);
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.section-header {
  margin-bottom: 1.5rem;
}

.section-title-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  flex-wrap: wrap;
  gap: 1rem;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--foreground);
  margin: 0;
}

.filter-controls {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.filter-select {
  padding: 0.5rem 1rem;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--background);
  color: var(--foreground);
  font-size: 0.9rem;
  cursor: pointer;
  min-width: 150px;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  border: none;
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--border);
  color: var(--foreground);
}

.btn-outline:hover {
  background: var(--accent);
  border-color: var(--accent);
}

.btn-primary {
  background: var(--primary);
  color: white;
  border: 1px solid var(--primary);
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.85rem;
}

.hashtag-search-section {
  background: var(--card);
  padding: 1.5rem;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  border: 1px solid var(--border);
}

.search-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.search-input-wrapper > .fa-hashtag {
  position: absolute;
  left: 1rem;
  color: var(--muted-foreground);
  font-size: 1.1rem;
}

.hashtag-search-input {
  width: 100%;
  padding: 0.875rem 3rem 0.875rem 3rem;
  border: 2px solid var(--border);
  border-radius: 8px;
  font-size: 0.95rem;
  background: var(--background);
  color: var(--foreground);
  transition: all 0.3s ease;
}

.hashtag-search-input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.clear-search-btn {
  position: absolute;
  right: 0.75rem;
  background: var(--muted);
  border: none;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--muted-foreground);
  transition: all 0.2s ease;
}

.trending-hashtags {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.trending-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--muted-foreground);
}

.hashtag-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.hashtag-pill {
  display: inline-flex;
  align-items: center;
  padding: 0.4rem 0.875rem;
  background: var(--muted);
  color: var(--foreground);
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: 2px solid transparent;
}

.hashtag-pill:hover {
  background: var(--primary);
  color: white;
  transform: translateY(-2px);
}

.topics-container {
  display: grid;
  gap: 1.5rem;
}

.topic-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.topic-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}
</style>

<div class="dashboard-container">
  <!-- sidebar -->
  <?php require '../app/views/partials/student_sidebar.php'; ?>

  <!-- Main Content Area -->
  <main class="main-content">
    <!-- Forum Topics Section -->
    <section class="dashboard-section forum-topics-section">
      <div class="section-header">
        <div class="section-title-container">
          <h2 class="section-title">Forum Topics</h2>
          <div class="filter-controls">
            <select class="filter-select" id="categoryFilter">
              <option value="">All Categories</option>
              <option value="csf">CSF</option>
              <option value="studytips">StudyTips</option>
              <option value="general">General</option>
            </select>
            <a href="<?=ROOT?>/student/discussionforum" class="btn btn-outline">
              <i class="fas fa-arrow-left"></i>
              <span>Back</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Hashtag Search Section -->
      <div class="hashtag-search-section">
        <div class="search-input-wrapper">
          <i class="fas fa-hashtag"></i>
          <input 
            type="text" 
            id="hashtagSearch" 
            class="hashtag-search-input" 
            placeholder="Search by hashtag (e.g., career, mentorship, AI)"
            autocomplete="off"
          >
          <button class="clear-search-btn" id="clearSearchBtn" style="display: none;">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="trending-hashtags">
          <span class="trending-label">Trending:</span>
          <div class="hashtag-pills">
            <span class="hashtag-pill" data-tag="career">#career</span>
            <span class="hashtag-pill" data-tag="mentorship">#mentorship</span>
            <span class="hashtag-pill" data-tag="AI">#AI</span>
            <span class="hashtag-pill" data-tag="networking">#networking</span>
            <span class="hashtag-pill" data-tag="experience">#experience</span>
            <span class="hashtag-pill" data-tag="tech">#tech</span>
          </div>
        </div>
      </div>
      
      <div class="topics-container">
        <?php foreach ($forumData['topics'] as $topic): ?>
        <div class="topic-card" data-category="<?= strtolower($topic['category']) ?>">
          <div class="topic-header">
            <div class="topic-info">
              <h3 class="topic-title"><?= esc($topic['title']) ?></h3>
              <p class="topic-creator">Created by <?= esc($topic['creator']) ?></p>
            </div>
            <span class="status-badge status-<?= $topic['status'] ?>">
              <?= ucfirst($topic['status']) ?>
            </span>
          </div>
          
          <div class="topic-description">
            <p><?= esc($topic['description']) ?></p>
          </div>
          
          <?php if (isset($topic['tags'])): ?>
          <div class="topic-tags">
            <?php foreach ($topic['tags'] as $tag): ?>
            <span class="topic-tag tag-<?= strtolower($tag) ?>">#<?= esc($tag) ?></span>
            <?php endforeach; ?>
          </div>
          <?php else: ?>
          <div class="topic-tags">
            <span class="topic-tag tag-<?= strtolower($topic['category']) ?>"><?= esc($topic['category']) ?></span>
          </div>
          <?php endif; ?>
          
          <div class="topic-footer">
            <div class="topic-meta">
              <div class="topic-views"><i class="fas fa-eye"></i> <strong><?= $topic['views'] ?></strong></div>
              <div class="topic-replies"><i class="fas fa-comment"></i> <strong><?= $topic['replies'] ?></strong> replies</div>
              <div class="topic-activity"><i class="fas fa-clock"></i> <?= esc($topic['last_activity']) ?></div>
            </div>
            <button class="btn btn-primary btn-sm" onclick="viewForumWithLoading(<?= $topic['id'] ?>, this)">
              <i class="fas fa-eye"></i> View
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?=ROOT?>/assets/js/main.js"></script>
<script src="<?=ROOT?>/assets/js/discussion-forum.js"></script>

<script>
// Hashtag Search Functionality
const hashtagSearchInput = document.getElementById('hashtagSearch');
const clearSearchBtn = document.getElementById('clearSearchBtn');
const hashtagPills = document.querySelectorAll('.hashtag-pill');
const topicCards = document.querySelectorAll('.topic-card');
const categoryFilter = document.getElementById('categoryFilter');

// Search input handler
if (hashtagSearchInput) {
  hashtagSearchInput.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().replace('#', '');
    clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
    filterTopics();
    updatePillHighlights(searchTerm);
  });
}

// Clear search button
if (clearSearchBtn) {
  clearSearchBtn.addEventListener('click', function() {
    hashtagSearchInput.value = '';
    clearSearchBtn.style.display = 'none';
    filterTopics();
    updatePillHighlights('');
  });
}

// Hashtag pill click handlers
hashtagPills.forEach(pill => {
  pill.addEventListener('click', function() {
    const tag = this.getAttribute('data-tag');
    hashtagSearchInput.value = tag;
    clearSearchBtn.style.display = 'flex';
    filterTopics();
    updatePillHighlights(tag.toLowerCase());
  });
});

// Category filter
if (categoryFilter) {
  categoryFilter.addEventListener('change', function() {
    filterTopics();
  });
}

// Combined filter function
function filterTopics() {
  const searchTerm = hashtagSearchInput.value.toLowerCase().replace('#', '');
  const selectedCategory = categoryFilter.value.toLowerCase();
  let visibleCount = 0;
  
  topicCards.forEach(card => {
    let matchesSearch = true;
    let matchesCategory = true;
    
    // Category filter
    if (selectedCategory) {
      const cardCategory = card.getAttribute('data-category');
      matchesCategory = cardCategory === selectedCategory;
    }
    
    // Hashtag/text search
    if (searchTerm) {
      const tags = card.querySelectorAll('.topic-tag');
      const title = card.querySelector('.topic-title')?.textContent.toLowerCase() || '';
      const description = card.querySelector('.topic-description')?.textContent.toLowerCase() || '';
      
      let hasTagMatch = false;
      tags.forEach(tag => {
        const tagText = tag.textContent.toLowerCase().replace('#', '');
        if (tagText.includes(searchTerm)) {
          hasTagMatch = true;
        }
      });
      
      matchesSearch = hasTagMatch || title.includes(searchTerm) || description.includes(searchTerm);
    }
    
    // Show/hide based on both filters
    if (matchesSearch && matchesCategory) {
      card.style.display = 'block';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });
  
  showNoResultsMessage(visibleCount);
}

// Update pill highlights
function updatePillHighlights(searchTerm) {
  hashtagPills.forEach(pill => {
    const pillTag = pill.getAttribute('data-tag').toLowerCase();
    if (searchTerm && pillTag === searchTerm) {
      pill.classList.add('active');
    } else {
      pill.classList.remove('active');
    }
  });
}

// Show/hide no results message
function showNoResultsMessage(visibleCount) {
  let noResultsMsg = document.getElementById('noResultsMessage');
  const topicsContainer = document.querySelector('.topics-container');
  
  if (visibleCount === 0) {
    if (!noResultsMsg) {
      noResultsMsg = document.createElement('div');
      noResultsMsg.id = 'noResultsMessage';
      noResultsMsg.className = 'no-results-message';
      noResultsMsg.innerHTML = `
        <i class="fas fa-search"></i>
        <p>No discussions found</p>
        <small>Try adjusting your search or filters</small>
      `;
      topicsContainer.appendChild(noResultsMsg);
    }
    noResultsMsg.style.display = 'block';
  } else {
    if (noResultsMsg) {
      noResultsMsg.style.display = 'none';
    }
  }
}

function showForumPageLoading() {
  let overlay = document.getElementById('forumPageLoadingOverlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.id = 'forumPageLoadingOverlay';
    overlay.innerHTML = `
      <div style="display:flex; flex-direction:column; align-items:center; gap:10px; color:#ffffff;">
        <i class="fas fa-spinner fa-spin" style="font-size:28px;"></i>
        <span style="font-weight:600;">Opening discussion...</span>
      </div>
    `;
    Object.assign(overlay.style, {
      position: 'fixed',
      inset: '0',
      background: 'rgba(17, 24, 39, 0.55)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: '9999'
    });
    document.body.appendChild(overlay);
  }
}

function viewForumWithLoading(postId, triggerBtn) {
  if (triggerBtn) {
    const viewCountEl = triggerBtn.closest('.topic-card')?.querySelector('.topic-views strong');
    if (viewCountEl) {
      const currentViews = parseInt(viewCountEl.textContent, 10) || 0;
      viewCountEl.textContent = String(currentViews + 1);
    }
  }

  showForumPageLoading();
  window.location.href = '<?= ROOT ?>/student/discussionforum?view=' + encodeURIComponent(postId);
}
</script>
</body>
</html>
