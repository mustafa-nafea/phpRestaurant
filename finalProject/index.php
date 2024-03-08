<?php
    session_start();

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
            header('Location: dashboard.php');
            exit();       
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CAT Restaurant</title>
        <link rel="stylesheet" href="css/indexStyle.css">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <header>
            <h1>CAT Restaurant</h1>
        </header>

        <div class="container">
            <h2>Welcome to CAT Restaurant</h2>
            <p> Please <a href="login.php">login</a> or <a href="register.php">register</a> to CAT Restaurant to get started.</p>
        </div>

    </body>
</html>
