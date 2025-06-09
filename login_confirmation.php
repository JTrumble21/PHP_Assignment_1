<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Success</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <main>
        <h2>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?>!</h2>
        <p><a href="index.php">Go to Inventory</a></p>
    </main>
</body>
</html>
