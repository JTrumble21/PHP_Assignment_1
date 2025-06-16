<?php
require('database.php');
require_once('image_util.php');

define('UPLOAD_DIR', 'assets/images/');
define('PLACEHOLDER_IMAGE', UPLOAD_DIR . 'placeholder_100.jpg');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "Invalid vehicle ID.";
    exit;
}

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
            // Remove old image only if it's not the placeholder
            if (!empty($vehicle['image_path']) && $vehicle['image_path'] !== PLACEHOLDER_IMAGE) {
                $base = pathinfo($vehicle['image_path'], PATHINFO_FILENAME);
                $extOld = pathinfo($vehicle['image_path'], PATHINFO_EXTENSION);
                $fullBasePath = UPLOAD_DIR . $base;

                @unlink($fullBasePath . '.' . $extOld);
                @unlink($fullBasePath . '_100.' . $extOld);
                @unlink($fullBasePath . '_400.' . $extOld);
            }

            process_image(UPLOAD_DIR, $newFileName);
            $imagePath = UPLOAD_DIR . $newBaseName . '_100.' . $ext;
        }
    }

    $updateQuery = "UPDATE cars 
                    SET year = :year, make = :make, model = :model, trim = :trim,
                        color = :color, price = :price, image_path = :image_path 
                    WHERE id = :id";
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
    </label><br>

    <label>Make:
      <input type="text" name="make" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
    </label><br>

    <label>Model:
      <input type="text" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
    </label><br>

    <label>Trim:
      <input type="text" name="trim" value="<?= htmlspecialchars($vehicle['trim']) ?>">
    </label><br>

    <label>Color:
      <input type="text" name="color" value="<?= htmlspecialchars($vehicle['color']) ?>">
    </label><br>

    <label>Price:
      <input type="number" name="price" value="<?= htmlspecialchars($vehicle['price']) ?>" required>
    </label><br><br>

    <?php
    $imgSrc = (!empty($vehicle['image_path']) && file_exists($vehicle['image_path'])) ? $vehicle['image_path'] : PLACEHOLDER_IMAGE;
    ?>
    <label>Current Image:</label><br>
    <img src="<?= htmlspecialchars($imgSrc) ?>" class="thumbnail" alt="Vehicle Image"><br><br>

    <label>Replace Image:
      <input type="file" name="vehicle_image" accept="image/*">
    </label><br><br>

    <button type="submit">Update Vehicle</button>
  </form>
  <p><a href="index.php">← Back to Inventory</a></p>
</main>
</body>
</html>
