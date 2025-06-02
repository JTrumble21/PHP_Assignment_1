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

    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['vehicle_image']['tmp_name'];
        $originalName = basename($_FILES['vehicle_image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newFileName = uniqid('car_', true) . '.' . $ext;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            $imagePath = $destination;
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
      <label>Year: <inp
