<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

require_once('database.php'); 

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = 'SELECT password FROM users WHERE userName = :username';  // <-- here is the fix
$statement = $db->prepare($query);
$statement->bindValue(':username', $username);
$statement->execute();

$row = $statement->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $hash = $row['password'];

    if (password_verify($password, $hash)) {
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["username"] = $username;

        header("Location: login_confirmation.php");
        exit;
    } else {
        $_SESSION['login_error'] = 'Incorrect password.';
    }
} else {
    $_SESSION['login_error'] = 'No user found with that username.';
}

header("Location: login_form.php");
exit;
?>
