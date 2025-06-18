<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $carId = (int) $_POST['car_id'];

    $soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];

    if (($key = array_search($carId, $soldCars)) !== false) {
        unset($soldCars[$key]);
        // Reindex array
        $soldCars = array_values($soldCars);
        file_put_contents('sold_vehicles.php', "<?php\nreturn " . var_export($soldCars, true) . ";\n");
    }

    header("Location: edit_vehicle.php?id=$carId");
    exit();
}
?>
