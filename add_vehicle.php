<?php
require("database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $imagePath = null;

    // Handle file upload
    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = 'uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true); // Create uploads folder if it doesn't exist
        }

        $tmpName = $_FILES['vehicle_image']['tmp_name'];
        $fileName = basename($_FILES['vehicle_image']['name']);
        $targetPath = $uploadsDir . time() . '_' . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    $query = "INSERT INTO cars (year, make, model, trim, color, price, image_path)
              VALUES (:year, :make, :model, :trim, :color, :price, :image_path)";
    $statement = $db->prepare($query);
    $statement->bindValue(':year', $year);
    $statement->bindValue(':make', $make);
    $statement->bindValue(':model', $model);
    $statement->bindValue(':trim', $trim);
    $statement->bindValue(':color', $color);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':image_path', $imagePath);
    $statement->execute();
    $statement->closeCursor();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Vehicle</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>
  <main>
    <h2>Add a New Vehicle</h2>
    <form action="add_vehicle.php" method="post" enctype="multipart/form-data">
      <label><input type="number" name="year" placeholder="Year" required></label><br>
      <label><input type="text" name="make" placeholder="Make" required></label><br>
      <label><input type="text" name="model" placeholder="Model" required></label><br>
      <label><input type="text" name="trim" placeholder="Trim"></label><br>
      <label><input type="text" name="color" placeholder="Color"></label><br>
      <label><input type="number" name="price" placeholder="Price" required></label><br>
      <label><input type="file" name="vehicle_image" accept="image/*"></label><br>
      <input type="submit" value="Add Vehicle">
    </form>
    <p><a href="index.php">← Back to Inventory</a></p>
  </main>
</body>
</html>
