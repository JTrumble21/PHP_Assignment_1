<?php
require 'database.php';
require 'image_util.php';

$cars = $db->query("SELECT image_path FROM cars")->fetchAll();

$processed = 0;
$skipped = 0;

foreach ($cars as $car) {
    $img = $car['image_path'];

    if (!empty($img) && file_exists($img)) {
        $dir = dirname($img);
        $filename = basename($img);
        process_image($dir, $filename);
        $processed++;
    } else {
        $skipped++;
    }
}

echo "<h3>Image Regeneration Complete</h3>";
echo "<p>Images processed: $processed</p>";
echo "<p>Images skipped (missing or empty): $skipped</p>";
?>
