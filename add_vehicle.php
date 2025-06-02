<?php
require("database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];

    $query = "INSERT INTO cars (year, make, model, trim, color, price)
              VALUES (:year, :make, :model, :trim, :color, :price)";
    $statement = $db->prepare($query);
    $statement->bindValue(':year', $year);
    $statement->bindValue(':make', $make);
    $statement->bindValue(':model', $model);
    $statement->bindValue(':trim', $trim);
    $statement->bindValue(':color', $color);
    $statement->bindValue(':price', $price);
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
    <form action="add_vehicle.php" method="post">
      <label>Year: <input type="number" name="year" required></label><br>
      <label>Make: <input type="text" name="make" required></label><br>
      <label>Model: <input type="text" name="model" required></label><br>
      <label>Trim: <input type="text" name="trim"></label><br>
      <label>Color: <input type="text" name="color"></label><br>
      <label>Price: <input type="number" name="price" required></label><br>
      <input type="submit" value="Add Vehicle">
    </form>
    <p><a href="index.php">← Back to Inventory</a></p>
  </main>
</body>
</html>
