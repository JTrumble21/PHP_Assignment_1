<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $carId = (int) $_POST['car_id'];

    $soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];

    if (!in_array($carId, $soldCars)) {
        $soldCars[] = $carId;
        file_put_contents('sold_vehicles.php', "<?php\nreturn " . var_export($soldCars, true) . ";\n");
    }

    header("Location: index.php");
    exit();
}

header("Location: index.php");
exit();
