<?php 
$page_title = "Discussion Forum";
$page_subtitle = "Engage with fellow alumni and share experiences";

// Trending Posts Configuration
$trending_threshold = 50;  // Minimum points to show "Trending" badge
$trending_hours = 48;      // Time window in hours for calculating trending points

require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css?v=<?=time()?>">

    <div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content forum-ui discussion-main-page">

        <nav class="forum-quick-nav" aria-label="Forum sections">
          <a href="#forum-topics" class="forum-nav-link">Forum Topics</a>
          <a href="#my-forums" class="forum-nav-link">My Published Forums</a>
          <a href="#my-replies" class="forum-nav-link">My Replies</a>
        </nav>

        <!-- Hashtag Search Section -->
        <div class="hashtag-search-section">
          <div class="forum-search-row">
            <div class="search-input-wrapper">
              <i class="fas fa-hashtag"></i>
              <input 
                type="text" 
                id="hashtagSearch" 
                class="hashtag-search-input" 
                placeholder="Search topics (e.g., #career, internship, AI ethics, mentorship)"
                autocomplete="off"
              >
              <button class="clear-search-btn" id="clearSearchBtn">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <button class="btn btn-primary top-new-post-btn" onclick="openNewPostModal()">
              <i class="fas fa-plus"></i>
              <span>New Post</span>
            </button>
          </div>
        </div>
        

        <!-- Forum Topics Section -->
        <section id="forum-topics" class="dashboard-section forum-topics-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="forum-heading">Forum Topics</h2>
              <div class="filter-controls">
                <select class="filter-select">
                  <option>Most Recent</option>
                  <option>Most Popular</option>
                  <option>Most Active</option>
                </select>
                <a href="<?=ROOT?>/alumni/discussionforum/viewall" class="btn btn-outline">
                  <span>View All</span>
                  <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="topics-container">
            <?php 
            // Show only one-row preview; use View All for the full list
            $displayTopics = isset($faculty_posts) && is_array($faculty_posts) ? array_slice($faculty_posts, 0, 3) : [];
            
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
            <div class="topic-card topic-card-clickable" data-post-id="<?= (int)$topic->post_id ?>" onclick="incrementAndViewPost(<?= $topic->post_id ?>, this)" role="button" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();incrementAndViewPost(<?= $topic->post_id ?>, this);}">
              <div class="topic-header">
                <div class="topic-info">
                  <h3 class="topic-title"><?= esc($topic->title) ?></h3>
                  <p class="topic-creator">Created by <?= esc($topic->author_name ?? 'Unknown') ?></p>
                </div>
                <?php if (isset($topic->trending_points) && $topic->trending_points >= $trending_threshold): ?>
                  <span class="status-badge status-trending">
                    Trending
                  </span>
                <?php endif; ?>
              </div>
              
              <div class="topic-description">
                <p><?= esc($topic->content ?? '') ?></p>
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
                  <div class="topic-replies"><i class="fas fa-comment"></i> <strong><?= $topic->replies ?? 0 ?></strong> replies</div>
                  <div class="topic-activity"><i class="fas fa-clock"></i> <?= $last_activity ?></div>
                </div>
              </div>
            </div>
            <?php endforeach; 
            endif; ?>
          </div>
        </section>

