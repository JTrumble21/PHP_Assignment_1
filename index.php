<?php
require 'database.php';

$cars = $db->query("SELECT * FROM cars ORDER BY year DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Car Inventory</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <header>
        <h2>Car Inventory</h2>
        <a href="add_vehicle.php" class="add-vehicle">Add New Car</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Trim</th>
                    <th>Color</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <?php
                        $img_path = $car['image_path'];
                        $abs_img_path = __DIR__ . DIRECTORY_SEPARATOR . $img_path;

                        if (empty($img_path) || !file_exists($abs_img_path)) {
                            $img_path = 'assets/images/placeholder_100.jpg';
                        }
                    ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($img_path) ?>" alt="Car Image" class="thumbnail" /></td>
                        <td><?= htmlspecialchars($car['year']) ?></td>
                        <td><?= htmlspecialchars($car['make']) ?></td>
                        <td><?= htmlspecialchars($car['model']) ?></td>
                        <td><?= htmlspecialchars($car['trim']) ?></td>
                        <td><?= htmlspecialchars($car['color']) ?></td>
                        <td>$<?= number_format($car['price'], 2) ?></td>
                        <td>
                            <a href="detailed_vehicle.php?id=<?= urlencode($car['id']) ?>" class="details-link">Details</a>
                        </td>
                        <td>
                            <a href="edit_vehicle.php?id=<?= $car['id'] ?>">Edit</a> |
                            <a href="delete_vehicle.php?id=<?= $car['id'] ?>" onclick="return confirm('Delete this vehicle?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($cars)): ?>
                    <tr>
                        <td colspan="9" style="text-align:center;">No vehicles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
