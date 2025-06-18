<?php
require 'database.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "Invalid vehicle ID.";
    exit;
}

// Fetch vehicle from DB
$stmt = $db->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$vehicle = $stmt->fetch();
$stmt->closeCursor();

if (!$vehicle) {
    echo "Vehicle not found.";
    exit;
}

// Load sold vehicles list
$soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];
$isSold = in_array($vehicle['id'], $soldCars);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Vehicle</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<main>
    <h2>Edit Vehicle: <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></h2>

    <!-- Vehicle details and edit form here -->
    <!-- For brevity, only showing sold/unmark buttons below -->

    <form method="POST" action="mark_sold.php" style="display:inline;">
        <input type="hidden" name="car_id" value="<?= htmlspecialchars($vehicle['id']) ?>">
        <?php if (!$isSold): ?>
            <button type="submit">Mark as Sold</button>
        <?php endif; ?>
    </form>

    <form method="POST" action="unmark_sold.php" style="display:inline;">
        <input type="hidden" name="car_id" value="<?= htmlspecialchars($vehicle['id']) ?>">
        <?php if ($isSold): ?>
            <button type="submit">Unmark as Sold</button>
        <?php endif; ?>
    </form>

    <p><a href="index.php">← Back to Inventory</a></p>
</main>
</body>
</html>
