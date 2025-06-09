<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require("database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'] ?? '';
    $color = $_POST['color'] ?? '';
    $price = $_POST['price'];
    $imagePath = null;

    // Handle image upload
    if (!empty($_FILES['vehicle_image']['name'])) {
        $uploadDir = 'assets/images/';
        $fileName = uniqid('car_', true) . '.' . pathinfo($_FILES['vehicle_image']['name'], PATHINFO_EXTENSION);
        $relativePath = $uploadDir . $fileName;
        $fullPath = __DIR__ . '/' . $relativePath;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $fullPath)) {
            $imagePath = $relativePath;
        }
    }

    // Insert into DB
    $stmt = $db->prepare("INSERT INTO cars (year, make, model, trim, color, price, image_path)
                          VALUES (:year, :make, :model, :trim, :color, :price, :image_path)");
    $stmt->execute([
        ':year' => $year,
        ':make' => $make,
        ':model' => $model,
        ':trim' => $trim,
        ':color' => $color,
        ':price' => $price,
        ':image_path' => $imagePath
    ]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <base href="/PHP_Assignment_1/">
  <title>Edit Vehicle</title>
  <link rel="stylesheet" href="css/main.css" />
</head>

<body>
<main>
    <h2>Add Vehicle</h2>
    <form action="add_vehicle.php" method="post" enctype="multipart/form-data">
        <label><input type="number" name="year" placeholder="Year" required></label><br>
        <label><input type="text" name="make" placeholder="Make" required></label><br>
        <label><input type="text" name="model" placeholder="Model" required></label><br>
        <label><input type="text" name="trim" placeholder="Trim"></label><br>
        <label><input type="text" name="color" placeholder="Color"></label><br>
        <label><input type="number" step="0.01" name="price" placeholder="Price" required></label><br>
        <label><input type="file" name="vehicle_image" accept="image/*"></label><br>
        <input type="submit" value="Add Vehicle">
    </form>
    <p><a href="index.php">← Back to Inventory</a></p>
</main>
</body>
</html>
