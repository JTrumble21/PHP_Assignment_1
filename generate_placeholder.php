<?php
function resize_image($old_image_path, $new_image_path, $max_width, $max_height) {
    $image_info = getimagesize($old_image_path);
    if (!$image_info) {
        die("Error: Could not get image info.");
    }

    $image_type = $image_info[2];

    switch($image_type) {
        case IMAGETYPE_JPEG:
            $image_from_file = 'imagecreatefromjpeg';
            $image_to_file = 'imagejpeg';
            break;
        case IMAGETYPE_GIF:
            $image_from_file = 'imagecreatefromgif';
            $image_to_file = 'imagegif';
            break;
        case IMAGETYPE_PNG:
            $image_from_file = 'imagecreatefrompng';
            $image_to_file = 'imagepng';
            break;
        default:
            die('Error: File must be a JPEG, GIF, or PNG image.');
    }

    $old_image = $image_from_file($old_image_path);
    if (!$old_image) {
        die("Error: Failed to load image.");
    }

    $old_width = imagesx($old_image);
    $old_height = imagesy($old_image);

    $width_ratio = $old_width / $max_width;
    $height_ratio = $old_height / $max_height;
    $ratio = max($width_ratio, $height_ratio);
    $new_width = round($old_width / $ratio);
    $new_height = round($old_height / $ratio);

    $new_image = imagecreatetruecolor($new_width, $new_height);

    imagecopyresampled($new_image, $old_image, 0, 0, 0, 0,
                       $new_width, $new_height, $old_width, $old_height);

    $image_to_file($new_image, $new_image_path);

    imagedestroy($new_image);
    imagedestroy($old_image);

    echo "Saved: $new_image_path<br>";
}

// Define file and directory
$dir = 'assets/images/';
$original = $dir . 'placeholder.jpg';
$output_100 = $dir . 'placeholder_100.jpg';
$output_400 = $dir . 'placeholder_400.jpg';

if (!file_exists($original)) {
    die("Error: placeholder.jpg not found in $dir");
}

resize_image($original, $output_100, 100, 100);
resize_image($original, $output_400, 400, 300);
?>
