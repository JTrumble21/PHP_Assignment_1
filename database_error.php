<?php
session_start();
$error = $_SESSION["database_error"] ?? "Unknown error.";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Database Error</title>
</head>
<body>
    <h1>Database Connection Error</h1>
    <p><?= htmlspecialchars($error) ?></p>
</body>
</html>
