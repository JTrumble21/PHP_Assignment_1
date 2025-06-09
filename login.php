<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$user_name = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

require_once('database.php'); 

echo "Username received: " . htmlspecialchars($username) . "<br>";
echo "Password received: " . htmlspecialchars($password) . "<br>";

// Look up the user
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
?>
