<?php require '../app/views/partials/alumni_header.php'; ?>

<?php
// Set current page for sidebar
$current_page = 'resources';

// Initialize current user ID if not set
$currentUserId = isset($currentUserId) ? $currentUserId : 123; // Default for demo

// Sample data if not provided by controller
if (!isset($resources) || empty($resources)) {
    $resources = [
        [
            'id' => 101,
            'title' => 'Final Year Project Guide',
            'description' => 'Comprehensive guide for completing your final year project successfully',
            'tags' => ['FYP', 'Guides', 'Project'],
            'file_type' => 'PDF',
            'file_size' => '1.2 MB',
            'uploaded_at' => '2025-08-20',
            'uploader_id' => 7,
            'uploader_name' => 'Jane Doe',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Guides',
            'downloads' => 42,
            'likes' => 9,
            'liked_by_me' => false
        ],
        [
            'id' => 102,
            'title' => 'Data Structures Past Papers',
            'description' => 'Collection of past exam papers for Data Structures course',
            'tags' => ['Past Papers', 'DSA', 'Exam'],
            'file_type' => 'ZIP',
            'file_size' => '3.4 MB',
            'uploaded_at' => '2025-08-18',
            'uploader_id' => 8,
            'uploader_name' => 'Dr. Smith',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Past Papers',
            'downloads' => 156,
            'likes' => 28,
            'liked_by_me' => true
        ],
        [
            'id' => 103,
            'title' => 'Research Methodology Slides',
            'description' => 'Comprehensive slides on research methodology for graduate students',
            'tags' => ['Research', 'Methodology', 'Graduate'],
            'file_type' => 'PPTX',
            'file_size' => '2.8 MB',
            'uploaded_at' => '2025-08-15',
            'uploader_id' => 9,
            'uploader_name' => 'Prof. Johnson',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Research',
            'downloads' => 89,
            'likes' => 15,
            'liked_by_me' => false
        ],
        [
            'id' => 104,
            'title' => 'Business Plan Template',
            'description' => 'Professional business plan template for startups',
            'tags' => ['Business', 'Template', 'Startup'],
            'file_type' => 'DOCX',
            'file_size' => '0.8 MB',
            'uploaded_at' => '2025-08-10',
            'uploader_id' => 123,
            'uploader_name' => 'Current User',
            'faculty' => 'Business',
            'department' => 'Management',
            'category' => 'Tools',
            'downloads' => 73,
            'likes' => 12,
            'liked_by_me' => false
        ],
        // New Popular Resources (high downloads)
        [
            'id' => 105,
            'title' => 'Machine Learning Algorithms Guide',
            'description' => 'Complete guide to understanding and implementing ML algorithms',
            'tags' => ['ML', 'AI', 'Algorithms'],
            'file_type' => 'PDF',
            'file_size' => '4.2 MB',
            'uploaded_at' => '2025-08-12',
            'uploader_id' => 10,
            'uploader_name' => 'Dr. AI Expert',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Research',
            'downloads' => 234,
            'likes' => 45,
            'liked_by_me' => false
        ],
        [
            'id' => 106,
            'title' => 'Database Design Best Practices',
            'description' => 'Essential patterns and practices for effective database design',
            'tags' => ['Database', 'Design', 'SQL'],
            'file_type' => 'PPTX',
            'file_size' => '3.1 MB',
            'uploaded_at' => '2025-08-14',
            'uploader_id' => 11,
            'uploader_name' => 'Prof. Database',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Guides',
            'downloads' => 189,
            'likes' => 37,
            'liked_by_me' => true
        ],
        // New Recent Uploads
        [
            'id' => 107,
            'title' => 'React.js Project Starter Kit',
            'description' => 'Complete React.js starter template with modern tooling setup',
            'tags' => ['React', 'JavaScript', 'Frontend'],
            'file_type' => 'ZIP',
            'file_size' => '2.7 MB',
            'uploaded_at' => '2025-08-25',
            'uploader_id' => 12,
            'uploader_name' => 'Frontend Dev',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Projects',
            'downloads' => 28,
            'likes' => 6,
            'liked_by_me' => false
        ],
        [
            'id' => 108,
            'title' => 'Network Security Lab Manual',
            'description' => 'Comprehensive lab exercises for network security course',
            'tags' => ['Security', 'Networking', 'Lab'],
            'file_type' => 'PDF',
            'file_size' => '5.3 MB',
            'uploaded_at' => '2025-08-24',
            'uploader_id' => 13,
            'uploader_name' => 'Security Expert',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Guides',
            'downloads' => 35,
            'likes' => 8,
            'liked_by_me' => false
        ],
        // New My Resources (uploader_id = 123)
        [
            'id' => 109,
            'title' => 'Python Data Analysis Notebook',
            'description' => 'Jupyter notebook with data analysis examples using pandas and matplotlib',
            'tags' => ['Python', 'Data', 'Analysis'],
            'file_type' => 'ZIP',
            'file_size' => '1.9 MB',
            'uploaded_at' => '2025-08-08',
            'uploader_id' => 123,
            'uploader_name' => 'Current User',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Projects',
            'downloads' => 67,
            'likes' => 14,
            'liked_by_me' => false
        ],
        [
            'id' => 110,
            'title' => 'Mobile App UI/UX Design Guide',
            'description' => 'Best practices and principles for mobile application design',
            'tags' => ['UI', 'UX', 'Mobile', 'Design'],
            'file_type' => 'PDF',
            'file_size' => '3.6 MB',
            'uploaded_at' => '2025-08-05',
            'uploader_id' => 123,
            'uploader_name' => 'Current User',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Guides',
            'downloads' => 91,
            'likes' => 19,
            'liked_by_me' => false
        ],
        [
            'id' => 111,
            'title' => 'Cloud Computing Architecture Patterns',
            'description' => 'Common architectural patterns for cloud-native applications',
            'tags' => ['Cloud', 'Architecture', 'AWS'],
            'file_type' => 'DOCX',
            'file_size' => '2.4 MB',
            'uploaded_at' => '2025-08-02',
            'uploader_id' => 123,
            'uploader_name' => 'Current User',
            'faculty' => 'Engineering',
            'department' => 'CSE',
            'category' => 'Research',
            'downloads' => 54,
            'likes' => 11,
            'liked_by_me' => false
        ]
    ];
}

