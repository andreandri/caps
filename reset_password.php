<?php
session_start();
include 'koneksi.php';

// Ambil data pengguna dari session
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Pesan sukses atau error
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Query untuk mencari pengguna berdasarkan username
    $sql = "SELECT * FROM tb_users WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Verifikasi password lama
        if ($old_password === $row['sandi']) { // Ganti dengan sandi_verify jika password di-hash
            // Update password baru
            $update_query = "UPDATE tb_users SET sandi = ? WHERE username = ?";
            $stmt_update = $koneksi->prepare($update_query);
            $stmt_update->bind_param("ss", $new_password, $username);
            $stmt_update->execute();

            $message = "Password berhasil diperbarui!";
        } else {
            $message = "Password lama tidak cocok!";
        }
    } else {
        $message = "Pengguna tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="change_username.css">
    <script type="module" src="scripts/index.js"></script>
</head>
<body>
    <header>
        <bar-app></bar-app>
    </header>

    <section class="Kembali">
      <a href="profile.php">Kembali</a>
    </section>

    <main>
        <section class="reset-password">
            <h2>Ganti Password</h2>
            <form action="reset_password.php" method="post">

                <label for="old_password">Password Lama</label>
                <input type="password" id="old_password" name="old_password" placeholder="Password Lama" required>

                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" placeholder="Password Baru" required>
                
            <button type="submit" class="btn">Ganti Password</button>
        </form>
        <?php
        if ($message) {
            echo "<div class='message'>$message</div>";
        }
        ?>
        </section>
    </main>
</body>
</html>
