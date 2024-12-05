<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="icon" href="favicon.png" type="image/png">
  <link rel="stylesheet" href="login.css">
  <script type="module" src="scripts/index.js"></script>
  <script>
    function togglePasswordVisibility() {
      const passwordInput = document.getElementById('sandi');
      const toggleIcon = document.getElementById('toggle-icon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.src = 'img/buka.png';
        toggleIcon.alt = 'Show Password';
      } else {
        passwordInput.type = 'password';
        toggleIcon.src = 'img/tutup.png';
        toggleIcon.alt = 'Hide Password';
      }
    }

    function validateForm(event) {
      const passwordInput = document.getElementById('sandi');
      if (passwordInput.value.length < 8) {
        alert('Password must be at least 8 characters long.');
        event.preventDefault();
      }
    }
  </script>
</head>

<body>
  <ind-loading-main></ind-loading-main>
  <div class="wrapper">
    <h1 tabindex="0">Welcome</h1>
    <h2 tabindex="0">Log in now to continue</h2>
    <div class="image">
      <img tabindex="0" src="img/EasyBusTix.png" alt="illustration" />
    </div>
    <form tabindex="0" action="fungsi/login_check.php" method="post" onsubmit="validateForm(event)">
      <?php
      if (isset($_SESSION['loginMessage'])) {
        echo $_SESSION['loginMessage'];
        unset($_SESSION['loginMessage']);
      }
      ?>
      <div class="input-box">
        <input tabindex="0" type="text" id="username" name="username_or_email" placeholder="Enter your username or email" required>
      </div>
      <div class="input-box">
        <input tabindex="0" type="password" id="sandi" name="sandi" placeholder="Enter your password" minlength="8" required>
        <span tabindex="0" class="toggle-password" onclick="togglePasswordVisibility()">
          <img id="toggle-icon" src="img/tutup.png" alt="Hide Password" />
        </span>
      </div>
      <button tabindex="0" type="submit" class="btn" name="login">Login</button>
      <div class="register-link">
        <p tabindex="0">Don't have an account? <a tabindex="0" href="register.php">Sign Up</a></p>
      </div>
    </form>
  </div>
</body>
</html>