<?php require '../app/views/partials/header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/auth.css?v=<?=time()?>">

<section class="auth-section gradient-hero">
  <div class="container">
    <div class="auth-card">
      <h1 class="auth-title">Welcome <span class="brand">Back!</span></h1>

      <form class="auth-form" method="POST">
        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <?= implode('<br>', $errors) ?>
          </div>
        <?php endif; ?>

        <input
          class="input"
          type="text"
          name="identifier"
          placeholder="Student ID or Alumni ID"
          value="<?= esc($identifier ?? '') ?>"
          required
        />

        <input class="input" type="password" name="password" placeholder="Password" required />

        <label class="auth-meta" style="display:flex; align-items:center; gap:0.5rem;">
          <input type="checkbox" class="js-toggle-password" />
          Show password
        </label>

        <div class="auth-actions">
          <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Sign In</button>
        </div>
      </form>

      <p class="auth-meta" style="margin-top: 8px;">Forgot password?</p>
      <p class="auth-meta" style="margin-top: 2px;">
        <a href="<?=ROOT?>/PasswordReset?role=student">Reset as Student</a>
        &nbsp;|&nbsp;
        <a href="<?=ROOT?>/PasswordReset?role=alumni">Reset as Alumni</a>
      </p>

      <p class="auth-meta">Don't have an account yet?</p>
      <p class="auth-meta" style="margin-top: 2px;">
        <a href="<?=ROOT?>/Student/Auth?action=signup">Register as Student</a>
        &nbsp;|&nbsp;
        <a href="<?=ROOT?>/alumni/auth?action=signup">Register as Alumni</a>
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
  })();
</script>

<?php require '../app/views/partials/footer.php'; ?>



