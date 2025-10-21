<?php 
$page_title = "Discussion Forum";
$page_subtitle = "Connect with peers and collaborate";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css">

<div class="dashboard-container">
      <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Forum Header (simple) -->
        <section class="dashboard-section forum-header-section">
          <div class="section-header">
            <div class="section-actions">
              <button class="btn btn-outline btn-md" onclick="openQuickTagsModal()">
                <i class="fas fa-hashtag"></i>
                <span>Quick Tags</span>
              </button>
              <button class="btn btn-primary btn-md" onclick="openNewPostModal()">
                <i class="fas fa-plus"></i>
                <span>New Post</span>
              </button>
            </div>
          </div>
        </section>

        <!-- My Discussions Section -->
        <section class="dashboard-section my-discussions-section">
          <div class="section-header">
            <h2 class="card-title">My Discussions</h2>
          </div>


          

          <div class="discussions-list">
            <?php if (!empty($my_posts)): ?>
              <?php foreach ($my_posts as $post): ?>
                <div class="discussion-item my-post">
                  <div class="discussion-avatar"><i class="fas fa-user"></i></div>
                  <div class="discussion-content">
                    <div class="discussion-header">
                      <h3 class="discussion-title"><?= esc($post->title) ?></h3>
                      <span class="discussion-category category-<?= strtolower($post->category) ?>">
                        <?php 
                          // Display friendly names
                          if ($post->category === 'CSF') echo 'CS Help';
                          elseif ($post->category === 'StudyTips') echo 'Study Tips';
                          else echo 'General';
                        ?>
                      </span>
                    </div>
                    <p class="discussion-excerpt"><?= esc(substr($post->content, 0, 150)) ?>...</p>
                    <div class="discussion-meta">
                      <span><i class="fas fa-user"></i> You</span>
                      <span><i class="fas fa-clock"></i> <?= time_elapsed_string($post->created_at) ?></span>
                      <?php if ($post->tags): ?>
                        <span class="discussion-tags">
                          <i class="fas fa-tags"></i> 
                          <?php 
                            $tags = explode(',', $post->tags);
                            foreach ($tags as $tag) {
                              echo '#' . esc(trim($tag)) . ' ';
                            }
                          ?>
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="discussion-actions">
                    <button class="btn btn-outline btn-sm" onclick="editPost(<?= $post->post_id ?>)">
                      <i class="fas fa-edit"></i><span>Edit</span>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deletePost(<?= $post->post_id ?>)">
                      <i class="fas fa-trash"></i><span>Delete</span>
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p style="text-align: center; color: var(--muted-foreground); padding: 2rem;">
                You haven't created any posts yet. Click "New Post" to get started!
              </p>
            <?php endif; ?>
          </div>
        </section>

        <!-- Recent Discussions (simple list) -->
        <section class="dashboard-section recent-discussions-section">
          <div class="section-header">
            <h2 class="card-title">Recent Discussions</h2>
          </div>

          <div class="discussions-list">
            <div class="discussion-item">
              <div class="discussion-avatar"><i class="fas fa-user"></i></div>
              <div class="discussion-content">
                <div class="discussion-header">
                  <h3 class="discussion-title">Tips for mastering recursion?</h3>
                  <span class="discussion-category category-csf">CS Help</span>
                </div>
                <p class="discussion-excerpt">I struggle to visualize recursive calls. Any strategies or resources you recommend?</p>
                <div class="discussion-meta">
                  <span><i class="fas fa-user"></i> Alex</span>
                  <span><i class="fas fa-clock"></i> 12 mins ago</span>
                  <span><i class="fas fa-comment"></i> 4 replies</span>
                  <span class="discussion-tags">
                    <i class="fas fa-tags"></i> #recursion #help
                  </span>
                </div>
              </div>
              <div class="discussion-actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-reply"></i><span>Reply</span></button>
              </div>
            </div>

            <div class="discussion-item">
              <div class="discussion-avatar"><i class="fas fa-user"></i></div>
              <div class="discussion-content">
                <div class="discussion-header">
                  <h3 class="discussion-title">Sharing: My study plan for finals</h3>
                  <span class="discussion-category category-studytips">Study Tips</span>
                </div>
                <p class="discussion-excerpt">Here's a simple schedule that helped me last term. Hope it helps someone!</p>
                <div class="discussion-meta">
                  <span><i class="fas fa-user"></i> Priya</span>
                  <span><i class="fas fa-clock"></i> 1 hour ago</span>
                  <span><i class="fas fa-comment"></i> 7 replies</span>
                  <span class="discussion-tags">
                    <i class="fas fa-tags"></i> #studyplan #finals
                  </span>
                </div>
              </div>
              <div class="discussion-actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-reply"></i><span>Reply</span></button>
              </div>
            </div>

            <div class="discussion-item">
              <div class="discussion-avatar"><i class="fas fa-user"></i></div>
              <div class="discussion-content">
                <div class="discussion-header">
                  <h3 class="discussion-title">Anyone up for a study group?</h3>
                  <span class="discussion-category category-general">General</span>
                </div>
                <p class="discussion-excerpt">Looking to form a study group for the upcoming semester. DM if interested!</p>
                <div class="discussion-meta">
                  <span><i class="fas fa-user"></i> Sarah</span>
                  <span><i class="fas fa-clock"></i> 3 hours ago</span>
                  <span><i class="fas fa-comment"></i> 12 replies</span>
                  <span class="discussion-tags">
                    <i class="fas fa-tags"></i> #studygroup #collaboration
                  </span>
                </div>
              </div>
              <div class="discussion-actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-reply"></i><span>Reply</span></button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- New Post Modal (minimal, matches JS/CSS) -->
    <div id="newPostModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Post</h2>
          <button class="modal-close" onclick="closeNewPostModal()"><i class="fas fa-times"></i></button>
        </div>

        <form class="new-post-form">
          <div class="form-group">
            <label for="postTitle">Title *</label>
            <input type="text" id="postTitle" name="postTitle" placeholder="e.g., Help with data structures" required>
            <div class="form-help">At least 10 characters.</div>
          </div>

          <div class="form-group">
            <label for="postCategory">Category *</label>
            <select id="postCategory" name="postCategory" required>
              <option value="">Select a category</option>
              <option value="CSF">CS Help</option>
              <option value="StudyTips">Study Tips</option>
              <option value="General">General</option>
            </select>
          </div>

          <div class="form-group">
            <label for="postContent">Content *</label>
            <textarea id="postContent" name="postContent" rows="5" placeholder="Write your question or post here..." required></textarea>
            <div class="form-help">At least 50 characters.</div>
          </div>

          <div class="form-group">
            <label for="postPriority">Priority</label>
            <select id="postPriority" name="postPriority">
              <option value="normal">Normal</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <div class="form-group">
            <label>Tags</label>
            <div class="tags-input-container">
              <div id="tagsDisplay" class="tags-display"></div>
              <input id="tagInput" type="text" placeholder="Type a tag and press Enter">
            </div>
            <div class="form-help">Examples: #python, #algorithms, #notes</div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline btn-md" onclick="openQuickTagsModal()">
              <i class="fas fa-hashtag"></i>
              <span>Quick Tags</span>
            </button>
            <button type="button" class="btn btn-outline btn-md" onclick="closeNewPostModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
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
          <button class="modal-close" onclick="closeEditPostModal()"><i class="fas fa-times"></i></button>
        </div>

        <form class="edit-post-form" id="editPostForm">
          <input type="hidden" id="editPostId" name="editPostId">
          
          <div class="form-group">
            <label for="editPostTitle">Title *</label>
            <input type="text" id="editPostTitle" name="editPostTitle" placeholder="e.g., Help with data structures" required>
            <div class="form-help">At least 10 characters.</div>
          </div>

          <div class="form-group">
            <label for="editPostCategory">Category *</label>
            <select id="editPostCategory" name="editPostCategory" required>
              <option value="">Select a category</option>
              <option value="CSF">CS Help</option>
              <option value="StudyTips">Study Tips</option>
              <option value="General">General</option>
            </select>
          </div>

          <div class="form-group">
            <label for="editPostContent">Content *</label>
            <textarea id="editPostContent" name="editPostContent" rows="5" placeholder="Write your question or post here..." required></textarea>
            <div class="form-help">At least 50 characters.</div>
          </div>

          <div class="form-group">
            <label for="editPostPriority">Priority</label>
            <select id="editPostPriority" name="editPostPriority">
              <option value="normal">Normal</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <div class="form-group">
            <label>Tags</label>
            <div class="tags-input-container">
              <div id="editTagsDisplay" class="tags-display"></div>
              <input id="editTagInput" type="text" placeholder="Type a tag and press Enter">
            </div>
            <div class="form-help">Examples: #python, #algorithms, #notes</div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline btn-md" onclick="closeEditPostModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
              <i class="fas fa-save"></i>
              <span>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Quick Tags Modal (minimal) -->
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
              <div class="quick-tag" onclick="addQuickTag('python')">#python</div>
              <div class="quick-tag" onclick="addQuickTag('algorithms')">#algorithms</div>
              <div class="quick-tag" onclick="addQuickTag('notes')">#notes</div>
              <div class="quick-tag" onclick="addQuickTag('help')">#help</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline btn-md" onclick="closeQuickTagsModal()">
            <span>Close</span>
          </button>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/discussion-forum.js"></script>
  </body>
  
</html>


