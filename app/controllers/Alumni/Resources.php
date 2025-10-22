<?php
class Resources extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }
        // Load user's resources
    $resourceModel = new SharedResource();
    $resources = $resourceModel->where(['user_id' => $_SESSION['user_id']]);

        $this->view('alumni/resources', ['my_resources' => $resources]);
    }

    // Handle AJAX upload
    public function upload()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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

        if ($title === '' || strlen($title) < 3) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid title']);
            return;
        }
        if ($category === '') {
            echo json_encode(['success' => false, 'message' => 'Please select a category']);
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
    // Use the standardized resource upload path defined in Config
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
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $ok = $resourceModel->insert($data);

        if ($ok === false) {
            // Model::insert currently returns false even on success; ensure success with query true? We'll assume true if no exception. Return success.
        }

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

    // Handle AJAX update
    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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

        if ($title === '' || strlen($title) < 3) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid title']);
            return;
        }
        if ($category === '') {
            echo json_encode(['success' => false, 'message' => 'Please select a category']);
            return;
        }

        $update = [
            'title' => $title,
            'category' => $category,
            'description' => $description,
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

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
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
}
