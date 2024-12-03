<?php
session_start();
include 'koneksi.php';

// Retrieve current username from session
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['new_username'];
    $sandi = $_POST['sandi'];

    $query = "SELECT sandi FROM tb_users WHERE username = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if ($sandi === $stored_password) { 
        $update_query = "UPDATE tb_users SET username = ? WHERE username = ?";
        $stmt = $koneksi->prepare($update_query);
        $stmt->bind_param("ss", $new_username, $username);
        
        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username;
            echo "Username berhasil diperbarui!";
        } else {
            echo "Gagal memperbarui username.";
        }
        $stmt->close();
    } else {
        echo "Sandi yang Anda masukkan salah.";
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

</head>
<body>
    <header>
        <bar-ganti-app></bar-ganti-app>
    </header>

    <main>
    <ind-loading-main></ind-loading-main>
        <h2 tabindex="0">Ganti Username</h2>
        <form method="POST" action="change_username.php">
            <label tabindex="0" for="new_username">Username Baru:</label>
            <input tabindex="0" type="text" id="new_username" name="new_username" placeholder="Masukkan Username Baru" required>

            <label tabindex="0" for="sandi">Sandi Saat Ini:</label>
            <input tabindex="0" type="password" id="sandi" name="sandi" placeholder="Sandi Username Baru" required>

            <button tabindex="0" type="submit">Ganti Username</button>
        </form>
    </main>
</body>
</html>
