<?php 
$page_title = "Forum Moderation";
$page_subtitle = "Manage and moderate forum discussions";

// Trending Posts Configuration
$trending_threshold = 10;  // Minimum points to show "Trending" badge
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
        <!-- My Published Forums Section -->
        <section class="dashboard-section my-published-forums-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="section-title">
                <i class="fas fa-newspaper"></i> My Published Forums
              </h2>
              <button class="btn btn-primary" onclick="openNewPostModal()">
                <i class="fas fa-plus"></i>
                <span>New Post</span>
              </button>
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
                      <?php else: ?>
                        <span class="status-badge status-active">
                          Active
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
                      <div class="topic-actions fae-32">
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

        <!-- Forum Topics Section -->
        <section class="dashboard-section forum-topics-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="section-title fae-33">
                <i class="fas fa-comments"></i> Forum Topics
              </h2>
              <div class="filter-controls">
                <select class="filter-select">
                  <option>Most Recent</option>
                  <option>Most Popular</option>
                  <option>Most Active</option>
                </select>
                <a href="<?=ROOT?>/admin/forummoderation/viewall" class="btn btn-outline">
                  <span>View All</span>
                  <i class="fas fa-arrow-right"></i>
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
            // Show only first 5 topics visible to faculty
            $displayTopics = isset($faculty_posts) && is_array($faculty_posts) ? array_slice($faculty_posts, 0, 5) : [];
            
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
                  <button class="btn-action btn-delete" onclick="confirmDeleteForum(<?= $topic->post_id ?>)">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
            <?php endforeach; 
            endif; ?>
          </div>
        </section>

        <!-- My Replies Section -->
        <section class="dashboard-section my-replies-section">
          <div class="section-header">
            <h2 class="section-title">
              <i class="fas fa-reply"></i> My Replies
            </h2>
          </div>
          
          <div class="my-replies-container">
            <?php
            // Use real database data from controller
            $myReplies = isset($my_replies) && is_array($my_replies) ? $my_replies : [];
            ?>
            
            <?php if (empty($myReplies)): ?>
              <div class="no-replies-message">
                <i class="fas fa-comments"></i>
                <p>No Replies Yet</p>
                <small>Your replies to forum discussions will appear here. Join conversations and share your insights!</small>
              </div>
            <?php else: ?>
              <div class="replies-grid">
                <?php foreach ($myReplies as $reply): 
                  // Format repliedtime as "X time ago"
                  $timestamp = strtotime($reply->repliedtime);
                  $diff = time() - $timestamp;
                  if ($diff < 60) $posted_date = $diff . ' seconds ago';
                  elseif ($diff < 3600) $posted_date = floor($diff / 60) . ' minutes ago';
                  elseif ($diff < 86400) $posted_date = floor($diff / 3600) . ' hours ago';
                  else $posted_date = floor($diff / 86400) . ' days ago';
                ?>
                  <div class="reply-card">
                    <div class="reply-card-header">
                      <div class="forum-link-info">
                        <span class="forum-label">Replied to:</span>
                        <a href="javascript:void(0)" class="forum-link" onclick="incrementAndViewPost(<?= $reply->forum_id ?>, this)">
                          <?= esc($reply->forum_title ?? 'Deleted Post') ?>
                        </a>
                      </div>
                      <span class="reply-date">
                        <i class="fas fa-clock"></i> <?= esc($posted_date) ?>
                      </span>
                    </div>
                    
                    <div class="reply-card-content">
                      <p class="reply-text"><?= esc($reply->reply) ?></p>
                    </div>
                    
                    <div class="reply-card-footer">
                      <div class="reply-stats">
                        <span class="reply-likes">
                          <i class="fas fa-thumbs-up"></i>
                          <?= $reply->likes ?? 0 ?> Likes
                        </span>
                      </div>
                      <div class="reply-actions">
                        <button class="btn-action btn-edit" onclick="openEditReplyModal(<?= $reply->replyid ?>, '<?= htmlspecialchars($reply->reply, ENT_QUOTES) ?>')">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDeleteReply(<?= $reply->replyid ?>)">
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

      </main>
    </div>

    <!-- New Post Modal -->
    <div id="newPostModal" class="modal fae-10">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Post</h2>
          <button class="modal-close" onclick="closeNewPostModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="newPostForm" class="new-post-form" onsubmit="return submitNewPost(event)">
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
            <select id="visibleFaculties" name="faculty_id" class="form-control fae-34" required>
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
            <textarea id="postTags" name="tags" placeholder="Click 'Quick Tags' button below to select tags" rows="2" readonly class="fae-35"></textarea>
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

    <!-- Edit Forum Modal -->
    <div id="editForumModal" class="modal fae-10">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Edit Forum Post</h2>
          <button class="modal-close" onclick="closeEditForumModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForumForm" class="new-post-form" onsubmit="return submitEditForum(event)">
          <input type="hidden" id="editPostId" name="post_id">
          
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
            <select id="editVisibleFaculties" name="faculty_id" class="form-control fae-34" required>
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
            <textarea id="editPostTags" name="tags" placeholder="Enter tags separated by commas or spaces (e.g., #career #advice)" rows="2"></textarea>
            <small>Optional: Add tags to help categorize your post</small>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeEditForumModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              <span>Update Post</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal">
      <div class="modal-content fae-36">
        <div class="modal-header fae-37">
          <h2 class="modal-title fae-38">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="fae-39">
          <p class="fae-40">
            Are you sure you want to delete this forum post?
          </p>
          <div class="fae-41">
            <p class="fae-42">
              <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone. All replies to this post will also be deleted.
            </p>
          </div>
        </div>
        <div class="modal-footer fae-43">
          <button class="btn btn-outline fae-44" onclick="closeDeleteModal()">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button class="btn fae-45" onclick="executeDelete()">
            <i class="fas fa-trash"></i>
            <span>Delete</span>
          </button>
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

    <!-- Delete Reply Confirmation Modal -->
    <div id="deleteReplyConfirmModal" class="modal">
      <div class="modal-content fae-36">
        <div class="modal-header fae-37">
          <h2 class="modal-title fae-38">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteReplyConfirmModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="fae-39">
          <p class="fae-40">
            Are you sure you want to delete this reply?
          </p>
          <div class="fae-41">
            <p class="fae-42">
              <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone.
            </p>
          </div>
        </div>
        <div class="modal-footer fae-43">
          <button class="btn btn-outline fae-44" onclick="closeDeleteReplyConfirmModal()">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button class="btn fae-45" onclick="executeDeleteReply()">
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
            <div id="customTagsDisplay" class="quick-tags-grid fae-46"></div>
          </div>
        </div>
        <div class="modal-footer fae-47">
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
    // Global variables for tags
    let selectedTags = [];
    let editSelectedTags = [];
    let currentPostId = null;

    // Notification System
    function showNotification(message, type = 'success') {
      const container = document.getElementById('notificationContainer');
      const notification = document.createElement('div');
      notification.className = `notification notification-${type}`;
      
      const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
      
      notification.innerHTML = `
        <div class="notification-icon">${icon}</div>
        <div class="notification-message">${message}</div>
      `;
      
      container.appendChild(notification);
      
      // Trigger animation
      setTimeout(() => notification.classList.add('show'), 10);
      
      // Auto remove after 4 seconds
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
      }, 4000);
    }

    // Open/Close New Post Modal
    function openNewPostModal() {
      document.getElementById('newPostModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeNewPostModal() {
      document.getElementById('newPostModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      document.getElementById('newPostForm').reset();
      selectedTags = [];
    }

    // Open/Close Edit Forum Modal
    function openEditForumModal(postId) {
      // Fetch forum data via AJAX
      fetch(`<?=ROOT?>/admin/forummoderation/getPost/${postId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('editPostId').value = data.post.post_id;
            document.getElementById('editPostTitle').value = data.post.title;
            document.getElementById('editPostContent').value = data.post.content;
            
            // Set faculty dropdown
            const facultyDropdown = document.getElementById('editVisibleFaculties');
            if (facultyDropdown && data.post.visiblefaculties) {
              facultyDropdown.value = data.post.visiblefaculties;
            }
            
            // Parse and set tags
            editSelectedTags = [];
            if (data.post.tags) {
              const tagsString = data.post.tags.replace(/#/g, '');
              editSelectedTags = tagsString.split(/[\s,]+/).filter(tag => tag.trim() !== '');
            }
            
            document.getElementById('editForumModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
          } else {
            showNotification('Failed to load post: ' + (data.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('Failed to load forum post data: ' + error.message, 'error');
        });
    }

    function closeEditForumModal() {
      document.getElementById('editForumModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      document.getElementById('editForumForm').reset();
      editSelectedTags = [];
    }

    // Open/Close Quick Tags Modal
    function openQuickTagsModal() {
      const modal = document.getElementById('quickTagsModal');
      modal.classList.add('show');
      modal.style.display = 'flex';
      
      // Sync current tags with modal selection
      const tagsTextarea = document.getElementById('postTags');
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

    function closeQuickTagsModal() {
      const modal = document.getElementById('quickTagsModal');
      modal.classList.remove('show');
      setTimeout(() => {
        modal.style.display = 'none';
      }, 300);
    }

    // Toggle tag selection in modal
    function toggleTagSelection(element) {
      element.classList.toggle('selected');
    }

    // Sync textarea with selected tags (replaces content instead of appending)
    function addSelectedTags() {
      const selectedTags = document.querySelectorAll('.quick-tag.selected');
      const tagsTextarea = document.getElementById('postTags');
      
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
        showNotification('Tag can only contain letters, numbers, and hyphens', 'error');
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

    // Submit New Post
    function submitNewPost(event) {
      event.preventDefault();
      
      const formData = new FormData(event.target);
      const facultyId = formData.get('faculty_id');
      
      // Validate faculty selection
      if (!facultyId) {
        showNotification('Please select a faculty', 'error');
        return false;
      }
      
      fetch('<?=ROOT?>/admin/forummoderation/createPost', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('Forum post created successfully!', 'success');
          closeNewPostModal();
          setTimeout(() => {
            location.reload();
          }, 1500);
        } else {
          showNotification(data.message || 'Failed to create post', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while creating the post', 'error');
      });
      
      return false;
    }

    // Submit Edit Forum
    function submitEditForum(event) {
      event.preventDefault();
      
      const formData = new FormData(event.target);
      const facultyId = formData.get('faculty_id');
      
      // Validate faculty selection
      if (!facultyId) {
        showNotification('Please select a faculty', 'error');
        return false;
      }
      
      fetch('<?=ROOT?>/admin/forummoderation/updatePost', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('Forum post updated successfully!', 'success');
          closeEditForumModal();
          location.reload();
        } else {
          showNotification(data.message || 'Failed to update post', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating the post', 'error');
      });
      
      return false;
    }

    // Delete Forum
    let postToDelete = null;

    function confirmDeleteForum(postId) {
      postToDelete = postId;
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
      }
    }

    function closeDeleteModal() {
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
      postToDelete = null;
    }

    function executeDelete() {
      if (!postToDelete) return;

      fetch(`<?=ROOT?>/admin/forummoderation/deletePost/${postToDelete}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          closeDeleteModal();
          location.reload();
        } else {
          showNotification(data.message || 'Failed to delete post', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while deleting the post', 'error');
      });
    }

    // View Post and Increment Views
    function incrementAndViewPost(postId, triggerBtn = null) {
      if (triggerBtn) {
        const viewCountEl = triggerBtn.closest('.topic-card')?.querySelector('.topic-views strong');
        if (viewCountEl) {
          const currentViews = parseInt(viewCountEl.textContent, 10) || 0;
          viewCountEl.textContent = String(currentViews + 1);
        }
      }

      // Open the modal immediately for better responsiveness.
      loadForumDetail(postId);

      // Increment view count in the background.
      fetch(`<?=ROOT?>/admin/forummoderation/incrementView/${postId}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .catch(error => {
        console.error('Error:', error);
      });
    }

    // Load Forum Detail
    function loadForumDetail(postId) {
      showForumLoadingState();
      currentPostId = postId;
      
      fetch(`<?=ROOT?>/admin/forummoderation/getPostDetail/${postId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Store liked reply IDs globally
            window.likedReplyIds = data.likedReplyIds || [];
            hideForumLoadingState();
            displayForumDetail(data.post, data.replies);
          } else {
            hideForumLoadingState();
            showNotification('Failed to load forum details', 'error');
          }
        })
        .catch(error => {
          hideForumLoadingState();
          console.error('Error:', error);
          showNotification('An error occurred while loading forum details', 'error');
        });
    }

    // Display Forum Detail in Modal
    function displayForumDetail(post, replies) {
      // Ensure replies is an array
      if (!Array.isArray(replies)) {
        replies = [];
      }
      
      // Populate modal with post details
      document.getElementById('forumDetailTitle').textContent = post.title;
      document.getElementById('forumDetailCreator').textContent = post.author_name || 'Unknown';
      document.getElementById('forumDetailViews').textContent = post.views || 0;
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
      const repliesList = document.getElementById('repliesList');
      repliesList.innerHTML = '';
      
      if (!replies || replies.length === 0) {
        repliesList.innerHTML = '<p class="fae-48">No replies yet. Be the first to reply!</p>';
      } else {
        replies.forEach((reply, index) => {
          const replyItem = document.createElement('div');
          replyItem.className = 'reply-item';
          
          // Hide replies after the first 3
          if (index >= 3) {
            replyItem.classList.add('hidden-reply');
            replyItem.style.display = 'none';
          }
          
          // Format reply time
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
          
          // Check if user has liked this reply
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
      
      // Show modal
      document.getElementById('forumDetailModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
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
        
        // Scroll back to replies section
        document.getElementById('repliesList').scrollIntoView({ behavior: 'smooth', block: 'start' });
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
          <div class="fae-49">
            <i class="fas fa-spinner fa-spin fae-50"></i>
            <span class="fae-51">Loading discussion...</span>
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

      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function hideForumLoadingState() {
      const modal = document.getElementById('forumDetailModal');
      const overlay = modal ? modal.querySelector('.forum-loading-overlay') : null;
      if (overlay) overlay.remove();
    }

    function closeForumDetailModal() {
      hideForumLoadingState();
      document.getElementById('forumDetailModal').style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    // Submit Reply
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
          // Clear the form
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
      
      // Update reply via backend
      fetch('<?=ROOT?>/admin/forummoderation/updatereply', {
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
      fetch('<?=ROOT?>/admin/forummoderation/deletereply', {
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

    // Edit Reply (from forum detail modal)
    function editReply(replyId) {
      // Implementation for editing reply
      console.log('Edit reply functionality - to be implemented');
    }

    // Delete Reply (from forum detail modal)
    function editReply(replyId) {
      // Implementation for editing reply
      console.log('Edit reply functionality - to be implemented');
    }

    // Delete Reply
    function deleteReply(replyId) {
      if (confirm('Are you sure you want to delete this reply?')) {
        fetch(`<?=ROOT?>/admin/forummoderation/deleteReply/${replyId}`, {
          method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification('Reply deleted successfully!', 'success');
            loadForumDetail(currentPostId);
          } else {
            showNotification(data.message || 'Failed to delete reply', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('An error occurred while deleting the reply', 'error');
        });
      }
    }

    // Toggle Like Reply
    function toggleLikeReply(replyId, buttonElement) {
      fetch(`<?=ROOT?>/admin/forummoderation/likeReply/${replyId}`, {
        method: 'POST'
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
          console.error(data.message || 'Failed to toggle like');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }

    // View Full Post (alias for incrementAndViewPost)
    function viewFullPost(postId) {
      incrementAndViewPost(postId);
    }

    // Moderation actions
    function approvePost(postId) {
      if (confirm('Are you sure you want to approve this post?')) {
        fetch(`<?=ROOT?>/admin/forummoderation/approvePost/${postId}`, {
          method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification('Post approved successfully!', 'success');
            location.reload();
          } else {
            showNotification(data.message || 'Failed to approve post', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('An error occurred', 'error');
        });
      }
    }

    function hidePost(postId) {
      if (confirm('Are you sure you want to hide this post?')) {
        fetch(`<?=ROOT?>/admin/forummoderation/hidePost/${postId}`, {
          method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification('Post hidden successfully!', 'success');
            location.reload();
          } else {
            showNotification(data.message || 'Failed to hide post', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('An error occurred', 'error');
        });
      }
    }

    function deletePost(postId) {
      postToDelete = postId;
      const modal = document.getElementById('deleteConfirmModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
      }
    }

    function warnUser(userId) {
      if (confirm('Send a warning to this user?')) {
        fetch(`<?=ROOT?>/admin/forummoderation/warnUser/${userId}`, {
          method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showNotification('Warning sent to user!', 'success');
          } else {
            showNotification(data.message || 'Failed to send warning', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('An error occurred', 'error');
        });
      }
    }

    // Helper function to format time ago
    function formatTimeAgo(dateString) {
      const timestamp = new Date(dateString).getTime();
      const diff = Math.floor((Date.now() - timestamp) / 1000);
      
      if (diff < 60) return diff + ' seconds ago';
      if (diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
      if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
      return Math.floor(diff / 86400) + ' days ago';
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
      
      const editCustomTagInput = document.getElementById('editCustomTagInput');
      if (editCustomTagInput) {
        editCustomTagInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            addEditCustomTag();
          }
        });
      }
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
      const newPostModal = document.getElementById('newPostModal');
      const quickTagsModal = document.getElementById('quickTagsModal');
      const editForumModal = document.getElementById('editForumModal');
      const forumDetailModal = document.getElementById('forumDetailModal');
      
      if (event.target === newPostModal) {
        closeNewPostModal();
      }
      if (event.target === quickTagsModal) {
        closeQuickTagsModal();
      }
      if (event.target === editForumModal) {
        closeEditForumModal();
      }
      if (event.target === forumDetailModal) {
        closeForumDetailModal();
      }
    }
    
    // Hashtag Search Functionality
    const hashtagSearchInput = document.getElementById('hashtagSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const hashtagPills = document.querySelectorAll('.hashtag-pill');
    const topicCards = document.querySelectorAll('.forum-topics-section .topic-card');
    
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
          // Sort by creation time (most recent first)
          const timeA = a.querySelector('.topic-activity')?.textContent.trim() || '';
          const timeB = b.querySelector('.topic-activity')?.textContent.trim() || '';
          return parseTimeToSeconds(timeA) - parseTimeToSeconds(timeB);
        } else if (sortType === 'Most Popular') {
          // Sort by views (highest first)
          const viewsA = parseInt(a.querySelector('.topic-views strong')?.textContent) || 0;
          const viewsB = parseInt(b.querySelector('.topic-views strong')?.textContent) || 0;
          return viewsB - viewsA;
        } else if (sortType === 'Most Active') {
          // Sort by trending points (highest first)
          const hasTrendingA = a.querySelector('.status-trending') !== null;
          const hasTrendingB = b.querySelector('.status-trending') !== null;
          
          if (hasTrendingA && !hasTrendingB) return -1;
          if (!hasTrendingA && hasTrendingB) return 1;
          
          // If both trending or both not trending, sort by replies
          const repliesA = parseInt(a.querySelector('.topic-replies strong')?.textContent) || 0;
          const repliesB = parseInt(b.querySelector('.topic-replies strong')?.textContent) || 0;
          return repliesB - repliesA;
        }
        return 0;
      });
      
      // Re-append sorted cards
      topicCardsArray.forEach(card => topicsContainer.appendChild(card));
    }
    
    function parseTimeToSeconds(timeStr) {
      const match = timeStr.match(/(\d+)\s*(second|minute|hour|day)/);
      if (!match) return 999999; // Put invalid dates at the end
      
      const value = parseInt(match[1]);
      const unit = match[2];
      
      if (unit.startsWith('second')) return value;
      if (unit.startsWith('minute')) return value * 60;
      if (unit.startsWith('hour')) return value * 3600;
      if (unit.startsWith('day')) return value * 86400;
      return 999999;
    }
    
    // Search input handler
    if (hashtagSearchInput) {
      hashtagSearchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().replace('#', '');
        
        // Show/hide clear button
        clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
        
        // Filter topics
        filterTopicsByHashtag(searchTerm);
        
        // Highlight matching pills
        updatePillHighlights(searchTerm);
      });
    }
    
    // Clear search button
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', function() {
        hashtagSearchInput.value = '';
        clearSearchBtn.style.display = 'none';
        filterTopicsByHashtag('');
        updatePillHighlights('');
      });
    }
    
    // Hashtag pill click handlers
    hashtagPills.forEach(pill => {
      pill.addEventListener('click', function() {
        const tag = this.getAttribute('data-tag');
        hashtagSearchInput.value = tag;
        clearSearchBtn.style.display = 'flex';
        filterTopicsByHashtag(tag.toLowerCase());
        updatePillHighlights(tag.toLowerCase());
      });
    });
    
    // Filter topics based on hashtag
    function filterTopicsByHashtag(searchTerm) {
      let visibleCount = 0;
      
      topicCards.forEach(card => {
        if (!searchTerm) {
          card.style.display = 'block';
          visibleCount++;
          return;
        }
        
        // Get all tags from the card
        const tags = card.querySelectorAll('.topic-tag');
        let hasMatch = false;
        
        tags.forEach(tag => {
          const tagText = tag.textContent.toLowerCase().replace('#', '');
          if (tagText.includes(searchTerm)) {
            hasMatch = true;
          }
        });
        
        // Also check title and description
        const title = card.querySelector('.topic-title')?.textContent.toLowerCase() || '';
        const description = card.querySelector('.topic-description')?.textContent.toLowerCase() || '';
        
        if (hasMatch || title.includes(searchTerm) || description.includes(searchTerm)) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });
      
      // Show "no results" message if needed
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

    
