<?php
require('database.php');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "Invalid vehicle ID.";
    exit;
}

$query = "SELECT * FROM cars WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$vehicle = $statement->fetch();
$statement->closeCursor();

if (!$vehicle) {
    echo "Vehicle not found.";
    exit;
}

define('UPLOAD_DIR', 'assets/images/');
define('PLACEHOLDER_IMAGE', UPLOAD_DIR . 'placeholder_400.jpg');

$imagePath = $vehicle['image_path'] ?? '';
$displayImage = PLACEHOLDER_IMAGE;

if ($imagePath) {
    $image400 = preg_replace('/_100(\.\w+)$/', '_400$1', $imagePath);
    if (file_exists($image400)) {
        $displayImage = $image400;
    } elseif (file_exists($imagePath)) {
        $displayImage = $imagePath;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Vehicle Details</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<main class="vehicle-detail">
    <h2><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></h2>

    <img src="<?= htmlspecialchars($displayImage) ?>" alt="Image of <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?>" class="vehicle-image" />

    <p><strong>Trim:</strong> <?= htmlspecialchars($vehicle['trim']) ?></p>
    <p><strong>Color:</strong> <?= htmlspecialchars($vehicle['color']) ?></p>
    <p><strong>Price:</strong> $<?= number_format($vehicle['price'], 2) ?></p>

    <a href="index.php" class="back-link" role="button">Close</a>
</main>
</body>
</html>
