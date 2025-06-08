<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: /PHP_Assignment_1/login_form.php");
exit();
