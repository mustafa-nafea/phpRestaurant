<?php
    require('config/config.php');
    require('config/db.php');
    session_start();
    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION['user_id'])) {
        header('location:login.php');
    }

    if (isset($_GET['logout'])) {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['user_type']);
        session_destroy();
        header('location:login.php');
        exit;
    }


    if (isset($_POST['add_to_cart'])) {
        $item_name = $_POST['item_name'];
        $item_price = $_POST['item_price'];
        $item_image = $_POST['item_image'];
        $item_quantity = $_POST['item_quantity'];

        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$item_name' AND user_id = '$user_id'");

        if (mysqli_num_rows($select_cart) > 0) {
            $message[] = 'item already added to cart!';
        } else {
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$item_name', '$item_price', '$item_image', '$item_quantity')");
            $message[] = 'item added to cart!';
        }
    }

    if (isset($_POST['update_cart'])) {
        $update_quantity = $_POST['cart_quantity'];
        $update_id = $_POST['cart_id'];
        mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'");
        $message[] = 'cart quantity updated successfully!';
    }

    if (isset($_GET['remove'])) {
        $remove_id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
        header('location:dashboard.php');
    }

    if (isset($_GET['delete_all'])) {
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
        header('location:dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CAT Restaurant</title>
        <link rel="stylesheet" href="css/dashboard.css">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message" onclick="this.remove();">' . $msg . '</div>';
            }
        }
        ?>

        <div class="container">

            <div class="user-profile">

                <?php
                    $fetch_user = array();
                    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'");
                    if(mysqli_num_rows($select_user) > 0){
                        $fetch_user = mysqli_fetch_assoc($select_user);
                    };
                ?>

                <p> Hello <span><?php echo $fetch_user['username']; ?></span> </p>
                <div class="flex">
                    <a href="cart.php" class="btn">Show Cart</a>
                    <a href="dashboard.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a>
                </div>

            </div>

            <div class="products">

                <h1 class="heading">Restaurant items</h1>

                <div class="box-container">

                <?php
                    $select_item = mysqli_query($conn, "SELECT * FROM `item`");
                    if(mysqli_num_rows($select_item) > 0){
                        while($fetch_item = mysqli_fetch_assoc($select_item)){
                ?>
                    <form method="post" class="box" action="dashboard.php">
                        <img src="images/<?php echo $fetch_item['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_item['name']; ?></div>
                        <div class="price">$<?php echo $fetch_item['price']; ?></div>
                        <input type="number" min="1" name="item_quantity" value="1">
                        <input type="hidden" name="item_image" value="<?php echo $fetch_item['image']; ?>">
                        <input type="hidden" name="item_name" value="<?php echo $fetch_item['name']; ?>">
                        <input type="hidden" name="item_price" value="<?php echo $fetch_item['price']; ?>">
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
                <?php
                    };
                };
                ?>

             </div>

            </div>

            
        </div>

    </body>
</html>