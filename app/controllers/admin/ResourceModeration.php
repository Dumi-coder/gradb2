<?php

class ResourceModeration extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in as admin
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Get faculty_id for this admin
        $faculty_id = $this->getFacultyIdForAdmin();
        
        if (!$faculty_id) {
            // Handle case where faculty_id is not found
            $data = [
                'title' => 'Resource Moderation - GradBridge',
                'page_title' => 'Resource Moderation',
                'page_subtitle' => 'Moderate and manage shared resources from students and alumni.',
                'user' => $_SESSION,
                'error' => 'Faculty ID not found. Please contact administrator.',
                'resourceData' => [
                    'reported_resources' => [],
                    'recent_resources' => [],
                    'stats' => [
                        'total_resources' => 0,
                        'reported_resources' => 0,
                        'pending_reports' => 0,
                        'approved_resources' => 0,
                        'total_downloads' => 0
                    ]
                ]
            ];
            $this->view('admin/resource-moderation', $data);
            return;
        }

        // Get resource moderation data
        $resourceData = $this->getResourceData($faculty_id);

        $data = [
            'title' => 'Resource Moderation - GradBridge',
            'page_title' => 'Resource Moderation',
            'page_subtitle' => 'Moderate and manage shared resources from students and alumni.',
            'user' => $_SESSION,
            'resourceData' => $resourceData
        ];

        $this->view('admin/resource-moderation', $data);
    }

    private function getFacultyIdForAdmin()
    {
        // Get faculty_id from faculty_admins table
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            error_log("getFacultyIdForAdmin: No user_id in session");
            return null;
        }
        
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $query = "SELECT faculty_id FROM faculty_admins WHERE user_id = :user_id";
            $stm = $con->prepare($query);
            $stm->execute(['user_id' => $user_id]);
            
            $result = $stm->fetch(PDO::FETCH_OBJ);
            
            if ($result && $result->faculty_id) {
                return $result->faculty_id;
            } else {
                error_log("getFacultyIdForAdmin: No faculty_admin record found for user_id {$user_id}");
                return null;
            }
        } catch (PDOException $e) {
            error_log("getFacultyIdForAdmin: Database error - " . $e->getMessage());
        }
        
        return null;
    }

    private function getResourceData($faculty_id)
    {
        $resourceModel = new SharedResource();
        
        // Get reported resources for this faculty
        $reported_resources_raw = $resourceModel->getReportedResourcesByFaculty($faculty_id);
        
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
                    'reported_by' => 'User', // We don't track who reported it yet
                    'reported_date' => $resource->updated_at ?? $resource->created_at,
                    'status' => 'pending',
                    'downloads' => $resource->downloads ?? 0,
                    'user_role' => $resource->user_role ?? 'Unknown',
                    'uploader_role' => $resource->uploader_role ?? '',
                    'user_is_suspended' => (int)($resource->user_is_suspended ?? 0)
                ];
            }
        }
        
        // Get faculty admin's own resources
        $my_resources = $resourceModel->where(['user_id' => $_SESSION['user_id']]);
        
        // Get recent resources for this faculty
        $recent_resources = $resourceModel->getRecentResourcesByFaculty($faculty_id);
        
        // Get category counts
        $category_counts = $resourceModel->getCategoryCounts($faculty_id);
        
        // Get statistics
        $stats = $resourceModel->getFacultyResourceStats($faculty_id);
        
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
        }

        // Get faculty_id for this admin
        $faculty_id = $this->getFacultyIdForAdmin();

        // Get filter parameters
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Fetch filtered resources
        $resourceModel = new SharedResource();
        $resources = [];
        if ($faculty_id) {
            $result = $resourceModel->browseResources($faculty_id, $category, $search);
            $resources = is_array($result) ? $result : [];
        }

        $this->view('admin/browse_resources', [
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
            redirect('admin');
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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
        $existing = $resourceModel->first(['resource_id' => $resourceId, 'user_id' => $_SESSION['user_id']]);
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

        if (!empty($existing->user_id)) {
            $notificationModel = new Notification();
            $notificationModel->createResourceDeletedNotification(
                (int)$existing->user_id,
                (int)($_SESSION['user_id'] ?? 0),
                (string)($existing->title ?? 'Untitled Resource'),
                'Faculty Admin'
            );
        }
        
        echo json_encode(['success' => true, 'message' => 'Resource deleted successfully']);
    }

    // Handle AJAX report
    public function report()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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
            if (!empty($resource->user_id)) {
                $notificationModel = new Notification();
                $notificationModel->createResourceReportedNotification(
                    (int)$resource->user_id,
                    (int)($_SESSION['user_id'] ?? 0),
                    (string)($resource->title ?? 'Untitled Resource'),
                    $reason
                );
            }

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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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
            if (!empty($resource->user_id)) {
                $notificationModel = new Notification();
                $notificationModel->createResourceFlagRemovedNotification(
                    (int)$resource->user_id,
                    (int)($_SESSION['user_id'] ?? 0),
                    (string)($resource->title ?? 'Untitled Resource'),
                    'Faculty Admin'
                );
            }

            echo json_encode(['success' => true, 'message' => 'Report flag removed successfully. Resource is now visible to users.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove flag']);
        }
    }

    public function suspenduser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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
        $resourceOwner = $resourceModel->query(
            "SELECT r.user_id, u.role
             FROM resources r
             JOIN users u ON r.user_id = u.user_id
             WHERE r.resource_id = :resource_id
             LIMIT 1",
            ['resource_id' => $resourceId]
        );

        if (!$resourceOwner || !is_array($resourceOwner) || empty($resourceOwner[0])) {
            echo json_encode(['success' => false, 'message' => 'Resource not found']);
            return;
        }

        $owner = $resourceOwner[0];
        $ownerRole = $owner->role ?? '';
        $ownerUserId = (int)($owner->user_id ?? 0);

        if (in_array($ownerRole, ['faculty_admin', 'super_admin'], true)) {
            echo json_encode(['success' => false, 'message' => 'This role cannot be suspended from this action. Use Notify User.']);
            return;
        }

        $targetTable = null;
        if ($ownerRole === 'student') {
            $targetTable = 'students';
        } elseif ($ownerRole === 'alumni') {
            $targetTable = 'alumnis';
        }

        if (!$targetTable) {
            echo json_encode(['success' => false, 'message' => 'Only student and alumni accounts can be suspended here.']);
            return;
        }

        $updated = $resourceModel->query(
            "UPDATE {$targetTable}
             SET is_suspended = 1
             WHERE user_id = :user_id",
            ['user_id' => $ownerUserId]
        );

        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'User suspended successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to suspend user']);
        }
    }

    public function notifyuser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty_admin') {
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

        $ownerUserId = (int)($resource->user_id ?? 0);
        if ($ownerUserId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Resource owner not found']);
            return;
        }

        $notificationModel = new Notification();
        $result = $notificationModel->createResourceWarningNotification(
            $ownerUserId,
            (int)($_SESSION['user_id'] ?? 0),
            (string)($resource->title ?? 'Untitled Resource'),
            'Your resource is under review. Please ensure it complies with our community guidelines.'
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'User notified successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to notify user']);
        }
    }
}
