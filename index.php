<?php
require 'database.php';
$cars = $db->query("SELECT * FROM cars ORDER BY year DESC")->fetchAll();
?>

<h2>Car Inventory</h2>
<a href="add_vehicle.php">Add New Car</a>
<table border="1">
    <tr>
        <th>Image</th><th>Year</th><th>Make</th><th>Model</th><th>Trim</th><th>Color</th><th>Price</th><th>Actions</th>
    </tr>
    <?php foreach ($cars as $car): ?>
        <tr>
            <td>
                <?php if ($car['image_path']): ?>
                    <img src="<?= $car['image_path'] ?>" width="100">
                <?php else: ?>No image<?php endif; ?>
            </td>
            <td><?= $car['year'] ?></td>
            <td><?= $car['make'] ?></td>
            <td><?= $car['model'] ?></td>
            <td><?= $car['trim'] ?></td>
            <td><?= $car['color'] ?></td>
            <td>$<?= number_format($car['price'], 2) ?></td>
            <td>
                <a href="edit_vehicle.php?id=<?= $car['id'] ?>">Edit</a> |
                <a href="delete_vehicle.php?id=<?= $car['id'] ?>" onclick="return confirm('Delete this vehicle?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
