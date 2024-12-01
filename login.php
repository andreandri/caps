<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="wrapper">
        <h1 tabindex="0">Welcome Back</h1>
        <h2 tabindex="0">Log in now to continue</h2>
        <div class="image">
            <img tabindex="0" src="img/EasyBusTix.png" alt="illustration" />
        </div>
        <form tabindex="0" action="fungsi/login_check.php" method="post">
            <?php
            if (isset($_SESSION['loginMessage'])) {
                echo $_SESSION['loginMessage'];
                unset($_SESSION['loginMessage']);
            }
            ?>
            <div class="input-box">
                <input tabindex="0" type="text" id="username" name="username_or_email" placeholder="Enter your username or email " reuired>
            </div>
            <div class="input-box">
                <input tabindex="0" type="sandi" id="sandi" name="sandi" placeholder="Enter your password" required>
            </div>
            <button tabindex="0" type="submit" class="btn" name="login">Login</button>
            <div class="register-link">
                <p tabindex="0">Don't have an account? <a tabindex="0" href="register.php">Sign Up</a></p>
            </div>
        </form>
    </div>
</body>
</html>

