<?php 
$page_title = "Fundraising";
$page_subtitle = "Support student initiatives and community campaigns";
require '../app/views/partials/student_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/fundraising.css">

<div class="dashboard-container">
            <!-- Sidebar Navigation -->
      <?php require '../app/views/partials/student_sidebar.php'; ?>

      <!-- Main Content Area -->
      <main class="main-content">
        <!-- Create Campaign Section -->
        <section class="dashboard-section create-section">
          <div class="section-header">
            <h2 class="card-title">Start a Campaign</h2>
            <button class="btn btn-primary btn-md" onclick="openCreateCampaignModal()">
              <i class="fas fa-plus"></i>
              <span>Create Campaign</span>
            </button>
          </div>
          
          <div class="create-info">
            <p>Launch fundraising campaigns for student projects, emergency aid, or community initiatives.</p>
          </div>
        </section>

        <!-- Active Campaigns -->
        <section class="dashboard-section campaigns-section">
          <div class="section-header">
            <h2 class="card-title">Active Campaigns</h2>
            <div class="section-actions">
              <button class="btn btn-outline btn-sm" onclick="viewAllCampaigns()">
                <span>View All</span>
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <div class="campaigns-grid">
            <div class="campaign-card">
              <div class="campaign-image">
                <div class="campaign-status urgent">Urgent</div>
                <div class="campaign-progress">
                  <div class="progress-bar">
                    <div class="progress-fill" style="width: 75%"></div>
                  </div>
                  <span class="progress-text">75% funded</span>
                </div>
              </div>
              <div class="campaign-content">
                <div class="campaign-category emergency">Emergency Aid</div>
                <h3 class="campaign-title">Student Emergency Fund</h3>
                <p class="campaign-description">Support students facing unexpected financial hardships due to family emergencies or medical expenses.</p>
                <div class="campaign-meta">
                  <div class="campaign-goal">
                    <span class="goal-amount">$15,000</span>
                    <span class="goal-label">Goal</span>
                  </div>
                  <div class="campaign-raised">
                    <span class="raised-amount">$11,250</span>
                    <span class="raised-label">Raised</span>
                  </div>
                  <div class="campaign-donors">
                    <span class="donors-count">127</span>
                    <span class="donors-label">Donors</span>
                  </div>
                </div>
                <div class="campaign-actions">
                  <button class="btn btn-primary btn-md" onclick="openDonateModal('Student Emergency Fund', 15000, 11250)">
                    <i class="fas fa-heart"></i>
                    <span>Donate Now</span>
                  </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-share"></i>
                    <span>Share</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="campaign-card">
              <div class="campaign-image">
                <div class="campaign-status active">Active</div>
                <div class="campaign-progress">
                  <div class="progress-bar">
                    <div class="progress-fill" style="width: 45%"></div>
                  </div>
                  <span class="progress-text">45% funded</span>
                </div>
              </div>
              <div class="campaign-content">
                <div class="campaign-category education">Education</div>
                <h3 class="campaign-title">Laptop Loan Program</h3>
                <p class="campaign-description">Provide laptops to students who cannot afford them for online learning and coursework.</p>
                <div class="campaign-meta">
                  <div class="campaign-goal">
                    <span class="goal-amount">$25,000</span>
                    <span class="goal-label">Goal</span>
                  </div>
                  <div class="campaign-raised">
                    <span class="raised-amount">$11,250</span>
                    <span class="raised-label">Raised</span>
                  </div>
                  <div class="campaign-donors">
                    <span class="donors-count">89</span>
                    <span class="donors-label">Donors</span>
                  </div>
                </div>
                <div class="campaign-actions">
                  <button class="btn btn-primary btn-md" onclick="openDonateModal('Laptop Loan Program', 25000, 11250)">
                    <i class="fas fa-heart"></i>
                    <span>Donate Now</span>
                  </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-share"></i>
                    <span>Share</span>
                  </button>
                </div>
              </div>
            </div>

            <div class="campaign-card">
              <div class="campaign-image">
                <div class="campaign-status completed">Completed</div>
                <div class="campaign-progress">
                  <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%"></div>
                  </div>
                  <span class="progress-text">100% funded</span>
                </div>
              </div>
              <div class="campaign-content">
                <div class="campaign-category community">Community</div>
                <h3 class="campaign-title">Study Room Renovation</h3>
                <p class="campaign-description">Renovate the main study room with new furniture, lighting, and technology equipment.</p>
                <div class="campaign-meta">
                  <div class="campaign-goal">
                    <span class="goal-amount">$8,000</span>
                    <span class="goal-label">Goal</span>
                  </div>
                  <div class="campaign-raised">
                    <span class="raised-amount">$8,000</span>
                    <span class="raised-label">Raised</span>
                  </div>
                  <div class="campaign-donors">
                    <span class="donors-count">156</span>
                    <span class="donors-label">Donors</span>
                  </div>
                </div>
                <div class="campaign-actions">
                  <button class="btn btn-outline btn-md" disabled>
                    <i class="fas fa-check"></i>
                    <span>Completed</span>
                  </button>
                  <button class="btn btn-outline btn-sm">
                    <i class="fas fa-eye"></i>
                    <span>View Results</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- My Donations -->
        <section class="dashboard-section donations-section">
          <div class="section-header">
            <h2 class="card-title">My Donations</h2>
          </div>

          <div class="donations-list">
            <div class="donation-item">
              <div class="donation-icon">
                <i class="fas fa-heart"></i>
              </div>
              <div class="donation-content">
                <h3 class="donation-campaign">Student Emergency Fund</h3>
                <p class="donation-amount">$50.00</p>
                <div class="donation-meta">
                  <span class="donation-date">Dec 10, 2024</span>
                  <span class="donation-status completed">Completed</span>
                </div>
              </div>
            </div>

            <div class="donation-item">
              <div class="donation-icon">
                <i class="fas fa-laptop"></i>
              </div>
              <div class="donation-content">
                <h3 class="donation-campaign">Laptop Loan Program</h3>
                <p class="donation-amount">$25.00</p>
                <div class="donation-meta">
                  <span class="donation-date">Dec 5, 2024</span>
                  <span class="donation-status completed">Completed</span>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- Create Campaign Modal -->
    <div id="createCampaignModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create Fundraising Campaign</h2>
          <button class="modal-close" onclick="closeCreateCampaignModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form class="campaign-form">
          <div class="form-group">
            <label for="campaignTitle">Campaign Title *</label>
            <input type="text" id="campaignTitle" name="campaignTitle" placeholder="Enter a compelling title" required>
          </div>

          <div class="form-group">
            <label for="campaignCategory">Category *</label>
            <select id="campaignCategory" name="campaignCategory" required>
              <option value="">Select a category</option>
              <option value="emergency">Emergency Aid</option>
              <option value="education">Education</option>
              <option value="community">Community</option>
              <option value="research">Research</option>
            </select>
          </div>

          <div class="form-group">
            <label for="campaignGoal">Fundraising Goal *</label>
            <input type="number" id="campaignGoal" name="campaignGoal" placeholder="Enter amount in USD" min="100" required>
          </div>

          <div class="form-group">
            <label for="campaignDescription">Description *</label>
            <textarea id="campaignDescription" name="campaignDescription" rows="4" placeholder="Describe your campaign and how funds will be used..." required></textarea>
          </div>

          <div class="form-group">
            <label for="campaignDeadline">Campaign Deadline</label>
            <input type="date" id="campaignDeadline" name="campaignDeadline">
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-outline btn-md" onclick="closeCreateCampaignModal()">
              <span>Cancel</span>
            </button>
            <button type="submit" class="btn btn-primary btn-md">
              <i class="fas fa-rocket"></i>
              <span>Launch Campaign</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Donate Modal -->
    <div id="donateModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Make a Donation</h2>
          <button class="modal-close" onclick="closeDonateModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="donation-content">
          <div class="campaign-info">
            <h3 id="donateCampaignTitle">Campaign Title</h3>
            <div class="campaign-progress">
              <div class="progress-bar">
                <div id="donateProgressFill" class="progress-fill"></div>
              </div>
              <div class="progress-text">
                <span id="donateRaised">$0</span> raised of <span id="donateGoal">$0</span>
              </div>
            </div>
          </div>

          <form class="donation-form">
            <div class="form-group">
              <label>Donation Amount *</label>
              <div class="amount-options">
                <button type="button" class="amount-btn" data-amount="10">$10</button>
                <button type="button" class="amount-btn" data-amount="25">$25</button>
                <button type="button" class="amount-btn" data-amount="50">$50</button>
                <button type="button" class="amount-btn" data-amount="100">$100</button>
              </div>
              <input type="number" id="donationAmount" name="donationAmount" placeholder="Enter custom amount" min="1" required>
            </div>

            <div class="form-group">
              <label for="donorName">Your Name *</label>
              <input type="text" id="donorName" name="donorName" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
              <label for="donorEmail">Email *</label>
              <input type="email" id="donorEmail" name="donorEmail" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
              <label for="donorMessage">Message (Optional)</label>
              <textarea id="donorMessage" name="donorMessage" rows="3" placeholder="Leave a message of support..."></textarea>
            </div>

            <div class="form-actions">
              <button type="button" class="btn btn-outline btn-md" onclick="closeDonateModal()">
                <span>Cancel</span>
              </button>
              <button type="submit" class="btn btn-primary btn-md">
                <i class="fas fa-heart"></i>
                <span>Donate Now</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
    <script src="<?=ROOT?>/assets/js/fundraising.js"></script>
  </body>
</html>

