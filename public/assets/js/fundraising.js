let currentCampaign = null;
let otpRequested = false;
const createCampaignModal = document.getElementById('createCampaignModal');
const editPendingCampaignModal = document.getElementById('editPendingCampaignModal');
const donateModal = document.getElementById('donateModal');
const detailsModal = document.getElementById('fundraiserDetailsModal');
const config = window.FUNDRAISING_CONFIG || {};
const donateButtons = document.querySelectorAll('.donate-now-btn');
const detailsButtons = document.querySelectorAll('.view-details-btn');
const editPendingButtons = document.querySelectorAll('.edit-pending-btn');
const payButton = document.getElementById('demo-pay-btn');
const confirmModal = document.getElementById('fundraisingConfirmModal');
const confirmText = document.getElementById('fundraisingConfirmText');
const confirmOkButton = document.getElementById('fundraisingConfirmOk');
let pendingConfirmForm = null;

document.addEventListener('DOMContentLoaded', () => {
    bindDonateButtons();
    bindDetailsButtons();
    bindEditPendingButtons();
    bindCustomConfirmActions();
    bindModalClose();
    bindPayButton();
});

function bindCustomConfirmActions() {
    const forms = document.querySelectorAll('form[data-confirm-message]');
    forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            openFundraisingConfirmModal(form.dataset.confirmMessage || 'Are you sure?', () => {
                form.submit();
            });
        });
    });

    const buttons = document.querySelectorAll('button[data-confirm-message]');
    buttons.forEach((button) => {
        button.addEventListener('click', (event) => {
            const parentForm = button.closest('form');
            if (!parentForm) return;

            event.preventDefault();
            openFundraisingConfirmModal(button.dataset.confirmMessage || 'Are you sure?', () => {
                parentForm.submit();
            });
        });
    });

    if (confirmOkButton) {
        confirmOkButton.addEventListener('click', () => {
            if (pendingConfirmForm) {
                const cb = pendingConfirmForm;
                pendingConfirmForm = null;
                closeFundraisingConfirmModal();
                cb();
            }
        });
    }
}

function openFundraisingConfirmModal(message, onConfirm) {
    if (!confirmModal) {
        if (typeof onConfirm === 'function') onConfirm();
        return;
    }

    pendingConfirmForm = onConfirm;
    if (confirmText) {
        confirmText.textContent = message;
    }
    confirmModal.style.display = 'block';
}

function closeFundraisingConfirmModal() {
    if (!confirmModal) return;
    confirmModal.style.display = 'none';
    pendingConfirmForm = null;
}

function bindEditPendingButtons() {
    editPendingButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openEditPendingCampaignModal({
                fundraiserId: button.dataset.fundraiserId || '',
                title: button.dataset.title || '',
                description: button.dataset.description || '',
                targetAmount: button.dataset.targetAmount || '',
                currency: button.dataset.currency || 'LKR'
            });
        });
    });
}

function bindDetailsButtons() {
    detailsButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openDetailsModal({
                title: button.dataset.title || 'Campaign',
                status: button.dataset.status || 'UNKNOWN',
                creator: button.dataset.creator || 'Student',
                description: button.dataset.description || '',
                goal: parseFloat(button.dataset.goal || '0'),
                raised: parseFloat(button.dataset.raised || '0'),
                donors: parseInt(button.dataset.donors || '0', 10)
            });
        });
    });
}

function bindDonateButtons() {
    donateButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openDonateModal({
                fundraiserId: button.dataset.fundraiserId,
                title: button.dataset.title,
                goal: parseFloat(button.dataset.goal || '0'),
                raised: parseFloat(button.dataset.raised || '0'),
                currency: button.dataset.currency || config.paypalCurrencyDefault || 'LKR'
            });
        });
    });
}

function bindModalClose() {
    window.addEventListener('click', (event) => {
        if (event.target.classList.contains('modal')) {
            closeFundraisingConfirmModal();
            closeDonateModal();
            closeCreateCampaignModal();
            closeEditPendingCampaignModal();
            closeDetailsModal();
        }
    });
}

