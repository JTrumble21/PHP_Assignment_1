<?php
session_start();

require_once('database.php');  // Ensure this path is correct!

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $_SESSION['login_error'] = "Please enter both username and password.";
        header('Location: login_form.php');
        exit;
    }

    $query = 'SELECT password FROM users WHERE userName = :username';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['username'] = $username;
            header('Location: login_confirmation.php');
            exit;
        } else {
            $_SESSION['login_error'] = "Incorrect password.";
        }
    } else {
        $_SESSION['login_error'] = "No user found with that username.";
    }

    header('Location: login_form.php');
    exit;
} else {
    header('Location: login_form.php');
    exit;
}
