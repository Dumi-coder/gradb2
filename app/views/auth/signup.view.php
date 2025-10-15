<?php require '../app/views/partials/header.php'; ?><!--footer-->


    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card" style="max-width:520px">
          <h1 class="auth-title">How would you like to register?</h1>
          <div style="display:grid; gap: 1rem; grid-template-columns: 1fr;">
            <a class="btn btn-secondary" href="<?=ROOT?>/Student/Auth">I am a Student</a>
            <a class="btn btn-outline" href="<?=ROOT?>/Alumni/Auth">I am an Alumni</a>
          </div>
        </div>
      </div>
    </section>

 
<?php require '../app/views/partials/footer.php'; ?><!--footer-->