function openCreateCampaignModal() {
    if (!createCampaignModal) return;
    createCampaignModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeCreateCampaignModal() {
    if (!createCampaignModal) return;
    createCampaignModal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function openEditPendingCampaignModal(campaign) {
    if (!editPendingCampaignModal) return;

    const idEl = document.getElementById('editFundraiserId');
    const titleEl = document.getElementById('editCampaignTitle');
    const descriptionEl = document.getElementById('editCampaignDescription');
    const goalEl = document.getElementById('editCampaignGoal');
    const currencyEl = document.getElementById('editCampaignCurrency');

    if (idEl) idEl.value = campaign.fundraiserId;
    if (titleEl) titleEl.value = campaign.title;
    if (descriptionEl) descriptionEl.value = campaign.description;
    if (goalEl) goalEl.value = campaign.targetAmount;
    if (currencyEl) currencyEl.value = (campaign.currency || 'LKR').toUpperCase();

    editPendingCampaignModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEditPendingCampaignModal() {
    if (!editPendingCampaignModal) return;
    editPendingCampaignModal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function openDetailsModal(details) {
    if (!detailsModal) return;

    const description = (details.description || '').trim();

    document.getElementById('detailsTitle').textContent = details.title;
    document.getElementById('detailsStatus').textContent = details.status;
    document.getElementById('detailsCreator').textContent = details.creator;
    document.getElementById('detailsDescription').textContent = description || '-';
    document.getElementById('detailsGoal').textContent = `Rs. ${details.goal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    document.getElementById('detailsRaised').textContent = `Rs. ${details.raised.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    document.getElementById('detailsDonors').textContent = Number.isNaN(details.donors) ? '0' : String(details.donors);

    detailsModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDetailsModal() {
    if (!detailsModal) return;
    detailsModal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function openDonateModal(campaign) {
    if (!donateModal) return;
    currentCampaign = campaign;
    resetDemoPaymentFlow();
    document.getElementById('donateFundraiserId').value = campaign.fundraiserId;
    document.getElementById('donateCurrency').value = campaign.currency;
    document.getElementById('donateCampaignTitle').textContent = campaign.title;
    document.getElementById('donateGoal').textContent = `Rs. ${campaign.goal.toLocaleString()}`;
    document.getElementById('donateRaised').textContent = `Rs. ${campaign.raised.toLocaleString()}`;

    const progress = campaign.goal > 0 ? (campaign.raised / campaign.goal) * 100 : 0;
    document.getElementById('donateProgressFill').style.width = `${Math.min(progress, 100)}%`;
    donateModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDonateModal() {
    if (!donateModal) return;
    donateModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    currentCampaign = null;
    resetDemoPaymentFlow();
}

function resetDemoPaymentFlow() {
    otpRequested = false;

    const otpStep = document.getElementById('otpStep');
    const otpInput = document.getElementById('donateOtp');
    const otpHint = document.getElementById('otpHint');

    if (otpStep) otpStep.style.display = 'none';
    if (otpInput) otpInput.value = '';
    if (otpHint) otpHint.textContent = 'OTP sent.';

    if (payButton) {
        payButton.dataset.originalLabel = '<i class="fas fa-heart"></i> Pay Now';
        payButton.innerHTML = payButton.dataset.originalLabel;
    }
}

function validateDemoCardInputs() {
    const cardName = (document.getElementById('donateCardName')?.value || '').trim();
    const cardNumberRaw = (document.getElementById('donateCardNumber')?.value || '').trim();
    const cardCvv = (document.getElementById('donateCardCvv')?.value || '').trim();

    const cardDigits = cardNumberRaw.replace(/\D+/g, '');

    if (cardName.length < 1) {
        return 'Please enter the cardholder name.';
    }

    if (!/^\d{12,19}$/.test(cardDigits)) {
        return 'Please enter a valid card number.';
    }

    if (!/^\d{3}$/.test(cardCvv)) {
        return 'CVV must be exactly 3 digits.';
    }

    return null;
}

function showOtpStep() {
    const otpStep = document.getElementById('otpStep');
    const otpHint = document.getElementById('otpHint');
    const last3 = (config.otpPhoneLast3 || '000').toString();

    if (otpHint) {
        otpHint.textContent = `We sent an OTP to your phone ending with ${last3}.`;
    }

    if (otpStep) {
        otpStep.style.display = 'block';
    }

    otpRequested = true;
    if (payButton) {
        payButton.innerHTML = '<i class="fas fa-shield-alt"></i> Verify OTP & Complete';
    }
}

function validateOtpInput() {
    const otp = (document.getElementById('donateOtp')?.value || '').trim();
    if (!/^\d{6}$/.test(otp)) {
        return 'Please enter a valid 6-digit OTP.';
    }
    return null;
}

function bindPayButton() {
    if (!payButton) {
        return;
    }
    payButton.addEventListener('click', startDemoCheckout);
}

async function startDemoCheckout() {
    if (!currentCampaign) {
        showSuccessToast('Please select a campaign first.', true);
        return;
    }

    const amount = parseFloat(document.getElementById('donationAmount').value || '0');
    if (!amount || amount <= 0) {
        showSuccessToast('Please enter a valid donation amount.', true);
        return;
    }

    if (!otpRequested) {
        const cardError = validateDemoCardInputs();
        if (cardError) {
            showSuccessToast(cardError, true);
            return;
        }

        showOtpStep();
        showSuccessToast('OTP sent successfully.', false);
        return;
    }

    const otpError = validateOtpInput();
    if (otpError) {
        showSuccessToast(otpError, true);
        return;
    }

    try {
        payButton.disabled = true;
        payButton.dataset.originalLabel = payButton.dataset.originalLabel || '<i class="fas fa-heart"></i> Pay Now';
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        const form = new FormData();
        form.append('fundraiser_id', currentCampaign.fundraiserId);
        form.append('amount', amount.toFixed(2));
        form.append('currency', currentCampaign.currency || config.currencyDefault || 'LKR');

        const res = await fetch(config.createPaymentUrl, {
            method: 'POST',
            body: form,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const rawText = await res.text();
        let data = null;
        try {
            data = JSON.parse(rawText);
        } catch (parseError) {
            throw new Error('Server returned an invalid response. Please reload and try again.');
        }

        if (!res.ok || !data.success) {
            throw new Error(data.message || 'Failed to complete the donation.');
        }

        showSuccessToast('Donation successful!');
        closeDonateModal();
        setTimeout(() => window.location.reload(), 1200);
    } catch (error) {
        showSuccessToast(error.message || 'Could not start payment.', true);
    } finally {
        if (payButton) {
            payButton.disabled = false;
            if (otpRequested) {
                payButton.innerHTML = '<i class="fas fa-shield-alt"></i> Verify OTP & Complete';
            } else {
                payButton.innerHTML = payButton.dataset.originalLabel || '<i class="fas fa-heart"></i> Pay Now';
            }
        }
    }
}

// Green toast popup
function showSuccessToast(message, isError = false) {
    let toast = document.getElementById('success-toast');
    let messageEl;
    let closeBtn;

    const hideToast = () => {
        if (!toast) return;
        toast.style.opacity = '0';
    };

    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'success-toast';
        toast.style.position = 'fixed';
        toast.style.top = '32px';
        toast.style.right = '32px';
        toast.style.zIndex = '9999';
        toast.style.display = 'flex';
        toast.style.alignItems = 'flex-start';
        toast.style.gap = '12px';
        toast.style.padding = '14px 16px';
        toast.style.borderRadius = '8px';
        toast.style.background = '#27ae60';
        toast.style.color = '#fff';
        toast.style.fontWeight = '600';
        toast.style.maxWidth = '460px';
        toast.style.width = 'calc(100% - 24px)';
        toast.style.boxShadow = '0 2px 12px rgba(0,0,0,0.12)';
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';

        messageEl = document.createElement('span');
        messageEl.id = 'success-toast-message';
        messageEl.style.flex = '1';
        messageEl.style.lineHeight = '1.35';
        messageEl.style.wordBreak = 'break-word';

        closeBtn = document.createElement('button');
        closeBtn.id = 'success-toast-close';
        closeBtn.type = 'button';
        closeBtn.textContent = 'x';
        closeBtn.setAttribute('aria-label', 'Close notification');
        closeBtn.style.background = 'transparent';
        closeBtn.style.border = 'none';
        closeBtn.style.color = '#fff';
        closeBtn.style.fontSize = '18px';
        closeBtn.style.lineHeight = '1';
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.padding = '0';
        closeBtn.style.marginTop = '1px';
        closeBtn.addEventListener('click', hideToast);

        toast.appendChild(messageEl);
        toast.appendChild(closeBtn);
        document.body.appendChild(toast);
    }

    messageEl = document.getElementById('success-toast-message');
    closeBtn = document.getElementById('success-toast-close');
    if (messageEl) {
        messageEl.textContent = message;
    } else {
        toast.textContent = message;
    }

    toast.style.background = isError ? '#e74c3c' : '#27ae60';
    toast.style.opacity = '1';

    if (closeBtn) {
        closeBtn.style.display = 'inline-block';
    }

    if (!isError) {
        setTimeout(hideToast, 1800);
    }
}

window.openCreateCampaignModal = openCreateCampaignModal;
window.closeCreateCampaignModal = closeCreateCampaignModal;
window.closeFundraisingConfirmModal = closeFundraisingConfirmModal;
window.closeEditPendingCampaignModal = closeEditPendingCampaignModal;
window.closeDonateModal = closeDonateModal;
window.closeDetailsModal = closeDetailsModal;