// Filter resources based on search parameters
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$filterFileType = isset($_GET['file_type']) ? $_GET['file_type'] : '';
$filterCategory = isset($_GET['category']) ? $_GET['category'] : '';
$filterFaculty = isset($_GET['faculty']) ? $_GET['faculty'] : '';

$filteredResources = $resources;

if (!empty($searchQuery) || !empty($filterFileType) || !empty($filterCategory) || !empty($filterFaculty)) {
    $filteredResources = array_filter($resources, function($resource) use ($searchQuery, $filterFileType, $filterCategory, $filterFaculty) {
        $matchesSearch = empty($searchQuery) || 
            stripos($resource['title'], $searchQuery) !== false ||
            stripos($resource['description'], $searchQuery) !== false ||
            stripos(implode(' ', $resource['tags']), $searchQuery) !== false ||
            stripos($resource['uploader_name'], $searchQuery) !== false;
        
        $matchesFileType = empty($filterFileType) || $resource['file_type'] === $filterFileType;
        $matchesCategory = empty($filterCategory) || $resource['category'] === $filterCategory;
    $matchesFaculty = empty($filterFaculty) || $resource['faculty'] === $filterFaculty;

    return $matchesSearch && $matchesFileType && $matchesCategory && $matchesFaculty;
    });
}

// Sort for recent resources (newest first)
$recentResources = $filteredResources;
usort($recentResources, function($a, $b) {
    return strtotime($b['uploaded_at']) - strtotime($a['uploaded_at']);
});

// Sort for popular resources (most downloads first)
$popularResources = $filteredResources;
usort($popularResources, function($a, $b) {
    return $b['downloads'] - $a['downloads'];
});

// Filter my resources
$myResources = array_filter($resources, function($resource) use ($currentUserId) {
    return $resource['uploader_id'] == $currentUserId;
});

// Get unique values for filter dropdowns
$allFileTypes = array_unique(array_column($resources, 'file_type'));
$allCategories = array_unique(array_column($resources, 'category'));
$allFaculties = array_unique(array_column($resources, 'faculty'));


// Function to get file type icon
function getFileIcon($fileType) {
    switch (strtoupper($fileType)) {
        case 'PDF': return '📄';
        case 'DOCX': return '📘';
        case 'PPTX': return '📊';
        case 'ZIP': return '📦';
        default: return '📄';
    }
}

// Function to format date
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}
?>

