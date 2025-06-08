<?php
session_start();

require_once('database.php');

// get data from form
$user_name = filter_input(INPUT_POST, 'user_name');
$password = filter_input(INPUT_POST, 'password');

$query = 'SELECT password FROM users WHERE userName = :userName';
$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->execute();
$row = $statement->fetch();
$statement->closeCursor();

if (!$row) {
    $_SESSION['login_error'] = "Invalid username or password.";
    header("Location: login_form.php");
    exit();
}

$hash = $row['password'];
$is_valid = password_verify($password, $hash);

if ($is_valid) {
    $_SESSION["isLoggedIn"] = true;
    $_SESSION["userName"] = $user_name;
    header("Location: login_confirmation.php");
    exit();
} else {
    $_SESSION['login_error'] = "Invalid username or password.";
    header("Location: login_form.php");
    exit();
}
?>
