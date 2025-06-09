<?php
session_start();

$user_name = filter_input(INPUT_POST, 'username');    
$password = filter_input(INPUT_POST, 'password');

require_once('database.php'); 


$query = 'SELECT password_hash FROM users WHERE username = :username';
$statement = $pdo->prepare($query);
$statement->bindValue(':username', $user_name);
$statement->execute();

$row = $statement->fetch();
$statement->closeCursor();

if ($row) {
    $hash = $row['password_hash'];

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