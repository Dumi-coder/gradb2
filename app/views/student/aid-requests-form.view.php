<?php
$page_title = "Aid Request Form";
$page_subtitle = "Submit an aid request";
require '../app/views/partials/student_header.php';
?>

<style>
    .aid-form-wrap {
        max-width: 920px;
        margin: var(--spacing-md, 16px) auto 0;
    }

    .aid-form-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .aid-form-card {
        background: var(--card, #fff);
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 14px;
        padding: 24px;
    }

    .aid-form-title {
        margin: 0;
        font-size: 1.45rem;
    }

    .aid-form-subtitle {
        margin-top: 8px;
        color: var(--muted-foreground, #6b7280);
        font-size: 0.95rem;
    }

    .aid-section {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px dashed var(--border, #e5e7eb);
    }

    .aid-section h3 {
        margin: 0 0 12px 0;
        font-size: 1.02rem;
    }

    .aid-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 0.92rem;
        margin: 0;
        line-height: 1.2;
    }

    .required-mark {
        color: #dc2626;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        border: 1px solid var(--border, #d1d5db);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 0.95rem;
        background: #fff;
    }

    .aid-request-form input,
    .aid-request-form textarea,
    .aid-request-form select,
    .aid-request-form button,
    .aid-request-form .btn {
        font-family: inherit;
    }

    .aid-request-form input::placeholder,
    .aid-request-form textarea::placeholder {
        font-family: inherit;
        color: #64748b;
    }

    .form-group small {
        color: var(--muted-foreground, #6b7280);
        font-size: 0.8rem;
        margin-top: 2px;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .doc-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .doc-item {
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 12px;
        padding: 12px;
        background: #fafafa;
    }

    .doc-item .doc-title {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .submit-row {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .submit-note {
        color: var(--muted-foreground, #6b7280);
        font-size: 0.85rem;
    }

    .student-info-box {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 14px;
    }

    .student-info-item {
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 10px;
        padding: 10px 12px;
        background: #fafafa;
    }

    .student-info-item .label {
        display: block;
        font-size: 0.78rem;
        color: var(--muted-foreground, #6b7280);
        margin-bottom: 4px;
    }

    .student-info-item .value {
        font-size: 0.95rem;
        font-weight: 600;
    }

    .amount-group.hidden {
        display: none;
    }

    .other-aid-group.hidden {
        display: none;
    }

    .toast-notice {
        position: fixed;
        top: 86px;
        right: 20px;
        z-index: 12000;
        min-width: 280px;
        max-width: 420px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-left-width: 4px;
        border-radius: 10px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        animation: toast-slide-in 0.24s ease-out;
    }

    .toast-notice.toast-success {
        border-left-color: #16a34a;
    }

    .toast-notice.toast-error,
    .toast-notice.toast-danger {
        border-left-color: #dc2626;
    }

    .toast-notice.toast-info {
        border-left-color: #2563eb;
    }

    .toast-content {
        flex: 1;
        color: #0f172a;
        font-size: 0.92rem;
        line-height: 1.35;
    }

    .toast-close {
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 1.1rem;
        line-height: 1;
        cursor: pointer;
        padding: 0;
    }

    .toast-notice.is-hiding {
        opacity: 0;
        transform: translateX(26px);
        transition: opacity 0.26s ease, transform 0.26s ease;
    }

    @keyframes toast-slide-in {
        from {
            opacity: 0;
            transform: translateX(24px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .aid-form-grid {
            grid-template-columns: 1fr;
        }

        .student-info-box {
            grid-template-columns: 1fr;
        }

        .aid-form-wrap {
            margin-top: 8px;
        }
    }
</style>

<div class="dashboard-container">
    <?php require '../app/views/partials/student_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section aid-form-wrap">
            <div class="aid-form-card">
                <div>
                    <div class="aid-form-head">
                        <div>
                            <h2 class="aid-form-title">Student Aid Request</h2>
                            <p class="aid-form-subtitle">Fill in your details and upload the required documents. Counselor will review your request first.</p>
                        </div>
                    </div>
                    <div class="student-info-box">
                        <div class="student-info-item">
                            <span class="label">Student Name</span>
                            <span class="value"><?= htmlspecialchars($studentInfo->name ?? 'N/A') ?></span>
                        </div>
                        <div class="student-info-item">
                            <span class="label">Student ID</span>
                            <span class="value"><?= htmlspecialchars($studentInfo->student_id ?? 'N/A') ?></span>
                        </div>
                        <div class="student-info-item">
                            <span class="label">Faculty</span>
                            <span class="value"><?= htmlspecialchars($studentInfo->faculty ?? ($studentInfo->faculty_name ?? 'N/A')) ?></span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($flashMessage)): ?>
                    <div class="toast-notice toast-<?= htmlspecialchars($flashMessage['type']) ?>" role="status" aria-live="polite">
                        <div class="toast-content"><?= htmlspecialchars($flashMessage['text']) ?></div>
                        <button type="button" class="toast-close" aria-label="Close">&times;</button>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="aid-request-form">
            <div class="aid-section">
                <h3>Basic Information</h3>
                <div class="aid-form-grid">
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number <span class="required-mark">*</span></label>
                        <input
                            type="tel"
                            id="mobile_number"
                            name="mobile_number"
                            required
                            pattern="[0-9]{10}"
                            placeholder="07XXXXXXXX"
                            value="<?= htmlspecialchars($formData['mobile_number'] ?? '') ?>"
                        >
                        <small>Use 10 digits starting with 07</small>
                    </div>
                </div>
            </div>

            <div class="aid-section">
                <h3>Aid Request Details</h3>
                <div class="aid-form-grid">
                    <div class="form-group">
                        <label for="aid_type">Type of Aid Request <span class="required-mark">*</span></label>
                        <select id="aid_type" name="aid_type" required>
                            <option value="">Select aid type</option>
                            <option value="money" <?= (($formData['aid_type'] ?? '') === 'money') ? 'selected' : '' ?>>Money</option>
                            <option value="laptop" <?= (($formData['aid_type'] ?? '') === 'laptop') ? 'selected' : '' ?>>Laptop</option>
                            <option value="textbooks" <?= (($formData['aid_type'] ?? '') === 'textbooks') ? 'selected' : '' ?>>Textbooks</option>
                            <option value="stationery" <?= (($formData['aid_type'] ?? '') === 'stationery') ? 'selected' : '' ?>>Stationery</option>
                            <option value="other" <?= (($formData['aid_type'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group amount-group hidden" id="amountGroup">
                        <label for="amount">If Money, How Much (LKR)</label>
                        <input
                            type="number"
                            min="1"
                            id="amount"
                            name="amount"
                            placeholder="Enter amount only for money requests"
                            value="<?= htmlspecialchars($formData['amount'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group other-aid-group hidden" id="otherAidGroup">
                        <label for="other_aid_type">If Other, Please Specify <span class="required-mark">*</span></label>
                        <input
                            type="text"
                            id="other_aid_type"
                            name="other_aid_type"
                            placeholder="Specify the aid you need"
                            value="<?= htmlspecialchars($formData['other_aid_type'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="reason">Reason Why You Need Aid <span class="required-mark">*</span></label>
                        <textarea
                            id="reason"
                            name="reason"
                            rows="4"
                            required
                            placeholder="Explain your current situation and why this aid is needed"
                        ><?= htmlspecialchars($formData['reason'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="aid-section">
                <h3>Required Documents</h3>
                <div class="doc-grid">
                    <div class="doc-item">
                        <label class="doc-title" for="student_id_pdf">Student ID (PDF or Image) <span class="required-mark">*</span></label>
                        <input type="file" id="student_id_pdf" name="student_id_pdf" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>

                    <div class="doc-item">
                        <label class="doc-title" for="income_statement">Income Statement from Divisional Secretary <span class="required-mark">*</span></label>
                        <input type="file" id="income_statement" name="income_statement" accept=".pdf" required>
                    </div>

                    <div class="doc-item">
                        <label class="doc-title" for="gramaseva_certificate">Gramaseva Niladhari Certificate <span class="required-mark">*</span></label>
                        <input type="file" id="gramaseva_certificate" name="gramaseva_certificate" accept=".pdf" required>
                    </div>
                </div>
            </div>

                    <div class="submit-row">
                        <span class="submit-note">Please check all information before submitting.</span>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <a href="<?=ROOT?>/student/aidrequests" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back</span>
                            </a>
                            <button type="submit" class="btn btn-primary">Submit Aid Request</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<script>
    (function () {
        const toast = document.querySelector('.toast-notice');
        if (toast) {
            const closeBtn = toast.querySelector('.toast-close');
            const closeToast = function () {
                toast.classList.add('is-hiding');
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 280);
            };

            if (closeBtn) {
                closeBtn.addEventListener('click', closeToast);
            }

            setTimeout(closeToast, 3600);
        }

        const aidType = document.getElementById('aid_type');
        const amountGroup = document.getElementById('amountGroup');
        const amountInput = document.getElementById('amount');
        const otherAidGroup = document.getElementById('otherAidGroup');
        const otherAidInput = document.getElementById('other_aid_type');

        function toggleAmount() {
            const isMoney = aidType && aidType.value === 'money';
            const isOther = aidType && aidType.value === 'other';

            if (isMoney) {
                amountGroup.classList.remove('hidden');
                amountInput.required = true;
            } else {
                amountGroup.classList.add('hidden');
                amountInput.required = false;
                amountInput.value = '';
            }

            if (isOther) {
                otherAidGroup.classList.remove('hidden');
                otherAidInput.required = true;
            } else {
                otherAidGroup.classList.add('hidden');
                otherAidInput.required = false;
                otherAidInput.value = '';
            }
        }

        if (aidType) {
            aidType.addEventListener('change', toggleAmount);
            toggleAmount();
        }
    })();
</script>

<script type="module" src="<?=ROOT?>/assets/js/main.js"></script>
</body>
</html>
