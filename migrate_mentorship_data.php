<?php
/**
 * Migration script to update existing mentorship_requests data
 * This script parses the old format and updates the new columns
 */

require_once 'app/core/init.php';

try {
    $db = new Database();
    $pdo = $db->connect();
    
    // Get all existing mentorship requests with old format
    $query = "SELECT request_id, request_reason FROM mentorship_requests WHERE mentorship_category IS NULL OR mentorship_category = ''";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($requests) . " requests to migrate\n";
    
    $categoryMap = [
        'Academic Guidance' => 'academic',
        'Career Development' => 'career', 
        'Research & Projects' => 'research',
        'Professional Networking' => 'networking',
        'Technical Skills' => 'skills',
        'Leadership & Management' => 'leadership',
        'Entrepreneurship' => 'entrepreneurship'
    ];
    
    foreach ($requests as $request) {
        $lines = explode("\n", $request['request_reason']);
        $category = 'academic'; // default
        $otherCategory = null;
        $description = $request['request_reason']; // fallback
        
        if (count($lines) >= 2 && strpos($lines[0], 'Category:') === 0) {
            $categoryLine = trim(str_replace('Category:', '', $lines[0]));
            
            if (isset($categoryMap[$categoryLine])) {
                $category = $categoryMap[$categoryLine];
            } else {
                $category = 'other';
                $otherCategory = $categoryLine;
            }
            
            // Get description (everything after the first two lines)
            if (count($lines) > 2 && strpos($lines[2], 'Description:') === 0) {
                $description = trim(str_replace('Description:', '', $lines[2]));
            }
        }
        
        // Update the record
        $updateQuery = "UPDATE mentorship_requests SET mentorship_category = :category, other_category = :other_category, request_reason = :description WHERE request_id = :request_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'category' => $category,
            'other_category' => $otherCategory,
            'description' => $description,
            'request_id' => $request['request_id']
        ]);
        
        echo "Updated request ID: " . $request['request_id'] . " - Category: " . $category . "\n";
    }
    
    echo "Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>
