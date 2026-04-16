<?php
$page_title = "Fundraising";
$page_subtitle = "Support student initiatives and community campaigns";
require '../app/views/partials/student_header.php';
?>

<link rel="stylesheet" href="<?=ROOT?>/assets/css/fundraising.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/student_sidebar.php'; ?>

  <main class="main-content">
    <?php if (!empty($flash)): ?>
      <?php $flashType = strtolower((string)($flash['type'] ?? 'success')) === 'error' ? 'error' : 'success'; ?>
      <div class="fundraising-toast fundraising-toast-<?= esc($flashType) ?>" role="status" aria-live="polite">
        <i class="fas <?= $flashType === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"></i>
        <span><?= esc($flash['text'] ?? '') ?></span>
        <button type="button" class="fundraising-toast-close" aria-label="Close message">&times;</button>
      </div>
    <?php endif; ?>



    <section class="dashboard-section create-section">
      <div class="section-header">
        <button class="btn btn-primary" onclick="openCreateCampaignModal()">
          <i class="fas fa-plus"></i><span>Create Campaign</span>
        </button>
      </div>
    </section>

    <section class="dashboard-section campaigns-section">
      <div class="section-header"><h2 class="card-title">My Fundraisers</h2></div>
      <div class="campaigns-grid">
        <?php if (!empty($myFundraisers)): foreach ($myFundraisers as $item): $percent = ((float)$item->target_amount > 0) ? min(100, (((float)$item->raised_amount / (float)$item->target_amount) * 100)) : 0; ?>
          <?php
            $rawStatus = strtolower((string)$item->status);
            $badgeClass = $rawStatus;
            $badgeLabel = strtoupper((string)$item->status);
            if ($rawStatus === 'approved') {
              $badgeClass = 'active';
              $badgeLabel = 'ACTIVE';
            } elseif (in_array($rawStatus, ['closed', 'stopped'], true)) {
              $badgeClass = 'stopped';
              $badgeLabel = 'CLOSED';
            }
          ?>
          <div class="campaign-card">
            <div class="campaign-image">
              <div class="campaign-status <?= esc($badgeClass) ?>\"><?= esc($badgeLabel) ?></div>
              <div class="campaign-progress">
                <div class="progress-bar"><div class="progress-fill" style="width: <?= (float)$percent ?>%"></div></div>
                <span class="progress-text"><?= number_format((float)$percent, 1) ?>% funded</span>
              </div>
            </div>
            <div class="campaign-content">
              <h3 class="campaign-title"><?= esc($item->title) ?></h3>
              <p class="campaign-description"><?= esc($item->description ?: 'No description provided.') ?></p>
              <div class="campaign-meta">
                <div class="campaign-goal"><span class="goal-amount">Rs. <?= esc(format_money($item->target_amount)) ?></span><span class="goal-label">Goal</span></div>
                <div class="campaign-raised"><span class="raised-amount">Rs. <?= esc(format_money($item->raised_amount)) ?></span><span class="raised-label">Raised</span></div>
                <div class="campaign-donors"><span class="donors-count"><?= (int)$item->donor_count ?></span><span class="donors-label">Donors</span></div>
              </div>
              <div class="campaign-actions">
                <button class="btn btn-outline btn-sm view-details-btn"
                        data-title="<?= esc($item->title) ?>"
                        data-status="<?= esc($badgeLabel) ?>"
                        data-creator="You"
                        data-description="<?= esc($item->description ?: '') ?>"
                        data-goal="<?= (float)$item->target_amount ?>"
                        data-raised="<?= (float)$item->raised_amount ?>"
                        data-donors="<?= (int)$item->donor_count ?>">
                  <i class="fas fa-eye"></i><span>View Details</span>
                </button>
                <?php if (strtolower((string)$item->status) === 'pending'): ?>
                  <button class="btn btn-outline btn-sm edit-pending-btn"
                          data-fundraiser-id="<?= (int)$item->fundraiser_id ?>"
                          data-title="<?= esc($item->title) ?>"
                          data-description="<?= esc($item->description ?: '') ?>"
                          data-target-amount="<?= esc(format_money($item->target_amount, false)) ?>"
                          data-currency="<?= esc($item->currency ?: 'LKR') ?>">
                    <i class="fas fa-pen"></i><span>Edit Request</span>
                  </button>
                  <form method="POST" action="<?=ROOT?>/student/fundraising/delete_pending" style="margin:0;" data-confirm-message="Delete this pending request? This cannot be undone.">
                    <input type="hidden" name="fundraiser_id" value="<?= (int)$item->fundraiser_id ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete Request</button>
                  </form>
                <?php endif; ?>
                <?php if (strtolower($item->status) === 'approved'): ?>
                  <form method="POST" action="<?=ROOT?>/student/fundraising/stop" style="margin:0;">
                    <input type="hidden" name="fundraiser_id" value="<?= (int)$item->fundraiser_id ?>">
                    <button type="submit" class="btn btn-danger btn-sm" data-confirm-message="End this fundraiser now? This will close donations for this campaign.">End Fundraiser</button>
                  </form>
                <?php endif; ?>
              </div>
              <?php if (!empty($item->admin_note)): ?><small class="form-help">Admin note: <?= esc($item->admin_note) ?></small><?php endif; ?>
            </div>
          </div>
        <?php endforeach; else: ?>
          <div class="campaign-card"><div class="campaign-content"><p class="campaign-description">No fundraiser requests yet.</p></div></div>
        <?php endif; ?>
      </div>
    </section>

    <section class="dashboard-section campaigns-section">
      <div class="section-header"><h2 class="card-title">Recent Fundraisers</h2></div>
      <div class="campaigns-grid">
        <?php if (!empty($recentFundraisers)): foreach ($recentFundraisers as $item): $percent = ((float)$item->target_amount > 0) ? min(100, (((float)$item->raised_amount / (float)$item->target_amount) * 100)) : 0; ?>
          <div class="campaign-card">
            <div class="campaign-image">
              <div class="campaign-status stopped">CLOSED</div>
              <div class="campaign-progress">
                <div class="progress-bar"><div class="progress-fill" style="width: <?= (float)$percent ?>%"></div></div>
                <span class="progress-text"><?= number_format((float)$percent, 1) ?>% funded</span>
              </div>
            </div>
            <div class="campaign-content">
              <h3 class="campaign-title"><?= esc($item->title) ?></h3>
              <p class="campaign-description"><?= esc($item->description ?: 'No description provided.') ?></p>
              <div class="campaign-meta">
                <div class="campaign-goal"><span class="goal-amount">Rs. <?= esc(format_money($item->target_amount)) ?></span><span class="goal-label">Goal</span></div>
                <div class="campaign-raised"><span class="raised-amount">Rs. <?= esc(format_money($item->raised_amount)) ?></span><span class="raised-label">Raised</span></div>
                <div class="campaign-donors"><span class="donors-count"><?= (int)$item->donor_count ?></span><span class="donors-label">Donors</span></div>
              </div>
              <div class="campaign-actions">
                <button class="btn btn-outline btn-sm view-details-btn"
                        data-title="<?= esc($item->title) ?>"
                        data-status="CLOSED"
                        data-creator="<?= esc($item->creator_name ?: 'Student') ?>"
                        data-description="<?= esc($item->description ?: '') ?>"
                        data-goal="<?= (float)$item->target_amount ?>"
                        data-raised="<?= (float)$item->raised_amount ?>"
                        data-donors="<?= (int)$item->donor_count ?>">
                  <i class="fas fa-eye"></i><span>View Details</span>
                </button>
              </div>
              <?php if (!empty($item->creator_name)): ?><small class="form-help">By <?= esc($item->creator_name) ?></small><?php endif; ?>
            </div>
          </div>
        <?php endforeach; else: ?>
          <div class="campaign-card"><div class="campaign-content"><p class="campaign-description">No recent fundraisers yet.</p></div></div>
        <?php endif; ?>
      </div>
    </section>

    <section class="dashboard-section campaigns-section">
      <?php
        $allCampaigns = is_array($campaigns ?? null) ? $campaigns : [];
        $showAllCampaigns = !empty($showAllCampaigns);
        $campaignsToShow = $showAllCampaigns ? $allCampaigns : array_slice($allCampaigns, 0, 1);
      ?>
      <div class="section-header">
        <h2 class="card-title">Active Campaigns</h2>
        <?php if (count($allCampaigns) > 1): ?>
          <a class="btn btn-outline btn-sm" href="<?= ROOT ?>/student/fundraising<?= $showAllCampaigns ? '' : '?view=all' ?>">
            <?= $showAllCampaigns ? 'Show Less' : 'View All' ?>
          </a>
        <?php endif; ?>
      </div>
      <div class="campaigns-grid">
        <?php if (!empty($campaignsToShow)): foreach ($campaignsToShow as $campaign): $percent = (float)($campaign->funded_percent ?? 0); ?>
          <div class="campaign-card">
            <div class="campaign-image">
              <div class="campaign-status active">ACTIVE</div>
              <div class="campaign-progress">
                <div class="progress-bar"><div class="progress-fill" style="width: <?= min(100, max(0, $percent)) ?>%"></div></div>
                <span class="progress-text"><?= number_format($percent, 1) ?>% funded</span>
              </div>
            </div>
            <div class="campaign-content">
              <h3 class="campaign-title"><?= esc($campaign->title) ?></h3>
              <p class="campaign-description"><?= esc($campaign->description ?: 'No description provided.') ?></p>
              <div class="campaign-meta">
                <div class="campaign-goal"><span class="goal-amount">Rs. <?= esc(format_money($campaign->target_amount)) ?></span><span class="goal-label">Goal</span></div>
                <div class="campaign-raised"><span class="raised-amount">Rs. <?= esc(format_money($campaign->raised_amount)) ?></span><span class="raised-label">Raised</span></div>
                <div class="campaign-donors"><span class="donors-count"><?= (int)$campaign->donor_count ?></span><span class="donors-label">Donors</span></div>
              </div>
              <div class="campaign-actions">
                <button class="btn btn-outline btn-sm view-details-btn"
                        data-title="<?= esc($campaign->title) ?>"
                        data-status="ACTIVE"
                        data-creator="<?= esc($campaign->creator_name ?: 'Student') ?>"
                        data-description="<?= esc($campaign->description ?: '') ?>"
                        data-goal="<?= (float)$campaign->target_amount ?>"
                        data-raised="<?= (float)$campaign->raised_amount ?>"
                        data-donors="<?= (int)$campaign->donor_count ?>">
                  <i class="fas fa-eye"></i><span>View Details</span>
                </button>
                <button class="btn btn-primary btn-sm donate-now-btn"
                        data-fundraiser-id="<?= (int)$campaign->fundraiser_id ?>"
                        data-title="<?= esc($campaign->title) ?>"
                        data-goal="<?= (float)$campaign->target_amount ?>"
                        data-raised="<?= (float)$campaign->raised_amount ?>"
                        data-currency="<?= esc($campaign->currency) ?>">
                  <i class="fas fa-heart"></i><span>Donate Now</span>
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; else: ?>
          <div class="campaign-card"><div class="campaign-content"><p class="campaign-description">No active campaigns available yet.</p></div></div>
        <?php endif; ?>
      </div>
    </section>

    <section class="dashboard-section donations-section">
      <div class="section-header"><h2 class="card-title">My Donations</h2></div>
      <div class="donations-list">
        <?php if (!empty($myDonations)): foreach ($myDonations as $donation): ?>
          <div class="donation-item">
            <div class="donation-icon"><i class="fas fa-heart"></i></div>
            <div class="donation-content">
              <h3 class="donation-campaign"><?= esc($donation->fundraiser_title) ?></h3>
              <p class="donation-amount">Rs. <?= esc(format_money($donation->amount)) ?></p>
              <div class="donation-meta">
                <span class="donation-date"><?= esc(date('M j, Y', strtotime((string)$donation->captured_at))) ?></span>
                <span class="donation-status completed"><?= esc(strtoupper((string)$donation->status)) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; else: ?>
          <div class="donation-item"><div class="donation-content"><p class="donation-campaign">No donations yet.</p></div></div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<div id="fundraisingConfirmModal" class="modal">
  <div class="modal-content" style="max-width:420px;">
    <div class="modal-header">
      <h2 class="modal-title">Confirm Action</h2>
      <button class="modal-close" onclick="closeFundraisingConfirmModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="donation-content">
      <p id="fundraisingConfirmText" style="margin:0 0 14px 0; color: var(--foreground);">Are you sure?</p>
      <div class="form-actions">
        <button type="button" class="btn btn-outline" onclick="closeFundraisingConfirmModal()">Cancel</button>
        <button type="button" id="fundraisingConfirmOk" class="btn btn-danger">Yes, Continue</button>
      </div>
    </div>
  </div>
