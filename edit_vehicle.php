<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("database.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid vehicle ID.";
    exit;
}

// Fetch existing vehicle
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $imagePath = $vehicle['image_path']; // Default to existing image path

    // Handle image upload
    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'assets/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['vehicle_image']['tmp_name'];
        $originalName = basename($_FILES['vehicle_image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newFileName = uniqid('car_', true) . '.' . $ext;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            // Delete old image if it exists
            if (!empty($vehicle['image_path']) && file_exists($vehicle['image_path'])) {
                unlink($vehicle['image_path']);
            }
            $imagePath = $destination;
        }
    }

    // Perform update
    $updateQuery = "UPDATE cars SET year = :year, make = :make, model = :model, trim = :trim,
                    color = :color, price = :price, image_path = :image_path WHERE id = :id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindValue(':year', $year);
    $updateStmt->bindValue(':make', $make);
    $updateStmt->bindValue(':model', $model);
    $updateStmt->bindValue(':trim', $trim);
    $updateStmt->bindValue(':color', $color);
    $updateStmt->bindValue(':price', $price);
    $updateStmt->bindValue(':image_path', $imagePath);
    $updateStmt->bindValue(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();
    $updateStmt->closeCursor();

    // Redirect after update
    header("Location: index.php");
    exit();
}
?>