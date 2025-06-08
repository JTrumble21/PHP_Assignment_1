<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Car Inventory Manager - Login</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<div class="login-form">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
