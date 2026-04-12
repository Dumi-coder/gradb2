<?php
class Resources extends Controller
{
    private function normalizeExternalLink($link)
    {
        $link = trim((string)$link);
        if ($link === '') {
            return '';
        }

        if (!preg_match('/^https?:\/\//i', $link)) {
            $link = 'https://' . $link;
        }

        return filter_var($link, FILTER_VALIDATE_URL) ? $link : '';
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
        $resources = $resourceModel->getOwnerResourcesWithReportMeta((int)($_SESSION['user_id'] ?? 0), 5);

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
            $viewerUserId = (int)($_SESSION['user_id'] ?? 0);
            $recent_resources = $resourceModel->getRecentResourcesByFaculty($user_faculty_id, 3, $viewerUserId);
            $category_counts = $resourceModel->getCategoryCounts($user_faculty_id, $viewerUserId);
            $stats = $resourceModel->getUserStats($_SESSION['user_id'], $user_faculty_id, $viewerUserId);
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
            $viewerUserId = (int)($_SESSION['user_id'] ?? 0);
            $result = $resourceModel->browseResources($user_faculty_id, $category, $search, $viewerUserId);
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
        $resourceType = trim(strtolower($_POST['resourceType'] ?? 'file'));
        if (!in_array($resourceType, ['file', 'link'], true)) {
            $resourceType = 'file';
        }

        $publicPath = '';
        $originalName = '';
        $fileSize = 0;
        $storedFileType = 'link';

        if ($resourceType === 'link') {
            $resourceLink = $this->normalizeExternalLink($_POST['resourceLink'] ?? '');
            if ($resourceLink === '') {
                echo json_encode(['success' => false, 'message' => 'Please provide a valid resource link']);
                return;
            }

            $publicPath = $resourceLink;
            $originalName = $resourceLink;
        } else {
            if (!isset($_FILES['resourceFile'])) {
                echo json_encode(['success' => false, 'message' => 'Please upload a file']);
                return;
            }

            $file = $_FILES['resourceFile'];
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'File upload failed. Please try again']);
                return;
            }

            $allowedExt = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'ppt', 'pptx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'gif'];
            $originalName = $file['name'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                echo json_encode(['success' => false, 'message' => 'Unsupported file type']);
                return;
            }
            if ((int)$file['size'] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'File too large (max 10MB)']);
                return;
            }

            $dir = RESOURCE_UPLOAD_PATH;
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            if (!is_dir($dir) || !is_writable($dir)) {
                echo json_encode(['success' => false, 'message' => 'Upload folder is not writable']);
                return;
            }

            $safeTitle = preg_replace('/[^a-zA-Z0-9-_]+/', '-', strtolower($title));
            $newName = $safeTitle . '-' . time() . '.' . $ext;
            $targetPath = $dir . $newName;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
                return;
            }

            $publicPath = ROOT . '/assets/uploads/resources/' . $newName;
            $fileSize = (int)$file['size'];
            $storedFileType = $ext;
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
            'file_path' => $publicPath,
            'file_size' => $fileSize,
            'file_type' => $storedFileType,
            'downloads' => 0,
            'likes' => 0,
            'status' => 'approved',
            'faculty_id' => $faculty_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $ok = $resourceModel->insert($data);
        if (!$ok) {
            echo json_encode(['success' => false, 'message' => 'Failed to share resource']);
            return;
        }

        $resource = $resourceModel->query(
            "SELECT * FROM resources WHERE user_id = :user_id AND file_path = :file_path ORDER BY resource_id DESC LIMIT 1",
            [
                'user_id' => (int)$_SESSION['user_id'],
                'file_path' => $publicPath,
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
        $faculty_input = trim($_POST['resourceFaculty'] ?? '');
        $resourceType = trim(strtolower($_POST['resourceType'] ?? ''));

        if (!in_array($resourceType, ['file', 'link'], true)) {
            $resourceType = (($existing->file_type ?? '') === 'link') ? 'link' : 'file';
        }

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

        $resourceLink = $this->normalizeExternalLink($_POST['resourceLink'] ?? '');
        $hasNewFile = isset($_FILES['resourceFile'])
            && is_array($_FILES['resourceFile'])
            && (int)$_FILES['resourceFile']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($resourceType === 'link') {
            if ($resourceLink === '') {
                echo json_encode(['success' => false, 'message' => 'Please provide a valid resource link']);
                return;
            }

            $update['file_name'] = $resourceLink;
            $update['file_path'] = $resourceLink;
            $update['file_size'] = 0;
            $update['file_type'] = 'link';
        }

        if ($resourceType === 'file' && $hasNewFile) {
            $file = $_FILES['resourceFile'];
            if ((int)$file['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'File upload failed. Please try again']);
                return;
            }

            $allowedExt = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'ppt', 'pptx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'gif'];
            $originalName = $file['name'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                echo json_encode(['success' => false, 'message' => 'Unsupported file type']);
                return;
            }
            if ((int)$file['size'] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'File too large (max 10MB)']);
                return;
            }

            $dir = RESOURCE_UPLOAD_PATH;
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            if (!is_dir($dir) || !is_writable($dir)) {
                echo json_encode(['success' => false, 'message' => 'Upload folder is not writable']);
                return;
            }

            $safeTitle = preg_replace('/[^a-zA-Z0-9-_]+/', '-', strtolower($title));
            $newName = $safeTitle . '-' . time() . '.' . $ext;
            $targetPath = $dir . $newName;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
                return;
            }

            $update['file_name'] = $originalName;
            $update['file_path'] = ROOT . '/assets/uploads/resources/' . $newName;
            $update['file_size'] = (int)$file['size'];
            $update['file_type'] = $ext;
        }

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
        $managedPrefix = ROOT . '/assets/uploads/resources/';
        if (!empty($existing->file_path) && strpos((string)$existing->file_path, $managedPrefix) === 0) {
            // Extract just the filename from the URL
            $fileName = basename($existing->file_path);
            $physicalPath = RESOURCE_UPLOAD_PATH . $fileName;
            
            // Delete physical file if it exists
            if (file_exists($physicalPath)) {
                @unlink($physicalPath);
            }
        }

        // Remove report log rows first to avoid FK constraint failures.
        $reportsTable = $resourceModel->query(
            "SELECT COUNT(*) AS total
             FROM information_schema.TABLES
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :table_name",
            ['table_name' => 'resource_reports']
        );
        $hasReportsTable = is_array($reportsTable)
            && !empty($reportsTable)
            && ((int)($reportsTable[0]->total ?? 0) > 0);
        if ($hasReportsTable) {
            $resourceModel->query(
                "DELETE FROM resource_reports WHERE resource_id = :resource_id",
                ['resource_id' => $resourceId]
            );
        }

        // Delete from database
        $deleted = $resourceModel->delete($resourceId, 'resource_id');
        if (!$deleted) {
            echo json_encode(['success' => false, 'message' => 'Unable to delete resource right now']);
            return;
        }
        
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

        if ((int)($resource->user_id ?? 0) === (int)($_SESSION['user_id'] ?? 0)) {
            echo json_encode(['success' => false, 'message' => 'You cannot report your own resource']);
            return;
        }

        $reportResult = $resourceModel->submitResourceReport($resourceId, (int)($_SESSION['user_id'] ?? 0), $reason, 5);

        if (!empty($reportResult['success'])) {
            if (!empty($resource->user_id)) {
                $notificationModel = new Notification();
                $totalReports = (int)($reportResult['total_reports'] ?? 1);
                $threshold = (int)($reportResult['threshold'] ?? 5);
                $globallyHidden = !empty($reportResult['globally_hidden']);

                if ($globallyHidden) {
                    // Send permanently hidden notification
                    $notificationModel->createResourcePermanentlyHiddenNotification(
                        (int)$resource->user_id,
                        (string)($resource->title ?? 'Untitled Resource'),
                        $totalReports
                    );
                } else {
                    // Send single report notification
                    $notificationModel->createResourceReportedNotification(
                        (int)$resource->user_id,
                        (int)($_SESSION['user_id'] ?? 0),
                        (string)($resource->title ?? 'Untitled Resource'),
                        $reason
                    );
                }
            }

            $totalReports = (int)($reportResult['total_reports'] ?? 1);
            $threshold = (int)($reportResult['threshold'] ?? 5);
            $globallyHidden = !empty($reportResult['globally_hidden']);

            $message = $globallyHidden
                ? 'Resource hidden after reaching ' . $threshold . ' reports'
                : 'Report submitted. This resource is hidden only from your view for now';

            echo json_encode([
                'success' => true,
                'message' => $message,
                'total_reports' => $totalReports,
                'threshold' => $threshold,
                'globally_hidden' => $globallyHidden,
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $reportResult['message'] ?? 'Failed to report resource']);
        }
    }
}
