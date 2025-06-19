<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['action'])) {
    $carId = (int) $_POST['car_id'];
    $action = $_POST['action'];
    
    if (!in_array($action, ['mark', 'unmark'])) {
        header('Location: index.php');
        exit;
    }

    $file = 'sold_vehicles.php';
    $soldCars = file_exists($file) ? include $file : [];

    if ($action === 'mark') {
        if (!in_array($carId, $soldCars)) {
            $soldCars[] = $carId;
        }
    } elseif ($action === 'unmark') {
        $soldCars = array_filter($soldCars, fn($id) => $id !== $carId);
        $soldCars = array_values($soldCars); // Reindex
    }

    file_put_contents($file, "<?php\nreturn " . var_export($soldCars, true) . ";\n");

    header("Location: index.php");
    exit;
}

header("Location: index.php");
exit;
