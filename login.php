<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: inventory.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please enter both username and password.';
    } else {
        
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
           
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: inventory.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - Car Inventory Manager</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="login-container">
    <h2>Login</h2>

    <?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

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
