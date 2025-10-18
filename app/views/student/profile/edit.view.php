
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="description" content="Edit Student Profile - GradBridge">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/edit-profile.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Main.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/other.css">
</head>
<body>
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="welcome-section">
                    <h1 class="welcome-text">Edit Profile</h1>
                    <p class="student-role">Update your information</p>
                </div>
                <div class="header-actions">
                    <a href="<?= ROOT ?>/student/profile" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <?php require '../app/views/partials/student_sidebar.php'; ?>
        <main class="main-content">
            <section class="dashboard-section profile-edit-section">
                <div class="section-header">
                    <h2 class="section-title">Edit Profile Information</h2>
                </div>
                <div class="profile-edit-card">
                    <form method="POST" class="profile-edit-form"  enctype="multipart/form-data">
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert <?= isset($errors['success']) ? 'alert-success' : 'alert-danger' ?>">
                                <?php foreach ($errors as $field => $error): ?>
                                    <p><?= esc($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div class="form-grid">
                            <!-- <div class="form-group">
                                <label for="profile_picture" class="form-label">
                                    <i class="fas fa-image"></i>
                                    Profile Picture
                                </label>
                                <input 
                                    type="file" 
                                    id="profile_picture"
                                    name="profile_picture" 
                                    class="form-input <?= isset($errors['profile_picture']) ? 'error' : '' ?>"
                                    accept="image/*"
                                >
                                <?php if (isset($errors['profile_picture'])): ?>
                                    <span class="error-message"><?= esc($errors['profile_picture']) ?></span>
                                <?php endif; ?>
                            </div> -->

                             <div class="form-group">
                                <label for="profile_picture" class="form-label">
                                    <i class="fas fa-image"></i>
                                    Profile Picture
                                </label>
                                <?php if (!empty($profile->profile_photo_url) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Uploads/' . $profile->profile_photo_url)): ?>
                                    <img src="<?= ROOT ?>/Uploads/<?= esc($profile->profile_photo_url) ?>" 
                                         alt="Current Profile Picture" 
                                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
                                <?php endif; ?>
                                <input 
                                    type="file" 
                                    id="profile_picture"
                                    name="profile_picture" 
                                    class="form-input <?= isset($errors['profile_picture']) ? 'error' : '' ?>"
                                    accept="image/*"
                                >
                                <div id="imagePreview" style="margin-top: 10px;"></div>
                                <?php if (isset($errors['profile_picture'])): ?>
                                    <span class="error-message"><?= esc($errors['profile_picture']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>
                                <input 
                                    type="text" 
                                    id="name"
                                    name="name" 
                                    class="form-input <?= isset($errors['name']) ? 'error' : '' ?>"
                                    value="<?= esc($profile->name) ?>"
                                    required
                                >
                                <?php if (isset($errors['name'])): ?>
                                    <span class="error-message"><?= esc($errors['name']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="faculty" class="form-label">
                                    <i class="fas fa-building"></i>
                                    Faculty
                                </label>
                                <select
                                    name="faculty" 
                                    id="faculty"
                                    class="form-input <?= isset($errors['faculty']) ? 'error' : '' ?>"
                                    required
                                >
                                    <option value="">Select Faculty</option>
                                    <option value="UCSC" <?= $profile->faculty == 'UCSC' ? 'selected' : '' ?>>UCSC</option>
                                    <option value="FOS" <?= $profile->faculty == 'FOS' ? 'selected' : '' ?>>FOS</option>
                                    <option value="FOM" <?= $profile->faculty == 'FOM' ? 'selected' : '' ?>>FOM</option>
                                    <option value="FOMF" <?= $profile->faculty == 'FOMF' ? 'selected' : '' ?>>FOMF</option>
                                    <option value="FOL" <?= $profile->faculty == 'FOL' ? 'selected' : '' ?>>FOL</option>
                                    <option value="FOE" <?= $profile->faculty == 'FOE' ? 'selected' : '' ?>>FOE</option>
                                    <option value="FOT" <?= $profile->faculty == 'FOT' ? 'selected' : '' ?>>FOT</option>
                                    
                                </select>
                                <?php if (isset($errors['faculty'])): ?>
                                    <span class="error-message"><?= esc($errors['faculty']) ?></span>
                                <?php endif; ?>

                            </div>

                            <div class="form-group">
                                <label for="academic_year" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Academic Year
                                </label>
                                <select 
                                    id="academic_year"
                                    name="academic_year" 
                                    class="form-input <?= isset($errors['academic_year']) ? 'error' : '' ?>"
                                    required
                                >
                                    <option value="">Select Year</option>
                                    <option value="1" <?= $profile->academic_year == 1 ? 'selected' : '' ?>>Year 1</option>
                                    <option value="2" <?= $profile->academic_year == 2 ? 'selected' : '' ?>>Year 2</option>
                                    <option value="3" <?= $profile->academic_year == 3 ? 'selected' : '' ?>>Year 3</option>
                                    <option value="4" <?= $profile->academic_year == 4 ? 'selected' : '' ?>>Year 4</option>
                                    
                                </select>
                                <?php if (isset($errors['academic_year'])): ?>
                                    <span class="error-message"><?= esc($errors['academic_year']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="student_id" class="form-label">
                                    <i class="fas fa-id-card"></i>
                                    Student ID
                                </label>
                                <input 
                                    type="text" 
                                    id="student_id"
                                    name="student_id" 
                                    class="form-input"
                                    value="<?= esc($profile->student_id) ?>"
                                    required
                                >
                                <?php if (isset($errors['student_id'])): ?>
                                    <span class="error-message"><?= esc($errors['student_id']) ?></span>
                                <?php endif; ?>

                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <input 
                                    type="email" 
                                    id="email"
                                    name="email" 
                                    class="form-input <?= isset($errors['email']) ? 'error' : '' ?>"
                                    value="<?= esc($profile->email) ?>"
                                    required
                                >
                                <?php if (isset($errors['email'])): ?>
                                    <span class="error-message"><?= esc($errors['email']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="mobile" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Mobile
                                </label>
                                <input
                                    type="text"
                                    id="mobile"
                                    name="mobile" 
                                    class="form-input <?= isset($errors['mobile']) ? 'error' : '' ?>"
                                    value="<?= esc($profile->mobile) ?>"
                                >
                                <?php if (isset($errors['mobile'])): ?>
                                    <span class="error-message"><?= esc($errors['mobile']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="bio" class="form-label">
                                    <i class="fas fa-user-edit"></i>
                                    Bio
                                </label>
                                <textarea 
                                    id="bio"
                                    name="bio" 
                                    class="form-input <?= isset($errors['bio']) ? 'error' : '' ?>"
                                    rows="4"
                                ><?= esc($profile->bio) ?></textarea>
                                <?php if (isset($errors['bio'])): ?>
                                    <span class="error-message"><?= esc($errors['bio']) ?></span>
                                <?php endif; ?>
                            </div>

                        </div>
                        
                        <input type="hidden" name="form_submitted" value="1">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <!-- <i class="fas fa-save"></i> -->
                                Save Changes
                            </button>
                            <a href="<?= ROOT ?>/student/profile" class="btn btn-secondary">
                                <!-- <i class="fas fa-times"></i> -->
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Image preview for profile picture
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.borderRadius = '50%';
                    img.style.objectFit = 'cover';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>