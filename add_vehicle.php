<?php
require_once 'database.php';
require_once 'image_util.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $trim = $_POST['trim'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $image_path = null;

    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $tmp = $_FILES['vehicle_image']['tmp_name'];
        $ext = pathinfo($_FILES['vehicle_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('car_') . '.' . $ext;
        $path = $upload_dir . $filename;

        if (move_uploaded_file($tmp, $path)) {
            process_image($upload_dir, $filename);
            $image_path = $upload_dir . pathinfo($filename, PATHINFO_FILENAME) . '_100.' . $ext;
        }
    }

    $stmt = $db->prepare("INSERT INTO cars (year, make, model, trim, color, price, image_path)
                          VALUES (:year, :make, :model, :trim, :color, :price, :image)");
    $stmt->execute([
        ':year' => $year,
        ':make' => $make,
        ':model' => $model,
        ':trim' => $trim,
        ':color' => $color,
        ':price' => $price,
        ':image' => $image_path
    ]);

    header("Location: index.php");
    exit;
}
?>

<form action="add_vehicle.php" method="POST" enctype="multipart/form-data">
    <label>Year: <input type="number" name="year" required></label><br>
    <label>Make: <input type="text" name="make" required></label><br>
    <label>Model: <input type="text" name="model" required></label><br>
    <label>Trim: <input type="text" name="trim"></label><br>
    <label>Color: <input type="text" name="color"></label><br>
    <label>Price: <input type="number" name="price" required step="0.01"></label><br>
    <label>Image: <input type="file" name="vehicle_image" accept="image/*"></label><br>
    <button type="submit">Add Vehicle</button>
</form>
