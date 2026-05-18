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
              <h2 class="section-title fae-33">
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
              <button class="clear-search-btn fae-10" id="clearSearchBtn">
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
              <div class="forum-empty-state">
                <i class="fas fa-comments forum-empty-state-icon"></i>
                <p class="forum-empty-state-title">No forum topics available for your faculty yet.</p>
                <p class="forum-empty-state-subtitle">Be the first to start a discussion!</p>
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
                <div class="topic-actions fae-32">
                  <button class="btn btn-primary btn-sm" onclick="incrementAndViewPost(<?= $topic->post_id ?>, this)">
                    <i class="fas fa-eye"></i> View
                  </button>
                  <button class="btn btn-outline btn-sm fae-52" onclick="deleteVisiblePost(<?= $topic->post_id ?>)">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
            <?php endforeach; 
            endif; ?>
          </div>
        </section>
      </main>
    </div>

    <!-- Forum Detail Modal -->
    <div id="forumDetailModal" class="modal fae-10">
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
    function showForumLoadingState() {
      const modal = document.getElementById('forumDetailModal');
      if (!modal) return;

      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';

      let loader = document.getElementById('forumDetailLoadingOverlay');
      if (!loader) {
        loader = document.createElement('div');
        loader.id = 'forumDetailLoadingOverlay';
        loader.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.82);z-index:20;';
        loader.innerHTML = '<div class="fae-53"><i class="fas fa-spinner fa-spin fae-54"></i><div class="fae-55">Loading forum content...</div></div>';
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
          if (!modalContent.style.position) {
            modalContent.style.position = 'relative';
          }
          modalContent.appendChild(loader);
        }
      }

      loader.style.display = 'flex';
    }

    function hideForumLoadingState() {
      const loader = document.getElementById('forumDetailLoadingOverlay');
      if (loader) loader.style.display = 'none';
    }

    function incrementAndViewPost(postId, triggerBtn) {
      if (triggerBtn) {
        const card = triggerBtn.closest('.topic-card');
        const viewsEl = card ? card.querySelector('.topic-views strong') : null;
        if (viewsEl) {
          const currentViews = parseInt(viewsEl.textContent || '0', 10) || 0;
          viewsEl.textContent = String(currentViews + 1);
        }
      }

      showForumLoadingState();

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

    function deleteVisiblePost(postId) {
      if (!confirm('Are you sure you want to delete this forum post? This cannot be undone.')) {
        return;
      }

      fetch(`<?=ROOT?>/admin/forummoderation/deletePost/${postId}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message || 'Post deleted successfully', 'success');
          setTimeout(() => location.reload(), 400);
        } else {
          showNotification(data.message || 'Failed to delete post', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while deleting the post', 'error');
      });
    }

    function loadForumDetail(postId) {
      currentPostId = postId;
      showForumLoadingState();
      
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
            hideForumLoadingState();
          } else {
            hideForumLoadingState();
            showNotification('Failed to load forum details: ' + (data.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          hideForumLoadingState();
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
        repliesList.innerHTML = '<p class="fae-48">No replies yet. Be the first to reply!</p>';
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
      hideForumLoadingState();
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
    