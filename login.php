<?php
    session_start();

    // get data from the form
    $user_name = filter_input(INPUT_POST, 'user_name');    
    $password = filter_input(INPUT_POST, 'password');

    require_once('database.php');

    $query = 'SELECT password FROM users
                WHERE userName = :userName';
    $statement1 = $db->prepare($query);

    $statement1->bindValue(':userName', $user_name);

    $statement1->execute();
    $row = $statement1->fetch();    

    $statement1->closeCursor();

    if (!$row) 
    {
        $_SESSION = [];
        session_destroy();

      $url = "/PHP_Assignment_1/login_confirmation.php";
      header("Location: " . $url);
      die();
    }

    $hash = $row['password'];

    $_SESSION["isLoggedIn"] = password_verify($password, $hash);

    if ($_SESSION["isLoggedIn"] == TRUE)
    {
        $_SESSION["userName"] = $user_name;

      $url = "/PHP_Assignment_1/login_confirmation.php";
      header("Location: " . $url);
      die();
    }
    elseif ($_SESSION["isLoggedIn"] == FALSE)
    {
        $_SESSION = [];
        session_destroy();

      $url = "/PHP_Assignment_1/login_form.php";
      header("Location: " . $url);
      die();
    }
?>