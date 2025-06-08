<?php
session_start();

// Redirect to login form if not logged in
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Inventory Manager - Login Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Login Confirmation</h2>
        <p>
            Thank you, <?php echo htmlspecialchars($_SESSION["userName"]); ?>, for logging in.
        </p>

        <p>You are logged in and may proceed to the inventory list by clicking below.</p>
        
        <p><a href="index.php">Car Inventory</a></p>
        <p><a href="logout.php">Logout</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
