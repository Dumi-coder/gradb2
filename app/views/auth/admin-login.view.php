<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Faculty Admin Login - GradBridge</title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/other.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/header.css">
  </head>

<body>
<!-- Admin Login Form -->
<section class="auth-section gradient-hero">
  <div class="container">
    <div class="auth-card">
      <h1 class="auth-title">Faculty Admin <span class="brand">Login</span></h1>
      
      <form class="auth-form" method="POST">
        <?php if(!empty($errors)):?>
        <div class="alert alert-danger">       
            <?= implode("<br>",$errors)?> 
        </div>
        <?php endif;?>

        <input class="input" type="email" name="email" placeholder="Admin Email" required />
        
        <input class="input" type="password" name="password" placeholder="Password" required />

        <label class="auth-meta" style="display:flex; align-items:center; gap:0.5rem;">
          <input type="checkbox" class="js-toggle-password" />
          Show password
        </label>
        
        <div class="auth-actions">
          <button type="submit" class="btn btn-primary" style="width:100%; min-width: unset;">Sign In</button>
        </div>
      </form>

      <p class="auth-meta"><a href="<?=ROOT?>/PasswordReset?role=faculty_admin">Forgot password?</a></p>
      
      <p class="auth-meta">Internal Access Only</p>
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
</body>
</html>
