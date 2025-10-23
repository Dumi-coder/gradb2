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
    <style>
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            color: #7f8c8d;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #2980b9;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        .alert-success {
            background-color: #efe;
            border: 1px solid #cfc;
            color: #3c3;
        }

        .main-content {
            padding: 30px;
        }
    </style>
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
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="Enter full name" required value="<?= $_POST['name'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="alumni@email.com" required value="<?= $_POST['email'] ?? '' ?>">
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
                        <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-graduate"></i> Register Alumni
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function logout() {
            window.location.href = '<?= ROOT ?>/admin/logout';
        }
    </script>
</body>
</html>

