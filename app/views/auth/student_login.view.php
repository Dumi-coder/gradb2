<?php require '../app/views/partials/header.php'; ?>


    <!-- Login Form -->
    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card">
          <h1 class="auth-title">Welcome <span class="brand">Back!</span></h1>
          
          <form class="auth-form"  method="POST">


            <?php if(!empty($errors)):?>
            <div class="auth-alert" role="alert" aria-live="polite">
              <div class="auth-alert-header">
                <i class="fa-solid fa-circle-exclamation auth-alert-icon" aria-hidden="true"></i>
                <span>Unable to sign in</span>
              </div>
              <ul class="auth-error-list">
                <?php foreach($errors as $error): ?>
                  <?php if(!empty($error)): ?>
                    <li><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif;?>

            <input class="input" type="text" name="student_id" placeholder="Student ID" required />
            
            <input class="input" type="password" name="password" placeholder="Password" required />

            <label class="auth-meta" style="display:flex; align-items:center; gap:0.5rem;">
              <input type="checkbox" class="js-toggle-password" />
              Show password
            </label>
            
            <div class="auth-actions">
              <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Sign In</button>
            </div>
          </form>
          <p class="auth-meta"><a href="<?=ROOT?>/PasswordReset?role=student">Forgot password?</a></p>
          <p class="auth-meta">Don't have an account? <a href="<?=ROOT?>/Student/Auth?action=signup">Sign Up</a></p>
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

 <?php require '../app/views/partials/footer.php'; ?><!--footer-->



