<?php require '../app/views/partials/superadmin_header.php'; ?>
<link rel="stylesheet" href="<?=ROOT?>/assets/css/profile.css">

<div class="dashboard-container">
    <?php require '../app/views/partials/superadmin_sidebar.php'; ?>

    <main class="main-content">
        <section class="dashboard-section profile-section">
            <div class="section-header">
                <h2 class="section-title">User Profile</h2>
                <a href="<?= ROOT ?>/superadmin/usermanagement" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>

            <div class="profile-card">
                <div class="profile-info">
                    <div class="profile-avatar">
                        <?php if (!empty($profile->profile_photo_url)): ?>
                            <img src="<?= esc($profile->profile_photo_url) ?>" alt="Profile Picture">
                        <?php else: ?>
                            <span class="avatar-initials"><?= strtoupper(substr((string)$profile->name, 0, 2)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="profile-details">
                        <h3><?= esc($profile->name) ?></h3>
                        <p><?= esc(ucfirst((string)$profile->role)) ?><?php if (!empty($profile->membership_id)): ?> ID: <?= esc($profile->membership_id) ?><?php endif; ?></p>
                        <div class="profile-meta">
                            <p><strong>Email:</strong> <?= esc($profile->email ?? 'N/A') ?></p>
                            <p><strong>Faculty:</strong> <?= esc($profile->faculty_name ?? 'N/A') ?></p>
                            <?php if (($profile->role ?? '') === 'student'): ?>
                                <p><strong>Academic Year:</strong> Year <?= esc($profile->academic_year ?? 'N/A') ?></p>
                            <?php else: ?>
                                <p><strong>Degree:</strong> <?= esc($profile->degrees ?? 'N/A') ?></p>
                                <p><strong>Graduated Year:</strong> <?= esc($profile->graduated_year ?? 'N/A') ?></p>
                                <p><strong>Current Workplace:</strong> <?= esc($profile->current_workplace ?? $profile->current_job ?? 'N/A') ?></p>
                                <p><strong>Expertise:</strong> <?= esc($profile->expertise_area ?? 'N/A') ?></p>
                            <?php endif; ?>
                            <p><strong>Mobile:</strong> <?= esc($profile->mobile ?? 'N/A') ?></p>
                            <p><strong>Status:</strong>
                                <?php if ((int)($profile->is_suspended ?? 0) === 1): ?>
                                    <span class="readonly-status readonly-status-suspended">Suspended</span>
                                <?php else: ?>
                                    <span class="readonly-status readonly-status-active">Active</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dashboard-section">
            <h2 class="section-title">About</h2>
            <div class="bio-section">
                <div class="bio-card">
                    <div class="bio-header">
                        <div class="bio-icon"><i class="fas fa-user"></i></div>
                        <div class="bio-title">
                            <h3>About <?= esc($profile->name) ?></h3>
                            <p>Read-only profile view</p>
                        </div>
                    </div>
                    <div class="bio-content">
                        <p><?= esc($profile->bio ?? 'No bio provided.') ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="dashboard-section">
            <h2 class="section-title">Contact & Social Links</h2>
            <div class="social-links-grid">
                <?php if (!empty($profile->linkedin_url)): ?>
                    <a href="<?= esc($profile->linkedin_url) ?>" target="_blank" class="social-link-card" rel="noopener">
                        <div class="social-link-icon linkedin"><i class="fab fa-linkedin"></i></div>
                        <div class="social-link-label">LinkedIn</div>
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile->github_url)): ?>
                    <a href="<?= esc($profile->github_url) ?>" target="_blank" class="social-link-card" rel="noopener">
                        <div class="social-link-icon github"><i class="fab fa-github"></i></div>
                        <div class="social-link-label">GitHub</div>
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile->twitter_url)): ?>
                    <a href="<?= esc($profile->twitter_url) ?>" target="_blank" class="social-link-card" rel="noopener">
                        <div class="social-link-icon twitter"><i class="fab fa-twitter"></i></div>
                        <div class="social-link-label">Twitter</div>
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile->personal_website)): ?>
                    <a href="<?= esc($profile->personal_website) ?>" target="_blank" class="social-link-card" rel="noopener">
                        <div class="social-link-icon website"><i class="fas fa-globe"></i></div>
                        <div class="social-link-label">Website</div>
                    </a>
                <?php endif; ?>
                <?php if (empty($profile->linkedin_url) && empty($profile->github_url) && empty($profile->twitter_url) && empty($profile->personal_website)): ?>
                    <div class="no-social-links"><p>No social media links available.</p></div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>


