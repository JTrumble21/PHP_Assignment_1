<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

require_once('database.php');  // Make sure this sets $pdo!

echo "POST Data: " . print_r($_POST, true) . "<br>";
echo "Username variable: '" . htmlspecialchars($username) . "'<br>";
echo "Password variable: '" . htmlspecialchars($password) . "'<br>";

try {
    $query = 'SELECT password FROM users WHERE userName = :username';
    $statement = $pdo->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $hash = $row['password'];
        echo "Password hash from DB: $hash<br>";

        if (password_verify($password, $hash)) {
            echo "✅ Password verified successfully!<br>";

            $_SESSION["isLoggedIn"] = true;
            $_SESSION["username"] = $username;

            header("Location: login_confirmation.php");
            exit;
        } else {
            echo "❌ Password did NOT verify.<br>";
        }
    } else {
        echo "❌ No user found with that username.<br>";
    }
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
}
?>
