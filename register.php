<?php
session_start();
include("koneksi.php");

$success_message = "";

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $sandi = $_POST['sandi'];
  $role = 'user';

  // Validasi panjang sandi di sisi server
  if (strlen($sandi) < 8) {
    $error_message = "Password harus minimal 8 karakter.";
  } else {
    // Cek apakah email sudah terdaftar
    $check_email_query = "SELECT * FROM tb_users WHERE email = ?";
    $stmt_email = $koneksi->prepare($check_email_query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();

    if ($result_email->num_rows > 0) {
      $error_message = "Email sudah terdaftar.";
    } else {
      // Cek apakah username sudah digunakan
      $check_username_query = "SELECT * FROM tb_users WHERE username = ?";
      $stmt_username = $koneksi->prepare($check_username_query);
      $stmt_username->bind_param("s", $username);
      $stmt_username->execute();
      $result_username = $stmt_username->get_result();

      if ($result_username->num_rows > 0) {
        $error_message = "Username telah digunakan.";
      } else {
        // Menyimpan data pengguna baru ke database
        $insert_query = "INSERT INTO tb_users (username, email, sandi, role) VALUES (?, ?, ?, ?)";
        $stmt_insert = $koneksi->prepare($insert_query);
        $stmt_insert->bind_param("ssss", $username, $email, $sandi, $role);
        $stmt_insert->execute();

        $success_message = "Registrasi berhasil! Anda akan diarahkan ke halaman login.";
      }
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Form</title>
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
  </script>
</head>

<body>
  <ind-loading-main></ind-loading-main>
  <div class="wrapper">
    <h1 tabindex="0">Create your account</h1>
    <h2 tabindex="0">Start your journey with us</h2>
    <div class="image">
      <img tabindex="0" src="img/EasyBusTix.png" alt="logo aplikasi">
    </div>
    <form tabindex="0" action="register.php" method="post">
      <?php
      if (isset($error_message)) {
        echo "<div class='error'>$error_message</div>";
      }
      ?>
      <div class="input-box">
        <input tabindex="0" type="text" id="username" name="username" placeholder="Enter your username" required>
      </div>
      <div class="input-box">
        <input tabindex="0" type="email" id="email" name="email" placeholder="Enter your email address" required>
      </div>
      <div class="input-box">
        <input tabindex="0" type="password" id="sandi" name="sandi" placeholder="Enter your password" minlength="8" required>
        <span tabindex="0" class="toggle-password" onclick="togglePasswordVisibility()">
          <img id="toggle-icon" src="img/tutup.png" alt="Hide Password" />
        </span>
      </div>
      <button tabindex="0" type="submit" class="btn" name="register">Register</button>
    </form>
    <div class="register-link">
      <p tabindex="0">Already have an account? <a tabindex="0" href="login.php">Login</a></p>
    </div>
  </div>

  <?php if ($success_message) { ?>
    <script>
      alert("<?php echo $success_message; ?>");
      window.location.href = "login.php";
    </script>
  <?php } ?>
</body>

</html>