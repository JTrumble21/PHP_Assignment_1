<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS car_inventory_manager");

    // Switch to the new DB
    $pdo->exec("USE car_inventory_manager");

    // Create cars table
    $sql = "CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        year INT NOT NULL,
        make VARCHAR(50) NOT NULL,
        model VARCHAR(50) NOT NULL,
        trim VARCHAR(50),
        color VARCHAR(30),
        price INT NOT NULL,
    )";
$pdo->exec("
    INSERT INTO cars (year, make, model, trim, color) VALUES
    (2021, 'Toyota', 'Camry', 'SE', 'Blue', '22,000'),
    (2020, 'Honda', 'Civic', 'EX', 'Red', '19,000'),
    (2019, 'Ford', 'Escape', 'Titanium', 'White' , '21,000'),
    (2022, 'Chevrolet', 'Malibu', 'LT', 'Black' , '34,000'),
    (2023, 'Tesla', 'Model 3', 'Performance', 'Silver' , '65,000');
  "); 

    $pdo->exec($sql);
    echo "Database and table created successfully.";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>
