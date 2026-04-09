<?php
class Resources extends Controller
{
    private function normalizeResourceTags($rawTags)
    {
        $rawTags = trim((string)$rawTags);
        if ($rawTags === '') {
            return '';
        }

        $parts = preg_split('/[\,;\n]+/', $rawTags);
        if (!is_array($parts) || empty($parts)) {
            $parts = [$rawTags];
        }

        $tags = [];
        foreach ($parts as $part) {
            $tag = trim(ltrim((string)$part, '#'));
            if ($tag === '') {
                continue;
            }
            $tags[strtolower($tag)] = $tag;
        }

        return implode(', ', array_values($tags));
    }
    
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }
        
        // Load user's resources
        $resourceModel = new SharedResource();
        $resources = $resourceModel->where(['user_id' => $_SESSION['user_id']]);

        // Get current user's faculty_id
        $student = new Student();
        $studentProfile = $student->getStudentProfile($_SESSION['student_id'] ?? '');
        $user_faculty_id = $studentProfile->faculty_id ?? null;

        // Fetch recent resources visible to this user
        $recent_resources = [];
        $category_counts = [];
        $stats = ['total_resources' => 0, 'my_resources' => 0, 'my_downloads' => 0];
        $resourceCategories = $resourceModel->getResourceCategories();
        if ($user_faculty_id) {
            $recent_resources = $resourceModel->getRecentResourcesByFaculty($user_faculty_id);
            $category_counts = $resourceModel->getCategoryCounts($user_faculty_id);
            $stats = $resourceModel->getUserStats($_SESSION['user_id'], $user_faculty_id);
        }

        $this->view('student/resources', [
            'my_resources' => $resources,
            'recent_resources' => $recent_resources,
            'category_counts' => $category_counts,
            'stats' => $stats,
            'resource_categories' => $resourceCategories
        ]);
    }

    // Handle browse page with category and search
    public function browse()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Get current user's faculty_id
        $student = new Student();
        $studentProfile = $student->getStudentProfile($_SESSION['student_id'] ?? '');
        $user_faculty_id = $studentProfile->faculty_id ?? null;

        // Get filter parameters
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Fetch filtered resources
        $resourceModel = new SharedResource();
        $resourceCategories = $resourceModel->getResourceCategories();
        $resources = [];
        if ($user_faculty_id) {
            $result = $resourceModel->browseResources($user_faculty_id, $category, $search);
            $resources = is_array($result) ? $result : [];
        }

        $this->view('student/browse_resources', [
            'resources' => $resources,
            'category' => $category,
            'search' => $search,
            'resource_categories' => $resourceCategories
        ]);
    }

    // Handle AJAX upload
    public function upload()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        // Validate fields
        $title = trim($_POST['resourceTitle'] ?? '');
        $category = trim($_POST['resourceCategory'] ?? '');
        $description = trim($_POST['resourceDescription'] ?? '');
        $tags = $this->normalizeResourceTags($_POST['resourceTags'] ?? '');
        $resourceLink = trim($_POST['resourceLink'] ?? '');
        $faculty_input = trim($_POST['resourceFaculty'] ?? '');

        if ($title === '' || strlen($title) < 3) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid title']);
            return;
        }
        if ($category === '') {
            echo json_encode(['success' => false, 'message' => 'Please select a category']);
            return;
        }
        if ($faculty_input === '') {
            echo json_encode(['success' => false, 'message' => 'Please select visibility']);
            return;
        }
        if ($resourceLink === '') {
            echo json_encode(['success' => false, 'message' => 'Please provide a resource link']);
            return;
        }

        if (!filter_var($resourceLink, FILTER_VALIDATE_URL)) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid URL']);
            return;
        }

        $linkPath = parse_url($resourceLink, PHP_URL_PATH);
        $originalName = $linkPath ? basename($linkPath) : '';
        if ($originalName === '' || $originalName === '/') {
            $originalName = $title . '.url';
        }
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'link';
        }

        // Map faculty selection to faculty_id
        $faculty_id = 999; // Default for "all-faculties"
        if ($faculty_input !== 'all-faculties') {
            $faculty_model = new Faculty();
            $faculty_record = $faculty_model->first(['faculty_name' => $faculty_input]);
            if ($faculty_record) {
                $faculty_id = $faculty_record->faculty_id;
            }
        }

        // Save to DB
        $resourceModel = new SharedResource();
        $data = [
            'user_id' => $_SESSION['user_id'],
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'file_name' => $originalName,
            'file_path' => $resourceLink,
            'file_size' => 0,
            'file_type' => $ext,
            'downloads' => 0,
            'likes' => 0,
            'status' => 'approved',
            'faculty_id' => $faculty_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($resourceModel->hasTagsColumn()) {
            $data['tags'] = $tags;
        }
        $ok = $resourceModel->insert($data);
        if (!$ok) {
            echo json_encode(['success' => false, 'message' => 'Failed to share resource']);
            return;
        }

        $resource = $resourceModel->query(
            "SELECT * FROM resources WHERE user_id = :user_id AND file_path = :file_path ORDER BY resource_id DESC LIMIT 1",
            [
                'user_id' => (int)$_SESSION['user_id'],
                'file_path' => $resourceLink,
            ]
        );
        $resource = (is_array($resource) && !empty($resource)) ? $resource[0] : null;

        echo json_encode([
            'success' => true,
            'message' => 'Resource uploaded successfully',
            'resource' => $resource ?: (object)$data,
        ]);
    }

    // Handle AJAX update
    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = (int)($_POST['resourceId'] ?? 0);
        if ($resourceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid resource id']);
            return;
        }

        $resourceModel = new SharedResource();
        $existing = $resourceModel->first(['resource_id' => $resourceId, 'user_id' => $_SESSION['user_id']]);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Resource not found']);
            return;
        }

        $title = trim($_POST['resourceTitle'] ?? '');
        $category = trim($_POST['resourceCategory'] ?? '');
        $description = trim($_POST['resourceDescription'] ?? '');
        $tags = $this->normalizeResourceTags($_POST['resourceTags'] ?? '');
        $resourceLink = trim($_POST['resourceLink'] ?? '');
        $faculty_input = trim($_POST['resourceFaculty'] ?? '');

        if ($title === '' || strlen($title) < 3) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid title']);
            return;
        }
        if ($category === '') {
            echo json_encode(['success' => false, 'message' => 'Please select a category']);
            return;
        }

        // Map faculty selection to faculty_id
        $faculty_id = (int)($existing->faculty_id ?? 999); // Keep current visibility if not changed
        if ($faculty_input !== '') {
            if ($faculty_input === 'all-faculties') {
                $faculty_id = 999;
            } else {
                $faculty_model = new Faculty();
                $faculty_record = $faculty_model->first(['faculty_name' => $faculty_input]);
                if ($faculty_record) {
                    $faculty_id = $faculty_record->faculty_id;
                }
            }
        }

        $update = [
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'faculty_id' => $faculty_id,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($resourceModel->hasTagsColumn()) {
            $update['tags'] = $tags;
        }

        if ($resourceLink === '' || !filter_var($resourceLink, FILTER_VALIDATE_URL)) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid resource link']);
            return;
        }

        $linkPath = parse_url($resourceLink, PHP_URL_PATH);
        $originalName = $linkPath ? basename($linkPath) : '';
        if ($originalName === '' || $originalName === '/') {
            $originalName = $title . '.url';
        }
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'link';
        }

        $update['file_name'] = $originalName;
        $update['file_path'] = $resourceLink;
        $update['file_size'] = 0;
        $update['file_type'] = $ext;

        $resourceModel->update($resourceId, $update, 'resource_id');
        $updated = $resourceModel->first(['resource_id' => $resourceId]);
        echo json_encode(['success' => true, 'message' => 'Resource updated', 'resource' => $updated ?: (object)$update]);
    }
    
    // Handle AJAX delete
    public function delete()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = (int)($_POST['resourceId'] ?? 0);
        if ($resourceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid resource id']);
            return;
        }

        $resourceModel = new SharedResource();
        $existing = $resourceModel->first(['resource_id' => $resourceId, 'user_id' => $_SESSION['user_id']]);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Resource not found or access denied']);
            return;
        }

        // Extract physical file path from file_path (which is a URL)
        // file_path looks like: http://localhost/gradb2/public/assets/uploads/resources/filename.pdf
        // We need to convert it to: C:\xampp\htdocs\gradb2\public\assets\uploads\resources\filename.pdf
        if (!empty($existing->file_path)) {
            // Extract just the filename from the URL
            $fileName = basename($existing->file_path);
            $physicalPath = RESOURCE_UPLOAD_PATH . $fileName;
            
            // Delete physical file if it exists
            if (file_exists($physicalPath)) {
                @unlink($physicalPath);
            }
        }

        // Delete from database
        $resourceModel->delete($resourceId, 'resource_id');
        
        echo json_encode(['success' => true, 'message' => 'Resource deleted successfully']);
    }

    // Handle download tracking
    public function download()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Get resource ID from URL parameter
        $resourceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($resourceId <= 0) {
            redirect('student/resources');
            return;
        }

        $resourceModel = new SharedResource();
        $resource = $resourceModel->first(['resource_id' => $resourceId]);
        
        if (!$resource) {
            redirect('student/resources');
            return;
        }

        // Increment download count
        $resourceModel->incrementDownloads($resourceId);

        // Redirect to the actual file
        header('Location: ' . $resource->file_path);
        exit();
    }

    // Handle resource reporting
    public function report()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = (int)($_POST['resourceId'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');

        if ($resourceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid resource ID']);
            return;
        }

        if (empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Please provide a reason']);
            return;
        }

        $resourceModel = new SharedResource();
        $resource = $resourceModel->first(['resource_id' => $resourceId]);

        if (!$resource) {
            echo json_encode(['success' => false, 'message' => 'Resource not found']);
            return;
        }

        // Update resource as reported
        $success = $resourceModel->reportResource($resourceId, $reason);

        if ($success) {
            if (!empty($resource->user_id)) {
                $notificationModel = new Notification();
                $notificationModel->createResourceReportedNotification(
                    (int)$resource->user_id,
                    (int)($_SESSION['user_id'] ?? 0),
                    (string)($resource->title ?? 'Untitled Resource'),
                    $reason
                );
            }

            echo json_encode(['success' => true, 'message' => 'Resource reported successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to report resource']);
        }
    }
}
