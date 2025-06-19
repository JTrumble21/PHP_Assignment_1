<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = $_POST['vehicle_id'] ?? null;
    $client = trim($_POST['client'] ?? '');
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;

    if (!$vehicleId || !$client || !$date || !$time) {
        echo "Missing booking data.";
        exit;
    }

    $insertQuery = "INSERT INTO test_drive_bookings (vehicle_id, client_name, booking_date, booking_time) 
                    VALUES (:vehicle_id, :client_name, :booking_date, :booking_time)";
    $stmt = $db->prepare($insertQuery);
    $stmt->bindValue(':vehicle_id', $vehicleId, PDO::PARAM_INT);
    $stmt->bindValue(':client_name', $client);
    $stmt->bindValue(':booking_date', $date);
    $stmt->bindValue(':booking_time', $time);
    $stmt->execute();
    $stmt->closeCursor();

    
    $query = "SELECT * FROM cars WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $vehicleId, PDO::PARAM_INT);
    $stmt->execute();
    $vehicle = $stmt->fetch();
    $stmt->closeCursor();
} else {
    header('Location: index.php');
    exit;
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
        <p>Client: <?= htmlspecialchars($client) ?></p>
        <p>Date: <?= htmlspecialchars($date) ?></p>
        <p>Time: <?= htmlspecialchars($time) ?></p>
    <?php else: ?>
        <p>Vehicle not found.</p>
    <?php endif; ?>
    <form action="index.php" method="get">
        <button type="submit">Close</button>
    </form>
</main>
</body>
</html>
