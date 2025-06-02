<?php

$host = 'localhost';
$db   = 'car_inventory_manager';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Include image_path in SELECT
    $stmt = $pdo->query("SELECT year, make, model, trim, color, price, image_path FROM cars ORDER BY year DESC");
    $cars = $stmt->fetchAll();
} catch (\PDOException $e) {
    echo "<p>Error connecting to database: " . $e->getMessage() . "</p>";
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dealership Inventory System</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<header>
    <h1>Dealership Inventory System</h1>
</header>

<main>
    <section class="inventory">
        <h2>Car Inventory</h2>
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Trim</th>
                    <th>Color</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
           
            <?php if (count($cars) > 0): ?>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td>
                            <?php if (!empty($car['image_path']) && file_exists($car['image_path'])): ?>
                                <img src="<?= htmlspecialchars($car['image_path']) ?>" alt="Vehicle Image" class="thumbnail" />
                            <?php else: ?>
                                <span>Images Coming Soon!</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($car['year']) ?></td>
                        <td><?= htmlspecialchars($car['make']) ?></td>
                        <td><?= htmlspecialchars($car['model']) ?></td>
                        <td><?= htmlspecialchars($car['trim']) ?></td>
                        <td><?= htmlspecialchars($car['color']) ?></td>
                        <td>$<?= number_format($car['price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                 <td colspan="7" class="empty">No cars in inventory.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
       <a href="add_vehicle.php" class="add-vehicle">Add Vehicle</a>
    </section>
</main>

<footer>
    <p>Used Cars Copyright © <?= date("Y") ?> - All rights reserved</p>
</footer>
</body>
</html>
