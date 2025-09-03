<?php

class Resources extends Controller
{
    public function index()
    {
        // Set current page for sidebar
        $current_page = 'resources';
        
        // In a real application, load resources from models
        // The view provides fallback sample data if these are not set
        
        // Pass data to view
        require '../app/views/alumni/alumni_resources.php';
    }

    // Placeholder endpoints for actions
    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload logic here
            echo json_encode(['success' => true, 'message' => 'Resource uploaded successfully']);
        }
    }
    
    public function download()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // Handle download logic here
            echo json_encode(['success' => true, 'message' => 'Download started for resource '. $id]);
        }
    }

    public function like()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['resource_id'] ?? null;
            echo json_encode(['success' => true, 'message' => 'Liked resource '. $id]);
        }
    }

    public function unlike()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['resource_id'] ?? null;
            echo json_encode(['success' => true, 'message' => 'Unliked resource '. $id]);
        }
    }

    public function report()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['resource_id'] ?? null;
            $reason = $_POST['reason'] ?? null;
            echo json_encode(['success' => true, 'message' => 'Reported resource '. $id . ' for: ' . $reason]);
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['resource_id'] ?? null;
            echo json_encode(['success' => true, 'message' => 'Edit form for resource '. $id]);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['resource_id'] ?? null;
            echo json_encode(['success' => true, 'message' => 'Deleted resource '. $id]);
        }
    }
}
