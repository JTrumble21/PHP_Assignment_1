<?php
session_start();
require_once('database.php');
require_once('image_util.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $imagePath = null;

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
            process_image($uploadDir, $newFileName); // Optional: create resized versions
            $imagePath = $destination;
        }
    }

    $query = "INSERT INTO cars (year, make, model, trim, color, price, image_path)
              VALUES (:year, :make, :model, :trim, :color, :price, :image_path)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':year', $year);
    $stmt->bindValue(':make', $make);
    $stmt->bindValue(':model', $model);
    $stmt->bindValue(':trim', $trim);
    $stmt->bindValue(':color', $color);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':image_path', $imagePath);
    $stmt->execute();
    $stmt->closeCursor();

    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Vehicle</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <main>
    <h2>Add New Vehicle</h2>
    <form action="add_vehicle.php" method="post" enctype="multipart/form-data">
      <label>Year: <input type="number" name="year" required></label>
      <label>Make: <input type="text" name="make" required></label>
      <label>Model: <input type="text" name="model" required></label>
      <label>Trim: <input type="text" name="trim"></label>
      <label>Color: <input type="text" name="color"></label>
      <label>Price: <input type="number" name="price" required></label>
      <label>Image: <input type="file" name="vehicle_image" accept="image/*"></label>
      <input type="submit" value="Add Vehicle">
    </form>
    <p><a href="index.php">&larr; Back to Inventory</a></p>
  </main>
</body>
</html>
