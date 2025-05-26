<?php
  session_start();
  require("database.php");

  $queryCars = 'SELECT * FROM cars';
  $statement = $db->prepare($queryCars);
  $statement->execute();
  $cars = $statement->fetchAll();
  $statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Inventory Manager</title>
  <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>

<body>
  <?php include("header.php"); ?>

  <main>
    <h2>Car Inventory</h2>

    <table>
      <tr>
        <th>Year</th>
        <th>Make</th>
        <th>Model</th>
        <th>Trim</th>
        <th>Color</th>
      </tr>

      <?php if (count($cars) > 0): ?>
        <?php foreach ($cars as $car): ?>
        <tr>
          <td><?= htmlspecialchars($car['year']) ?></td>
          <td><?= htmlspecialchars($car['make']) ?></td>
          <td><?= htmlspecialchars($car['model']) ?></td>
          <td><?= htmlspecialchars($car['trim']) ?></td>
          <td><?= htmlspecialchars($car['color']) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5">No cars in inventory.</td></tr>
      <?php endif; ?>
    </table>
  </main>

  <?php include("footer.php"); ?>
</body>
</html>
