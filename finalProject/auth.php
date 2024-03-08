<?php
    session_start();
    require('config/config.php');
    require('config/db.php');

    // check if the user is logged in 
    if (isset($_SESSION['user_id'])) {
        header('Location: dashboard.php');
        exit();
    }

    $errors = array();
    $username = "";
    $email = "";
    $password = "";
    $hashedPassword = "";

    // Check if the signup form submited of not
    if (isset($_POST['signup-btn'])) {
        // Dealing with the Username
        if (!empty($_POST['new-username'])) {
            $username = strip_tags(trim($_POST['new-username']));
            $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

            if (strlen($username) < 3 || strlen($username) > 20) {
                $errors['username'] = "Username is not valid!";
            } else {
                $query = "SELECT * FROM users WHERE username = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $errors['username'] = "Username already taken. Please choose another one.";
                }
            }
        } else {
            $errors['username'] = "Username is Required!";
        }

        // Dealing with the Email
        if (!empty($_POST['email'])) {
            $email = trim($_POST['email']);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $query = "SELECT * FROM users WHERE email = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $errors['email'] = "Email already taken. Please Enter another one.";
                }
            } else {
                $errors['email'] = "Email is not valid!";
            }
        } else {
            $errors['email'] = "Email is Required!";
        }

        // Dealing with the passwords
        if (!empty($_POST['new-password'])) {
            if (!empty($_POST['confirm-password'])) {
                $password = $_POST['new-password'];
                $passwordConf = $_POST['confirm-password'];
                if (strlen($password) < 8) {
                    $errors['password'] = "Password must be at least 8 characters long!";
                } elseif (($password !== $passwordConf)) {
                    $errors['password'] = "Passwords do not match. Please enter matching passwords.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                }
            } else {
                $errors['password'] = "Please Confirm Your password.!";
            }
        } else {
            $errors['password'] = "Password is Required!";
        }

        // Check if there are no errors and insert the data into the database
        if (empty($errors)) {
            // Use a prepared statement for the INSERT query
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);

            // Bind parameters and execute the statement
            $insert_stmt->bind_param("sss", $username, $email, $hashedPassword);
            $insert_stmt->execute();

            if ($insert_stmt->affected_rows > 0) {
                // redirect to login page or display a success message
                header("Location: login.php");
                exit();
            } else {
                $errors['db_error'] = "Error during user registration.";
            }

        }
    }

    // Check if the login form submitted or not
    if (isset($_POST['login-btn'])) {
        // Function to validate the email
        function isEmail($input) {
            return filter_var($input, FILTER_VALIDATE_EMAIL);
        }
        
        // Dealing with the Username
        if (!empty($_POST['username_email'])) {
            // check if input is email or username
            $usernameOrEmail = $_POST['username_email'];
            
            if (isEmail($usernameOrEmail)) {
                $email = trim($_POST['username_email']);
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            } else {
                $username = strip_tags(trim($_POST['username_email']));
                $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            }
        } else {
            $errors['username_email'] = "Username or Email are required !";
        }

        // Dealing with the password
        if (!empty($_POST['password'])) {
                $password = $_POST['password'];
        } else {
            $errors['password'] = "Password is Required !";
        }

        if (empty($errors)) {
            $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);

                // Verifing the password
                if (password_verify($password, $row['password'])) {
                    // check the type of the user 
                    if ($row['user_type'] = 'admin') {
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_type'] = $row['user_type'];
                        header('Location: dashboard.php');
                    }else{
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_type'] = $row['user_type'];
                        header('Location: dashboard.php');
                        exit();
                    }
                }else {
                    $errors['password'] = "Password is Invalid !";
                }
            }else {
                $errors['username_email'] = "Username or Email are not valid !";
            }

        }
    }

?>
