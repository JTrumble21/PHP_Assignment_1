<?php
// index.php
require 'database.php';
$cars = $db->query("SELECT * FROM cars ORDER BY year DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Car Inventory</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <header>
    <h2>Car Inventory</h2>
    <a class="add-vehicle" href="add_vehicle.php">Add New Car</a>
  </header>
  <main>
    <table>
      <tr>
        <th>Image</th><th>Year</th><th>Make</th><th>Model</th><th>Trim</th><th>Color</th><th>Price</th><th>Actions</th>
      </tr>
      <?php foreach ($cars as $car): ?>
        <tr>
          <td>
            <?php if (!empty($car['image_path']) && file_exists($car['image_path'])): ?>
              <img src="<?= htmlspecialchars($car['image_path']) ?>" class="thumbnail">
            <?php else: ?>
              <span class="no-image">No image</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($car['year']) ?></td>
          <td><?= htmlspecialchars($car['make']) ?></td>
          <td><?= htmlspecialchars($car['model']) ?></td>
          <td><?= htmlspecialchars($car['trim']) ?></td>
          <td><?= htmlspecialchars($car['color']) ?></td>
          <td>$<?= number_format($car['price'], 2) ?></td>
          <td>
            <a href="edit_vehicle.php?id=<?= $car['id'] ?>">Edit</a> |
            <a href="delete_vehicle.php?id=<?= $car['id'] ?>" onclick="return confirm('Delete this vehicle?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>