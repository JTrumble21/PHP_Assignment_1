<?php

$host = 'localhost';
$db   = 'car_inventory';
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

    $stmt = $pdo->query("SELECT year, make, model, trim, color FROM cars ORDER BY year DESC");
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
    <link rel="stylesheet" href="styles.css">
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
                    <th>Year</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Trim</th>
                    <th>Color</th>
                </tr>
            </thead>
            <tbody>
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
                <tr>
                    <td colspan="5" class="empty">No cars in inventory.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <p>Used Cars Copyright © <?= date("Y") ?> - All rights reserved</p>
</footer>
</body>
</html>
