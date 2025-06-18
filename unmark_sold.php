<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $carId = (int) $_POST['car_id'];

    $soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];

    $soldCars = array_filter($soldCars, fn($id) => $id !== $carId);

    file_put_contents('sold_vehicles.php', "<?php\nreturn " . var_export(array_values($soldCars), true) . ";\n");

    header("Location: edit_vehicle.php?id=$carId");
    exit();
}

header("Location: index.php");
exit();
