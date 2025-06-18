<?php
require 'database.php';
require_once 'image_util.php';

define('UPLOAD_DIR', 'assets/images/');
define('PLACEHOLDER_IMAGE', UPLOAD_DIR . 'placeholder_100.jpg');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "Invalid vehicle ID.";
    exit;
}

// Fetch vehicle from DB
$query = "SELECT * FROM cars WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$vehicle = $statement->fetch();
$statement->closeCursor();

if (!$vehicle) {
    echo "Vehicle not found.";
    exit;
}

// Load sold vehicles list
$soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];
$isSold = in_array($vehicle['id'], $soldCars);

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['mark_sold']) && !isset($_POST['unmark_sold'])) {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $imagePath = $vehicle['image_path'];

    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $tmpName = $_FILES['vehicle_image']['tmp_name'];
        $originalName = basename($_FILES['vehicle_image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newBaseName = uniqid('car_', true);
        $newFileName = $newBaseName . '.' . $ext;
        $destination = UPLOAD_DIR . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            // Remove old image if not placeholder
            if (!empty($vehicle['image_path']) && $vehicle['image_path'] !== PLACEHOLDER_IMAGE) {
                @unlink($vehicle['image_path']);
            }

            process_image(UPLOAD_DIR, $newFileName);
            $imagePath = UPLOAD_DIR . $newBaseName . '_100.' . $ext;
        }
    }

    $updateQuery = "UPDATE cars 