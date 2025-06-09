<?php
require 'database.php';
require 'image_util.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid ID.");

$stmt = $db->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->execute([':id' => $id]);
$car = $stmt->fetch();

if (!$car) die("Vehicle not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $image_path = $car['image_path'];

    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/images/';
        $ext = pathinfo($_FILES['vehicle_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('car_') . '.' . $ext;
        $path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $path)) {
            process_image($upload_dir, $filename);
            $image_path = $upload_dir . pathinfo($filename, PATHINFO_FILENAME) . '_100.' . $ext;
        }
    }

    $stmt = $db->prepare("UPDATE cars SET year = :year, make = :make, model = :model, trim = :trim,
                          color = :color, price = :price, image_path = :image WHERE id = :id");
    $stmt->execute([
        ':year' => $year, ':make' => $make, ':model' => $model, ':trim' => $trim,
        ':color' => $color, ':price' => $price, ':image' => $image_path, ':id' => $id
    ]);

    header("Location: index.php");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Year: <input type="number" name="year" value="<?= $car['year'] ?>"></label><br>
    <label>Make: <input type="text" name="make" value="<?= $car['make'] ?>"></label><br>
    <label>Model: <input type="text" name="model" value="<?= $car['model'] ?>"></label><br>
    <label>Trim: <input type="text" name="trim" value="<?= $car['trim'] ?>"></label><br>
    <label>Color: <input type="text" name="color" value="<?= $car['color'] ?>"></label><br>
    <label>Price: <input type="number" name="price" value="<?= $car['price'] ?>" step="0.01"></label><br>
    <?php if ($car['image_path']): ?>
        <img src="<?= $car['image_path'] ?>" width="100"><br>
    <?php endif; ?>
    <label>Replace Image: <input type="file" name="vehicle_image" accept="image/*"></label><br>
    <button type="submit">Update Vehicle</button>
</form>
