<?php
require 'image_util.php';

$base_dir = __DIR__ . '/assets/images/';
$filename = 'placeholder.jpg';

if (!file_exists($base_dir . $filename)) {
    die("Error: placeholder.jpg does not exist in $base_dir");
}

process_image($base_dir, $filename);
