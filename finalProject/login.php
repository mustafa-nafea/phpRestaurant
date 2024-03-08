<?php require_once 'auth.php'?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CAT Restaurant</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/authstyle.css">

    </head>
    <body>
        
        <div class="form-container">
            <div id="login-page">
                <form action="login.php" method="post">
                    <h3>Login</h3>
                    <?php if (!empty($errors)): ?>
                        <?php foreach ($errors as $error): ?>
                            <?php echo '<span class="error-msg">'.$error.'</span>';?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <input type="text" name="username_email" required placeholder="enter your Username or Email">
                    <input type="password" name="password" required placeholder="enter your password">
                    <input type="submit" name="login-btn" value="login now" class="form-btn">
                    <p>don't have an account? <a href="register.php">register now</a></p>
                </form>

            </div>
        </div>

    </body>
</html>