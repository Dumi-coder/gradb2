<?php require '../app/views/partials/header.php'; ?><!--header-->


    <!-- Login Form -->
    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card">
          <h1 class="auth-title">Welcome <span class="brand">Back!</span></h1>

          <form class="auth-form" action="#" method="post">

          <?php if(!empty($errors)):?>
            <div class="alert alert-danger">       
                <?= implode("<br>",$errors)?> 
            </div>
            <?php endif;?>
            
            <input class="input" type="text" name="alumni_id" placeholder="Alumni Membership No" required />
            
            <input class="input" type="password" name="password" placeholder="Password" required />
            
            <div class="auth-actions">
              <button type="submit" class="btn btn-primary" style="width:100%">Sign In</button>
            </div>
          </form>
          <p class="auth-meta">Don't have an account? <a href="<?=ROOT?>/alumni/signup">Sign Up</a></p>
        </div>
      </div>
    </section>


<?php require '../app/views/partials/footer.php'; ?><!--footer-->
