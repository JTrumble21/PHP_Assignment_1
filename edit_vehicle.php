<?php

require('database.php');

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
    $imagePath = $vehicle['image_path'];

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
            if (!empty($vehicle['image_path']) && file_exists($vehicle['image_path'])) {
                unlink($vehicle['image_path']);
            }
            $imagePath = $destination;
        }
    }

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
    <label>Year:
      <input type="number" name="year" value="<?= htmlspecialchars($vehicle['year']) ?>" required>
    </label>

    <label>Make:
      <input type="text" name="make" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
    </label>

    <label>Model:
      <input type="text" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
    </label>

    <label>Trim:
      <input type="text" name="trim" value="<?= htmlspecialchars($vehicle['trim']) ?>">
    </label>

    <label>Color:
      <input type="text" name="color" value="<?= htmlspecialchars($vehicle['color']) ?>">
    </label>

    <label>Price:
      <input type="number" name="price" value="<?= htmlspecialchars($vehicle['price']) ?>" required>
    </label>

    <?php if (!empty($vehicle['image_path']) && file_exists($vehicle['image_path'])): ?>
      <label>Current Image:</label><br>
      <img src="<?= htmlspecialchars($vehicle['image_path']) ?>" class="thumbnail" alt="Vehicle Image"><br>
    <?php endif; ?>

    <label>Replace Image:
      <input type="file" name="vehicle_image" accept="image/*">
    </label>

    <input type="submit" value="Update Vehicle">
  </form>
  <p><a href="index.php">← Back to Inventory</a></p>
</main>
</body>
</html>
