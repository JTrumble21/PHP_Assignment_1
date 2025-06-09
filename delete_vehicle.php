<?php
session_start();
require('database.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid ID.";
    exit();
}

$id = (int)$_GET['id'];


$query = "SELECT image_path FROM cars WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$vehicle = $stmt->fetch();
$stmt->closeCursor();

if ($vehicle && !empty($vehicle['image_path']) && file_exists($vehicle['image_path'])) {
    unlink($vehicle['image_path']);
}

// Delete vehicle
$deleteQuery = "DELETE FROM cars WHERE id = :id";
$deleteStmt = $db->prepare($deleteQuery);
$deleteStmt->bindValue(':id', $id, PDO::PARAM_INT);
$deleteStmt->execute();
$deleteStmt->closeCursor();

header("Location: index.php");
exit();
?>
