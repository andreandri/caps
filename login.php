<?php
// Sertakan file koneksi database
include 'koneksi.php';

// Tangkap data dari form login jika form telah disubmit
if (isset($_POST['login'])) {
    // Tangkap nilai dari input username atau email dan password yang dikirimkan melalui POST
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Lindungi dari serangan SQL Injection dengan menggunakan prepared statement
    // Ubah query untuk mencocokkan baik username maupun email
    $query = "SELECT * FROM users WHERE (username=? OR email=?) AND password=?";
    $stmt = $koneksi->prepare($query);

    // Bind parameter ke statement
    $stmt->bind_param("sss", $username_or_email, $username_or_email, $password);

    // Eksekusi statement
    $stmt->execute();

    // Ambil hasil dari eksekusi statement
    $result = $stmt->get_result();

    // Periksa apakah data ditemukan berdasarkan username atau email dan password
    if ($result->num_rows > 0) {
        // Jika data ditemukan, set session untuk menandai bahwa pengguna sudah login
        session_start();
        // Ambil username dari hasil query
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];

        // Redirect ke halaman selamat datang setelah login berhasil
        header("Location: tampilan.php");
        exit; // Penting untuk menghentikan eksekusi setelah melakukan redirect
    } else {
        // Jika data tidak ditemukan, set pesan kesalahan dalam session
        session_start();
        $_SESSION['error_message'] = "Username atau email atau password salah.";
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi database
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="script.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <form action="login.php" method="post">
            <h1>Login</h1>  
            <?php
            // Memeriksa apakah pesan error ditemukan dalam session
            if (isset($_SESSION['error_message'])) {
                // Tampilkan pesan error dan hapus dari session
                echo "<div class='error'>" . $_SESSION['error_message'] . "</div>";
                unset($_SESSION['error_message']);
            }
            ?>
            <div class="input-box">
                <label for="username"><i class='bx bxs-user'></i></label>
                <input type="text" id="username" name="username_or_email" placeholder="Username or Email" required>
            </div>
            <div class="input-box">
                <label for="password"><i class='bx bxs-lock-alt'></i></label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn" name="login">Login</button>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>
