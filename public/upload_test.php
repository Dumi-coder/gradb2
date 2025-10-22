<?php
$upload_dir = '/opt/lampp/htdocs/gradb2/public/assets/uploads/profiles/';

echo "<h2>Upload Test</h2>";
echo "<pre>";
echo "Upload directory: $upload_dir\n";
echo "Directory exists: " . (is_dir($upload_dir) ? 'YES' : 'NO') . "\n";
echo "Directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO') . "\n";
echo "Directory permissions: " . substr(sprintf('%o', fileperms($upload_dir)), -4) . "\n";
echo "\nPHP Upload Settings:\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "\n";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "\n";

// Test write
$test_file = $upload_dir . 'test_' . time() . '.txt';
if (file_put_contents($test_file, 'test')) {
    echo "\n✓ Successfully wrote test file!\n";
    unlink($test_file);
} else {
    echo "\n✗ Failed to write test file\n";
}

echo "\nApache user: ";
echo exec('whoami');
echo "</pre>";
?>