<!-- My Published Forums Section -->
        <section id="my-forums" class="dashboard-section my-published-forums-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="forum-heading">My Published Forums</h2>
            </div>
          </div>
          
          <div class="my-published-container">
            <?php 
            // Use real database data from controller
            $myPublishedForums = isset($my_posts) && is_array($my_posts) ? $my_posts : [];
            
            if (empty($myPublishedForums)): ?>
              <div class="no-forums-message">
                <i class="fas fa-newspaper"></i>
                <p>No Forums Published Yet</p>
                <small>Start a discussion and share your knowledge with the community. Click "New Post" to create your first forum topic!</small>
              </div>
            <?php else: ?>
              <div class="topics-container">
                <?php foreach ($myPublishedForums as $forum): 
                  // Format created_at as "X time ago"
                  $timestamp = strtotime($forum->created_at);
                  $diff = time() - $timestamp;
                  if ($diff < 60) $posted_date = $diff . ' seconds ago';
                  elseif ($diff < 3600) $posted_date = floor($diff / 60) . ' minutes ago';
                  elseif ($diff < 86400) $posted_date = floor($diff / 3600) . ' hours ago';
                  else $posted_date = floor($diff / 86400) . ' days ago';
                  
                  // Format created_at as "X time ago" for last activity
                  $created_timestamp = strtotime($forum->created_at);
                  $created_diff = time() - $created_timestamp;
                  if ($created_diff < 60) $last_activity = $created_diff . ' seconds ago';
                  elseif ($created_diff < 3600) $last_activity = floor($created_diff / 60) . ' minutes ago';
                  elseif ($created_diff < 86400) $last_activity = floor($created_diff / 3600) . ' hours ago';
                  else $last_activity = floor($created_diff / 86400) . ' days ago';
                  
                  // Parse tags from string to array
                  $tags_array = [];
                  if (!empty($forum->tags)) {
                    $tags_string = str_replace('#', '', $forum->tags);
                    $tags_array = preg_split('/[\s,]+/', $tags_string, -1, PREG_SPLIT_NO_EMPTY);
                  }
                ?>
                  <div class="topic-card">
                    <div class="topic-header">
                      <div class="topic-info">
                        <h3 class="topic-title"><?= esc($forum->title) ?></h3>
                        <p class="topic-creator">Created by You</p>
                      </div>
                      <?php if (isset($forum->trending_points) && $forum->trending_points >= $trending_threshold): ?>
                        <span class="status-badge status-trending">
                          Trending
                        </span>
                      <?php endif; ?>
                    </div>
                    
                    <div class="topic-description">
                      <p><?= esc(substr($forum->content, 0, 150)) ?><?= strlen($forum->content) > 150 ? '...' : '' ?></p>
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
                        <div class="topic-views"><i class="fas fa-eye"></i> <strong><?= $forum->views ?? 0 ?></strong></div>
                        <div class="topic-replies"><i class="fas fa-comment"></i> <strong><?= $forum->replies ?? 0 ?></strong> replies</div>
                        <div class="topic-activity"><i class="fas fa-clock"></i> <?= $last_activity ?></div>
                      </div>
                      <div class="topic-actions">
                        <button class="btn btn-primary btn-sm" onclick="incrementAndViewPost(<?= $forum->post_id ?>, this)">
                          <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn-action btn-edit" onclick="openEditForumModal(<?= $forum->post_id ?>)">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDeleteForum(<?= $forum->post_id ?>)">
                          <i class="fas fa-trash"></i> Delete
                        </button>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <!-- My Replies Section -->
        <section id="my-replies" class="dashboard-section my-replies-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="forum-heading">My Replies</h2>
            </div>
          </div>
          
          <div class="my-replies-container">
            <?php
            $myReplies = isset($my_replies) && is_array($my_replies) ? $my_replies : [];
            $repliesByThread = [];
            foreach ($myReplies as $reply) {
              $threadId = (int)($reply->forum_id ?? 0);
              if (!isset($repliesByThread[$threadId])) {
                $repliesByThread[$threadId] = [
                  'forum_id' => $threadId,
                  'forum_title' => (string)($reply->forum_title ?? 'Deleted Post'),
                  'items' => []
                ];
              }
              $repliesByThread[$threadId]['items'][] = $reply;
            }
            ?>

            <?php if (empty($repliesByThread)): ?>
              <div class="no-replies-message">
                <i class="fas fa-comments"></i>
                <p>No Replies Yet</p>
                <small>Your replies to forum discussions will appear here. Join conversations and share your insights!</small>
              </div>
            <?php else: ?>
              <div class="replies-grid">
                <?php foreach ($repliesByThread as $thread): ?>
                  <article class="reply-thread" data-thread-id="<?= (int)$thread['forum_id'] ?>">
                    <header class="reply-thread-header">
                      <a href="javascript:void(0)" class="reply-thread-title" onclick="incrementAndViewPost(<?= (int)$thread['forum_id'] ?>, this)">
                        <?= esc($thread['forum_title']) ?>
                      </a>
                    </header>

                    <div class="reply-thread-list">
                      <?php foreach ($thread['items'] as $reply):
                        $timestamp = strtotime((string)$reply->repliedtime);
                        $diff = time() - $timestamp;
                        if ($diff < 60) $posted_date = $diff . ' seconds ago';
                        elseif ($diff < 3600) $posted_date = floor($diff / 60) . ' minutes ago';
                        elseif ($diff < 86400) $posted_date = floor($diff / 3600) . ' hours ago';
                        else $posted_date = floor($diff / 86400) . ' days ago';
                      ?>
                        <div class="thread-reply-item">
                          <div class="thread-reply-meta">
                            <span class="reply-date"><i class="fas fa-clock"></i> <?= esc($posted_date) ?></span>
                            <span class="reply-likes"><i class="fas fa-thumbs-up"></i> <?= (int)($reply->likes ?? 0) ?> likes</span>
                          </div>
                          <p class="reply-text"><?= esc((string)($reply->reply ?? '')) ?></p>
                          <div class="thread-reply-actions">
                            <button class="btn-action btn-edit" onclick="openEditReplyModal(<?= (int)$reply->replyid ?>, '<?= htmlspecialchars((string)($reply->reply ?? ''), ENT_QUOTES) ?>')">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-action btn-delete" onclick="confirmDeleteReply(<?= (int)$reply->replyid ?>)">
                              <i class="fas fa-trash"></i> Delete
                            </button>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      </main>
    </div>

    <!-- Forum Detail Modal -->
    <div id="forumDetailModal" class="modal">
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
              <span class="status-badge" id="forumDetailStatus">Active</span>
              <span><i class="fas fa-eye"></i> <strong id="forumDetailViews">0</strong> views</span>
              <span><i class="fas fa-comment"></i> <strong id="forumDetailReplies">0</strong> replies</span>
              <span><i class="fas fa-clock"></i> <span id="forumDetailActivity">Last activity</span></span>
            </div>
          </div>

          <!-- Forum Category & Tags -->
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
              <form class="reply-form" onsubmit="submitReply(event)">
                <textarea 
                  class="reply-textarea" 
                  placeholder="Share your thoughts, experiences, or advice..."
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
    </div>

    <!-- Edit Reply Modal -->
    <div id="editReplyModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">
            <i class="fas fa-edit"></i> Edit Reply
          </h2>
          <button class="modal-close" onclick="closeEditReplyModal()"><i class="fas fa-times"></i></button>
        </div>
        <form class="edit-reply-form" onsubmit="submitEditReply(event)">
          <input type="hidden" id="editReplyId" value="">
          <div class="form-group">
            <label for="editReplyText">Your Reply</label>
            <textarea 
              id="editReplyText"
              class="form-control reply-textarea" 
              placeholder="Edit your reply..."
              rows="6"
              required
            ></textarea>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeEditReplyModal()">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- New Post Modal -->
    <div id="newPostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Post</h2>
          <button class="modal-close" onclick="closeNewPostModal()"><i class="fas fa-times"></i></button>
        </div>
        <form class="new-post-form">
          <div class="form-group">
            <label for="postTitle">Title *</label>
            <input type="text" id="postTitle" name="title" placeholder="e.g., Industry insights and career advice" required>
            <small>At least 10 characters.</small>
          </div>
          <div class="form-group">
            <label for="postContent">Content *</label>
            <textarea id="postContent" name="content" placeholder="Share your experiences and insights..." rows="6" required></textarea>
            <small>At least 50 characters.</small>
          </div>
          <div class="form-group">
            <label for="visibleFaculties">Visible Faculties *</label>
            <select id="visibleFaculties" name="faculty_id" class="form-control forum-faculty-select" required>
              <option value="">-- Select Faculty --</option>
              <option value="999">All Faculties</option>
              <?php if (!empty($faculties)): ?>
                <?php foreach ($faculties as $faculty): ?>
                  <option value="<?= $faculty->faculty_id ?>"><?= htmlspecialchars($faculty->faculty_name) ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <small>Select which faculty can see this post</small>
          </div>
          <div class="form-group">
            <label for="postTags">Tags</label>
            <textarea id="postTags" name="tags" class="tags-readonly-field" placeholder="Click 'Quick Tags' button below to select tags" rows="2" readonly></textarea>
            <small>Click the "Quick Tags" button to select tags from the list</small>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="openQuickTagsModal()">
              <i class="fas fa-hashtag"></i>
              <span>Quick Tags</span>
            </button>
            <button type="button" class="btn btn-outline" onclick="closeNewPostModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane"></i>
              <span>Publish</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editPostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Edit Post</h2>
          <button class="modal-close" onclick="closeEditForumModal()"><i class="fas fa-times"></i></button>
        </div>
        <form class="edit-post-form">
          <input type="hidden" id="editPostId" name="post_id" value="">
          <div class="form-group">
            <label for="editPostTitle">Title *</label>
            <input type="text" id="editPostTitle" name="title" placeholder="e.g., Industry insights and career advice" required>
            <small>At least 10 characters.</small>
          </div>
          <div class="form-group">
            <label for="editPostContent">Content *</label>
            <textarea id="editPostContent" name="content" placeholder="Share your experiences and insights..." rows="6" required></textarea>
            <small>At least 50 characters.</small>
          </div>
          <div class="form-group">
            <label for="editVisibleFaculties">Visible Faculties *</label>
            <select id="editVisibleFaculties" name="faculty_id" class="form-control forum-faculty-select" required>
              <option value="">-- Select Faculty --</option>
              <option value="999">All Faculties</option>
              <?php if (!empty($faculties)): ?>
                <?php foreach ($faculties as $faculty): ?>
                  <option value="<?= $faculty->faculty_id ?>"><?= htmlspecialchars($faculty->faculty_name) ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <small>Select which faculty can see this post</small>
          </div>
          <div class="form-group">
            <label for="editPostTags">Tags</label>
            <textarea id="editPostTags" name="tags" class="tags-readonly-field" placeholder="Click 'Quick Tags' button below to select tags" rows="2" readonly></textarea>
            <small>Click the "Quick Tags" button to select tags from the list</small>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="openQuickTagsModalForEdit()">
              <i class="fas fa-hashtag"></i>
              <span>Quick Tags</span>
            </button>
            <button type="button" class="btn btn-outline" onclick="closeEditForumModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              <span>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal">
      <div class="modal-content modal-content-sm">
        <div class="modal-header modal-header-danger">
          <h2 class="modal-title modal-title-danger">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteConfirmModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="danger-modal-body">
          <p class="danger-modal-text">
            Are you sure you want to delete this forum post?
          </p>
          <div class="danger-modal-warning-box">
            <p class="danger-modal-warning-text">
              <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone. All replies to this post will also be deleted.
            </p>
          </div>
        </div>
        <div class="modal-footer danger-modal-footer">
          <button class="btn btn-outline danger-modal-btn" onclick="closeDeleteConfirmModal()">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button class="btn btn-danger danger-modal-btn" onclick="executeDeleteForum()">
            <i class="fas fa-trash"></i>
            <span>Delete</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Reply Confirmation Modal -->
    <div id="deleteReplyConfirmModal" class="modal">
      <div class="modal-content modal-content-sm">
        <div class="modal-header modal-header-danger">
          <h2 class="modal-title modal-title-danger">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteReplyConfirmModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="danger-modal-body">
          <p class="danger-modal-text">
            Are you sure you want to delete this reply?
          </p>
          <div class="danger-modal-warning-box">
            <p class="danger-modal-warning-text">
              <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone.
            </p>
          </div>
        </div>
        <div class="modal-footer danger-modal-footer">
          <button class="btn btn-outline danger-modal-btn" onclick="closeDeleteReplyConfirmModal()">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button class="btn btn-danger danger-modal-btn" onclick="executeDeleteReply()">
            <i class="fas fa-trash"></i>
            <span>Delete</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Quick Tags Modal -->
    <div id="quickTagsModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Quick Tags</h2>
          <button class="modal-close" onclick="closeQuickTagsModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="quick-tags-content">
          <div class="tags-section">
            <h3>Popular</h3>
            <div class="quick-tags-grid">
              <div class="quick-tag selectable" data-tag="career" onclick="toggleTagSelection(this)">#career</div>
              <div class="quick-tag selectable" data-tag="mentorship" onclick="toggleTagSelection(this)">#mentorship</div>
              <div class="quick-tag selectable" data-tag="networking" onclick="toggleTagSelection(this)">#networking</div>
              <div class="quick-tag selectable" data-tag="experience" onclick="toggleTagSelection(this)">#experience</div>
              <div class="quick-tag selectable" data-tag="advice" onclick="toggleTagSelection(this)">#advice</div>
              <div class="quick-tag selectable" data-tag="general" onclick="toggleTagSelection(this)">#general</div>
            </div>
          </div>
          
          <div class="tags-section">
            <h3>All Tags</h3>
            <div class="quick-tags-grid">
              <div class="quick-tag selectable" data-tag="AI" onclick="toggleTagSelection(this)">#AI</div>
              <div class="quick-tag selectable" data-tag="tech" onclick="toggleTagSelection(this)">#tech</div>
              <div class="quick-tag selectable" data-tag="leadership" onclick="toggleTagSelection(this)">#leadership</div>
              <div class="quick-tag selectable" data-tag="trending" onclick="toggleTagSelection(this)">#trending</div>
              <div class="quick-tag selectable" data-tag="remote-work" onclick="toggleTagSelection(this)">#remote-work</div>
              <div class="quick-tag selectable" data-tag="work-life-balance" onclick="toggleTagSelection(this)">#work-life-balance</div>
              <div class="quick-tag selectable" data-tag="productivity" onclick="toggleTagSelection(this)">#productivity</div>
              <div class="quick-tag selectable" data-tag="collaboration" onclick="toggleTagSelection(this)">#collaboration</div>
              <div class="quick-tag selectable" data-tag="innovation" onclick="toggleTagSelection(this)">#innovation</div>
              <div class="quick-tag selectable" data-tag="startup" onclick="toggleTagSelection(this)">#startup</div>
              <div class="quick-tag selectable" data-tag="entrepreneurship" onclick="toggleTagSelection(this)">#entrepreneurship</div>
              <div class="quick-tag selectable" data-tag="professional-development" onclick="toggleTagSelection(this)">#professional-development</div>
              <div class="quick-tag selectable" data-tag="skills" onclick="toggleTagSelection(this)">#skills</div>
              <div class="quick-tag selectable" data-tag="learning" onclick="toggleTagSelection(this)">#learning</div>
              <div class="quick-tag selectable" data-tag="job-search" onclick="toggleTagSelection(this)">#job-search</div>
              <div class="quick-tag selectable" data-tag="interview" onclick="toggleTagSelection(this)">#interview</div>
              <div class="quick-tag selectable" data-tag="salary" onclick="toggleTagSelection(this)">#salary</div>
              <div class="quick-tag selectable" data-tag="promotion" onclick="toggleTagSelection(this)">#promotion</div>
              <div class="quick-tag selectable" data-tag="freelance" onclick="toggleTagSelection(this)">#freelance</div>
              <div class="quick-tag selectable" data-tag="consulting" onclick="toggleTagSelection(this)">#consulting</div>
              <div class="quick-tag selectable" data-tag="management" onclick="toggleTagSelection(this)">#management</div>
              <div class="quick-tag selectable" data-tag="team-building" onclick="toggleTagSelection(this)">#team-building</div>
              <div class="quick-tag selectable" data-tag="communication" onclick="toggleTagSelection(this)">#communication</div>
              <div class="quick-tag selectable" data-tag="project-management" onclick="toggleTagSelection(this)">#project-management</div>
            </div>
          </div>
          
          <div class="tags-section">
            <h3>Create Custom Tag</h3>
            <div class="custom-tag-input-wrapper">
              <input 
                type="text" 
                id="customTagInput" 
                class="custom-tag-input" 
                placeholder="Enter custom tag name"
                maxlength="30"
              >
              <button class="btn btn-primary btn-sm" onclick="addCustomTag()">
                <i class="fas fa-plus"></i>
                <span>Add Tag</span>
              </button>
            </div>
            <small class="custom-tag-help">Create your own tag and select it below</small>
            <div id="customTagsDisplay" class="quick-tags-grid custom-tags-display"></div>
          </div>
        </div>
        <div class="modal-footer modal-footer-between">
          <button class="btn btn-outline" onclick="closeQuickTagsModal()">
            <span>Cancel</span>
          </button>
          <button class="btn btn-primary" onclick="addSelectedTags()">
            <i class="fas fa-check"></i>
            <span>Add Selected Tags</span>
          </button>
        </div>
      </div>
    </div>

    <script src="<?=ROOT?>/assets/js/discussion-forum.js"></script>
    
    <script>
    // Make sure functions are available globally
    window.openNewPostModal = openNewPostModal;
    window.closeNewPostModal = closeNewPostModal;
    window.openQuickTagsModal = openQuickTagsModal;
    window.closeQuickTagsModal = closeQuickTagsModal;
    window.addQuickTag = addQuickTag;
    // Debug: Check if page is loaded
    console.log('Alumni discussion forum script loaded');
    
    // Modal Functions
    function openNewPostModal() {
      console.log('Opening New Post Modal');
      const modal = document.getElementById('newPostModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
        console.log('Modal display set to block and show class added');
      } else {
        console.error('New Post Modal not found');
      }
    }
    
    function closeNewPostModal() {
      console.log('Closing New Post Modal');
      const modal = document.getElementById('newPostModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    // Forum Detail Modal Functions
    let currentPostId = null;

    function openForumDetailModal(postId) {
      currentPostId = postId;
      showForumLoadingState();
      
      // Fetch post details and replies from server
      fetch(`<?=ROOT?>/alumni/discussionforum/getpostwithreplies?post_id=${postId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const post = data.post;
            const replies = Array.isArray(data.replies) ? data.replies : [];
            const likedReplyIds = data.likedReplyIds || [];
            
            // Store liked reply IDs globally
            window.likedReplyIds = likedReplyIds;
            
            // Populate modal with post details
            document.getElementById('forumDetailTitle').textContent = post.title;
            document.getElementById('forumDetailCreator').textContent = post.author_name || 'Unknown';
            const forumDetailViewsEl = document.getElementById('forumDetailViews');
            if (forumDetailViewsEl) {
              forumDetailViewsEl.textContent = post.views || 0;
            }
            document.getElementById('forumDetailReplies').textContent = replies.length;
            
            // Format created_at as readable date and time
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
            
            // Display tags
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
            
            // Display replies
            displayReplies(replies);
            
            // Update reply count
            document.getElementById('repliesCount').textContent = replies.length;
            
            // Show/hide View All Replies button based on reply count
            const viewAllBtn = document.getElementById('viewAllRepliesBtn');
            if (viewAllBtn) {
              if (replies.length > 3) {
                viewAllBtn.style.display = 'block';
              } else {
                viewAllBtn.style.display = 'none';
              }
            }
            
            hideForumLoadingState();
            // Show modal
            const modal = document.getElementById('forumDetailModal');
            modal.classList.add('show');
            modal.style.display = 'block';
          } else {
            hideForumLoadingState();
            showNotification(data.message || 'Failed to load post', 'error');
          }
        })
        .catch(error => {
          hideForumLoadingState();
          console.error('Error:', error);
          showNotification('Failed to load post details', 'error');
        });
    }

    function displayReplies(replies) {
      const repliesList = document.getElementById('repliesList');
      repliesList.innerHTML = '';
      
      // Ensure replies is an array
      if (!Array.isArray(replies)) {
        replies = [];
      }
      
      if (replies.length === 0) {
        repliesList.innerHTML = '<p class="replies-empty-message">No replies yet. Be the first to reply!</p>';
        return;
      }
      
      replies.forEach((reply, index) => {
        const replyItem = document.createElement('div');
        replyItem.className = 'reply-item';
        
        // Hide replies after the first 3
        if (index >= 3) {
          replyItem.classList.add('hidden-reply');
          replyItem.style.display = 'none';
        }
        
        // Format reply time - show time if today, date if not
        const replyDate = new Date(reply.repliedtime);
        const today = new Date();
        const isToday = replyDate.toDateString() === today.toDateString();
        
        let displayTime;
        if (isToday) {
          // Show just the time if posted today
          displayTime = replyDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
          });
        } else {
          // Show the date if not today
          displayTime = replyDate.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
          });
        }
        
        // Check if this reply is liked by current user
        const isLiked = window.likedReplyIds && window.likedReplyIds.includes(reply.replyid);
        const likedClass = isLiked ? 'liked' : '';
        
        replyItem.innerHTML = `
          <div class="reply-header">
            <div class="reply-author">
              <i class="fas fa-user-circle"></i>
              <strong>${reply.author_name || reply.user_name || 'Anonymous'}</strong>
            </div>
            <span class="reply-time">${displayTime}</span>
          </div>
          <div class="reply-content">
            <p>${reply.reply}</p>
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

    function toggleAllReplies() {
      const hiddenReplies = document.querySelectorAll('.hidden-reply');
      const viewAllBtn = document.querySelector('.btn-view-all-replies');
      
      if (hiddenReplies.length === 0) return;
      
      // Check if replies are currently hidden
      const isHidden = hiddenReplies[0].style.display === 'none';
      
      if (isHidden) {
        // Show all replies
        hiddenReplies.forEach(reply => reply.style.display = 'block');
        viewAllBtn.innerHTML = '<i class="fas fa-chevron-up"></i> Show Less';
      } else {
        // Hide additional replies
        hiddenReplies.forEach(reply => reply.style.display = 'none');
        viewAllBtn.innerHTML = '<i class="fas fa-comments"></i> View All Replies';
      }
    }

    function showForumLoadingState() {
      const modal = document.getElementById('forumDetailModal');
      if (!modal) return;

      let overlay = modal.querySelector('.forum-loading-overlay');
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'forum-loading-overlay';
        overlay.innerHTML = `
          <div class="forum-loading-content">
            <i class="fas fa-spinner fa-spin forum-loading-icon"></i>
            <span class="forum-loading-text">Loading discussion...</span>
          </div>
        `;
        Object.assign(overlay.style, {
          position: 'absolute',
          inset: '0',
          background: 'rgba(255, 255, 255, 0.85)',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          zIndex: '20'
        });

        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
          modalContent.style.position = 'relative';
          modalContent.appendChild(overlay);
        }
      }

      modal.classList.add('show');
      modal.style.display = 'block';
    }

    function hideForumLoadingState() {
      const modal = document.getElementById('forumDetailModal');
      const overlay = modal ? modal.querySelector('.forum-loading-overlay') : null;
      if (overlay) overlay.remove();
    }

    function closeForumDetailModal() {
      console.log('Closing Forum Detail Modal');
      const modal = document.getElementById('forumDetailModal');
      hideForumLoadingState();
      if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
          modal.style.display = 'none';
        }, 300);
      }
      currentPostId = null;
    }

    function submitReply(event) {
      event.preventDefault();
      const textarea = event.target.querySelector('.reply-textarea');
      const replyText = textarea.value.trim();
      
      if (!replyText) {
        showNotification('Please enter a reply', 'warning');
        return;
      }

      if (replyText.length < 10) {
        showNotification('Reply must be at least 10 characters long', 'warning');
        return;
      }
      
      if (!currentPostId) {
        showNotification('Post ID not found', 'error');
        return;
      }
      
      // Submit reply to backend
      fetch('<?=ROOT?>/alumni/discussionforum/addreply', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          post_id: currentPostId,
          reply: replyText
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message || 'Reply posted successfully', 'success');
          textarea.value = '';

          // Refresh modal replies immediately
          openForumDetailModal(currentPostId);

          // Refresh forum topic cards/counts without full page refresh
          bumpReplyCountForPost(currentPostId);
          if (typeof loadForumSearchResults === 'function') {
            loadForumSearchResults();
          }
          refreshMyRepliesSection();
        } else {
          showNotification(data.message || 'Failed to post reply', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to post reply', 'error');
      });
    }

    function bumpReplyCountForPost(postId) {
      const cards = document.querySelectorAll(`.topic-card[data-post-id="${Number(postId)}"]`);
      cards.forEach(card => {
        const replyCountEl = card.querySelector('.topic-replies strong');
        if (!replyCountEl) return;
        const current = parseInt(replyCountEl.textContent, 10) || 0;
        replyCountEl.textContent = String(current + 1);
      });
    }

    function refreshMyRepliesSection() {
      fetch('<?=ROOT?>/alumni/discussionforum/myreplies')
        .then(response => response.json())
        .then(data => {
          if (!data.success) return;
          renderMyRepliesSection(Array.isArray(data.replies) ? data.replies : []);
        })
        .catch(() => {
          // Keep existing UI if refresh fails
        });
    }

    function renderMyRepliesSection(replies) {
      const container = document.querySelector('.my-replies-container');
      if (!container) return;

      if (!Array.isArray(replies) || replies.length === 0) {
        container.innerHTML = `
          <div class="no-replies-message">
            <i class="fas fa-comments"></i>
            <p>No Replies Yet</p>
            <small>Your replies to forum discussions will appear here. Join conversations and share your insights!</small>
          </div>
        `;
        return;
      }

      const grouped = {};
      replies.forEach(reply => {
        const threadId = Number(reply.forum_id || 0);
        if (!grouped[threadId]) {
          grouped[threadId] = {
            forumId: threadId,
            title: reply.forum_title || 'Deleted Post',
            items: []
          };
        }
        grouped[threadId].items.push(reply);
      });

      const html = Object.values(grouped).map(thread => {
        const itemsHtml = thread.items.map(reply => {
          const ts = new Date(reply.repliedtime || Date.now()).getTime();
          const diff = Math.max(0, Math.floor((Date.now() - ts) / 1000));
          let postedDate = `${diff} seconds ago`;
          if (diff >= 86400) postedDate = `${Math.floor(diff / 86400)} days ago`;
          else if (diff >= 3600) postedDate = `${Math.floor(diff / 3600)} hours ago`;
          else if (diff >= 60) postedDate = `${Math.floor(diff / 60)} minutes ago`;

          return `
            <div class="thread-reply-item">
              <div class="thread-reply-meta">
                <span class="reply-date"><i class="fas fa-clock"></i> ${postedDate}</span>
                <span class="reply-likes"><i class="fas fa-thumbs-up"></i> ${Number(reply.likes || 0)} likes</span>
              </div>
              <p class="reply-text">${escapeHtml(reply.reply || '')}</p>
              <div class="thread-reply-actions">
                <button class="btn-action btn-edit" onclick="openEditReplyModal(${Number(reply.replyid || 0)}, '${String(reply.reply || '').replace(/'/g, "\\'")}')">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-action btn-delete" onclick="confirmDeleteReply(${Number(reply.replyid || 0)})">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </div>
            </div>
          `;
        }).join('');

        return `
          <article class="reply-thread" data-thread-id="${thread.forumId}">
            <header class="reply-thread-header">
              <a href="javascript:void(0)" class="reply-thread-title" onclick="incrementAndViewPost(${thread.forumId}, this)">${escapeHtml(thread.title)}</a>
            </header>
            <div class="reply-thread-list">${itemsHtml}</div>
          </article>
        `;
      }).join('');

      container.innerHTML = `<div class="replies-grid">${html}</div>`;
    }

    // Toggle Like Reply Function
    function toggleLikeReply(replyId, buttonElement) {
      fetch('<?=ROOT?>/alumni/discussionforum/likereply', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          reply_id: replyId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update the like count display
          const likeCountSpan = buttonElement.querySelector('.like-count');
          if (likeCountSpan) {
            likeCountSpan.textContent = data.likes;
            // Update the pluralization
            const likeText = data.likes !== 1 ? 's' : '';
            buttonElement.innerHTML = `<i class="fas fa-thumbs-up"></i> <span class="like-count">${data.likes}</span> Like${likeText}`;
          }
          
          // Toggle the liked class for highlighting
          if (data.liked) {
            buttonElement.classList.add('liked');
            // Update global liked array
            if (window.likedReplyIds && !window.likedReplyIds.includes(replyId)) {
              window.likedReplyIds.push(replyId);
            }
          } else {
            buttonElement.classList.remove('liked');
            // Update global liked array
            if (window.likedReplyIds) {
              window.likedReplyIds = window.likedReplyIds.filter(id => id !== replyId);
            }
          }
        } else {
          showNotification(data.message || 'Failed to toggle like', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to toggle like', 'error');
      });
    }

    // My Replies Management Functions
    function openEditReplyModal(replyId, replyText) {
      console.log('Opening Edit Reply Modal for reply:', replyId);
      const modal = document.getElementById('editReplyModal');
      const replyIdInput = document.getElementById('editReplyId');
      const replyTextArea = document.getElementById('editReplyText');
      
      if (modal && replyIdInput && replyTextArea) {
        replyIdInput.value = replyId;
        replyTextArea.value = replyText;
        modal.classList.add('show');
        modal.style.display = 'block';
      }
    }

    function closeEditReplyModal() {
      console.log('Closing Edit Reply Modal');
      const modal = document.getElementById('editReplyModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    function submitEditReply(event) {
      event.preventDefault();
      const replyId = document.getElementById('editReplyId').value;
      const replyText = document.getElementById('editReplyText').value.trim();
      
      if (!replyText) {
        showNotification('Please enter a reply', 'warning');
        return;
      }
      
      if (replyText.length < 10) {
        showNotification('Reply must be at least 10 characters long', 'warning');
        return;
      }
      
      // Update reply via backend
      fetch('<?=ROOT?>/alumni/discussionforum/updatereply', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          reply_id: replyId,
          reply: replyText
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message || 'Reply updated successfully', 'success');
          closeEditReplyModal();
          // Reload page to update My Replies section
          setTimeout(() => location.reload(), 1000);
        } else {
          showNotification(data.message || 'Failed to update reply', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update reply', 'error');
      });
    }

    let deleteReplyId = null;

    function confirmDeleteReply(replyId) {
      deleteReplyId = replyId;
      const modal = document.getElementById('deleteReplyConfirmModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
      }
    }

    function closeDeleteReplyConfirmModal() {
      const modal = document.getElementById('deleteReplyConfirmModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
      deleteReplyId = null;
    }

    function executeDeleteReply() {
      if (!deleteReplyId) {
        showNotification('No reply selected', 'error');
        return;
      }

      // Delete reply via backend
      fetch('<?=ROOT?>/alumni/discussionforum/deletereply', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          reply_id: deleteReplyId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message || 'Reply deleted successfully', 'success');
          closeDeleteReplyConfirmModal();
          // Reload page to update My Replies section
          setTimeout(() => location.reload(), 1000);
        } else {
          showNotification(data.message || 'Failed to delete reply', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to delete reply', 'error');
      });
    }

    // My Published Forums Management Functions
    let deleteForumId = null;

    function openEditForumModal(forumId) {
      console.log('Opening Edit Forum Modal for forum:', forumId);
      
      // Fetch post data from server
      fetch(`<?=ROOT?>/alumni/discussionforum/get?post_id=${forumId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const post = data.post;
            
            // Populate the edit form
            document.getElementById('editPostId').value = post.post_id;
            document.getElementById('editPostTitle').value = post.title;
            document.getElementById('editPostContent').value = post.content;
            document.getElementById('editPostTags').value = post.tags || '';
            document.getElementById('editVisibleFaculties').value = post.visiblefaculties || '';
            
            // Show the modal
            const modal = document.getElementById('editPostModal');
            modal.classList.add('show');
            modal.style.display = 'block';
          } else {
            showNotification(data.message || 'Failed to load post', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('Failed to load post', 'error');
        });
    }

    function closeEditForumModal() {
      const modal = document.getElementById('editPostModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    function confirmDeleteForum(forumId) {
      deleteForumId = forumId;
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
      }
    }

    function closeDeleteConfirmModal() {
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
      deleteForumId = null;
    }

    function incrementAndViewPost(postId, triggerBtn = null) {
      if (triggerBtn) {
        const viewCountEl = triggerBtn.closest('.topic-card')?.querySelector('.topic-views strong');
        if (viewCountEl) {
          const currentViews = parseInt(viewCountEl.textContent, 10) || 0;
          viewCountEl.textContent = String(currentViews + 1);
        }
      }

      // Open modal immediately and load content while increment happens in background.
      openForumDetailModal(postId);

      fetch('<?= ROOT ?>/alumni/discussionforum/incrementview', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + postId
      })
      .then(response => response.json())
      .catch(error => {
        console.error('Error incrementing views:', error);
      });
    }

    function executeDeleteForum() {
      if (!deleteForumId) {
        showNotification('No forum selected', 'error');
        return;
      }

      fetch('<?=ROOT?>/alumni/discussionforum/delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          post_id: deleteForumId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification(data.message || 'Forum deleted successfully', 'success');
          closeDeleteConfirmModal();
          location.reload(); // Reload to refresh the list
        } else {
          showNotification(data.message || 'Failed to delete forum', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to delete forum', 'error');
      });
    }

    // Initialize and Event Listeners
    // Toast notification system already set up above
    
    // Form submission
    document.addEventListener('DOMContentLoaded', function() {
      const navLinks = Array.from(document.querySelectorAll('.forum-nav-link'));
      const sectionIds = ['forum-topics', 'my-forums', 'my-replies'];

      const setActiveNav = function (id) {
        navLinks.forEach((link) => {
          const href = link.getAttribute('href') || '';
          link.classList.toggle('is-active', href === '#' + id);
        });
      };

      const getNearestVisibleSection = function () {
        let nearestId = sectionIds[0];
        let nearestDistance = Number.POSITIVE_INFINITY;

        sectionIds.forEach((id) => {
          const section = document.getElementById(id);
          if (!section) return;
          const rect = section.getBoundingClientRect();
          const distance = Math.abs(rect.top - 130);
          if (distance < nearestDistance) {
            nearestDistance = distance;
            nearestId = id;
          }
        });

        return nearestId;
      };

      const initialHash = (window.location.hash || '').replace('#', '');
      setActiveNav(sectionIds.includes(initialHash) ? initialHash : sectionIds[0]);

      navLinks.forEach((link) => {
        link.addEventListener('click', function () {
          const targetId = (link.getAttribute('href') || '').replace('#', '');
          if (targetId) {
            setActiveNav(targetId);
          }
        });
      });

      window.addEventListener('scroll', function () {
        setActiveNav(getNearestVisibleSection());
      }, { passive: true });

      // New Post Form
      const newPostForm = document.querySelector('.new-post-form');
      if (newPostForm) {
        newPostForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Get form data
          const formData = new FormData(this);
          const title = formData.get('title');
          const content = formData.get('content');
          const faculty_id = formData.get('faculty_id');
          const tags = formData.get('tags');
          
          // Basic validation
          if (title.length < 10) {
            showNotification('Title must be at least 10 characters long', 'error');
            return;
          }
          
          if (content.length < 50) {
            showNotification('Content must be at least 50 characters long', 'error');
            return;
          }

          if (!faculty_id) {
            showNotification('Please select a faculty', 'error');
            return;
          }
          
          // Submit to server
          fetch('<?=ROOT?>/alumni/discussionforum/create', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showNotification(data.message, 'success');
              closeNewPostModal();
              this.reset();
              setTimeout(() => {
                location.reload();
              }, 1000);
            } else {
              showNotification(data.message, 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to create post', 'error');
          });
        });
      }

      // Edit Post Form
      const editPostForm = document.querySelector('.edit-post-form');
      if (editPostForm) {
        editPostForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(this);
          const title = formData.get('title');
          const content = formData.get('content');
          const faculty_id = formData.get('faculty_id');
          
          if (title.length < 10) {
            showNotification('Title must be at least 10 characters long', 'error');
            return;
          }
          
          if (content.length < 50) {
            showNotification('Content must be at least 50 characters long', 'error');
            return;
          }

          if (!faculty_id) {
            showNotification('Please select a faculty', 'error');
            return;
          }
          
          fetch('<?=ROOT?>/alumni/discussionforum/update', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              showNotification(data.message || 'Post updated successfully!', 'success');
              closeEditForumModal();
              location.reload();
            } else {
              showNotification(data.message || 'Update failed', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to update post', 'error');
          });
        });
      }
    });
    
    function openQuickTagsModal() {
      console.log('Opening Quick Tags Modal');
      const modal = document.getElementById('quickTagsModal');
      if (modal) {
        // Pre-select tags that are already in the textarea
        syncModalWithTextarea('postTags');
        modal.classList.add('show');
        modal.style.display = 'block';
        console.log('Quick Tags Modal display set to block and show class added');
      } else {
        console.error('Quick Tags Modal not found');
      }
    }

    function openQuickTagsModalForEdit() {
      console.log('Opening Quick Tags Modal for Edit');
      const modal = document.getElementById('quickTagsModal');
      if (modal) {
        // Pre-select tags that are already in the edit textarea
        syncModalWithTextarea('editPostTags');
        modal.classList.add('show');
        modal.style.display = 'block';
      } else {
        console.error('Quick Tags Modal not found');
      }
    }
    
    function closeQuickTagsModal() {
      console.log('Closing Quick Tags Modal');
      const modal = document.getElementById('quickTagsModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }
    
    // Sync modal selections with textarea content
    function syncModalWithTextarea(textareaId = 'postTags') {
      const tagsTextarea = document.getElementById(textareaId);
      if (!tagsTextarea) return;
      
      const currentTags = tagsTextarea.value.trim();
      
      // Clear all selections first
      const allTags = document.querySelectorAll('.quick-tag.selectable');
      allTags.forEach(tag => tag.classList.remove('selected'));
      
      // If there are tags in the textarea, select them in the modal
      if (currentTags) {
        const tagsArray = currentTags.split(/\s+/).map(tag => tag.replace('#', '').trim());
        
        allTags.forEach(tagElement => {
          const tagValue = tagElement.getAttribute('data-tag');
          if (tagsArray.includes(tagValue)) {
            tagElement.classList.add('selected');
          }
        });
      }
    }
    
    // Toggle tag selection in modal
    function toggleTagSelection(element) {
      element.classList.toggle('selected');
    }
    
    // Clear all selected tags
    function clearTagSelection() {
      const selectedTags = document.querySelectorAll('.quick-tag.selected');
      selectedTags.forEach(tag => tag.classList.remove('selected'));
    }
    
    // Sync textarea with selected tags (replaces content instead of appending)
    function addSelectedTags() {
      const selectedTags = document.querySelectorAll('.quick-tag.selected');
      
      // Determine which textarea to update (for new or edit post)
      const newPostModal = document.getElementById('newPostModal');
      const editPostModal = document.getElementById('editPostModal');
      let tagsTextarea;
      
      if (editPostModal && editPostModal.classList.contains('show')) {
        tagsTextarea = document.getElementById('editPostTags');
      } else {
        tagsTextarea = document.getElementById('postTags');
      }
      
      if (!tagsTextarea) return;
      
      if (selectedTags.length === 0) {
        // No tags selected, clear the textarea
        tagsTextarea.value = '';
        closeQuickTagsModal();
        return;
      }
      
      // Build the tags string from selected tags
      let tags = [];
      selectedTags.forEach(tagElement => {
        const tag = tagElement.getAttribute('data-tag');
        tags.push('#' + tag);
      });
      
      // Replace textarea content with selected tags
      tagsTextarea.value = tags.join(' ');
      
      // Close modal (don't clear selections - they're synced now)
      closeQuickTagsModal();
    }
    
    function addQuickTag(tag) {
      const tagsTextarea = document.getElementById('postTags');
      const currentTags = tagsTextarea.value;
      const newTag = '#' + tag;
      
      if (currentTags.includes(newTag)) {
        return; // Tag already exists
      }
      
      if (currentTags.trim() === '') {
        tagsTextarea.value = newTag;
      } else {
        tagsTextarea.value = currentTags + ' ' + newTag;
      }
      
      closeQuickTagsModal();
    }
    
    function addCustomTag() {
      const customTagInput = document.getElementById('customTagInput');
      const tagValue = customTagInput.value.trim();
      
      if (!tagValue) {
        return; // Just return silently if empty
      }
      
      // Remove # if user included it
      const cleanTag = tagValue.replace(/^#+/, '');
      
      // Validate tag (alphanumeric, hyphens allowed)
      if (!/^[a-zA-Z0-9-]+$/.test(cleanTag)) {
        alert('Tag can only contain letters, numbers, and hyphens');
        return;
      }
      
      // Check if tag already exists in display
      const existingTags = document.querySelectorAll('#customTagsDisplay .quick-tag');
      for (let tag of existingTags) {
        if (tag.getAttribute('data-tag') === cleanTag) {
          return; // Silently ignore duplicates
        }
      }
      
      // Create new tag element
      const customTagsDisplay = document.getElementById('customTagsDisplay');
      const newTagElement = document.createElement('div');
      newTagElement.className = 'quick-tag selectable selected';
      newTagElement.setAttribute('data-tag', cleanTag);
      newTagElement.onclick = function() { toggleTagSelection(this); };
      newTagElement.innerHTML = '#' + cleanTag;
      
      // Add to display
      customTagsDisplay.appendChild(newTagElement);
      
      // Clear input
      customTagInput.value = '';
      
      // Show success feedback
      newTagElement.style.animation = 'fadeIn 0.3s ease';
    }
    
    // Allow Enter key to add custom tag
    document.addEventListener('DOMContentLoaded', function() {
      const customTagInput = document.getElementById('customTagInput');
      if (customTagInput) {
        customTagInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            addCustomTag();
          }
        });
      }
    });
    
    // Close modals when clicking outside
    window.onclick = function(event) {
      const newPostModal = document.getElementById('newPostModal');
      const quickTagsModal = document.getElementById('quickTagsModal');
      
      if (event.target === newPostModal) {
        closeNewPostModal();
      }
      if (event.target === quickTagsModal) {
        closeQuickTagsModal();
      }
    }
    
    // Keyword Search Functionality (server-backed)
    const hashtagSearchInput = document.getElementById('hashtagSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const hashtagPills = document.querySelectorAll('.hashtag-pill');
    const forumTopicsContainer = document.querySelector('.forum-topics-section .topics-container');
    let searchDebounceTimer = null;
    
    // Sort Filter Functionality
    const filterSelect = document.querySelector('.filter-select');
    if (filterSelect) {
      filterSelect.addEventListener('change', function(e) {
        sortTopics(e.target.value);
      });
    }
    
    function sortTopics() {
      loadForumSearchResults();
    }
    
    function mapSortValue() {
      const selected = filterSelect?.value || 'Most Recent';
      if (selected === 'Most Popular') return 'popular';
      if (selected === 'Most Active') return 'active';
      return 'recent';
    }

    function getTimeAgo(createdAt) {
      const timestamp = new Date(createdAt).getTime();
      const diff = Math.max(0, Math.floor((Date.now() - timestamp) / 1000));
      if (diff < 60) return `${diff} seconds ago`;
      if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
      if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
      return `${Math.floor(diff / 86400)} days ago`;
    }

    function parseTags(tagsValue) {
      if (!tagsValue) return [];
      return tagsValue
        .replace(/#/g, '')
        .split(/[\s,]+/)
        .map(tag => tag.trim())
        .filter(Boolean);
    }

    function toTagClass(tag) {
      return String(tag).toLowerCase().replace(/[^a-z0-9-]/g, '');
    }

    function renderForumSearchResults(posts) {
      if (!forumTopicsContainer) return;

      if (!Array.isArray(posts) || posts.length === 0) {
        forumTopicsContainer.innerHTML = `
          <div class="forum-empty-state" id="noResultsMessage">
            <i class="fas fa-search forum-empty-state-icon"></i>
            <p class="forum-empty-state-title">No discussions found</p>
            <p class="forum-empty-state-subtitle">Try another keyword or hashtag.</p>
          </div>
        `;
        return;
      }

      const limitedPosts = posts.slice(0, 3);

      forumTopicsContainer.innerHTML = limitedPosts.map(topic => {
        const tags = parseTags(topic.tags);
        const lastActivity = getTimeAgo(topic.created_at);
        const isTrending = Number(topic.trending_points || 0) >= <?= (int)$trending_threshold ?>;

        return `
          <div class="topic-card topic-card-clickable" data-post-id="${Number(topic.post_id)}" onclick="incrementAndViewPost(${Number(topic.post_id)}, this)" role="button" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();incrementAndViewPost(${Number(topic.post_id)}, this);}">
            <div class="topic-header">
              <div class="topic-info">
                <h3 class="topic-title">${escapeHtml(topic.title || '')}</h3>
                <p class="topic-creator">Created by ${escapeHtml(topic.author_name || 'Unknown')}</p>
              </div>
              ${isTrending ? '<span class="status-badge status-trending">Trending</span>' : ''}
            </div>
            <div class="topic-description">
              <p>${escapeHtml(topic.content || '')}</p>
            </div>
            ${tags.length ? `<div class="topic-tags">${tags.map(tag => `<span class="topic-tag tag-${toTagClass(tag)}">#${escapeHtml(tag)}</span>`).join('')}</div>` : ''}
            <div class="topic-footer">
              <div class="topic-meta">
                <div class="topic-replies"><i class="fas fa-comment"></i> <strong>${Number(topic.replies || 0)}</strong> replies</div>
                <div class="topic-activity"><i class="fas fa-clock"></i> ${lastActivity}</div>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    function escapeHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function loadForumSearchResults() {
      const keyword = (hashtagSearchInput?.value || '').trim().replace(/^#/, '');
      const sort = mapSortValue();

      fetch(`<?=ROOT?>/alumni/discussionforum/search?q=${encodeURIComponent(keyword)}&sort=${encodeURIComponent(sort)}`)
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            showNotification(data.message || 'Search failed', 'error');
            return;
          }
          renderForumSearchResults(data.posts || []);
        })
        .catch(() => {
          showNotification('Unable to search discussions right now.', 'error');
        });
    }
    
    // Search input handler
    if (hashtagSearchInput) {
      hashtagSearchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().replace('#', '');
        
        // Show/hide clear button
        clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
        
        // Backend keyword search with debounce
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(loadForumSearchResults, 250);
        
        // Highlight matching pills
        updatePillHighlights(searchTerm);
      });
    }
    
    // Clear search button
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', function() {
        hashtagSearchInput.value = '';
        clearSearchBtn.style.display = 'none';
        loadForumSearchResults();
        updatePillHighlights('');
      });
    }
    
    // Hashtag pill click handlers
    hashtagPills.forEach(pill => {
      pill.addEventListener('click', function() {
        const tag = this.getAttribute('data-tag');
        hashtagSearchInput.value = tag;
        clearSearchBtn.style.display = 'flex';
        loadForumSearchResults();
        updatePillHighlights(tag.toLowerCase());
      });
    });
    
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
    

    </script>
    
    
  </body>
</html>