<!-- Alumni Resources Content -->
<div class="dashboard-container">
    <?php require '../app/views/partials/alumni_sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-left">
                    <h1 class="dashboard-title">Shared Resources</h1>
                    <p class="dashboard-subtitle">Upload, discover, and download academic resources</p>
                    <div class="header-subaction">
                        <button id="uploadResourceBtn" class="btn btn-primary">+ Upload Resource</button>
                    </div>
                </div>
                <div class="header-actions">
                    <!-- reserved for other header actions -->
                </div>
            </header>

            <!-- Upload Resource Form (hidden by default) -->
            <section class="dashboard-section" id="upload-resource-section" style="display:none;">
                <div class="card upload-card">
                    <div class="upload-grid">
                        <div class="upload-info">
                            <h2 class="section-title">Upload New Resource</h2>
                            <p class="muted">Share lecture notes, templates, past papers or helpful guides with other alumni. Files are reviewed before publishing.</p>

                            <form class="form-grid" method="post" action="/resources/upload" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" id="title" name="title" placeholder="Enter resource title" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Guides">Guides</option>
                                            <option value="Past Papers">Past Papers</option>
                                            <option value="Research">Research</option>
                                            <option value="Tools">Tools</option>
                                            <option value="Projects">Projects</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group full">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" placeholder="Describe the resource..." rows="3" required></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="tags">Tags (comma-separated)</label>
                                        <input type="text" id="tags" name="tags" placeholder="e.g., FYP, Programming, CSE">
                                    </div>

                                    <div class="form-group">
                                        <label for="faculty">Faculty</label>
                                        <select id="faculty" name="faculty" required>
                                            <option value="">Select Faculty</option>
                                            <option value="Engineering">Engineering</option>
                                            <option value="Business">Business</option>
                                            <option value="Science">Science</option>
                                            <option value="Arts">Arts</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-actions full">
                                    <button type="submit" class="btn btn-primary">Upload Resource</button>
                                    <button type="button" id="cancelUploadBtn" class="btn btn-text">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <div class="upload-droparea">
                            <div class="dropbox" onclick="document.getElementById('file').click()">
                                <div class="drop-icon">�</div>
                                <div class="drop-text">Drag & drop files here or click to browse</div>
                                <div class="drop-hint">Accepted: PDF, DOCX, PPTX, ZIP — Max 10MB</div>
                                <input type="file" id="file" name="file" accept=".pdf,.docx,.pptx,.zip" style="display:none;">
                                <div class="upload-features">
                                    <div class="feature">✓ Secure Upload</div>
                                    <div class="feature">✓ Auto Review</div>
                                    <div class="feature">✓ Share Instantly</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Search & Filter Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        Search & Filter
                    </h2>
                </div>
                <div class="card">
                    <form class="search-filter-form" method="get">
                        <div class="search-row">
                            <div class="form-group">
                                <input type="text" name="q" placeholder="Search resources..." value="<?= htmlspecialchars($searchQuery) ?>" class="search-input">
                            </div>
                            
                            <div class="form-group">
                                <select name="file_type">
                                    <option value="">All Types</option>
                                    <?php foreach ($allFileTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type) ?>" <?= $filterFileType === $type ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($type) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <select name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($allCategories as $category): ?>
                                        <option value="<?= htmlspecialchars($category) ?>" <?= $filterCategory === $category ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <select name="faculty">
                                    <option value="">All Faculties</option>
                                    <?php foreach ($allFaculties as $faculty): ?>
                                        <option value="<?= htmlspecialchars($faculty) ?>" <?= $filterFaculty === $faculty ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($faculty) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Department filter removed -->
                            
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                    <!-- Optional section footer for quick actions -->
                    <div class="section-footer">
                        <a href="#" class="btn btn-text view-all-link">View All Resources →</a>
                    </div>
                </div>
            </section>

            <!-- Resource Tabs Section -->
            <div class="tab-container">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="recent">Recently Uploaded</button>
                    <button class="tab-btn" data-tab="popular">Popular Resources</button>
                </div>

                <!-- Recent Resources Tab -->
                <div class="tab-content active" id="recent-tab">
                    <section class="dashboard-section">
                        <div class="section-header">
                            <h2 class="section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                                Recently Uploaded
                            </h2>
                            <span class="section-count"><?php echo count($recentResources); ?> resources</span>
                        </div>
                        
                        <?php if (empty($recentResources)): ?>
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                                <h3>No Resources Found</h3>
                                <p>No resources found matching your criteria.</p>
                            </div>
                        <?php else: ?>
                            <div class="mentorship-grid">
                                <?php $recentToShow = count($recentResources) > 1 ? array_slice($recentResources, 0, count($recentResources) - 1) : $recentResources; ?>
                                <?php foreach ($recentToShow as $resource): ?>
                                    <div class="mentorship-card">
                                        <div class="card-header">
                                            <div class="student-info">
                                                <h3 class="card-title"><?= htmlspecialchars($resource['title']) ?></h3>
                                                <p class="request-type"><?= htmlspecialchars($resource['category']) ?> • <?= htmlspecialchars($resource['file_size']) ?></p>
                                            </div>
                                            <span class="card-badge <?= strtolower($resource['file_type']) ?>"><?= htmlspecialchars($resource['file_type']) ?></span>
                                        </div>
                                        <div class="card-content">
                                            <p class="card-description"><?= htmlspecialchars($resource['description']) ?></p>
                                            
                                            <div class="aid-details">
                                                <div class="aid-amount">Date: <strong><?= formatDate($resource['uploaded_at']) ?></strong></div>
                                                <div class="aid-type">By: <strong><?= htmlspecialchars($resource['uploader_name']) ?></strong></div>
                                            </div>
                                        </div>
                                        <div class="card-actions">
                                            <button class="btn btn-primary">Download</button>
                                            <button class="btn btn-secondary">Like</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- View All Link for Recent Resources -->
                        <div class="section-footer">
                            <a href="#" class="btn btn-text view-all-link">View All Recent Resources →</a>
                        </div>
                    </section>
                </div>

                <!-- Popular Resources Tab -->
                <div class="tab-content" id="popular-tab">
                    <section class="dashboard-section">
                        <div class="section-header">
                            <h2 class="section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                </svg>
                                Popular Resources
                            </h2>
                            <span class="section-count"><?php echo count($popularResources); ?> resources</span>
                        </div>
                        
                        <?php if (empty($popularResources)): ?>
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                </svg>
                                <h3>No Popular Resources</h3>
                                <p>No popular resources found.</p>
                            </div>
                        <?php else: ?>
                            <div class="mentorship-grid">
                                <?php $popularToShow = count($popularResources) > 1 ? array_slice($popularResources, 0, count($popularResources) - 1) : $popularResources; ?>
                                <?php foreach ($popularToShow as $resource): ?>
                                    <div class="mentorship-card">
                                        <div class="card-header">
                                            <div class="student-info">
                                                <h3 class="card-title"><?= htmlspecialchars($resource['title']) ?></h3>
                                                <p class="request-type"><?= htmlspecialchars($resource['category']) ?> • <?= htmlspecialchars($resource['file_size']) ?></p>
                                            </div>
                                            <span class="card-badge popular">Popular</span>
                                        </div>
                                        <div class="card-content">
                                            <p class="card-description"><?= htmlspecialchars($resource['description']) ?></p>
                                            
                                            <div class="aid-details">
                                                <div class="aid-amount">Downloads: <strong><?= $resource['downloads'] ?></strong></div>
                                                <div class="aid-type">Likes: <strong><?= $resource['likes'] ?></strong></div>
                                            </div>
                                        </div>
                                        <div class="card-actions">
                                            <button class="btn btn-primary">Download</button>
                                            <button class="btn btn-secondary">Like</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- View All Link for Popular Resources -->
                        <div class="section-footer">
                            <a href="#" class="btn btn-text view-all-link">View All Popular Resources →</a>
                        </div>
                    </section>
                </div>
            </div>

            <!-- My Resources Section -->
            <section class="dashboard-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                        My Resources
                    </h2>
                    <span class="section-count"><?php echo count($myResources); ?> resources</span>
                </div>
                
                <?php if (empty($myResources)): ?>
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                        <h3>No Resources Yet</h3>
                        <p>You haven't uploaded any resources yet.</p>
                        <button class="btn btn-primary" onclick="document.getElementById('uploadResourceBtn').click()">Upload Your First Resource</button>
                    </div>
                <?php else: ?>
                    <div class="mentorship-grid">
                        <?php foreach ($myResources as $resource): ?>
                            <div class="mentorship-card">
                                <div class="card-header">
                                    <div class="student-info">
                                        <h3 class="card-title"><?= htmlspecialchars($resource['title']) ?></h3>
                                        <p class="request-type"><?= htmlspecialchars($resource['category']) ?> • <?= htmlspecialchars($resource['file_size']) ?></p>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <p class="card-description"><?= htmlspecialchars($resource['description']) ?></p>
                                    
                                    <div class="aid-details">
                                        <div class="aid-amount">Uploaded: <strong><?= formatDate($resource['uploaded_at']) ?></strong></div>
                                        <div class="aid-type">Downloads: <strong><?= $resource['downloads'] ?></strong> • Likes: <strong><?= $resource['likes'] ?></strong></div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-primary">Edit</button>
                                    <button class="btn btn-secondary">Delete</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</div>

