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
    
    <?php if (isset($_SESSION['login_error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['login_error']) ?></p>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="user_name">Username:</label>
        <input type="text" name="user_name" id="user_name" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>

