<?php require '../app/views/partials/header.php'; ?>

<section class="auth-section gradient-hero">
  <div class="container">
    <div class="auth-card">
      <h1 class="auth-title">Please select your Admin Access</h1>
      <div style="display:grid; gap: 1rem; grid-template-columns: 1fr;">
        <a class="btn btn-secondary" href="<?=ROOT?>/FacultyAdmin/Dashboard">Faculty Admin</a>
        <a class="btn btn-outline" href="<?=ROOT?>/SuperAdmin/Dashboard">Super Admin</a>
      </div>
    </div>
  </div>
</section>

<?php require '../app/views/partials/footer.php'; ?>
