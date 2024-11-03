<?php
session_start();
include("koneksi.php");

$success_message = ""; // Variabel untuk pesan sukses

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sandi = $_POST['sandi'];
    $role = 'user';

    // Cek apakah email sudah terdaftar
    $check_email_query = "SELECT * FROM users WHERE Email = ?";
    $stmt_email = $koneksi->prepare($check_email_query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();

    if ($result_email->num_rows > 0) {
        $error_message = "Email sudah terdaftar.";
    } else {
        // Cek apakah username sudah digunakan
        $check_username_query = "SELECT * FROM users WHERE Username = ?";
        $stmt_username = $koneksi->prepare($check_username_query);
        $stmt_username->bind_param("s", $username);
        $stmt_username->execute();
        $result_username = $stmt_username->get_result();

        if ($result_username->num_rows > 0) {
            $error_message = "Username telah digunakan.";
        } else {
            // Menyimpan data pengguna baru ke database
            $insert_query = "INSERT INTO users (Username, Email, sandi, role) VALUES (?, ?, ?, ?)";
            $stmt_insert = $koneksi->prepare($insert_query);
            $stmt_insert->bind_param("ssss", $username, $email, $sandi, $role);
            $stmt_insert->execute();

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
</head>
<body>
    <div class="wrapper">
        <form action="register.php" method="post">
            <h1>Register</h1>
            <?php
            if (isset($error_message)) {
                echo "<div class='error'>$error_message</div>";
            }
            ?>
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <label for="sandi">sandi</label>
                <input type="sandi" id="sandi" name="sandi" placeholder="sandi" required>
            </div>
            <button type="submit" class="btn" name="register">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
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
