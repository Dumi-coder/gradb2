<?php require '../app/views/partials/header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/auth.css?v=<?=time()?>">

<?php
$loginLinks = [
  'student' => ROOT . '/login',
  'alumni' => ROOT . '/login',
  'counselor' => ROOT . '/counselor',
  'faculty_admin' => ROOT . '/admin',
  'super_admin' => ROOT . '/superadmin',
];
$loginLink = $loginLinks[$role] ?? (ROOT . '/login');
?>

<section class="auth-section gradient-hero">
  <div class="container">
    <div class="auth-card">
      <h1 class="auth-title">Reset <span class="brand">Password</span></h1>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success">
          <?= esc($success) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <?= implode("<br>", array_map('esc', $errors)) ?>
        </div>
      <?php endif; ?>

      <form class="auth-form" method="POST">
        <input type="hidden" name="role" value="<?= esc($role) ?>" />

        <?php if (!empty($role) && isset($config[$role])): ?>
          <p class="auth-meta" style="margin-top:0;">Resetting for <?= esc($config[$role]['label']) ?> account</p>
        <?php endif; ?>

        <?php if ($step === 'request'): ?>
          <input type="hidden" name="stage" value="request" />

          <?php if ($role === 'student'): ?>
            <input class="input" type="text" name="student_id" placeholder="Student ID" value="<?= esc($values['student_id'] ?? '') ?>" required />
          <?php elseif ($role === 'alumni'): ?>
            <input class="input" type="text" name="alumni_id" placeholder="Alumni ID" value="<?= esc($values['alumni_id'] ?? '') ?>" required />
          <?php else: ?>
            <input class="input" type="email" name="email" placeholder="Email address" value="<?= esc($values['email'] ?? '') ?>" required />
          <?php endif; ?>

          <div class="auth-actions">
            <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Continue</button>
          </div>

        <?php elseif ($step === 'confirm'): ?>
          <input type="hidden" name="stage" value="send_otp" />
          <p class="auth-meta" style="margin-top:0;">We will send the OTP to: <strong><?= esc($masked_email) ?></strong></p>
          <div class="auth-actions">
            <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Send OTP</button>
          </div>

        <?php elseif ($step === 'verify'): ?>
          <input type="hidden" name="stage" value="verify_otp" />
          <input type="hidden" name="otp" id="otp" value="<?= esc($values['otp'] ?? '') ?>" />
          <div class="otp-row" style="display:flex; gap:0.5rem; justify-content:space-between;">
            <?php for ($i = 0; $i < 6; $i++): ?>
              <input class="input otp-input" type="text" name="otp_digits[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="OTP digit <?= $i + 1 ?>" />
            <?php endfor; ?>
          </div>
          <div class="auth-actions">
            <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Verify OTP</button>
          </div>

        <?php elseif ($step === 'reset'): ?>
          <input type="hidden" name="stage" value="reset_password" />
          <input class="input" type="password" name="password" placeholder="New password" required />
          <input class="input" type="password" name="confirm_password" placeholder="Confirm new password" required />

          <p class="auth-meta" style="margin-top:0;">
            Password must be at least 10 characters and include letters, numbers, and a special character.
          </p>

          <label class="auth-meta" style="display:flex; align-items:center; gap:0.5rem;">
            <input type="checkbox" class="js-toggle-password" />
            Show password
          </label>

          <div class="auth-actions">
            <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Reset Password</button>
          </div>
        <?php endif; ?>
      </form>

      <p class="auth-meta">
        Remembered your password? <a href="<?= esc($loginLink) ?>">Back to login</a>
      </p>
    </div>
  </div>
 </section>

<script>
  (function() {
    const form = document.querySelector('.auth-form');
    if (!form) {
      return;
    }
    const toggle = form.querySelector('.js-toggle-password');
    if (!toggle) {
      return;
    }
    const fields = form.querySelectorAll('input[type="password"]');
    toggle.addEventListener('change', function() {
      fields.forEach(function(field) {
        field.type = toggle.checked ? 'text' : 'password';
      });
    });

    const otpInputs = form.querySelectorAll('.otp-input');
    const otpHidden = form.querySelector('#otp');
    if (otpInputs.length && otpHidden) {
      otpInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
          const value = input.value.replace(/\D/g, '').slice(0, 1);
          input.value = value;
          if (value && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
          }
          otpHidden.value = Array.from(otpInputs).map(i => i.value).join('');
        });

        input.addEventListener('keydown', function(event) {
          if (event.key === 'Backspace' && !input.value && index > 0) {
            otpInputs[index - 1].focus();
          }
        });
      });

      form.addEventListener('submit', function() {
        otpHidden.value = Array.from(otpInputs).map(i => i.value).join('');
      });
    }
  })();
</script>

<?php require '../app/views/partials/footer.php'; ?>
