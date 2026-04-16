<?php 
$page_title = "Fundraising";
$page_subtitle = "Support student initiatives and community campaigns";
require '../app/views/partials/alumni_header.php'; 
?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/fundraising.css">

<div class="dashboard-container">
  <?php require '../app/views/partials/alumni_sidebar.php'; ?>
  <main class="main-content">
    <?php if (!empty($flash)): ?>
      <?php $flashType = strtolower((string)($flash['type'] ?? 'success')) === 'error' ? 'error' : 'success'; ?>
      <div class="fundraising-toast fundraising-toast-<?= esc($flashType) ?>" role="status" aria-live="polite">
        <i class="fas <?= $flashType === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"></i>
        <span><?= esc($flash['text'] ?? '') ?></span>
        <button type="button" class="fundraising-toast-close" aria-label="Close message">&times;</button>
      </div>
    <?php endif; ?>



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
              <p class="campaign-description\"><?= esc($item->description ?: 'No description provided.') ?></p>
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
      <div class="section-header"><h2 class="card-title">Active Campaigns</h2></div>
      <div class="campaigns-grid">
        <?php if (!empty($campaigns)): foreach ($campaigns as $campaign): $percent = (float)($campaign->funded_percent ?? 0); ?>
          <div class="campaign-card">
            <div class="campaign-image">
              <div class="campaign-status active">ACTIVE</div>
              <div class="campaign-progress"><div class="progress-bar"><div class="progress-fill" style="width: <?= min(100, max(0, $percent)) ?>%"></div></div><span class="progress-text"><?= number_format($percent, 1) ?>% funded</span></div>
            </div>
            <div class="campaign-content">
              <h3 class="campaign-title"><?= esc($campaign->title) ?></h3>
              <p class="campaign-description\"><?= esc($campaign->description ?: 'No description provided.') ?></p>
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
    role: 'alumni',
    createPaymentUrl: '<?= ROOT ?>/alumni/fundraising/demo_create_payment',
    currencyDefault: 'LKR',
    otpPhoneLast3: '<?= esc((string)($otpPhoneLast3 ?? '000')) ?>'
  };
</script>
<script src="<?=ROOT?>/assets/js/fundraising.js"></script>
