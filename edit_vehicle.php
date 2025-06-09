<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require("database.php");

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Invalid vehicle ID.";
    exit;
}

// Fetch vehicle
$stmt = $db->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$vehicle = $stmt->fetch();
$stmt->closeCursor();

if (!$vehicle) {
    echo "Vehicle not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $imagePath = $vehicle['image_path'];

    // Handle image upload
    if (!empty($_FILES['vehicle_image']['name'])) {
        $uploadDir = 'assets/images/';
        $fileName = uniqid('car_', true) . '.' . pathinfo($_FILES['vehicle_image']['name'], PATHINFO_EXTENSION);
        $relativePath = $uploadDir . $fileName;
        $fullPath = __DIR__ . '/' . $relativePath;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $fullPath)) {
            // Delete old image
            if (!empty($vehicle['image_path']) && file_exists(__DIR__ . '/' . $vehicle['image_path'])) {
                unlink(__DIR__ . '/' . $vehicle['image_path']);
            }
            $imagePath = $relativePath;
        }
    }

    $update = $db->prepare("UPDATE cars SET year=:year, make=:make, model=:model, trim=:trim,
        color=:color, price=:price, image_path=:image_path WHERE id=:id");
    $update->execute([
        ':year' => $year,
        ':make' => $make,
        ':model' => $model,
        ':trim' => $trim,
        ':color' => $color,
        ':price' => $price,
        ':image_path' => $imagePath,
        ':id' => $id
    ]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <main>
        <h2>Edit Vehicle</h2>
        <form action="edit_vehicle.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
            <input type="number" name="year" value="<?= htmlspecialchars($vehicle['year']) ?>" required><br>
            <input type="text" name="make" value="<?= htmlspecialchars($vehicle['make']) ?>" required><br>
            <input type="text" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required><br>
            <input type="text" name="trim" value="<?= htmlspecialchars($vehicle['trim']) ?>"><br>
            <input type="text" name="color" value="<?= htmlspecialchars($vehicle['color']) ?>"><br>
            <input type="number" name="price" value="<?= htmlspecialchars($vehicle['price']) ?>" required><br>

            <?php if ($vehicle['image_path']): ?>
                <img src="<?= htmlspecialchars($vehicle['image_path']) ?>" alt="Car Image" style="max-width:200px;"><br>
            <?php endif; ?>

            <input type="file" name="vehicle_image" accept="image/*"><br>
            <input type="submit" value="Update Vehicle">
        </form>
        <p><a href="index.php">← Back</a></p>
    </main>
</body>
</html>
