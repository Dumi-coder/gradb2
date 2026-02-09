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
                      <div class="topic-actions" style="display: flex; gap: 0.5rem;">
                        <button class="btn btn-primary btn-sm" onclick="incrementAndViewPost(<?= $forum->post_id ?>)">
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
              <h2 class="section-title" style="font-size: 1.5rem;">
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
            // Show only first 5 topics visible to faculty
            $displayTopics = isset($faculty_posts) && is_array($faculty_posts) ? array_slice($faculty_posts, 0, 5) : [];
            
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
                        <a href="javascript:void(0)" class="forum-link" onclick="incrementAndViewPost(<?= $reply->forum_id ?>)">
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

        <!-- Reported Posts Section -->
        <section class="dashboard-section reported-posts-section">
          <div class="section-header">
            <div class="section-title-container">
              <h2 class="section-title">
                <i class="fas fa-flag"></i> Reported Posts
              </h2>
            </div>
          </div>
          
          <div class="topics-container">
            <?php
            // Hardcoded demo data for reported posts
            $reportedPosts = [
              (object)[
                'post_id' => 1001,
                'title' => 'Looking for study group members',
                'author_name' => 'John Doe',
                'content' => 'I am looking for study group members for CS 301. Anyone interested in joining? We plan to meet twice a week to review course materials and work on assignments together.',
                'report_reason' => 'Spam',
                'reported_by' => 'Jane Smith',
                'reported_date' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'report_count' => 1
              ],
              (object)[
                'post_id' => 1002,
                'title' => 'Job opportunities in tech',
                'author_name' => 'Mike Johnson',
                'content' => 'Sharing some great job opportunities I found in the tech industry. Check out these amazing positions at top companies!',
                'report_reason' => 'Inappropriate Content',
                'reported_by' => 'Sarah Wilson',
                'reported_date' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'report_count' => 3
              ],
              (object)[
                'post_id' => 1003,
                'title' => 'Help with assignment deadline',
                'author_name' => 'Emily Brown',
                'content' => 'Can someone help me understand the requirements for the final project? The deadline is approaching and I want to make sure I am on the right track.',
                'report_reason' => 'Off-topic',
                'reported_by' => 'David Lee',
                'reported_date' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'report_count' => 1
              ]
            ];
            ?>
            
            <?php if (empty($reportedPosts)): ?>
              <div style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5; color: #10b981;"></i>
                <p style="font-size: 16px; margin: 0;">No Reported Posts</p>
                <p style="font-size: 14px; margin-top: 8px; opacity: 0.8;">Great! There are no posts requiring moderation at this time.</p>
              </div>
            <?php else: ?>
              <?php foreach ($reportedPosts as $post): 
                // Format reported_date as "X time ago"
                $timestamp = strtotime($post->reported_date);
                $diff = time() - $timestamp;
                if ($diff < 60) $reported_time = $diff . ' seconds ago';
                elseif ($diff < 3600) $reported_time = floor($diff / 60) . ' minutes ago';
                elseif ($diff < 86400) $reported_time = floor($diff / 3600) . ' hours ago';
                else $reported_time = floor($diff / 86400) . ' days ago';
              ?>
                <div class="topic-card reported-card">
                  <div class="topic-header">
                    <div class="topic-info">
                      <h3 class="topic-title"><?= esc($post->title) ?></h3>
                      <p class="topic-creator">By <?= esc($post->author_name) ?> • Reported by <?= esc($post->reported_by) ?></p>
                    </div>
                    <span class="status-badge status-reported">
                      <i class="fas fa-flag"></i> Reported
                    </span>
                  </div>
                  
                  <div class="topic-description">
                    <p><?= esc(substr($post->content, 0, 150)) ?><?= strlen($post->content) > 150 ? '...' : '' ?></p>
                  </div>
                  
                  <div class="topic-tags">
                    <span class="topic-tag tag-report"><?= esc($post->report_reason) ?></span>
                    <?php if ($post->report_count > 1): ?>
                      <span class="topic-tag tag-trending"><?= $post->report_count ?> Reports</span>
                    <?php endif; ?>
                  </div>
                  
                  <div class="topic-footer">
                    <div class="topic-meta">
                      <div class="topic-activity"><i class="fas fa-clock"></i> <?= $reported_time ?></div>
                    </div>
                    <div class="topic-actions" style="display: flex; gap: 0.5rem;">
                      <button class="btn btn-primary btn-sm" onclick="viewFullPost(<?= $post->post_id ?>)">
                        <i class="fas fa-eye"></i> View
                      </button>
                      <button class="btn-action btn-edit" onclick="approvePost(<?= $post->post_id ?>)">
                        <i class="fas fa-check"></i> Approve
                      </button>
                      <button class="btn-action btn-delete" onclick="deletePost(<?= $post->post_id ?>)">
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>

      </main>
    </div>

    <!-- New Post Modal -->
    <div id="newPostModal" class="modal" style="display: none;">
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
            <select id="visibleFaculties" name="faculty_id" class="form-control" required style="padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; background-color: #fff; font-size: 14px;">
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
            <textarea id="postTags" name="tags" placeholder="Click 'Quick Tags' button below to select tags" rows="2" readonly style="cursor: pointer; background-color: var(--muted);"></textarea>
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
    <div id="editForumModal" class="modal" style="display: none;">
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
            <select id="editVisibleFaculties" name="faculty_id" class="form-control" required style="padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; background-color: #fff; font-size: 14px;">
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
      <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="border-bottom: 1px solid #e5e7eb;">
          <h2 class="modal-title" style="color: #dc2626; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding: 24px;">
          <p style="color: #1f2937; font-size: 15px; line-height: 1.6; margin-bottom: 16px;">
            Are you sure you want to delete this forum post?
          </p>
          <div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 12px 16px; border-radius: 4px;">
            <p style="color: #991b1b; font-size: 14px; margin: 0; line-height: 1.5;">
              <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone. All replies to this post will also be deleted.
            </p>
          </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
          <button class="btn btn-outline" onclick="closeDeleteModal()" style="min-width: 100px;">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button class="btn" onclick="executeDelete()" style="background: #dc2626; color: white; min-width: 100px;">
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
      <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="border-bottom: 1px solid #e5e7eb;">
          <h2 class="modal-title" style="color: #dc2626; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
          </h2>
          <button class="modal-close" onclick="closeDeleteReplyConfirmModal()"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding: 24px;">
          <p style="color: #1f2937; font-size: 15px; line-height: 1.6; margin-bottom: 16px;">
            Are you sure you want to delete this reply?
          </p>
          <div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 12px 16px; border-radius: 4px;">
            <p style="color: #991b1b; font-size: 14px; margin: 0; line-height: 1.5;">
              <i class="fas fa-info-circle"></i> <strong>Warning:</strong> This action cannot be undone.
            </p>
          </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
          <button class="btn btn-outline" onclick="closeDeleteReplyConfirmModal()" style="min-width: 100px;">
            <i class="fas fa-times"></i>
            <span>Cancel</span>
          </button>
          <button class="btn" onclick="executeDeleteReply()" style="background: #dc2626; color: white; min-width: 100px;">
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
            <div id="customTagsDisplay" class="quick-tags-grid" style="margin-top: 1rem;"></div>
          </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
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
    function incrementAndViewPost(postId) {
      // Increment view count
      fetch(`<?=ROOT?>/admin/forummoderation/incrementView/${postId}`, {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        // Load and show forum detail
        loadForumDetail(postId);
      })
      .catch(error => {
        console.error('Error:', error);
        loadForumDetail(postId);
      });
    }

    // Load Forum Detail
    function loadForumDetail(postId) {
      currentPostId = postId;
      
      fetch(`<?=ROOT?>/admin/forummoderation/getPostDetail/${postId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Store liked reply IDs globally
            window.likedReplyIds = data.likedReplyIds || [];
            displayForumDetail(data.post, data.replies);
          } else {
            showNotification('Failed to load forum details', 'error');
          }
        })
        .catch(error => {
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
        repliesList.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">No replies yet. Be the first to reply!</p>';
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

    function closeForumDetailModal() {
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

    // Moderation Actions for Reported Posts
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

    <style>
    /* Section Header Styles */
    .section-title-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .section-title {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin: 0;
    }

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

    /* Topic Card Styles */
    .topics-container {
      margin-top: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

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

    .topic-actions {
      display: flex;
      gap: 0.5rem;
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
    
    /* My Forums empty state */
    .my-forums-list {
      margin-top: 1.5rem;
    }
    
    .no-forums-message {
      text-align: center;
      padding: 3rem 2rem;
      background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(79, 70, 229, 0.02) 100%);
      border-radius: 16px;
      border: 2px dashed rgba(79, 70, 229, 0.3);
      color: var(--muted-foreground);
      transition: all 0.3s ease;
    }
    
    .no-forums-message:hover {
      border-color: rgba(79, 70, 229, 0.5);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
    }
    
    .no-forums-message i {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      color: var(--primary);
      opacity: 0.7;
      animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
      0%, 100% { opacity: 0.7; transform: scale(1); }
      50% { opacity: 1; transform: scale(1.05); }
    }
    
    .no-forums-message p {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
      color: var(--foreground);
    }
    
    .no-forums-message small {
      font-size: 0.95rem;
      color: var(--muted-foreground);
      line-height: 1.6;
      display: block;
      max-width: 500px;
      margin: 0 auto;
    }

    /* My Replies Section Styles */
    .my-replies-section {
      margin-bottom: 2rem;
    }

    .my-replies-section .section-title {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .my-replies-section .section-title i {
      color: var(--primary);
    }

    .my-replies-container {
      margin-top: 1.5rem;
    }

    .replies-grid {
      display: grid;
      gap: 1.25rem;
    }

    .reply-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.5rem;
      transition: all 0.3s ease;
    }

    .reply-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
    }

    .reply-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .forum-link-info {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }

    .forum-label {
      font-size: 0.8rem;
      color: var(--muted-foreground);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 500;
    }

    .forum-link {
      color: var(--primary);
      font-weight: 600;
      text-decoration: none;
      font-size: 0.95rem;
      transition: color 0.2s ease;
    }

    .forum-link:hover {
      color: #5850c7;
      text-decoration: underline;
    }

    .reply-date {
      display: flex;
      align-items: center;
      gap: 0.35rem;
      color: var(--muted-foreground);
      font-size: 0.85rem;
    }

    .reply-card-content {
      margin-bottom: 1rem;
    }

    .reply-text {
      color: var(--foreground);
      line-height: 1.6;
      font-size: 0.95rem;
    }

    .reply-card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
      flex-wrap: wrap;
      gap: 1rem;
    }

    .reply-stats {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .reply-likes {
      display: flex;
      align-items: center;
      gap: 0.35rem;
      color: var(--muted-foreground);
      font-size: 0.9rem;
    }

    .reply-likes i {
      color: var(--primary);
    }

    .reply-actions {
      display: flex;
      gap: 0.75rem;
    }

    .btn-action {
      padding: 0.5rem 1rem;
      border: 1px solid var(--border);
      border-radius: 6px;
      background: var(--background);
      color: var(--foreground);
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.35rem;
    }

    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-edit:hover {
      background: #1a1a1a;
      color: white;
      border-color: #1a1a1a;
    }

    .btn-delete:hover {
      background: #dc2626;
      color: white;
      border-color: #dc2626;
    }

    .no-replies-message {
      text-align: center;
      padding: 3rem 1.5rem;
      background: var(--card);
      border-radius: 12px;
      border: 2px dashed var(--border);
    }

    .no-replies-message i {
      font-size: 3rem;
      color: var(--muted-foreground);
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .no-replies-message p {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--foreground);
    }

    .no-replies-message small {
      font-size: 0.9rem;
      color: var(--muted-foreground);
      line-height: 1.6;
      display: block;
      max-width: 400px;
      margin: 0 auto;
    }

    /* My Published Forums Section Styles */
    .my-published-forums-section {
      margin-bottom: 2rem;
    }

    .my-published-forums-section .section-title {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .my-published-forums-section .section-title i {
      color: var(--primary);
    }

    .my-published-container {
      margin-top: 1.5rem;
    }

    .published-forums-grid {
      display: grid;
      gap: 1.5rem;
    }

    .published-forum-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.5rem;
      transition: all 0.3s ease;
    }

    .published-forum-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
      border-color: var(--primary);
    }

    .published-forum-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
      gap: 1rem;
      flex-wrap: wrap;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--border);
    }

    .forum-title-section {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex: 1;
      flex-wrap: wrap;
    }

    .forum-title-text {
      font-size: 1.15rem;
      font-weight: 600;
      color: var(--foreground);
      margin: 0;
      line-height: 1.4;
    }

    .forum-posted-date {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--muted-foreground);
      font-size: 0.85rem;
      font-weight: 500;
      white-space: nowrap;
    }

    .forum-posted-date i {
      color: var(--primary);
    }

    .published-forum-description {
      margin-bottom: 1rem;
    }

    .published-forum-description p {
      color: var(--muted-foreground);
      line-height: 1.6;
      font-size: 0.95rem;
      margin: 0;
    }

    .published-forum-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .published-forum-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
      flex-wrap: wrap;
      gap: 1rem;
    }

    .forum-stats-section {
      display: flex;
      gap: 1.25rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 0.35rem;
      color: var(--muted-foreground);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .stat-item i {
      color: var(--primary);
    }

    .forum-actions-section {
      display: flex;
      gap: 0.65rem;
      flex-wrap: wrap;
    }

    .btn-view:hover {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    /* Reported Posts Section */
    .reported-posts-section {
      margin-bottom: 2rem;
    }

    /* Reported Posts - use same card styling as Forum Topics */
    .status-badge.status-reported {
      background: #fef2f2;
      color: #dc2626;
      border: 1px solid #fecaca;
    }

    .topic-card.reported-card {
      border-left: 3px solid #dc2626;
    }

    .tag-report {
      background: #fef2f2;
      color: #dc2626;
      border: 1px solid #fecaca;
    }

    /* Status Badge */
    .status-badge {
      padding: 0.25rem 0.625rem;
      border-radius: 4px;
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

    /* Responsive */
    @media (max-width: 768px) {
      .reply-card-header,
      .reply-card-footer,
      .published-forum-header,
      .published-forum-footer {
        flex-direction: column;
        align-items: flex-start;
      }

      .reply-actions,
      .forum-actions-section {
        width: 100%;
      }

      .btn-action {
        flex: 1;
      }

      .filter-controls {
        flex-direction: column;
        width: 100%;
      }

      .filter-select {
        width: 100%;
      }

      .hashtag-search-section {
        padding: 1rem;
      }
      
      .trending-hashtags {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .hashtag-pills {
        width: 100%;
      }
    }

    /* Quick Tags Modal - Remove double scrollbar */
    #quickTagsModal .modal-content {
      overflow-y: visible;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
    }

    #quickTagsModal .quick-tags-content {
      flex: 1;
      overflow-y: auto;
      max-height: calc(90vh - 180px);
    }

    #quickTagsModal .modal-footer {
      flex-shrink: 0;
      border-top: 1px solid var(--border);
    }

    /* Notification System */
    .notification-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 10000;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .notification {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      min-width: 300px;
      max-width: 400px;
      transform: translateX(400px);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .notification.show {
      transform: translateX(0);
      opacity: 1;
    }

    .notification-icon {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 16px;
      flex-shrink: 0;
    }

    .notification-success {
      border-left: 4px solid #10b981;
    }

    .notification-success .notification-icon {
      background: #d1fae5;
      color: #059669;
    }

    .notification-error {
      border-left: 4px solid #ef4444;
    }

    .notification-error .notification-icon {
      background: #fee2e2;
      color: #dc2626;
    }

    .notification-info {
      border-left: 4px solid #3b82f6;
    }

    .notification-info .notification-icon {
      background: #dbeafe;
      color: #2563eb;
    }

    .notification-message {
      flex: 1;
      color: #374151;
      font-size: 14px;
      line-height: 1.5;
    }

    /* Forum Detail Modal Styling */
    .modal-large {
      max-width: 900px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
    }

    .forum-detail-content {
      padding: 1.5rem;
    }

    .forum-detail-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
      margin-bottom: 1.5rem;
    }

    .creator-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--muted-foreground);
    }

    .creator-info i {
      font-size: 1.25rem;
      color: var(--primary);
    }

    .forum-stats {
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
      font-size: 0.9rem;
      color: var(--muted-foreground);
    }

    .forum-stats span {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .forum-detail-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }

    .forum-detail-description {
      margin-bottom: 2rem;
    }

    .forum-detail-description h3 {
      color: var(--foreground);
      font-size: 1.1rem;
      margin-bottom: 0.75rem;
      font-weight: 600;
    }

    .forum-detail-description p {
      color: var(--muted-foreground);
      line-height: 1.6;
    }

    .replies-section {
      border-top: 2px solid var(--border);
      padding-top: 2rem;
    }

    .replies-heading {
      color: var(--foreground);
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .replies-heading i {
      color: var(--primary);
    }

    .replies-list {
      margin-bottom: 2rem;
    }

    /* Hidden Replies */
    .hidden-reply {
      display: none;
    }

    /* View All Replies Button */
    .view-all-replies-container {
      text-align: center;
      margin: 1.5rem 0;
      padding-top: 1rem;
      border-top: 1px solid var(--border);
    }

    .btn-view-all-replies {
      padding: 0.75rem 2rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-view-all-replies:hover {
      background: #5850c7;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-view-all-replies i {
      font-size: 1rem;
    }

    .reply-item {
      background: var(--card);
      padding: 1.25rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      border: 1px solid var(--border);
      transition: all 0.2s ease;
    }

    .reply-item:hover {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .reply-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.75rem;
    }

    .reply-author {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--foreground);
      font-weight: 600;
    }

    .reply-author i {
      font-size: 1.5rem;
      color: var(--primary);
    }

    .reply-time {
      color: var(--muted-foreground);
      font-size: 0.85rem;
    }

    .reply-content {
      margin-bottom: 0.75rem;
      color: var(--muted-foreground);
      line-height: 1.6;
    }

    .reply-footer {
      display: flex;
      gap: 1.5rem;
    }

    .btn-link {
      background: none;
      border: none;
      color: var(--muted-foreground);
      cursor: pointer;
      font-size: 0.9rem;
      padding: 0;
      display: flex;
      align-items: center;
      gap: 0.35rem;
      transition: color 0.2s ease;
    }

    .btn-link:hover {
      color: var(--primary);
    }

    .btn-link.liked {
      color: var(--primary);
      font-weight: 600;
    }

    .btn-link.liked i {
      color: var(--primary);
    }

    .reply-form-container {
      background: var(--card);
      padding: 1.5rem;
      border-radius: 8px;
      border: 1px solid var(--border);
    }

    .reply-form-container h4 {
      color: var(--foreground);
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .reply-form-container h4 i {
      color: var(--primary);
    }

    .reply-textarea {
      width: 100%;
      padding: 1rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.95rem;
      color: var(--foreground);
      background: var(--background);
      resize: vertical;
      min-height: 100px;
      transition: border-color 0.3s ease;
    }

    .reply-textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .reply-form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      margin-top: 1rem;
    }

    @media (max-width: 768px) {
      .modal-large {
        width: 95%;
        max-height: 95vh;
      }

      .forum-detail-meta {
        flex-direction: column;
        align-items: flex-start;
      }

      .forum-stats {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .reply-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .reply-form-actions {
        flex-direction: column-reverse;
      }

      .reply-form-actions button {
        width: 100%;
      }
    }
    </style>
