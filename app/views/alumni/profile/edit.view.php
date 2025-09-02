<?php require '../app/views/partials/alumni_header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">
<div class="dashboard-container" style="display:block;background:#f8fafc;margin-top:90px;min-height:calc(100vh - 90px);">
    <main class="main-content" style="margin-left:0;min-height:auto;">
        <div class="content-wrapper" style="max-width:960px;margin:0 auto;">
            <header class="dashboard-header" style="margin-bottom:1.5rem;">
                <h1 class="dashboard-title" style="font-size:2rem;font-weight:700;color:#1e293b;margin:0;">Edit Profile</h1>
                <p class="dashboard-subtitle" style="color:#64748b;font-size:1rem;margin-top:6px;">Update your personal information</p>
            </header>
            <div class="profile-container" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:1rem;padding:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                <div class="edit-form" style="max-width:820px;margin:0 auto;">
                    <div class="profile-section">
                        <h2 class="section-title">Personal Information</h2>
                        <div class="form-grid">
                            <label class="form-group"> <span class="form-label">Full Name</span><input class="form-input" value="John Michael Doe"/></label>
                            <label class="form-group"> <span class="form-label">Graduation Year</span><input type="number" class="form-input" value="2018"/></label>
                            <label class="form-group"> <span class="form-label">Degree</span><input class="form-input" value="Computer Science"/></label>
                            <label class="form-group"> <span class="form-label">Current Job</span><input class="form-input" value="Senior Software Engineer"/></label>
                            <label class="form-group"> <span class="form-label">Location</span><input class="form-input" value="San Francisco, CA"/></label>
                            <label class="form-group"> <span class="form-label">Email</span><input type="email" class="form-input" value="john.doe@email.com"/></label>
                        </div>
                    </div>
                    <div class="profile-section">
                        <h2 class="section-title">Additional Information</h2>
                        <div class="form-grid">
                            <label class="form-group"> <span class="form-label">Phone</span><input class="form-input" value="+1 (555) 123-4567"/></label>
                            <label class="form-group"> <span class="form-label">LinkedIn</span><input class="form-input" value="https://linkedin.com/in/johndoe"/></label>
                            <label class="form-group form-full"> <span class="form-label">Bio</span><textarea class="form-textarea" rows="4">Experienced software engineer with expertise in full-stack development. Passionate about mentoring students and contributing to the alumni community.</textarea></label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="<?=ROOT?>/alumni/profile" class="btn btn-secondary">Cancel</a>
                        <button type="button" class="btn btn-primary" onclick="showDemoSuccess()">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<div id="demoSuccess" class="demo-success" style="display:none;">Profile updated</div>
<style>
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
    .form-group{display:flex;flex-direction:column;gap:6px}
    .form-full{grid-column:1 / -1}
    .form-label{font-size:14px;font-weight:600;color:#333}
    .form-input,.form-textarea{padding:12px 14px;border:2px solid #e0e0e0;border-radius:8px;font-size:15px;transition:border .25s,box-shadow .25s;background:#fff}
    .form-input:focus,.form-textarea:focus{outline:none;border-color:#007bff;box-shadow:0 0 0 3px rgba(0,123,255,.15)}
    .btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:8px;padding:10px 20px;font-size:14px;font-weight:500;cursor:pointer;transition:.25s;background:#eee;text-decoration:none}
    .btn-primary{background:#007bff;color:#fff}
    .btn-primary:hover{background:#005fcc}
    .btn-secondary{background:#6c757d;color:#fff}
    .btn-secondary:hover{background:#545b62}
    .form-actions{display:flex;justify-content:flex-end;gap:14px;margin-top:10px;padding-top:25px;border-top:1px solid #e0e0e0}
    .section-title{font-size:20px;margin:0 0 18px;font-weight:600;color:#1e293b}
    .demo-success{position:fixed;top:110px;right:20px;background:#dbeafe;color:#1e40af;padding:14px 18px;border:1px solid #bfdbfe;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,.12);animation:fadeIn .35s ease;font-weight:500;z-index:1500}
    @keyframes fadeIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
    @media (max-width:780px){.form-grid{grid-template-columns:1fr}.form-actions{flex-direction:column}.btn{width:100%}}
</style>
<script>
function showDemoSuccess(){const n=document.getElementById('demoSuccess');n.style.display='block';setTimeout(()=>{n.style.display='none'},3000)}
</script>
<?php require '../app/views/partials/footer.php'; ?>