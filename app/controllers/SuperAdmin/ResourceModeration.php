<?php

class ResourceModeration extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as superadmin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        // Get resource moderation data (all faculties)
        $resourceData = $this->getResourceData();

        $data = [
            'title' => 'Resource Moderation - GradBridge',
            'page_title' => 'Resource Moderation',
            'page_subtitle' => 'Moderate and manage shared resources from all faculties.',
            'user' => $_SESSION,
            'resourceData' => $resourceData
        ];

        $this->view('superadmin/resource-moderation', $data);
    }

    private function getResourceData()
    {
        $resourceModel = new SharedResource();
        
        // Get all reported resources (no faculty filter)
        $reported_resources_raw = $resourceModel->getAllReportedResources();
        
        // Format reported resources for the view
        $reported_resources = [];
        if (is_array($reported_resources_raw)) {
            foreach ($reported_resources_raw as $resource) {
                $reported_resources[] = [
                    'id' => $resource->resource_id,
                    'title' => $resource->title,
                    'author' => $resource->author_name,
                    'author_email' => $resource->author_email,
                    'resource_type' => ucwords(str_replace('-', ' ', $resource->category)),
                    'file_size' => number_format(($resource->file_size ?? 0) / 1024 / 1024, 1) . ' MB',
                    'description' => $resource->description,
                    'report_reason' => $resource->rep_reason ?? 'No reason provided',
                    'reported_date' => $resource->updated_at ?? $resource->created_at,
                    'status' => 'pending',
                    'downloads' => $resource->downloads ?? 0,
                    'user_role' => $resource->user_role ?? 'Unknown',
                    'faculty_name' => $resource->faculty_name ?? 'All Faculties'
                ];
            }
        }
        
        // Get super admin's own resources
        $my_resources = $resourceModel->where(['user_id' => $_SESSION['user_id']]);
        
        // Get recent resources from all faculties
        $recent_resources = $resourceModel->getAllRecentResources();
        
        // Get category counts (all faculties)
        $category_counts = $resourceModel->getAllCategoryCounts();
        
        // Get statistics (all faculties)
        $stats = $resourceModel->getAllResourceStats();
        
        return [
            'reported_resources' => $reported_resources,
            'my_resources' => $my_resources,
            'recent_resources' => $recent_resources,
            'category_counts' => $category_counts,
            'stats' => $stats
        ];
    }

    // Handle browse page with category and search
    public function browse()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        // Get filter parameters
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Fetch filtered resources (all faculties)
        $resourceModel = new SharedResource();
        $result = $resourceModel->browseAllResources($category, $search);
        $resources = is_array($result) ? $result : [];

        $this->view('superadmin/browse_resources', [
            'resources' => $resources,
            'category' => $category,
            'search' => $search
        ]);
    }

    // Handle download with tracking
    public function download()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            redirect('superadmin');
        }

        $resource_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($resource_id <= 0) {
            die('Invalid resource ID');
        }

        $resourceModel = new SharedResource();
        $resource = $resourceModel->first(['resource_id' => $resource_id]);

        if (!$resource) {
            die('Resource not found');
        }

        // Increment download count
        $resourceModel->incrementDownloads($resource_id);

        // Redirect to the actual file
        header('Location: ' . $resource->file_path);
        exit();
    }

    public function upload()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
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
        if (!isset($_FILES['resourceFile'])) {
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
            return;
        }

        $file = $_FILES['resourceFile'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Upload error: ' . $file['error']]);
            return;
        }

        // Validate file
        $allowedExt = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'ppt', 'pptx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'gif'];
        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            echo json_encode(['success' => false, 'message' => 'Unsupported file type']);
            return;
        }
        // Limit 10MB
        if ($file['size'] > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File too large (max 10MB)']);
            return;
        }

        // Ensure upload directory
        $dir = RESOURCE_UPLOAD_PATH;
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        if (!is_dir($dir)) {
            echo json_encode(['success' => false, 'message' => 'Upload folder missing', 'server_path' => $dir]);
            return;
        }
        if (!is_writable($dir)) {
            echo json_encode(['success' => false, 'message' => 'Upload folder not writable', 'server_path' => $dir]);
            return;
        }

        // Unique file name
        $safeTitle = preg_replace('/[^a-zA-Z0-9-_]+/', '-', strtolower($title));
        $newName = $safeTitle . '-' . time() . '.' . $ext;
        $targetPath = $dir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to save file', 'server_path' => $targetPath]);
            return;
        }

        // Build public URL path
        $publicPath = ROOT . '/assets/uploads/resources/' . $newName;

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
            'file_size' => (int)$file['size'],
            'file_type' => $ext,
            'downloads' => 0,
            'likes' => 0,
            'status' => 'approved',
            'faculty_id' => $faculty_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $ok = $resourceModel->insert($data);

        $resource = $resourceModel->first([
            'file_path' => $publicPath
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Resource uploaded successfully',
            'resource' => $resource ?: (object)$data,
            'server_path' => $targetPath,
        ]);
    }

    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
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

        // Map faculty selection to faculty_id
        $faculty_id = 999; // Default for "all-faculties"
        if ($faculty_input !== 'all-faculties') {
            $faculty_model = new Faculty();
            $faculty_record = $faculty_model->first(['faculty_name' => $faculty_input]);
            if ($faculty_record) {
                $faculty_id = $faculty_record->faculty_id;
            }
        }

        $update = [
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'faculty_id' => $faculty_id,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Optional file replacement
        if (isset($_FILES['resourceFile']) && is_array($_FILES['resourceFile']) && $_FILES['resourceFile']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['resourceFile'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Upload error: ' . $file['error']]);
                return;
            }
            $allowedExt = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'ppt', 'pptx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'gif'];
            $originalName = $file['name'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt)) {
                echo json_encode(['success' => false, 'message' => 'Unsupported file type']);
                return;
            }
            if ($file['size'] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'File too large (max 10MB)']);
                return;
            }
            $dir = RESOURCE_UPLOAD_PATH;
            if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
            if (!is_dir($dir) || !is_writable($dir)) {
                echo json_encode(['success' => false, 'message' => 'Upload folder not writable', 'server_path' => $dir]);
                return;
            }
            $safeTitle = preg_replace('/[^a-zA-Z0-9-_]+/', '-', strtolower($title));
            $newName = $safeTitle . '-' . time() . '.' . $ext;
            $targetPath = $dir . $newName;
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save file', 'server_path' => $targetPath]);
                return;
            }
            $publicPath = ROOT . '/assets/uploads/resources/' . $newName;
            $update['file_name'] = $originalName;
            $update['file_path'] = $publicPath;
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = (int)($_POST['resource_id'] ?? 0);
        if ($resourceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid resource id']);
            return;
        }

        $resourceModel = new SharedResource();
        $existing = $resourceModel->first(['resource_id' => $resourceId]);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Resource not found or access denied']);
            return;
        }

        // Extract physical file path from file_path (which is a URL)
        if (!empty($existing->file_path)) {
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

    // Handle AJAX report
    public function report()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = (int)($_POST['resource_id'] ?? 0);
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
            echo json_encode(['success' => true, 'message' => 'Resource reported successfully. Thank you for helping maintain quality content.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to report resource']);
        }
    }

    // Handle remove flag action
    public function removeflag()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $resourceId = (int)($_POST['resource_id'] ?? 0);

        if ($resourceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid resource ID']);
            return;
        }

        $resourceModel = new SharedResource();
        $resource = $resourceModel->first(['resource_id' => $resourceId]);

        if (!$resource) {
            echo json_encode(['success' => false, 'message' => 'Resource not found']);
            return;
        }

        // Remove the report flag
        $success = $resourceModel->removeFlagFromResource($resourceId);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Report flag removed successfully. Resource is now visible to users.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove flag']);
        }
    }
}
