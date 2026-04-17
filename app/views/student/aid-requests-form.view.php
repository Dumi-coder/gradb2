<?php
$page_title = "Aid Request Form";
$page_subtitle = "Submit an aid request";
require '../app/views/partials/student_header.php';
?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/aid-request-form.css">

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

</body>
</html>