</div>

<div id="fundraiserDetailsModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Fundraiser Details</h2>
      <button class="modal-close" onclick="closeDetailsModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="donation-content">
      <div class="campaign-info">
        <h3 id="detailsTitle">Campaign</h3>
        <p class="form-help" style="margin:6px 0 0 0;">Status: <strong id="detailsStatus">-</strong> | By: <strong id="detailsCreator">-</strong></p>
      </div>
      <div class="details-grid">
        <div class="detail-item">
          <span class="detail-label">Description</span>
          <p id="detailsDescription" class="detail-text">-</p>
        </div>
      </div>
      <div class="campaign-meta" style="margin-top:14px;">
        <div class="campaign-goal"><span class="goal-amount" id="detailsGoal">Rs. 0.00</span><span class="goal-label">Goal</span></div>
        <div class="campaign-raised"><span class="raised-amount" id="detailsRaised">Rs. 0.00</span><span class="raised-label">Raised</span></div>
        <div class="campaign-donors"><span class="donors-count" id="detailsDonors">0</span><span class="donors-label">Donors</span></div>
      </div>
    </div>
  </div>
</div>

<div id="createCampaignModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Create Fundraising Campaign</h2>
      <button class="modal-close" onclick="closeCreateCampaignModal()"><i class="fas fa-times"></i></button>
    </div>
    <form class="campaign-form" method="POST" action="<?=ROOT?>/student/fundraising/create" enctype="multipart/form-data">
      <p class="form-help" style="margin-top: 0; margin-bottom: 12px;">Submit a clear description and supporting documents. Campaigns become visible after admin approval.</p>
      <div class="form-group"><label for="campaignTitle">Campaign Title *</label><input type="text" id="campaignTitle" name="title" required></div>
      <div class="form-group"><label for="campaignGoal">Fundraising Goal *</label><input type="number" id="campaignGoal" name="target_amount" min="1" step="0.01" required></div>
      <div class="form-group"><label for="campaignDescription">Description *</label><textarea id="campaignDescription" name="description" rows="4" required></textarea></div>
      <div class="form-group"><label for="campaignCurrency">Currency *</label><input type="text" id="campaignCurrency" name="currency" value="LKR" maxlength="3" required></div>
      <div class="form-group"><label for="supportingDocuments">Supporting Documents *</label><input type="file" id="supportingDocuments" name="supporting_documents[]" multiple required><small class="form-help">PDF, JPG, PNG, DOC, DOCX (10MB max each)</small></div>
      <div class="form-actions"><button type="button" class="btn btn-outline" onclick="closeCreateCampaignModal()">Cancel</button><button type="submit" class="btn btn-primary">Submit Request</button></div>
    </form>
  </div>
