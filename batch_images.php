<?php
require_once 'image_util.php'; 

$dir = 'assets/images/';  


$extensions = ['jpg', 'jpeg', 'png', 'gif'];


$files = scandir($dir);

foreach ($files as $file) {
   
    if ($file === '.' || $file === '..') {
        continue;
    }

 
    if (!is_file($dir . $file)) {
        continue;
    }

    
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    
    if (!in_array($ext, $extensions)) {
        continue;
    }

   
    if (preg_match('/_(100|400)\.' . preg_quote($ext, '/') . '$/', $file)) {
        continue;
    }

    echo "Processing $file...\n";
    process_image($dir, $file);
}

echo "Processing complete.\n";
?>
