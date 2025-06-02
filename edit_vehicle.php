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
    $imagePath = $vehicle['image_path']; // default to existing image

    // Handle file upload
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
            // Delete old image
            if (!empty($vehicle['image_path']) && file_exists($vehicle['image_path'])) {
                unlink($vehicle['image_path']);
            }
            $imagePath = $destination;
        }
    }

    // Update query
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

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Vehicle</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>
  <main>
    <h2>Edit Vehicle</h2>
    <form action="edit_vehicle.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
      <label><input type="number" name="year" placeholder="Year" value="<?= htmlspecialchars($vehicle['year']) ?>" required></label><br>
      <label><input type="text" name="make" placeholder="Make" value="<?= htmlspecialchars($vehicle['make']) ?>" required></label><br>
      <label><input type="text" name="model" placeholder="Model" value="<?= htmlspecialchars($vehicle['model']) ?>" required></label><br>
      <label><input type="text" name="trim" placeholder="Trim" value="<?= htmlspecialchars($vehicle['trim']) ?>"></label><br>
      <label><input type="text" name="color" placeholder="Color" value="<?= htmlspecialchars($vehicle['color']) ?>"></label><br>
      <label><input type="number" name="price" placeholder="Price" value="<?= htmlspecialchars($vehicle['price']) ?>" required></label><br>

      <?php if ($vehicle['image_path']): ?>
        <img src="<?= htmlspecialchars($vehicle['image_path']) ?>" alt="Vehicle Image" class="thumbnail" style="max-width: 200px;"><br>
      <?php endif; ?>

      <label><input type="file" name="vehicle_image" accept="image/*"></label><br>
      <input type="submit" value="Update Vehicle">
    </form>
    <p><a href="index.php">← Back to Inventory</a></p>
  </main>
</body>
</html>
