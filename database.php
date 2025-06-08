<?php
$dsn = 'mysql:host=localhost;dbname=car_inventory_manager';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $_SESSION["database_error"] = $e->getMessage();
    header("Location: database_error.php");
    exit();
}
?>
