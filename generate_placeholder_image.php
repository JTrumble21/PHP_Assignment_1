<?php
require 'image_util.php';

$base_dir = 'assets/images/';
$placeholder = 'placeholder.jpg';

if (!file_exists($base_dir . $placeholder)) {
    die("Placeholder image not found in $base_dir");
}

if (!file_exists($base_dir . 'placeholder_100.jpg') || !file_exists($base_dir . 'placeholder_400.jpg')) {
    process_image($base_dir, $placeholder);
    echo "Placeholder image resized versions (_100 and _400) generated successfully.";
} else {
    echo "Resized placeholder images already exist.";
}
