<?php
session_start();
include 'koneksi.php';

// Ambil data pengguna dari session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">

    <script type="module" src="scripts/index.js"></script>
</head>
<body>
    <header>
        <bar-app></bar-app>
    </header>
    <main>
        <section class="profile">
            <h2>Informasi Pribadi</h2>
            <div class="avatar"></div>
            <p>username: <?php echo htmlspecialchars($username); ?> <a href="#">&#x270E;</a></p>
            <p>email: <?php echo htmlspecialchars($email); ?> <a href="#">&#x270E;</a></p>
        </section>
        
        <section class="security">
            <h3>Keamanan</h3>
            <p><a href="reset_password.php">&#x1F512; Ganti Password</a></p>
        </section>
        
        <section class="support">
            <h3>Bantuan</h3>
            <p><a href="#">&#x1F4AC; Pertanyaan</a></p>
            <p><a href="#">&#x1F4D3; Kebijakan Privasi</a></p>
        </section>

        <button class="logout" onclick="window.location.href='fungsi/logout.php'">Logout</button>
    </main>
</body>
</html>
