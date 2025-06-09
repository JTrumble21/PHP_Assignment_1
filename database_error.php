<?php
session_start();
$error = $_SESSION["database_error"] ?? "Unknown error.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Error</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <main>
        <h1>Database Connection Error</h1>
        <p><?= htmlspecialchars($error) ?></p>
    </main>
</body>
</html>
