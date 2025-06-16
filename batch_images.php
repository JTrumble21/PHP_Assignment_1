<?php
require_once 'image_util.php'; // Your image processing functions

$dir = 'assets/images/';  // Adjust path as needed

// Supported image extensions (lowercase)
$extensions = ['jpg', 'jpeg', 'png', 'gif'];

// Scan directory
$files = scandir($dir);

foreach ($files as $file) {
    // Skip . and ..
    if ($file === '.' || $file === '..') {
        continue;
    }

    // Skip if it's not a file
    if (!is_file($dir . $file)) {
        continue;
    }

    // Get file extension
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    // Skip if not an image
    if (!in_array($ext, $extensions)) {
        continue;
    }

    // Skip already resized images (_100 or _400 suffix)
    if (preg_match('/_(100|400)\.' . preg_quote($ext, '/') . '$/', $file)) {
        continue;
    }

    // Process image to create resized versions
    echo "Processing $file...\n";
    process_image($dir, $file);
}

echo "Processing complete.\n";
?>
