<?php
include 'koneksi.php'; // Sertakan file koneksi database

$success_message = ""; // Variabel untuk menampung pesan sukses

if (isset($_POST['register'])) {
    // Tangkap nilai dari input username, email, dan password yang dikirimkan melalui POST
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Lindungi dari serangan SQL Injection dengan menggunakan prepared statement
    // Periksa apakah email sudah terdaftar
    $check_email_query = "SELECT * FROM users WHERE email=?";
    $check_email_stmt = $koneksi->prepare($check_email_query);
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();

    if ($check_email_result->num_rows > 0) {
        // Jika email sudah terdaftar, berikan notifikasi
        $error_message = "Email sudah terdaftar.";
    } else {
        // Periksa apakah username sudah digunakan
        $check_username_query = "SELECT * FROM users WHERE username=?";
        $check_username_stmt = $koneksi->prepare($check_username_query);
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_result = $check_username_stmt->get_result();

        if ($check_username_result->num_rows > 0) {
            // Jika username sudah digunakan, berikan notifikasi
            $error_message = "Username telah digunakan.";
        } else {
            // Jika tidak ada masalah, lakukan penyisipan data pengguna ke dalam database
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $insert_stmt = $koneksi->prepare($insert_query);
            $insert_stmt->bind_param("sss", $username, $email, $password);
            $insert_stmt->execute();

            // Set pesan sukses
            $success_message = "Registrasi berhasil! Anda akan diarahkan ke halaman login.";
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
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="script.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <form action="register.php" method="post">
            <h1>Register</h1>
            <?php if (isset($error_message)) { ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php } ?>
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn" name="register">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>        
    </div>

    <!-- Cek apakah ada pesan sukses -->
    <?php if ($success_message) { ?>
        <script>
            // Tampilkan pop-up pesan sukses
            alert("<?php echo $success_message; ?>");
            // Redirect ke halaman login setelah pop-up ditutup
            window.location.href = "login.php";
        </script>
    <?php } ?>
</body>
</html>
