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
        <form action="fungsi/login_check.php" method="post">
            <h1>Login</h1>
            <?php
            if (isset($_SESSION['loginMessage'])) {
                echo $_SESSION['loginMessage'];
                unset($_SESSION['loginMessage']); // Hapus pesan setelah ditampilkan
            }
            ?>
            <div class="input-box">
                <label for="username">username or email</label>
                <input type="text" id="username" name="username_or_email" placeholder="username or email" required>
            </div>
            <div class="input-box">
                <label for="sandi">sandi</label>
                <input type="sandi" id="sandi" name="sandi" placeholder="sandi" required>
            </div>
            <button type="submit" class="btn" name="login">Login</button>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>
