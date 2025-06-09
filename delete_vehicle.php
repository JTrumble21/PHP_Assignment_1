<?php
require 'database.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Missing ID.");

$stmt = $db->prepare("SELECT image_path FROM cars WHERE id = :id");
$stmt->execute([':id' => $id]);
$car = $stmt->fetch();

if ($car && !empty($car['image_path']) && file_exists($car['image_path'])) {
    unlink($car['image_path']);
}

$stmt = $db->prepare("DELETE FROM cars WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php");
exit;
