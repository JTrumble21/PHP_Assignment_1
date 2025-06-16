<?php
require 'database.php';
require 'image_util.php';

$base_dir = __DIR__ . '/assets/images/';  
$web_base_dir = 'assets/images/';          

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
    $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_STRING);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING);
    $trim = filter_input(INPUT_POST, 'trim', FILTER_SANITIZE_STRING);
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $image = $_FILES['image'] ?? null;

    $image_name = '';

    // Validate required fields
    if (!$year || !$make || !$model || !$price) {
        die('Year, Make, Model, and Price are required.');
    }

    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $original_filename = basename($image['name']);
        $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            die('Error: Image must be JPG, JPEG, PNG or GIF.');
        }

        $unique_filename = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $original_filename);
        $upload_path = $base_dir . $unique_filename;

        if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
            die('Failed to move uploaded file.');
        }

       
        if (!process_image($base_dir, $unique_filename)) {
            die('Failed to process image.');
        }

       
        $dot_pos = strrpos($unique_filename, '.');
        $image_name = substr($unique_filename, 0, $dot_pos) . '_100' . substr($unique_filename, $dot_pos);
    }

    
    if (!$image_name) {
        $image_name = 'placeholder_100.jpg';

       
        if (!file_exists($base_dir . $image_name)) {
            die('Placeholder image missing: ' . $image_name);
        }
    }

    
    $query = 'INSERT INTO cars (year, make, model, trim, color, price, image_path)
              VALUES (:year, :make, :model, :trim, :color, :price, :image_path)';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':year', $year);
    $stmt->bindValue(':make', $make);
    $stmt->bindValue(':model', $model);
    $stmt->bindValue(':trim', $trim);
    $stmt->bindValue(':color', $color);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':image_path', $web_base_dir . $image_name);
    $stmt->execute();
    $stmt->closeCursor();

    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Vehicle</title>
    <style>
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input[type=text], input[type=number], input[type=file] { width: 100%; padding: 6px; }
        button { margin-top: 15px; padding: 10px 20px; background-color: #78c88c; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #5a7a5b; }
    </style>
</head>
<body>
    <h2>Add Vehicle</h2>
    <form method="post" enctype="multipart/form-data" action="add_vehicle.php">
        <label for="year">Year *</label>
        <input type="number" id="year" name="year" min="1900" max="<?php echo date('Y') + 1; ?>" required>

        <label for="make">Make *</label>
        <input type="text" id="make" name="make" required>

        <label for="model">Model *</label>
        <input type="text" id="model" name="model" required>

        <label for="trim">Trim</label>
        <input type="text" id="trim" name="trim">

        <label for="color">Color</label>
        <input type="text" id="color" name="color">

        <label for="price">Price *</label>
        <input type="number" id="price" name="price" min="0" step="0.01" required>

        <label for="image">Image (JPG, PNG, GIF)</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif">

        <button type="submit">Add Vehicle</button>
    </form>
</body>
</html>
