<?php
$dsn = 'mysql:host=localhost;dbname=car_inventory_manager;charset=utf8mb4';
$username = 'admin';
$password = 'inventory';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    session_start();
    $_SESSION["database_error"] = $e->getMessage();
    header("Location: database_error.php");
    exit();
}
?>
