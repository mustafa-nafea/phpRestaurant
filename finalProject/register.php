<?php require_once 'auth.php'?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAT NOTES</title>
    <link rel="stylesheet" href="css/authstyle.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">


    </head>
    <body>
    
        <div class="form-container">
            <div id="signup-page">
                <form action="register.php" method="post">
                    <h3>register </h3>
                    <?php if (!empty($errors)): ?>
                            <?php foreach ($errors as $error): ?>
                                <?php echo '<span class="error-msg">'.$error.'</span>';?>
                            <?php endforeach; ?>
                    <?php endif; ?>
                    <input type="email" name="email" value="<?php echo $email ?>" placeholder="enter your email" required>
                    <input type="text" name="new-username" value="<?php echo $username ?>" placeholder="enter your Username" required>
                    <input type="password" name="new-password" placeholder="enter your password" required>
                    <input type="password" name="confirm-password" placeholder="confirm your password" required>
                    <input type="submit" name="signup-btn" value="register now" class="form-btn">
                    <p>already have an account? <a href="login.php">login now</a></p>
                </form>

            </div>
        </div>

    </body>
</html>