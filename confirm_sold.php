<?php
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id || !is_numeric($id) || !in_array($action, ['mark', 'unmark'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirm <?= $action === 'mark' ? 'Mark as Sold' : 'Unmark as Sold' ?></title>
</head>
<body>
    <h2>Confirm Action</h2>
    <p>Are you sure you want to <?= $action === 'mark' ? '<strong>MARK</strong>' : '<strong>UNMARK</strong>' ?> this vehicle as sold?</p>
    <form action="toggle_sold.php" method="post">
        <input type="hidden" name="car_id" value="<?= htmlspecialchars($id) ?>">
        <input type="hidden" name="action" value="<?= $action ?>">
        <button type="submit">Yes, <?= $action === 'mark' ? 'Mark as Sold' : 'Unmark as Sold' ?></button>
    </form>
    <p><a href="edit_vehicle.php?id=<?= $id ?>">Cancel</a></p>
</body>
</html>
