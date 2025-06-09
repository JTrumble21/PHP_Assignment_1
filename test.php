<?php
require_once('database.php');

$username = 'admin';

$query = 'SELECT password FROM users WHERE userName = :username';
$stmt = $pdo->prepare($query);
$stmt->bindValue(':username', $username);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User found: " . htmlspecialchars($username) . "<br>";
    echo "Password hash: " . htmlspecialchars($user['password']) . "<br>";
} else {
    echo "No user found with username: " . htmlspecialchars($username);
}
