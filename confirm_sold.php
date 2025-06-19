<?php
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id || !is_numeric($id) || !in_array($action, ['mark', 'unmark'])) {
    echo "Invalid request.";
    exit;
}

// Set the confirmation message
$confirmationText = $action === 'mark' ? 'Mark this vehicle as SOLD?' : 'Unmark this vehicle as SOLD?';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm <?= ucfirst($action) ?> as Sold</title>
</head>
<body>
    <h2>Confirm Action</h2>
    <p><?= $confirmationText ?></p>

    <form action="toggle_sold.php" method="post">
        <input type="hidden" name="car_id" value="<?= htmlspecialchars($id) ?>">
        <input type="hidden" name="action" value="<?= htmlspecialchars($action) ?>">
        <button type="submit">Yes</button>
        <a href="edit_vehicle.php?id=<?= $id ?>">Cancel</a>
    </form>
</body>
</html>