<!-- Add CSS for dashboard theme -->
<link rel="stylesheet" href="<?=ROOT?>/assets/css/dashboard.css">

<style>
/* Resource-specific styling additions to match alumni theme */
.search-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 1rem;
    align-items: center;
}

.search-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.tab-container {
    margin-bottom: 2rem;
}

/* Space between search card and uploaded files (tabs) */
.tab-container {
    margin-top: 2.5rem;
}

@media (max-width: 768px) {
    .tab-container {
        margin-top: 1.5rem;
    }
}

.tab-buttons {
    display: flex;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 0;
}

.tab-btn {
    padding: 1rem 2rem;
    border: none;
    background: none;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    font-weight: 500;
    color: #666;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    color: #333;
    background: #f8f9fa;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
    background: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Mentorship Grid - 2 columns with proper spacing */
.mentorship-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 1rem;
}

/* Card spacing improvements */
.mentorship-card {
    margin-bottom: 0;
    padding: 1.5rem;
}

.card-header {
    margin-bottom: 1rem;
}

.card-content {
    margin-bottom: 1rem;
}

.aid-details {
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}

.card-actions {
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
    gap: 0.75rem;
}

/* Section Footer */
.section-footer {
    margin-top: 2rem;
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.view-all-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

/* Improved select styling for search/filter (pure CSS) */
.search-filter-form .form-group {
    position: relative;
}

.search-filter-form select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    width: 100%;
    padding: 0.6rem 1rem;
    padding-right: 2.5rem; /* space for arrow */
    border: 1px solid #e6eef8;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(16,24,40,0.03);
    font-size: 0.95rem;
    color: #233044;
}

