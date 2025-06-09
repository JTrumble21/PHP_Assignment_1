<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$user_name = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

require_once('database.php'); 

$query = 'SELECT password FROM users WHERE username = :username';
$statement = $db->prepare($query);
$statement->bindValue(':username', $user_name);
$statement->execute();

$row = $statement->fetch();
$statement->closeCursor();

if ($row) {
   $hash = $row['password'];

    if (password_verify($password, $hash)) {
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["username"] = $user_name;

        header("Location: login_confirmation.php");
        exit;
    }
}

// If no user found or password incorrect:
session_unset();
session_destroy();
header("Location: login_form.php");
exit;
?>
