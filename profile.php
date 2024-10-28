<?php
// Mulai sesi di awal skrip
session_start();

// Sertakan file koneksi database
include 'koneksi.php';

// Tangkap data dari form login jika form telah disubmit
if (isset($_POST['login'])) {
    // Tangkap nilai dari input username/email dan password yang dikirim melalui POST
    $username_or_email = isset($_POST['username_or_email']) ? $_POST['username_or_email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Periksa apakah input username/email dan password tidak kosong
    if (!empty($username_or_email) && !empty($password)) {
        // Query untuk mencocokkan baik username maupun email menggunakan prepared statement
        $query = "SELECT * FROM users WHERE (username=? OR email=?)";
        $stmt = $koneksi->prepare($query);

        // Bind parameter ke statement
        $stmt->bind_param("ss", $username_or_email, $username_or_email);

        // Eksekusi statement
        $stmt->execute();

        // Ambil hasil dari eksekusi statement
        $result = $stmt->get_result();

        // Periksa apakah data ditemukan berdasarkan username atau email
        if ($result->num_rows > 0) {
            // Ambil data pengguna
            $row = $result->fetch_assoc();

            // Verifikasi password dengan `password_verify()`
            if (password_verify($password, $row['password'])) {
                // Jika password benar, set session untuk menandai bahwa pengguna sudah login
                $_SESSION['username'] = $row['username'];

                // Redirect ke halaman jadwal setelah login berhasil
                header("Location: jadwal.php");
                exit;
            } else {
                // Jika password salah, set pesan kesalahan dalam session
                $_SESSION['error_message'] = "Password salah.";
            }
        } else {
            // Jika data tidak ditemukan, set pesan kesalahan dalam session
            $_SESSION['error_message'] = "Username atau email tidak ditemukan.";
        }

        // Tutup statement
        $stmt->close();
    } else {
        // Set pesan kesalahan jika username/email atau password kosong
        $_SESSION['error_message'] = "Username/email dan password harus diisi.";
    }
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
    <link rel="stylesheet" href="styles.css">
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