.search-filter-form .form-group::after {
    content: '';
    position: absolute;
    right: 12px;
    top: 50%;
    width: 12px;
    height: 12px;
    margin-top: -6px;
    pointer-events: none;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236672ea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
    background-repeat: no-repeat;
    background-size: 12px 12px;
}

.search-filter-form select:focus {
    outline: none;
    border-color: #a3b2ff;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.12);
}

.search-filter-form .search-input {
    border-radius: 8px;
}

/* Header left alignment: ensure subtitle is directly under title */
.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.dashboard-header .header-left {
    display: flex;
    flex-direction: column;
}

.dashboard-title {
    margin: 0 0 0.25rem 0;
}

.dashboard-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .dashboard-header {
        align-items: flex-start;
    }
}

/* place upload button beneath subtitle */
.header-subaction {
    margin-top: 0.6rem;
}

.header-subaction .btn {
    padding: 0.55rem 1rem;
}

@media (max-width: 768px) {
    .header-subaction {
        margin-top: 0.4rem;
    }
}

/* Add spacing below the Upload New Resource section to separate it from Search */
#upload-resource-section {
    margin-bottom: 2.25rem;
}

@media (min-width: 769px) {
    #upload-resource-section {
        margin-bottom: 3.5rem;
    }
}

/* Upload card styling - Enhanced */
.upload-card {
    padding: 1.25rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(16,24,40,0.06);
    border: 1px solid #eef2f7;
    position: relative;
}

.upload-card::before {
    display: none;
}

.upload-card .section-title {
    color: #102a43;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.upload-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    align-items: center; /* vertically center form and dropbox */
    position: relative;
    z-index: 1;
}

.upload-info .muted {
    color: #6b7280;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    line-height: 1.4;
}

