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
<body class="login_confirmation-page">
    <main>
        <h2>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h2>
        <p><a href="index.php">Go to Inventory</a></p>
        <p><a href="logout.php">Logout</a></p>
    </main>
</body>
</html>
