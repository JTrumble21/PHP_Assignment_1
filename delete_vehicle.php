<?php
session_start();
require('database.php');

define('PLACEHOLDER_IMAGE', 'assets/images/placeholder_100.jpg');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid ID.";
    exit();
}

$id = (int)$_GET['id'];

// Get image path before deleting
$query = "SELECT image_path FROM cars WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$vehicle = $stmt->fetch();
$stmt->closeCursor();

if ($vehicle) {
    $image_path = $vehicle['image_path'];

    // If it's not the placeholder, delete original + resized versions
    if ($image_path !== PLACEHOLDER_IMAGE && file_exists($image_path)) {
        // Delete _100 version
        unlink($image_path);

        // Delete _400 and original image versions
        $dot_pos = strrpos($image_path, '_100');
        if ($dot_pos !== false) {
            $base = substr($image_path, 0, $dot_pos);
            $ext = substr($image_path, -4); // .jpg, .png, etc.

            $image_400 = $base . '_400' . $ext;
            $original = $base . $ext;

            if (file_exists($image_400)) unlink($image_400);
            if (file_exists($original)) unlink($original);
        }
    }

    // Delete the database record
    $deleteQuery = "DELETE FROM cars WHERE id = :id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindValue(':id', $id, PDO::PARAM_INT);
    $deleteStmt->execute();
    $deleteStmt->closeCursor();
}

header("Location: index.php");
exit();
?>

