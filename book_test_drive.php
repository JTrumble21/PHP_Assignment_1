<?php
require 'database.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "Invalid vehicle ID.";
    exit;
}

$query = "SELECT * FROM cars WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$vehicle = $stmt->fetch();
$stmt->closeCursor();

if (!$vehicle) {
    echo "Vehicle not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Test Drive</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<main>
    <h2>Book a Test Drive</h2>
    <form action="confirm_test_drive.php" method="post">
        <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($vehicle['id']) ?>">
        <p>Booking for: <?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></p>

        <label>Customer Name:
            <input type="name" name="client" required>
        </label><br>

        <label>Date:
            <input type="date" name="date" required>
        </label><br>

        <label>Time:
            <input type="time" name="time" required>
        </label><br><br>

        <button type="submit">Book Now</button>
    </form>
</main>
</body>
</html>
