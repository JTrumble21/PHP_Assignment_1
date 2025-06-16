<?php
require 'database.php';
require 'image_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_STRING);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING);
    $trim = filter_input(INPUT_POST, 'trim', FILTER_SANITIZE_STRING);
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $image = $_FILES['image'] ?? null;

    $base_dir = 'images/';
    $image_name = '';

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $original_filename = basename($image['name']);
        $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        // Validate allowed image extensions
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $unique_filename = uniqid() . '_' . $original_filename;
            $upload_path = $base_dir . $unique_filename;

            if (move_uploaded_file($image['tmp_name'], $upload_path)) {
                process_image($base_dir, $unique_filename);

                $dot_pos = strrpos($unique_filename, '.');
                $image_name = substr($unique_filename, 0, $dot_pos) . '_100' . substr($unique_filename, $dot_pos);
            }
        }
    }

    
    if (!$image_name) {
        $image_name = 'placeholder_100.jpg';
    }

    
    $query = 'INSERT INTO cars (year, make, model, trim, color, price, image_path)
              VALUES (:year, :make, :model, :trim, :color, :price, :image_path)';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':year', $year);
    $stmt->bindValue(':make', $make);
    $stmt->bindValue(':model', $model);
    $stmt->bindValue(':trim', $trim);
    $stmt->bindValue(':color', $color);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':image_path', $base_dir . $image_name);
    $stmt->execute();
    $stmt->closeCursor();

    header("Location: index.php");
    exit();
}
?>