.upload-info .form-grid {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.upload-droparea {
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropbox {
    border: 2px dashed #e6eef8;
    border-radius: 12px;
    padding: 2rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
    background: #fbfdff;
    transition: all 0.18s ease;
    min-height: 220px;
    width: 100%;
    max-width: 360px; /* constrain width so it visually centers */
    margin: 0 auto;
}

.dropbox:hover {
    border-color: #c7defb;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16,24,40,0.06);
}

.drop-icon {
    font-size: 36px;
    margin-bottom: 0.5rem;
}

.drop-text {
    font-weight: 600;
    color: #102a43;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.drop-hint {
    color: #64748b;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

/* Form enhancements within upload card */
.upload-info .form-grid {
    display: grid;
    gap: 1.25rem;
}

.upload-info .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.upload-info .form-group {
    display: flex;
    flex-direction: column;
}

.upload-info .form-group.full {
    grid-column: 1 / -1;
}

.upload-info .form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-align: left;
}

.upload-info .form-group input,
.upload-info .form-group select,
.upload-info .form-group textarea {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.2s ease;
    width: 100%;
    box-sizing: border-box;
    font-size: 0.95rem;
}

.upload-info .form-group input:focus,
.upload-info .form-group select:focus,
.upload-info .form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.upload-info .form-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: flex-start;
    margin-top: 1rem;
}

.upload-info .btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.upload-info .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(37,99,235,0.28);
}

/* Ensure header upload button matches the upload card primary color */
#uploadResourceBtn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    color: #fff;
}

#uploadResourceBtn:hover {
    box-shadow: 0 8px 16px rgba(37,99,235,0.18);
    transform: translateY(-1px);
}

.upload-info .btn-text {
    color: #6b7280;
    font-weight: 500;
}

/* Upload features styling */
.upload-features {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.upload-features .feature {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.85rem;
    font-weight: 500;
}

/* Small screens: stack upload grid */
@media (max-width: 768px) {
    .upload-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
    }
    
    .upload-info .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .dropbox {
    min-height: 200px;
    padding: 2rem 1rem;
    max-width: 100%;
    }
    
    .upload-features {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}

.view-all-link:hover {
    background: #f8fafc;
    color: #5a67d8;
    text-decoration: none;
}

.card-badge.pdf { background: #e3f2fd; color: #1976d2; }
.card-badge.docx { background: #e8f5e8; color: #388e3c; }
.card-badge.pptx { background: #fff3e0; color: #f57c00; }
.card-badge.zip { background: #f3e5f5; color: #7b1fa2; }
.card-badge.popular { background: #fff8e1; color: #f57f17; }
.card-badge.mine { background: #e8f5e8; color: #2e7d32; }

.report-dropdown {
    position: relative;
    display: inline-block;
}

.report-dropdown summary {
    list-style: none;
    cursor: pointer;
}

.dropdown-content {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 1rem;
    min-width: 200px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 1000;
}

.dropdown-content select,
.dropdown-content textarea {
    width: 100%;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 768px) {
    .search-row {
        grid-template-columns: 1fr;
    }
    
    .tab-buttons {
        flex-direction: column;
    }
    
    .mentorship-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .mentorship-card {
        padding: 1rem;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .mentorship-grid {
        gap: 1.25rem;
    }
}
</style>

<script>
// Tab functionality (minimal JS for tab switching)
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });
    
    // Upload form toggle
    const uploadBtn = document.getElementById('uploadResourceBtn');
    const uploadSection = document.getElementById('upload-resource-section');
    const cancelBtn = document.getElementById('cancelUploadBtn');
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            uploadSection.style.display = uploadSection.style.display === 'none' ? 'block' : 'none';
        });
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            uploadSection.style.display = 'none';
        });
    }
    
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle && sidebar && !sidebarToggle.hasAttribute('data-initialized')) {
        sidebarToggle.setAttribute('data-initialized', 'true');
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                if (mainContent) {
                    mainContent.style.marginLeft = '80px';
                }
            } else {
                if (mainContent) {
                    mainContent.style.marginLeft = '280px';
                }
            }
        });
    }

    // Handle responsive behavior - both collapse and expand
    function handleResize() {
        if (window.innerWidth <= 1024) {
            if (sidebar && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                if (mainContent) {
                    mainContent.style.marginLeft = '80px';
                }
            }
        } else {
            if (sidebar && sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                if (mainContent) {
                    mainContent.style.marginLeft = '280px';
                }
            }
        }
    }

    // Initial call and resize listener
    handleResize();
    window.addEventListener('resize', handleResize);

    // MutationObserver to reinitialize if sidebar is dynamically added
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                const newSidebarToggle = document.getElementById('sidebarToggle');
                if (newSidebarToggle && !newSidebarToggle.hasAttribute('data-initialized')) {
                    // Reinitialize sidebar toggle for dynamically added elements
                    location.reload();
                }
            }
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
});
</script>

<?php require '../app/views/partials/footer.php'; ?>
