<?php require '../app/views/partials/header.php'; ?>

<?php
$loginLinks = [
  'student' => ROOT . '/student/Auth?action=login',
  'alumni' => ROOT . '/alumni/Auth?action=login',
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

        <input class="input" type="email" name="email" placeholder="Email address" value="<?= esc($values['email'] ?? '') ?>" required />

        <?php if ($role === 'student'): ?>
          <input class="input" type="text" name="student_id" placeholder="Student ID" value="<?= esc($values['student_id'] ?? '') ?>" required />
        <?php elseif ($role === 'alumni'): ?>
          <input class="input" type="text" name="alumni_id" placeholder="Alumni ID" value="<?= esc($values['alumni_id'] ?? '') ?>" required />
        <?php endif; ?>

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
  })();
</script>

<?php require '../app/views/partials/footer.php'; ?>
