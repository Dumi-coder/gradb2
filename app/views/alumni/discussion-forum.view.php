<?php 
$page_title = "Discussion Forum";
$page_subtitle = "Engage with fellow alumni and share experiences";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/discussion-forum.css">

    <div class="dashboard-container">
     <!-- sidebar -->
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

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
            // Sample published forums data (replace with actual data from database later)
            $myPublishedForums = [
              [
                'id' => 13,
                'title' => 'Best Practices for Remote Team Management',
                'description' => 'Looking to discuss effective strategies for managing remote teams. What tools and practices have worked well for you?',
                'category' => 'Leadership',
                'tags' => ['Leadership', 'Remote', 'Management'],
                'status' => 'active',
                'views' => 87,
                'replies' => 14,
                'posted_date' => '3 days ago',
                'last_activity' => '1 hour ago'
              ],
              [
                'id' => 14,
                'title' => 'Career Growth in Software Engineering',
                'description' => 'Discussion on career progression paths in software engineering. From junior to senior roles - what skills matter most?',
                'category' => 'Tech',
                'tags' => ['Tech', 'Career', 'Software'],
                'status' => 'active',
                'views' => 156,
                'replies' => 23,
                'posted_date' => '1 week ago',
                'last_activity' => '2 hours ago'
              ],
              [
                'id' => 15,
                'title' => 'Networking Tips for Introverts',
                'description' => 'How do introverts build strong professional networks? Share your experiences and strategies that have worked for you.',
                'category' => 'Networking',
                'tags' => ['Networking', 'Career', 'Advice'],
                'status' => 'active',
                'views' => 134,
                'replies' => 19,
                'posted_date' => '2 weeks ago',
                'last_activity' => '1 day ago'
              ],
              [
                'id' => 16,
                'title' => 'Balancing Multiple Projects Effectively',
                'description' => 'Seeking advice on managing multiple projects simultaneously without burning out. What are your time management secrets?',
                'category' => 'General',
                'tags' => ['Experience', 'Advice', 'Productivity'],
                'status' => 'active',
                'views' => 92,
                'replies' => 11,
                'posted_date' => '3 weeks ago',
                'last_activity' => '3 days ago'
              ],
              [
                'id' => 17,
                'title' => 'Transitioning from Technical to Business Roles',
                'description' => 'Anyone here made the switch from technical roles to business/product management? Would love to hear your journey.',
                'category' => 'Career',
                'tags' => ['Career', 'Leadership', 'Experience'],
                'status' => 'trending',
                'views' => 198,
                'replies' => 31,
                'posted_date' => '1 month ago',
                'last_activity' => '5 hours ago'
              ]
            ];
            ?>
            
            <?php if (empty($myPublishedForums)): ?>
              <div class="no-forums-message">
                <i class="fas fa-newspaper"></i>
                <p>No Forums Published Yet</p>
                <small>Start a discussion and share your knowledge with the community. Click "New Post" to create your first forum topic!</small>
              </div>
            <?php else: ?>
              <div class="topics-container">
                <?php foreach ($myPublishedForums as $forum): ?>
                  <div class="topic-card">
                    <div class="topic-header">
                      <div class="topic-info">
                        <h3 class="topic-title"><?= esc($forum['title']) ?></h3>
                        <p class="topic-creator">Created by You</p>
                      </div>
                      <span class="status-badge status-<?= $forum['status'] ?>">
                        <?= ucfirst($forum['status']) ?>
                      </span>
                    </div>
                    
                    <div class="topic-description">
                      <p><?= esc($forum['description']) ?></p>
                    </div>
                    
                    <div class="topic-tags">
                      <?php foreach ($forum['tags'] as $tag): ?>
                        <span class="topic-tag tag-<?= strtolower($tag) ?>">#<?= esc($tag) ?></span>
                      <?php endforeach; ?>
                    </div>
                    
                    <div class="topic-footer">
                      <div class="topic-meta">
                        <div class="topic-views"><i class="fas fa-eye"></i> <strong><?= $forum['views'] ?></strong></div>
                        <div class="topic-replies"><i class="fas fa-comment"></i> <strong><?= $forum['replies'] ?></strong> replies</div>
                        <div class="topic-activity"><i class="fas fa-clock"></i> <?= esc($forum['last_activity']) ?></div>
                      </div>
                      <div class="topic-actions" style="display: flex; gap: 0.5rem;">
                        <button class="btn btn-primary btn-sm" onclick="openForumDetailModal(<?= $forum['id'] ?>)">
                          <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn-action btn-edit" onclick="openEditForumModal(<?= $forum['id'] ?>)">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDeleteForum(<?= $forum['id'] ?>)">
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
                <a href="<?=ROOT?>/alumni/discussionforum/viewall" class="btn btn-outline">
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
            // Show only first 5 topics
            $displayTopics = array_slice($forumData['topics'], 0, 5);
            foreach ($displayTopics as $topic): ?>
            <div class="topic-card">
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
                <button class="btn btn-primary btn-sm" onclick="openForumDetailModal(<?= $topic['id'] ?>)">
                  <i class="fas fa-eye"></i> View
                </button>
              </div>
            </div>
            <?php endforeach; ?>
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
            // Sample replies data (replace with actual data from database later)
            $myReplies = [
              [
                'id' => 1,
                'forum_id' => 1,
                'forum_title' => 'Mentoring New Graduates: Effective Strategies',
                'reply_text' => 'I\'ve been mentoring for 3 years now and the most important thing I\'ve learned is to be patient and provide constructive feedback. Always encourage questions and create a safe space for learning.',
                'posted_date' => '2 days ago',
                'likes' => 12
              ],
              [
                'id' => 2,
                'forum_id' => 3,
                'forum_title' => 'Work-Life Balance in Tech: Your Strategies',
                'reply_text' => 'Setting clear boundaries has been crucial for me. I don\'t check work emails after 7 PM and make sure to take regular breaks during the day. Also, exercise and hobbies are essential!',
                'posted_date' => '5 days ago',
                'likes' => 8
              ],
              [
                'id' => 3,
                'forum_id' => 8,
                'forum_title' => 'Tech Certifications Worth Pursuing in 2026',
                'reply_text' => 'I recently completed AWS Solutions Architect certification and it really helped advance my career. The hands-on experience is invaluable. Highly recommend it for anyone in cloud computing!',
                'posted_date' => '1 week ago',
                'likes' => 15
              ],
              [
                'id' => 4,
                'forum_id' => 5,
                'forum_title' => 'Building a Strong Professional Network',
                'reply_text' => 'Attending industry conferences has been invaluable for my networking. Don\'t be afraid to reach out to people and follow up after events!',
                'posted_date' => '2 weeks ago',
                'likes' => 10
              ],
              [
                'id' => 5,
                'forum_id' => 2,
                'forum_title' => 'Industry Trends: AI and Machine Learning Impact',
                'reply_text' => 'I\'ve been working with AI tools daily and they\'ve significantly increased my productivity. The key is learning how to use them effectively while maintaining critical thinking.',
                'posted_date' => '3 weeks ago',
                'likes' => 18
              ],
              [
                'id' => 6,
                'forum_id' => 11,
                'forum_title' => 'Data Science and Analytics Career Paths',
                'reply_text' => 'Started as a data analyst and transitioned to data science. Python, SQL, and understanding business context are crucial skills.',
                'posted_date' => '1 month ago',
                'likes' => 14
              ],
              [
                'id' => 7,
                'forum_id' => 6,
                'forum_title' => 'Transitioning to Leadership Roles',
                'reply_text' => 'The biggest challenge was shifting from doing the work to enabling others. Delegation and trust are key leadership skills.',
                'posted_date' => '1 month ago',
                'likes' => 11
              ]
            ];
            ?>
            
            <?php if (empty($myReplies)): ?>
              <div class="no-replies-message">
                <i class="fas fa-comments"></i>
                <p>No Replies Yet</p>
                <small>Your replies to forum discussions will appear here. Join conversations and share your insights!</small>
              </div>
            <?php else: ?>
              <div class="replies-grid">
                <?php foreach ($myReplies as $reply): ?>
                  <div class="reply-card">
                    <div class="reply-card-header">
                      <div class="forum-link-info">
                        <span class="forum-label">Replied to:</span>
                        <a href="javascript:void(0)" class="forum-link" onclick="openForumDetailModal(<?= $reply['forum_id'] ?>)">
                          <?= esc($reply['forum_title']) ?>
                        </a>
                      </div>
                      <span class="reply-date">
                        <i class="fas fa-clock"></i> <?= esc($reply['posted_date']) ?>
                      </span>
                    </div>
                    
                    <div class="reply-card-content">
                      <p class="reply-text"><?= esc($reply['reply_text']) ?></p>
                    </div>
                    
                    <div class="reply-card-footer">
                      <div class="reply-stats">
                        <span class="reply-likes">
                          <i class="fas fa-thumbs-up"></i> <?= $reply['likes'] ?> likes
                        </span>
                      </div>
                      <div class="reply-actions">
                        <button class="btn-action btn-edit" onclick="openEditReplyModal(<?= $reply['id'] ?>, '<?= htmlspecialchars($reply['reply_text'], ENT_QUOTES) ?>')">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDeleteReply(<?= $reply['id'] ?>)">
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
              <!-- Sample replies - will be dynamically loaded -->
              <div class="reply-item">
                <div class="reply-header">
                  <div class="reply-author">
                    <i class="fas fa-user-circle"></i>
                    <strong>Sarah Johnson</strong>
                  </div>
                  <span class="reply-time">2 hours ago</span>
                </div>
                <div class="reply-content">
                  <p>This is a great discussion topic! I've been working in the tech industry for 5 years now and I'd be happy to share my experience. The most important thing is to keep learning and stay updated with the latest technologies.</p>
                </div>
                <div class="reply-footer">
                  <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
                </div>
              </div>

              <div class="reply-item">
                <div class="reply-header">
                  <div class="reply-author">
                    <i class="fas fa-user-circle"></i>
                    <strong>Michael Chen</strong>
                  </div>
                  <span class="reply-time">1 day ago</span>
                </div>
                <div class="reply-content">
                  <p>I completely agree! Networking has been crucial for my career growth. I recommend attending tech meetups and conferences whenever possible. Also, contributing to open-source projects is a great way to build your portfolio.</p>
                </div>
                <div class="reply-footer">
                  <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
                </div>
              </div>

              <div class="reply-item">
                <div class="reply-header">
                  <div class="reply-author">
                    <i class="fas fa-user-circle"></i>
                    <strong>Emily Rodriguez</strong>
                  </div>
                  <span class="reply-time">2 days ago</span>
                </div>
                <div class="reply-content">
                  <p>Don't forget about soft skills! Communication and teamwork are just as important as technical skills. I've seen many talented developers struggle because they couldn't effectively communicate their ideas.</p>
                </div>
                <div class="reply-footer">
                  <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
                </div>
              </div>
            </div>

            <!-- Hidden replies (shown when View All is clicked) -->
            <div class="reply-item hidden-reply">
              <div class="reply-header">
                <div class="reply-author">
                  <i class="fas fa-user-circle"></i>
                  <strong>David Martinez</strong>
                </div>
                <span class="reply-time">3 days ago</span>
              </div>
              <div class="reply-content">
                <p>I'd also recommend finding a mentor in your field. Having someone to guide you and provide feedback on your career decisions can make a huge difference.</p>
              </div>
              <div class="reply-footer">
                <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
              </div>
            </div>

            <div class="reply-item hidden-reply">
              <div class="reply-header">
                <div class="reply-author">
                  <i class="fas fa-user-circle"></i>
                  <strong>Jennifer Wong</strong>
                </div>
                <span class="reply-time">4 days ago</span>
              </div>
              <div class="reply-content">
                <p>Building a strong portfolio is key! Work on personal projects that showcase your skills and passion. Employers love to see practical applications of your knowledge.</p>
              </div>
              <div class="reply-footer">
                <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
              </div>
            </div>

            <div class="reply-item hidden-reply">
              <div class="reply-header">
                <div class="reply-author">
                  <i class="fas fa-user-circle"></i>
                  <strong>Robert Lee</strong>
                </div>
                <span class="reply-time">5 days ago</span>
              </div>
              <div class="reply-content">
                <p>Don't underestimate the power of continuous learning. Take online courses, read industry blogs, and stay curious. The tech landscape changes rapidly!</p>
              </div>
              <div class="reply-footer">
                <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
              </div>
            </div>

            <div class="reply-item hidden-reply">
              <div class="reply-header">
                <div class="reply-author">
                  <i class="fas fa-user-circle"></i>
                  <strong>Amanda Foster</strong>
                </div>
                <span class="reply-time">1 week ago</span>
              </div>
              <div class="reply-content">
                <p>Remember to take care of your work-life balance from the start. It's easy to burn out, especially when you're trying to prove yourself in a new role.</p>
              </div>
              <div class="reply-footer">
                <button class="btn-link"><i class="fas fa-thumbs-up"></i> Like</button>
              </div>
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
            <input type="text" id="postTitle" name="title" placeholder="e.g., Help with data structures" required>
            <small>At least 10 characters.</small>
          </div>
          <div class="form-group">
            <label for="postContent">Content *</label>
            <textarea id="postContent" name="content" placeholder="Write your question or post here..." rows="6" required></textarea>
            <small>At least 50 characters.</small>
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

    <script src="<?=ROOT?>/assets/js/main.js"></script>
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
    function openForumDetailModal(topicId) {
      console.log('Opening Forum Detail Modal for topic:', topicId);
      
      // Sample data (replace with actual data fetching later)
      const forumData = {
        1: {
          title: "Mentoring New Graduates: Effective Strategies",
          creator: "Nimal Perera",
          status: "active",
          views: 134,
          replies: 23,
          last_activity: "1 day ago",
          tags: ["Mentorship", "Career", "Experience"],
          category: "Mentorship",
          description: "Share your experiences and tips for mentoring recent graduates in your field. What approaches have worked best for you in guiding new professionals?"
        },
        2: {
          title: "Industry Trends: AI and Machine Learning Impact",
          creator: "Sanduni Jayawardena",
          status: "trending",
          views: 198,
          replies: 45,
          last_activity: "2 days ago",
          tags: ["General", "Trending", "AI", "Tech"],
          category: "General",
          description: "Discussion about how AI/ML is reshaping different industries and career paths. How are you adapting to these technological changes in your profession?"
        },
        3: {
          title: "Work-Life Balance in Tech: Your Strategies",
          creator: "Chaminda Silva",
          status: "active",
          views: 92,
          replies: 18,
          last_activity: "3 days ago",
          tags: ["Career", "Tech", "Experience"],
          category: "Career",
          description: "How do you maintain a healthy work-life balance in demanding tech roles? Share your strategies and tips for managing stress and personal time."
        },
        4: {
          title: "Remote Work Best Practices",
          creator: "Dilani Fernando",
          status: "active",
          views: 67,
          replies: 12,
          last_activity: "4 days ago",
          tags: ["Career", "General", "Networking"],
          category: "Career",
          description: "What are your best practices for remote work productivity? Looking for tips on home office setup, communication tools, and maintaining team collaboration."
        },
        5: {
          title: "Building a Strong Professional Network",
          creator: "Kasun Rajapaksha",
          status: "active",
          views: 156,
          replies: 31,
          last_activity: "5 days ago",
          tags: ["Networking", "Career", "Experience"],
          category: "Networking",
          description: "How do you expand your professional network effectively? Share your networking strategies, tips for LinkedIn, and experiences from industry events."
        },
        6: {
          title: "Transitioning to Leadership Roles",
          creator: "Tharindi Wickramasinghe",
          status: "active",
          views: 89,
          replies: 20,
          last_activity: "1 week ago",
          tags: ["Leadership", "Career", "Mentorship"],
          category: "Leadership",
          description: "Advice and experiences for alumni transitioning from technical roles to leadership and management positions. What challenges did you face and how did you overcome them?"
        },
        7: {
          title: "Startup Funding: From Idea to Investment",
          creator: "Rukmal Wijemanne",
          status: "active",
          views: 142,
          replies: 28,
          last_activity: "2 weeks ago",
          tags: ["Startup", "Entrepreneurship", "Networking"],
          category: "Entrepreneurship",
          description: "Discussing strategies for securing startup funding, pitching to investors, and navigating the venture capital landscape. Share your fundraising stories and lessons learned."
        },
        8: {
          title: "Tech Certifications Worth Pursuing in 2026",
          creator: "Amila Jayasinghe",
          status: "trending",
          views: 215,
          replies: 42,
          last_activity: "3 days ago",
          tags: ["Tech", "Career", "Skills", "Learning"],
          category: "Career",
          description: "Which tech certifications are most valuable in today's market? AWS, Azure, GCP, or specialized certifications? Share your experiences and recommendations."
        },
        9: {
          title: "Mental Health in High-Pressure Careers",
          creator: "Shalini Perera",
          status: "active",
          views: 178,
          replies: 35,
          last_activity: "4 days ago",
          tags: ["Experience", "General", "Advice"],
          category: "General",
          description: "Let's discuss mental health awareness and strategies for managing stress in demanding career paths. How do you maintain your well-being while pursuing professional goals?"
        },
        10: {
          title: "International Career Opportunities: Tips for Relocation",
          creator: "Dinesh Fernando",
          status: "active",
          views: 134,
          replies: 25,
          last_activity: "5 days ago",
          tags: ["Career", "Networking", "Experience"],
          category: "Career",
          description: "Exploring international job opportunities? Share your experiences with visa processes, cultural adaptation, and building a career abroad."
        },
        11: {
          title: "Data Science and Analytics Career Paths",
          creator: "Hasini Wickramaratne",
          status: "active",
          views: 198,
          replies: 37,
          last_activity: "6 days ago",
          tags: ["Tech", "AI", "Career", "Skills"],
          category: "Tech",
          description: "Discussion about breaking into data science, essential skills, tools, and career progression. From data analyst to data scientist - what's your journey been like?"
        },
        12: {
          title: "Effective Communication Skills for Leaders",
          creator: "Mahesh Silva",
          status: "active",
          views: 112,
          replies: 22,
          last_activity: "1 week ago",
          tags: ["Leadership", "Mentorship", "Experience"],
          category: "Leadership",
          description: "What communication strategies have helped you become a better leader? Share tips on public speaking, team communication, and stakeholder management."
        }
      };

      const forum = forumData[topicId] || forumData[1]; // Default to first topic if ID not found

      // Update modal content
      document.getElementById('forumDetailTitle').textContent = forum.title;
      document.getElementById('forumDetailCreator').textContent = forum.creator;
      document.getElementById('forumDetailStatus').textContent = forum.status.charAt(0).toUpperCase() + forum.status.slice(1);
      document.getElementById('forumDetailStatus').className = 'status-badge status-' + forum.status;
      document.getElementById('forumDetailViews').textContent = forum.views;
      document.getElementById('forumDetailReplies').textContent = forum.replies;
      document.getElementById('repliesCount').textContent = forum.replies;
      document.getElementById('forumDetailActivity').textContent = forum.last_activity;
      document.getElementById('forumDetailDescription').textContent = forum.description;

      // Update tags
      const tagsContainer = document.getElementById('forumDetailTagsContainer');
      tagsContainer.innerHTML = '';
      if (forum.tags && forum.tags.length > 0) {
        forum.tags.forEach(tag => {
          const tagSpan = document.createElement('span');
          tagSpan.className = 'topic-tag tag-' + tag.toLowerCase();
          tagSpan.textContent = '#' + tag;
          tagsContainer.appendChild(tagSpan);
        });
      }

      // Reset hidden replies to hidden state
      const hiddenReplies = document.querySelectorAll('.hidden-reply');
      hiddenReplies.forEach(reply => reply.style.display = 'none');
      
      // Show View All button if there are more than 3 replies
      const viewAllBtn = document.getElementById('viewAllRepliesBtn');
      if (viewAllBtn) {
        if (forum.replies > 3) {
          viewAllBtn.style.display = 'block';
          viewAllBtn.querySelector('.btn-view-all-replies').innerHTML = '<i class="fas fa-comments"></i> View All Replies';
        } else {
          viewAllBtn.style.display = 'none';
        }
      }

      // Show modal
      const modal = document.getElementById('forumDetailModal');
      if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
      }
    }

    function toggleAllReplies() {
      const hiddenReplies = document.querySelectorAll('.hidden-reply');
      const viewAllBtn = document.getElementById('viewAllRepliesBtn');
      const btnElement = viewAllBtn.querySelector('.btn-view-all-replies');
      
      // Check if replies are currently hidden
      const isHidden = hiddenReplies[0].style.display === 'none' || !hiddenReplies[0].style.display;
      
      if (isHidden) {
        // Show all replies
        hiddenReplies.forEach(reply => reply.style.display = 'block');
        btnElement.innerHTML = '<i class="fas fa-chevron-up"></i> Show Less';
      } else {
        // Hide additional replies
        hiddenReplies.forEach(reply => reply.style.display = 'none');
        btnElement.innerHTML = '<i class="fas fa-comments"></i> View All Replies';
        
        // Scroll to replies section
        document.querySelector('.replies-section').scrollIntoView({ behavior: 'smooth' });
      }
    }

    function closeForumDetailModal() {
      console.log('Closing Forum Detail Modal');
      const modal = document.getElementById('forumDetailModal');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    function submitReply(event) {
      event.preventDefault();
      const textarea = event.target.querySelector('.reply-textarea');
      const replyText = textarea.value.trim();
      
      if (replyText) {
        console.log('Submitting reply:', replyText);
        // TODO: Add backend logic to save reply
        
        // For now, just show a success message and clear the form
        alert('Reply posted successfully! (Backend integration pending)');
        textarea.value = '';
      }
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
      
      if (replyText) {
        console.log('Updating reply:', replyId, replyText);
        // TODO: Add backend logic to update reply
        
        alert('Reply updated successfully! (Backend integration pending)');
        closeEditReplyModal();
        // Optionally reload the page to show updated content
        // location.reload();
      }
    }

    function confirmDeleteReply(replyId) {
      if (confirm('Are you sure you want to delete this reply? This action cannot be undone.')) {
        console.log('Deleting reply:', replyId);
        // TODO: Add backend logic to delete reply
        
        alert('Reply deleted successfully! (Backend integration pending)');
        // Optionally reload the page to remove the deleted reply
        // location.reload();
      }
    }

    // My Published Forums Management Functions
    function openEditForumModal(forumId) {
      console.log('Opening Edit Forum Modal for forum:', forumId);
      // TODO: Implement edit forum modal
      alert('Edit forum functionality - Backend integration pending\nForum ID: ' + forumId);
    }

    function confirmDeleteForum(forumId) {
      if (confirm('Are you sure you want to delete this forum? This will also delete all replies. This action cannot be undone.')) {
        console.log('Deleting forum:', forumId);
        // TODO: Add backend logic to delete forum
        
        alert('Forum deleted successfully! (Backend integration pending)');
        // Optionally reload the page to remove the deleted forum
        // location.reload();
      }
    }
    
    function openQuickTagsModal() {
      console.log('Opening Quick Tags Modal');
      const modal = document.getElementById('quickTagsModal');
      if (modal) {
        // Pre-select tags that are already in the textarea
        syncModalWithTextarea();
        modal.classList.add('show');
        modal.style.display = 'block';
        console.log('Quick Tags Modal display set to block and show class added');
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
    function syncModalWithTextarea() {
      const tagsTextarea = document.getElementById('postTags');
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
      const tagsTextarea = document.getElementById('postTags');
      
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
    
    // Form submission
    document.addEventListener('DOMContentLoaded', function() {
      const newPostForm = document.querySelector('.new-post-form');
      if (newPostForm) {
        newPostForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Get form data
          const formData = new FormData(this);
          const title = formData.get('title');
          const category = formData.get('category');
          const content = formData.get('content');
          const priority = formData.get('priority');
          const tags = formData.get('tags');
          
          // Basic validation
          if (title.length < 10) {
            alert('Title must be at least 10 characters long.');
            return;
          }
          
          if (content.length < 50) {
            alert('Content must be at least 50 characters long.');
            return;
          }
          
          if (!category) {
            alert('Please select a category.');
            return;
          }
          
          // Here you would typically send the data to the server
          console.log('New post data:', {
            title: title,
            category: category,
            content: content,
            priority: priority,
            tags: tags
          });
          
          alert('Post created successfully!');
          closeNewPostModal();
          this.reset();
        });
      }
    });
    
    // Hashtag Search Functionality
    const hashtagSearchInput = document.getElementById('hashtagSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const hashtagPills = document.querySelectorAll('.hashtag-pill');
    const topicCards = document.querySelectorAll('.topic-card');
    
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
      const topicsContainer = document.querySelector('.topics-container');
      
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
    /* Custom Tag Input Styles */
    .custom-tag-input-wrapper {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    
    .custom-tag-input {
      flex: 1;
      padding: 0.625rem 1rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-size: 0.9rem;
      background: var(--background);
      color: var(--foreground);
      transition: all 0.3s ease;
    }
    
    .custom-tag-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .custom-tag-help {
      display: block;
      font-size: 0.8rem;
      color: var(--muted-foreground);
      margin-top: 0.25rem;
    }
    
    /* Selected state for quick tags in modal */
    .quick-tag.selectable {
      position: relative;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s ease;
    }
    
    .quick-tag.selectable.selected {
      background: var(--primary) !important;
      color: white !important;
      border-color: var(--primary) !important;
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4) !important;
      transform: translateY(-2px) !important;
    }
    
    .quick-tag.selectable.selected:hover {
      background: var(--primary) !important;
      color: white !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 16px rgba(79, 70, 229, 0.5) !important;
    }
    
    .quick-tag.selectable.selected::after {
      content: '\2713';
      position: absolute;
      top: -6px;
      right: -6px;
      background: #10b981;
      color: white;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: bold;
      border: 2px solid white;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    
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
    
    /* View All Button */
    .view-all-container {
      display: flex;
      justify-content: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid var(--border);
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
      background: var(--primary);
      color: white;
      border-color: var(--primary);
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

    .no-forums-message {
      text-align: center;
      padding: 3rem 1.5rem;
      background: var(--card);
      border-radius: 12px;
      border: 2px dashed var(--border);
    }

    .no-forums-message i {
      font-size: 3rem;
      color: var(--muted-foreground);
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .no-forums-message p {
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

    /* Edit Reply Modal */
    .edit-reply-form .form-group {
      margin-bottom: 1.5rem;
    }

    .edit-reply-form label {
      display: block;
      margin-bottom: 0.5rem;
      color: var(--foreground);
      font-weight: 500;
      font-size: 0.95rem;
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }

    @media (max-width: 768px) {
      .reply-card-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .reply-card-footer {
        flex-direction: column;
        align-items: flex-start;
      }

      .reply-actions {
        width: 100%;
      }

      .btn-action {
        flex: 1;
      }

      .modal-actions {
        flex-direction: column-reverse;
      }

      .modal-actions button {
        width: 100%;
      }
    }
    
    .btn-lg {
      padding: 0.875rem 2rem;
      font-size: 1rem;
      font-weight: 500;
      gap: 0.75rem;
      transition: all 0.3s ease;
    }
    
    .btn-lg:hover {
      transform: translateX(5px);
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }
    
    .btn-lg i {
      transition: transform 0.3s ease;
    }
    
    .btn-lg:hover i {
      transform: translateX(5px);
    }
    
    /* Trending badge red color */    .no-results-message i {
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
    
    /* Trending badge red color */
    .status-badge.status-trending {
      background: #ef4444;
      color: white;
      font-weight: 600;
    }
    
    @media (max-width: 768px) {
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

    /* Forum Detail Modal Styles */
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
  </body>
</html>
