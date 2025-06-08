<?php
$host = 'localhost';
$db   = 'car_inventory_manager';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $userName = 'admin';          // Change this username
    $plainPassword = 'inventory';  // Change this password

    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (userName, password) VALUES (:userName, :password)");
    $stmt->bindValue(':userName', $userName);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->execute();

    echo "User '$userName' added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
