<?php
session_start();
include 'koneksi.php';

// Retrieve current username from session
$username = $_SESSION['username'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['new_username'];
    $sandi = $_POST['sandi'];

    // Periksa sandi pengguna
    $query = "SELECT sandi FROM tb_users WHERE username = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if ($sandi === $stored_password) {
        // Update username
        $update_query = "UPDATE tb_users SET username = ? WHERE username = ?";
        $stmt = $koneksi->prepare($update_query);
        $stmt->bind_param("ss", $new_username, $username);

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username;
            $message = "Username berhasil diperbarui!";
        } else {
            $message = "Gagal memperbarui username.";
        }
        $stmt->close();
    } else {
        $message = "Sandi yang Anda masukkan salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Username</title>
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="change_username.css">
    <script type="module" src="scripts/index.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('sandi');
            const toggleIcon = document.getElementById('toggle-password-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.src = 'img/buka.png'; // Ganti dengan ikon "lihat"
                toggleIcon.alt = 'Sembunyikan Sandi';
            } else {
                passwordInput.type = 'password';
                toggleIcon.src = 'img/tutup.png'; // Ganti dengan ikon "tutup"
                toggleIcon.alt = 'Lihat Sandi';
            }
        }
    </script>
</head>
<body>
    <header>
        <bar-ganti-app></bar-ganti-app>
    </header>

    <main>
        <ind-loading-main></ind-loading-main>
        <h2 tabindex="0">Ganti Username</h2>
        <?php
        if ($message) {
            echo "<div class='message'>$message</div>";
        }
        ?>
        <form method="POST" action="change_username.php">
            <label tabindex="0" for="new_username">Username Baru:</label>
            <div class="input-box">
                <input tabindex="0" type="text" id="new_username" name="new_username" placeholder="Masukkan Username Baru" required>
            </div>
            <label tabindex="0" for="sandi">Sandi Saat Ini:</label>
            <div class="input-box">
                <input tabindex="0" type="password" id="sandi" name="sandi" placeholder="Sandi Username Baru" required>
                <span tabindex="0" class="toggle-password" onclick="togglePasswordVisibility()">
                    <img id="toggle-password-icon" src="img/tutup.png" alt="Lihat Sandi">
                </span>
            </div>

            <button tabindex="0" type="submit">Ganti Username</button>
        </form>
    </main>
</body>
</html>
