<?php 
$page_title = "All Forum Topics";
$page_subtitle = "Browse all forum discussions for your faculty";

// Trending Posts Configuration
$trending_threshold = 50;  // Minimum points to show "Trending" badge
$trending_hours = 48;      // Time window in hours for calculating trending points

require '../app/views/partials/admin_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css">

    <!-- Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>

    <div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/admin_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- All Forum Topics Section -->
        <section class="dashboard-section forum-topics-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="section-title" style="font-size: 1.5rem;">
                <i class="fas fa-comments"></i> All Forum Topics
              </h2>
              <div class="filter-controls">
                <select class="filter-select">
                  <option>Most Recent</option>
                  <option>Most Popular</option>
                  <option>Most Active</option>
                </select>
                <a href="<?=ROOT?>/admin/forummoderation" class="btn btn-outline">
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
            <?php 
            // Show ALL topics visible to faculty
            $displayTopics = isset($all_posts) && is_array($all_posts) ? $all_posts : [];
            
            if (empty($displayTopics)): ?>
              <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p style="font-size: 16px; margin: 0;">No forum topics available for your faculty yet.</p>
                <p style="font-size: 14px; margin-top: 8px; opacity: 0.8;">Be the first to start a discussion!</p>
              </div>
            <?php else:
            foreach ($displayTopics as $topic): 
              // Format created_at as "X time ago"
              $timestamp = strtotime($topic->created_at);
              $diff = time() - $timestamp;
              if ($diff < 60) $last_activity = $diff . ' seconds ago';
              elseif ($diff < 3600) $last_activity = floor($diff / 60) . ' minutes ago';
              elseif ($diff < 86400) $last_activity = floor($diff / 3600) . ' hours ago';
              else $last_activity = floor($diff / 86400) . ' days ago';
              
              // Parse tags from string to array
              $tags_array = [];
              if (!empty($topic->tags)) {
                // Remove # symbols and split by space or comma
                $tags_string = str_replace('#', '', $topic->tags);
                $tags_array = preg_split('/[\s,]+/', $tags_string, -1, PREG_SPLIT_NO_EMPTY);
              }
            ?>
            <div class="topic-card">
              <div class="topic-header">
                <div class="topic-info">
                  <h3 class="topic-title"><?= esc($topic->title) ?></h3>
                  <p class="topic-creator">Created by <?= esc($topic->author_name ?? 'Unknown') ?></p>
                </div>
                <?php if (isset($topic->trending_points) && $topic->trending_points >= $trending_threshold): ?>
                  <span class="status-badge status-trending">
                    Trending
                  </span>
                <?php else: ?>
                  <span class="status-badge status-active">
                    Active
                  </span>
                <?php endif; ?>
              </div>
              
              <div class="topic-description">
                <p><?= esc(substr($topic->content, 0, 150)) ?><?= strlen($topic->content) > 150 ? '...' : '' ?></p>
              </div>
              
              <?php if (!empty($tags_array)): ?>
              <div class="topic-tags">
                <?php foreach ($tags_array as $tag): ?>
                <span class="topic-tag tag-<?= strtolower($tag) ?>">#<?= esc($tag) ?></span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
              
              <div class="topic-footer">
                <div class="topic-meta">
                  <div class="topic-views"><i class="fas fa-eye"></i> <strong><?= $topic->views ?? 0 ?></strong></div>
                  <div class="topic-replies"><i class="fas fa-comment"></i> <strong><?= $topic->replies ?? 0 ?></strong> replies</div>
                  <div class="topic-activity"><i class="fas fa-clock"></i> <?= $last_activity ?></div>
                </div>
                <button class="btn btn-primary btn-sm" onclick="incrementAndViewPost(<?= $topic->post_id ?>)">
                  <i class="fas fa-eye"></i> View
                </button>
              </div>
            </div>
            <?php endforeach; 
            endif; ?>
          </div>
        </section>
      </main>
    </div>

    <!-- Forum Detail Modal -->
    <div id="forumDetailModal" class="modal" style="display: none;">
      <div class="modal-content modal-large">
        <div class="modal-header">
          <h2 class="modal-title" id="forumDetailTitle">Forum Topic Title</h2>
          <button class="modal-close" onclick="closeForumDetailModal()"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="forum-detail-content">
          <!-- Forum Meta Info -->
          <div class="forum-detail-meta">
            <div class="creator-info">
              <i class="fas fa-user-circle"></i>
              <span>Created by <strong id="forumDetailCreator">Creator Name</strong></span>
            </div>
            <div class="forum-stats">
              <span><i class="fas fa-eye"></i> <strong id="forumDetailViews">0</strong> views</span>
              <span><i class="fas fa-comment"></i> <strong id="forumDetailReplies">0</strong> replies</span>
              <span><i class="fas fa-clock"></i> <span id="forumDetailActivity">Last activity</span></span>
            </div>
          </div>

          <!-- Forum Tags -->
          <div class="forum-detail-tags" id="forumDetailTagsContainer">
            <!-- Tags will be dynamically inserted -->
          </div>

          <!-- Forum Description -->
          <div class="forum-detail-description">
            <h3>Description</h3>
            <p id="forumDetailDescription">Forum description goes here...</p>
          </div>

          <!-- Replies Section -->
          <div class="replies-section">
            <h3 class="replies-heading">
              <i class="fas fa-comments"></i> 
              Replies (<span id="repliesCount">0</span>)
            </h3>
            
            <div class="replies-list" id="repliesList">
              <!-- Replies will be dynamically loaded from database -->
            </div>

            <!-- View All Replies Button -->
            <div class="view-all-replies-container" id="viewAllRepliesBtn">
              <button class="btn-view-all-replies" onclick="toggleAllReplies()">
                <i class="fas fa-comments"></i> View All Replies
              </button>
            </div>
          </div>

          <!-- Reply Form -->
          <div class="reply-form-container">
            <h4><i class="fas fa-pen"></i> Add Your Reply</h4>
            <form onsubmit="return submitReply(event, currentPostId)">
              <textarea 
                class="reply-textarea" 
                name="reply"
                placeholder="Share your thoughts..."
                rows="4"
                required
              ></textarea>
              <div class="reply-form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeForumDetailModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-paper-plane"></i> Post Reply
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Global variables
    let currentPostId = null;

    // Notification System
    function showNotification(message, type = 'success') {
      const container = document.getElementById('notificationContainer');
      if (!container) return;
      
      const notification = document.createElement('div');
      notification.className = `notification notification-${type}`;
      
      const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
      
      notification.innerHTML = `
        <div class="notification-icon">${icon}</div>
        <div class="notification-message">${message}</div>
      `;
      
      container.appendChild(notification);
      
      setTimeout(() => notification.classList.add('show'), 10);
      
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
      }, 4000);
    }

    // Forum View and Detail Functions
    function incrementAndViewPost(postId) {
      fetch(`<?=ROOT?>/admin/forummoderation/incrementView/${postId}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        loadForumDetail(postId);
      })
      .catch(error => {
        console.error('Error:', error);
        loadForumDetail(postId);
      });
    }

    function loadForumDetail(postId) {
      currentPostId = postId;
      
      fetch(`<?=ROOT?>/admin/forummoderation/getPostDetail/${postId}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            window.likedReplyIds = data.likedReplyIds || [];
            displayForumDetail(data.post, data.replies);
          } else {
            showNotification('Failed to load forum details: ' + (data.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('An error occurred while loading forum details: ' + error.message, 'error');
        });
    }

    function displayForumDetail(post, replies) {
      if (!Array.isArray(replies)) {
        replies = [];
      }
      
      document.getElementById('forumDetailTitle').textContent = post.title;
      document.getElementById('forumDetailCreator').textContent = post.author_name || 'Unknown';
      document.getElementById('forumDetailViews').textContent = post.views || 0;
      document.getElementById('forumDetailReplies').textContent = replies.length;
      
      const createdDate = new Date(post.created_at);
      const formattedDateTime = createdDate.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      
      document.getElementById('forumDetailActivity').textContent = formattedDateTime;
      document.getElementById('forumDetailDescription').textContent = post.content;
      
      const tagsContainer = document.getElementById('forumDetailTagsContainer');
      tagsContainer.innerHTML = '';
      if (post.tags) {
        const tags = post.tags.split(/[\s,]+/).filter(tag => tag.trim());
        tags.forEach(tag => {
          const tagSpan = document.createElement('span');
          tagSpan.className = 'topic-tag tag-' + tag.toLowerCase().replace('#', '');
          tagSpan.textContent = tag.startsWith('#') ? tag : '#' + tag;
          tagsContainer.appendChild(tagSpan);
        });
      }
      
      const repliesList = document.getElementById('repliesList');
      repliesList.innerHTML = '';
      
      if (!replies || replies.length === 0) {
        repliesList.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">No replies yet. Be the first to reply!</p>';
      } else {
        replies.forEach((reply, index) => {
          const replyItem = document.createElement('div');
          replyItem.className = 'reply-item';
          
          if (index >= 3) {
            replyItem.classList.add('hidden-reply');
            replyItem.style.display = 'none';
          }
          
          const replyDate = new Date(reply.repliedtime);
          const today = new Date();
          const isToday = replyDate.toDateString() === today.toDateString();
          
          let displayTime;
          if (isToday) {
            displayTime = replyDate.toLocaleTimeString('en-US', {
              hour: '2-digit',
              minute: '2-digit'
            });
          } else {
            displayTime = replyDate.toLocaleDateString('en-US', {
              month: 'short',
              day: 'numeric',
              year: 'numeric'
            });
          }
          
          const likedClass = (window.likedReplyIds && window.likedReplyIds.includes(reply.replyid)) ? 'liked' : '';
          
          replyItem.innerHTML = `
            <div class="reply-header">
              <div class="reply-author">
                <i class="fas fa-user-circle"></i>
                <span>${reply.author_name || 'Anonymous'}</span>
              </div>
              <span class="reply-time">${displayTime}</span>
            </div>
            <div class="reply-content">
              ${reply.reply}
            </div>
            <div class="reply-footer">
              <button class="btn-link btn-like ${likedClass}" data-reply-id="${reply.replyid}" onclick="toggleLikeReply(${reply.replyid}, this)">
                <i class="fas fa-thumbs-up"></i> 
                <span class="like-count">${reply.likes || 0}</span> Like${(reply.likes || 0) !== 1 ? 's' : ''}
              </button>
            </div>
          `;
          
          repliesList.appendChild(replyItem);
        });
      }
      
      document.getElementById('repliesCount').textContent = replies.length;
      
      const viewAllBtn = document.getElementById('viewAllRepliesBtn');
      if (viewAllBtn) {
        if (replies.length > 3) {
          viewAllBtn.style.display = 'block';
        } else {
          viewAllBtn.style.display = 'none';
        }
      }
      
      document.getElementById('forumDetailModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function toggleAllReplies() {
      const hiddenReplies = document.querySelectorAll('.hidden-reply');
      const viewAllBtn = document.querySelector('.btn-view-all-replies');
      
      if (hiddenReplies.length === 0) return;
      
      const isHidden = hiddenReplies[0].style.display === 'none';
      
      if (isHidden) {
        hiddenReplies.forEach(reply => reply.style.display = 'block');
        viewAllBtn.innerHTML = '<i class="fas fa-chevron-up"></i> Show Less';
      } else {
        hiddenReplies.forEach(reply => reply.style.display = 'none');
        viewAllBtn.innerHTML = '<i class="fas fa-comments"></i> View All Replies';
        document.getElementById('repliesList').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    function closeForumDetailModal() {
      document.getElementById('forumDetailModal').style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    function submitReply(event, postId) {
      event.preventDefault();
      
      const formData = new FormData(event.target);
      formData.append('post_id', postId);
      
      fetch('<?=ROOT?>/admin/forummoderation/addReply', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          event.target.reset();
          loadForumDetail(postId);
        } else {
          console.error(data.message || 'Failed to post reply');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
      
      return false;
    }

    function toggleLikeReply(replyId, buttonElement) {
      fetch(`<?=ROOT?>/admin/forummoderation/likeReply/${replyId}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const likeCountSpan = buttonElement.querySelector('.like-count');
          if (likeCountSpan) {
            likeCountSpan.textContent = data.likes;
            const likeText = data.likes !== 1 ? 's' : '';
            buttonElement.innerHTML = `<i class="fas fa-thumbs-up"></i> <span class="like-count">${data.likes}</span> Like${likeText}`;
          }
          
          if (data.liked) {
            buttonElement.classList.add('liked');
            if (window.likedReplyIds && !window.likedReplyIds.includes(replyId)) {
              window.likedReplyIds.push(replyId);
            }
          } else {
            buttonElement.classList.remove('liked');
            if (window.likedReplyIds) {
              window.likedReplyIds = window.likedReplyIds.filter(id => id !== replyId);
            }
          }
        } else {
          console.error(data.message || 'Failed to toggle like');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }

    </script>

    <script>
    // Sort Filter Functionality
    const filterSelect = document.querySelector('.filter-select');
    if (filterSelect) {
      filterSelect.addEventListener('change', function(e) {
        sortTopics(e.target.value);
      });
    }
    
    function sortTopics(sortType) {
      const topicsContainer = document.querySelector('.forum-topics-section .topics-container');
      const topicCardsArray = Array.from(topicsContainer.querySelectorAll('.topic-card'));
      
      topicCardsArray.sort((a, b) => {
        if (sortType === 'Most Recent') {
          const timeA = a.querySelector('.topic-activity')?.textContent.trim() || '';
          const timeB = b.querySelector('.topic-activity')?.textContent.trim() || '';
          return parseTimeToSeconds(timeA) - parseTimeToSeconds(timeB);
        } else if (sortType === 'Most Popular') {
          const viewsA = parseInt(a.querySelector('.topic-views strong')?.textContent) || 0;
          const viewsB = parseInt(b.querySelector('.topic-views strong')?.textContent) || 0;
          return viewsB - viewsA;
        } else if (sortType === 'Most Active') {
          const hasTrendingA = a.querySelector('.status-trending') !== null;
          const hasTrendingB = b.querySelector('.status-trending') !== null;
          
          if (hasTrendingA && !hasTrendingB) return -1;
          if (!hasTrendingA && hasTrendingB) return 1;
          
          const repliesA = parseInt(a.querySelector('.topic-replies strong')?.textContent) || 0;
          const repliesB = parseInt(b.querySelector('.topic-replies strong')?.textContent) || 0;
          return repliesB - repliesA;
        }
        return 0;
      });
      
      topicCardsArray.forEach(card => topicsContainer.appendChild(card));
    }
    
    function parseTimeToSeconds(timeStr) {
      const match = timeStr.match(/(\d+)\s*(second|minute|hour|day)/);
      if (!match) return 999999;
      
      const value = parseInt(match[1]);
      const unit = match[2];
      
      if (unit.startsWith('second')) return value;
      if (unit.startsWith('minute')) return value * 60;
      if (unit.startsWith('hour')) return value * 3600;
      if (unit.startsWith('day')) return value * 86400;
      return 999999;
    }

    // Hashtag Search
    const hashtagSearchInput = document.getElementById('hashtagSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const hashtagPills = document.querySelectorAll('.hashtag-pill');
    const topicCards = document.querySelectorAll('.forum-topics-section .topic-card');
    
    if (hashtagSearchInput) {
      hashtagSearchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().replace('#', '');
        
        clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
        filterTopicsByHashtag(searchTerm);
      });
    }
    
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', function() {
        hashtagSearchInput.value = '';
        clearSearchBtn.style.display = 'none';
        filterTopicsByHashtag('');
      });
    }
    
    hashtagPills.forEach(pill => {
      pill.addEventListener('click', function() {
        const tag = this.getAttribute('data-tag');
        hashtagSearchInput.value = tag;
        clearSearchBtn.style.display = 'flex';
        filterTopicsByHashtag(tag.toLowerCase());
      });
    });
    
    function filterTopicsByHashtag(searchTerm) {
      const topicCards = document.querySelectorAll('.forum-topics-section .topic-card');
      let visibleCount = 0;
      
      topicCards.forEach(card => {
        if (!searchTerm) {
          card.style.display = 'block';
          visibleCount++;
          return;
        }
        
        const tagsContainer = card.querySelector('.topic-tags');
        if (!tagsContainer) {
          card.style.display = 'none';
          return;
        }
        
        const tagElements = tagsContainer.querySelectorAll('.topic-tag');
        let hasMatch = false;
        
        tagElements.forEach(tagEl => {
          const tagText = tagEl.textContent.toLowerCase().replace('#', '');
          if (tagText.includes(searchTerm)) {
            hasMatch = true;
          }
        });
        
        if (hasMatch) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });
      
      showNoResultsMessage(visibleCount);
    }
    
    function showNoResultsMessage(visibleCount) {
      let noResultsMsg = document.getElementById('noResultsMessage');
      const topicsContainer = document.querySelector('.forum-topics-section .topics-container');
      
      if (visibleCount === 0) {
        if (!noResultsMsg) {
          noResultsMsg = document.createElement('div');
          noResultsMsg.id = 'noResultsMessage';
          noResultsMsg.className = 'no-results-message';
          noResultsMsg.innerHTML = `
            <i class="fas fa-search"></i>
            <p>No discussions found with this hashtag</p>
            <small>Try searching for different keywords or browse all topics</small>
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
    </script>
    <style>
    /* Filter Controls Styles */
    .filter-controls {
      display: flex;
      gap: 0.75rem;
      align-items: center;
    }

    .filter-select {
      padding: 0.625rem 2.5rem 0.625rem 1rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      background: var(--card);
      color: var(--foreground);
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 12px;
    }

    .filter-select:hover {
      border-color: var(--primary);
      background-color: var(--background);
    }

    .filter-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Hashtag Search Styles */
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
    
    .clear-search-btn:hover {
      background: var(--accent);
      color: var(--accent-foreground);
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
      white-space: nowrap;
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
      box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2);
    }
    
    .hashtag-pill.active {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .no-results-message {
      text-align: center;
      padding: 3rem 2rem;
      color: var(--muted-foreground);
    }
    
    .no-results-message i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }
    
    .no-results-message p {
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: var(--foreground);
    }
    
    .no-results-message small {
      font-size: 0.875rem;
      color: var(--muted-foreground);
    }

    /* Topic Card Styles */
    .topic-card {
      background-color: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.5rem;
      transition: all 0.2s ease;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .topic-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .topic-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
    }

    .topic-info {
      flex: 1;
    }

    .topic-title {
      font-size: 1.125rem;
      font-weight: 600;
      color: var(--foreground);
      margin: 0 0 0.5rem 0;
      line-height: 1.3;
    }

    .topic-creator {
      font-size: 0.875rem;
      color: var(--muted-foreground);
      margin: 0;
      font-weight: 400;
    }

    .topic-description {
      margin-bottom: 1rem;
    }

    .topic-description p {
      font-size: 1rem;
      color: var(--foreground);
      line-height: 1.6;
      margin: 0;
    }

    .topic-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .topic-tag {
      padding: 0.375rem 0.75rem;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .tag-career {
      background-color: #E3F2FD;
      color: #1976D2;
    }

    .tag-mentorship {
      background-color: #E8F5E8;
      color: #2E7D32;
    }

    .tag-general {
      background-color: #F5F5F5;
      color: #616161;
    }

    .tag-networking {
      background-color: #FFF3E0;
      color: #F57C00;
    }

    .tag-leadership {
      background-color: #F3E5F5;
      color: #7B1FA2;
    }

    .tag-entrepreneurship {
      background-color: #FCE4EC;
      color: #C2185B;
    }

    .tag-tech {
      background-color: #E1F5FE;
      color: #0277BD;
    }

    .tag-ai {
      background-color: #FFF9C4;
      color: #F57F17;
    }

    .tag-experience {
      background-color: #E0F2F1;
      color: #00695C;
    }

    .tag-advice {
      background-color: #FFF3E0;
      color: #EF6C00;
    }

    .tag-trending {
      background-color: #FFF8E1;
      color: #F57C00;
    }

    .tag-remote {
      background-color: #E8EAF6;
      color: #3F51B5;
    }

    .tag-management {
      background-color: #FCE4EC;
      color: #AD1457;
    }

    .tag-software {
      background-color: #E0F7FA;
      color: #00838F;
    }

    .tag-productivity {
      background-color: #F1F8E9;
      color: #558B2F;
    }

    .topic-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 1rem;
    }

    .topic-meta {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      align-items: center;
      flex-direction: row;
    }

    .topic-views,
    .topic-replies,
    .topic-activity {
      font-size: 0.875rem;
      color: var(--muted-foreground);
      font-weight: 400;
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      white-space: nowrap;
    }

    .topic-views strong,
    .topic-replies strong {
      color: var(--foreground);
      font-weight: 600;
    }

    .topic-views i,
    .topic-replies i,
    .topic-activity i {
      color: var(--primary);
    }

    .status-badge {
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-trending {
      background: #ef4444;
      color: white;
    }

    .status-active {
      background: #10b981;
      color: white;
    }

    .status-pending {
      background: #f59e0b;
      color: white;
    }
    </style>