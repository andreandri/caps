<?php
session_start();
include 'koneksi.php';

$username = $_SESSION['username'];
$email = $_SESSION['email'];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $sql = "SELECT * FROM tb_users WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        if ($old_password === $row['sandi']) {
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
    <link rel="icon" href="favicon.png" type="image/png">
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
            <h2 tabindex="0">Ganti Password</h2>
            <form tabindex="0" action="reset_password.php" method="post">

                <label tabindex="0" for="old_password">Password Lama</label>
                <input tabindex="0" type="password" id="old_password" name="old_password" placeholder="Password Lama" required>

                <label tabindex="0" for="new_password">Password Baru</label>
                <input tabindex="0" type="password" id="new_password" name="new_password" placeholder="Password Baru" required>
                
            <button tabindex="0" type="submit" class="btn">Ganti Password</button>
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
