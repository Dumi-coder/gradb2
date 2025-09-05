<?php require '../app/views/partials/header.php'; ?><!--footer-->


    <section class="auth-section gradient-hero">
      <div class="container">
        <div class="auth-card">
          <h1 class="auth-title">How would you like to sign in?</h1>
          <div style="display:grid; gap: 1rem; grid-template-columns: 1fr;">
            <a class="btn btn-secondary" href="<?=ROOT?>/student/login">I am a Student</a>
            <a class="btn btn-outline" href="<?=ROOT?>/alumni/login">I am an Alumni</a>
          </div>
        </div>
      </div>
    </section>

<?php require '../app/views/partials/footer.php'; ?><!--footer-->



