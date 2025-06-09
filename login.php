<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Debugging: Show POST data
echo '<pre>POST data received: ';
print_r($_POST);
echo '</pre>';

// Grab input values from POST
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

echo "Username variable: " . var_export($username, true) . "<br>";
echo "Password variable: " . var_export($password, true) . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('database.php');

    $query = 'SELECT password FROM users WHERE username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();

    $row = $statement->fetch();
    $statement->closeCursor();

    if ($row) {
        $hash = $row['password'];
        echo "Password hash from DB: $hash<br>";

        if (password_verify($password, $hash)) {
            echo "✅ Password verified successfully!<br>";

            $_SESSION["isLoggedIn"] = true;
            $_SESSION["username"] = $username;

            echo "Redirecting to login_confirmation.php...<br>";
            header("Location: login_confirmation.php");
            exit;
        } else {
            echo "❌ Password did NOT verify.<br>";
        }
    } else {
        echo "❌ No user found with that username.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Car Inventory Manager</title>
    <link rel="stylesheet" href="css/main.css" />
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <form method="post" action="login.php" novalidate>
            <label for="username">Username:</label><br />
            <input type="text" id="username" name="username" required autofocus /><br />

            <label for="password">Password:</label><br />
            <input type="password" id="password" name="password" required /><br />

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
