<?php
require 'database.php';
require_once 'image_util.php';

define('UPLOAD_DIR', 'assets/images/');

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

$soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];
$isSold = in_array($vehicle['id'], $soldCars);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_sold'])) {
        if (!$isSold) {
            $soldCars[] = $vehicle['id'];
            file_put_contents('sold_vehicles.php', "<?php\nreturn " . var_export($soldCars, true) . ";\n");
        }
        header("Location: edit_vehicle.php?id=" . $id);
        exit;
    }

    if (isset($_POST['unmark_sold'])) {
        if ($isSold) {
            $soldCars = array_filter($soldCars, fn($carId) => $carId !== $vehicle['id']);
            $soldCars = array_values($soldCars);
            file_put_contents('sold_vehicles.php', "<?php\nreturn " . var_export($soldCars, true) . ";\n");
        }
        header("Location: edit_vehicle.php?id=" . $id);
        exit;
    }

    $year = $_POST['year'] ?? '';
    $make = $_POST['make'] ?? '';
    $model = $_POST['model'] ?? '';
    $trim = $_POST['trim'] ?? '';
    $color = $_POST['color'] ?? '';
    $price = $_POST['price'] ?? '';
    $imagePath = $vehicle['image_path'];

    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $tmpName = $_FILES['vehicle_image']['tmp_name'];
        $originalName = basename($_FILES['vehicle_image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newBaseName = uniqid('car_', true);
        $newFileName = $newBaseName . '.' . $ext;
        $destination = UPLOAD_DIR . $newFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            if (!empty($vehicle['image_path'])) {
                $baseOld = pathinfo($vehicle['image_path'], PATHINFO_FILENAME);
                $extOld = pathinfo($vehicle['image_path'], PATHINFO_EXTENSION);

                @unlink(UPLOAD_DIR . $baseOld . '.' . $extOld);
                @unlink(UPLOAD_DIR . $baseOld . '_100.' . $extOld);
                @unlink(UPLOAD_DIR . $baseOld . '_400.' . $extOld);
            }

            process_image(UPLOAD_DIR, $newFileName);
            $imagePath = UPLOAD_DIR . $newBaseName . '_100.' . $ext;
        }
    }

    $updateQuery = "UPDATE cars 
                    SET year = :year, make = :make, model = :model, trim = :trim,
                        color = :color, price = :price, image_path = :image_path 
                    WHERE id = :id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindValue(':year', $year);
    $updateStmt->bindValue(':make', $make);
    $updateStmt->bindValue(':model', $model);
    $updateStmt->bindValue(':trim', $trim);
    $updateStmt->bindValue(':color', $color);
    $updateStmt->bindValue(':price', $price);
    $updateStmt->bindValue(':image_path', $imagePath);
    $updateStmt->bindValue(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();
    $updateStmt->closeCursor();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Vehicle</title>
</head>
<body>
<main>
    <h2>Edit Vehicle</h2>

    <form action="edit_vehicle.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
        <label>
            Year:<br>
            <input type="number" name="year" value="<?= htmlspecialchars($vehicle['year']) ?>" required>
        </label><br><br>

        <label>
            Make:<br>
            <input type="text" name="make" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
        </label><br><br>

        <label>
            Model:<br>
            <input type="text" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
        </label><br><br>

        <label>
            Trim:<br>
            <input type="text" name="trim" value="<?= htmlspecialchars($vehicle['trim']) ?>">
        </label><br><br>

        <label>
            Color:<br>
            <input type="text" name="color" value="<?= htmlspecialchars($vehicle['color']) ?>">
        </label><br><br>

        <label>
            Price:<br>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($vehicle['price']) ?>" required>
        </label><br><br>

        <label>
            Replace Image:<br>
            <input type="file" name="vehicle_image" accept="image/*">
        </label><br><br>

        <button type="submit">Update Vehicle</button>
    </form>

    <form action="edit_vehicle.php?id=<?= htmlspecialchars($id) ?>" method="post" style="margin-top: 15px;">
        <?php if (!$isSold): ?>
            <button type="submit" name="mark_sold">Mark as Sold</button>
        <?php else: ?>
            <button type="submit" name="unmark_sold">Unmark as Sold</button>
        <?php endif; ?>
    </form>

    <p><a href="index.php">← Back to Inventory</a></p>
</main>
</body>
</html>
