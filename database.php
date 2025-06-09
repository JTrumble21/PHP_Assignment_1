<?php
$dsn = 'mysql:host=localhost;dbname=car_inventory_manager';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    session_start();
    $_SESSION["database_error"] = $e->getMessage();
    header("Location: database_error.php");
    exit();
}
?>