</div>

<div id="editPendingCampaignModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Edit Pending Request</h2>
      <button class="modal-close" onclick="closeEditPendingCampaignModal()"><i class="fas fa-times"></i></button>
    </div>
    <form class="campaign-form" method="POST" action="<?=ROOT?>/student/fundraising/edit_pending">
      <input type="hidden" id="editFundraiserId" name="fundraiser_id">
      <div class="form-group"><label for="editCampaignTitle">Campaign Title *</label><input type="text" id="editCampaignTitle" name="title" required></div>
      <div class="form-group"><label for="editCampaignGoal">Fundraising Goal *</label><input type="number" id="editCampaignGoal" name="target_amount" min="1" step="0.01" required></div>
      <div class="form-group"><label for="editCampaignDescription">Description *</label><textarea id="editCampaignDescription" name="description" rows="4" required></textarea></div>
      <div class="form-group"><label for="editCampaignCurrency">Currency *</label><input type="text" id="editCampaignCurrency" name="currency" maxlength="3" required></div>
      <div class="form-actions"><button type="button" class="btn btn-outline" onclick="closeEditPendingCampaignModal()">Cancel</button><button type="submit" class="btn btn-primary">Save Changes</button></div>
    </form>
  </div>
</div>

<div id="donateModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Make a Donation</h2>
      <button class="modal-close" onclick="closeDonateModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="donation-content">
      <div class="campaign-info">
        <h3 id="donateCampaignTitle">Campaign</h3>
        <div class="campaign-progress"><div class="progress-bar"><div id="donateProgressFill" class="progress-fill"></div></div><div class="progress-text"><span id="donateRaised"></span> raised of <span id="donateGoal"></span></div></div>
      </div>
      <form class="donation-form">
        <input type="hidden" id="donateFundraiserId">
        <input type="hidden" id="donateCurrency">
        <div class="form-group"><label>Amount *</label><input type="number" id="donationAmount" min="1" step="0.01" required></div>
        <div class="form-group"><label>Cardholder Name *</label><input type="text" id="donateCardName" maxlength="80" required></div>
        <div class="form-group"><label>Card Number *</label><input type="text" id="donateCardNumber" inputmode="numeric" autocomplete="cc-number" placeholder="1234 5678 9012 3456" required></div>
        <div class="form-group"><label>CVV *</label><input type="text" id="donateCardCvv" inputmode="numeric" maxlength="3" autocomplete="cc-csc" placeholder="123" required></div>
        <div id="otpStep" class="demo-otp-step" style="display:none;">
          <p id="otpHint" class="form-help" style="margin-top:0; margin-bottom:8px;">OTP sent.</p>
          <div class="form-group" style="margin-bottom:10px;"><label>Enter OTP *</label><input type="text" id="donateOtp" inputmode="numeric" maxlength="6" placeholder="6-digit OTP"></div>
        </div>
        <button type="button" id="demo-pay-btn" class="btn btn-primary">
          <i class="fas fa-heart"></i> Pay Now
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    const toast = document.querySelector('.fundraising-toast');
    if (!toast) return;

    const closeBtn = toast.querySelector('.fundraising-toast-close');

    const dismissToast = function () {
      if (toast.classList.contains('is-hiding')) return;
      toast.classList.add('is-hiding');
      setTimeout(() => {
        if (toast && toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 260);
    };

    if (closeBtn) {
      closeBtn.addEventListener('click', dismissToast);
    }

    setTimeout(dismissToast, 3200);
  })();
</script>
<script>
  window.FUNDRAISING_CONFIG = {
    role: 'student',
    createPaymentUrl: '<?= ROOT ?>/student/fundraising/demo_create_payment',
    paymentMode: '<?= esc($paymentMode ?? 'demo') ?>',
    currencyDefault: 'LKR',
    otpPhoneLast3: '<?= esc((string)($otpPhoneLast3 ?? '000')) ?>'
  };
</script>
<script src="<?=ROOT?>/assets/js/fundraising.js"></script>

