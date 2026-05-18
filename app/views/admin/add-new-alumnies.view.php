<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Alumni - GradBridge Admin</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin_header.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin_sidebar.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>
    <?php include '../app/views/partials/admin_header.php'; ?>
    
    <div class="dashboard-container">
        <?php include '../app/views/partials/admin_sidebar.php'; ?>
        
        <main class="main-content">
            <div class="form-container">
                <h1 class="form-title">Register New Alumni</h1>
                <p class="form-subtitle">Add a new alumni member to the system</p>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?= implode("<br>", $errors) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="graduated_year">Graduation Year *</label>
                        <input type="number" id="graduated_year" name="graduated_year" placeholder="e.g., 2020" min="1900" max="<?= date('Y') ?>" required value="<?= $_POST['graduated_year'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="Enter full name" required value="<?= $_POST['name'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="alumni@email.com" required value="<?= $_POST['email'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Phone Number *</label>
                        <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" required value="<?= $_POST['mobile'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="degrees">Degree *</label>
                        <input type="text" id="degrees" name="degrees" placeholder="e.g., BSc Computer Science" required value="<?= $_POST['degrees'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="current_workplace">Current Workplace *</label>
                        <input type="text" id="current_workplace" name="current_workplace" placeholder="Enter current workplace" required value="<?= $_POST['current_workplace'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="expertise_area">Area of Expertise *</label>
                        <input type="text" id="expertise_area" name="expertise_area" placeholder="e.g., Data Science, Web Development" required value="<?= $_POST['expertise_area'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="is_mentor">Willing to Be a Mentor? *</label>
                        <select id="is_mentor" name="is_mentor" required>
                            <option value="">Select Option</option>
                            <option value="1" <?= (isset($_POST['is_mentor']) && $_POST['is_mentor'] === '1') ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?= (isset($_POST['is_mentor']) && $_POST['is_mentor'] === '0') ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="alumni_id">Alumni Membership Number *</label>
                        <input type="text" id="alumni_id" name="alumni_id" placeholder="Enter alumni membership number" required value="<?= $_POST['alumni_id'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="faculty">Faculty *</label>
                        <select id="faculty" name="faculty" required>
                            <option value="">Select Faculty</option>
                            <option value="UCSC" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'UCSC') ? 'selected' : '' ?>>UCSC</option>
                            <option value="FOA" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOA') ? 'selected' : '' ?>>FOA</option>
                            <option value="FOS" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOS') ? 'selected' : '' ?>>FOS</option>
                            <option value="FOM" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOM') ? 'selected' : '' ?>>FOM</option>
                            <option value="FOMF" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOMF') ? 'selected' : '' ?>>FOMF</option>
                            <option value="FOL" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOL') ? 'selected' : '' ?>>FOL</option>
                            <option value="FOE" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOE') ? 'selected' : '' ?>>FOE</option>
                            <option value="FOT" <?= (isset($_POST['faculty']) && $_POST['faculty'] == 'FOT') ? 'selected' : '' ?>>FOT</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div id="admin-password-container">
                            <input type="password" id="admin-password" name="password" placeholder="Enter secure password" required>
                            <input type="password" id="admin-confirm-password" name="confirm_password" placeholder="Re-enter password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-graduate"></i> Register Alumni
                    </button>
                </form>
            </div>
        </main>
    </div>

    <!-- Password Validation Script -->
    <script src="<?= ROOT ?>/assets/js/password-validation.js"></script>
    
    <script>
        function logout() {
            window.location.href = '<?= ROOT ?>/admin/logout';
        }
    </script>
</body>
</html>

