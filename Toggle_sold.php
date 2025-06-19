<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $carId = (int) $_POST['car_id'];
    $action = $_POST['action'] ?? '';

    $soldCars = file_exists('sold_vehicles.php') ? include 'sold_vehicles.php' : [];

    if ($action === 'mark') {
        if (!in_array($carId, $soldCars)) {
            $soldCars[] = $carId;
        }
    } elseif ($action === 'unmark') {
        $soldCars = array_filter($soldCars, fn($id) => $id !== $carId);
    }

    file_put_contents('sold_vehicles.php', "<?php\nreturn " . var_export(array_values($soldCars), true) . ";\n");

    header("Location: index.php");
    exit();
}

header("Location: index.php");
exit();
