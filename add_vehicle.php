<?php
session_start();
require_once('database.php');
require_once('image_util.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Directory to store images (make sure this folder exists & writable)
    $upload_dir = 'assets/images/';  // <== Added trailing slash here

    // File info
    $file_tmp = $_FILES['car_image']['tmp_name'];
    $file_name = basename($_FILES['car_image']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

 
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_ext, $allowed_exts)) {
        die("Invalid file type. Only JPG, PNG, GIF files are allowed.");
    }

   
    $unique_name = uniqid('car_') . '.' . $file_ext;
    $upload_path = $upload_dir . $unique_name;  

    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Process images: create resized versions
        process_image($upload_dir, $unique_name);

        // Save thumbnail (_100) path in DB (relative path)
        $image_name_only = pathinfo($unique_name, PATHINFO_FILENAME);
        $thumbnail_path = $upload_dir . '/' . $image_name_only . '_100.' . $file_ext;

        // Collect & validate inputs
        $make = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_STRING);
        $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_STRING);
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);

        if (!$make || !$model || !$year) {
            die("Make, model, and year are required and must be valid.");
        }

        $query = "INSERT INTO vehicles (make, model, year, image_path) VALUES (:make, :model, :year, :image_path)";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':make', $make);
        $stmt->bindValue(':model', $model);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':image_path', $thumbnail_path);
        $stmt->execute();

        $_SESSION['message'] = "Vehicle added successfully!";
        header('Location: index.php');
        exit();
    } else {
        die("Failed to upload image.");
    }
}
?>
