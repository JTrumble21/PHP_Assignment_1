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

    // Drop cars table if it exists
    $pdo->exec("DROP TABLE IF EXISTS cars");

    // Create cars table with image_path column
    $sql = "CREATE TABLE cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        year INT NOT NULL,
        make VARCHAR(50) NOT NULL,
        model VARCHAR(50) NOT NULL,
        trim VARCHAR(50),
        color VARCHAR(30),
        price DECIMAL(10,2) NOT NULL,
        image_path VARCHAR(255) NULL
    )";
    $pdo->exec($sql);

    // Insert seed data with image paths pointing to assets/images
    $pdo->exec("
        INSERT INTO cars (year, make, model, trim, color, price, image_path) VALUES
        (2019, 'Ford', 'Escape', 'Titanium', 'White', 21000.00, 'assets/images/ford_escape.jpeg'),
        (2022, 'Chevrolet', 'Malibu', 'LT', 'Black', 34000.00, 'assets/images/chevy_malibu.jpeg'),
        (2023, 'Tesla', 'Model 3', 'Performance', 'Silver', 65000.00, 'assets/images/Tesla-Mmodel-3.jpg'),
        (2020, 'Chevrolet', 'Silverado', 'HD', 'White', 48000.00, 'assets/images/chevy_silverado.jpg'),
        (2024, 'Chevrolet', 'Onix', 'RS Hatchback', 'Red', 32000.00, 'assets/images/chevy_onix.jpg'),
        (2022, 'Dodge', 'Hornet', 'GT', 'Blue', 52000.00, 'assets/images/dodge_hornet.jpg'),
        (2024, 'Dodge', 'Charger', 'Daytona Scat Pack', 'Silver', 63000.00, 'assets/images/dodge_charger.jpg'),
        (2018, 'Honda', 'CRV', '', 'Silver', 30000.00, 'assets/images/honda_crv.jpg'),
        (2014, 'Dodge', 'Avenger', 'SXT', 'Silver', 10000.00, 'assets/images/dodge_avenger.jpeg'),
    ");

    echo "Database and table created successfully with image paths.";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>
