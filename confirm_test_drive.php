<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = $_POST['vehicle_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;

    if (!$vehicleId || !$date || !$time) {
        echo "Missing booking data.";
        exit;
    }

    $query = "SELECT * FROM cars WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $vehicleId, PDO::PARAM_INT);
    $stmt->execute();
    $vehicle = $stmt->fetch();
    $stmt->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<main>
    <h2>Test Drive Confirmed</h2>
    <?php if (isset($vehicle)): ?>
        <p>You have booked a test drive for the <strong><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></strong>.</p>
        <p>Date: <?= htmlspecialchars($date) ?></p>
        <p>Time: <?= htmlspecialchars($time) ?></p>
    <?php endif; ?>
    <form action="index.php" method="get">
        <button type="submit">Close</button>
    </form>
</main>
</body>
</html>
