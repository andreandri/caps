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
    <script>
        function togglePasswordVisibility(id, iconId) {
            const passwordInput = document.getElementById(id);
            const toggleIcon = document.getElementById(iconId);

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
    <header>
        <bar-ganti-app></bar-ganti-app>
    </header>
    <main>
        <ind-loading-main></ind-loading-main>
        <section class="reset-password">
            <h2 tabindex="0">Ganti Password</h2>
            <form tabindex="0" action="reset_password.php" method="post">
            <?php
            if ($message) {
                echo "<div class='message'>$message</div>";
            }
            ?>
                <label tabindex="0" for="old_password">Password Lama</label>
                <div class="input-box">
                    <input tabindex="0" type="password" id="old_password" name="old_password" placeholder="Password Lama" required>
                    <span tabindex="0" class="toggle-password" onclick="togglePasswordVisibility('old_password', 'toggle-icon-old')">
                        <img id="toggle-icon-old" src="img/tutup.png" alt="Hide Password" />
                    </span>
                </div>

                <label tabindex="0" for="new_password">Password Baru</label>
                <div class="input-box">
                    <input tabindex="0" type="password" id="new_password" name="new_password" placeholder="Password Baru" minlength="8" required>
                    <span tabindex="0" class="toggle-password" onclick="togglePasswordVisibility('new_password', 'toggle-icon-new')">
                        <img id="toggle-icon-new" src="img/tutup.png" alt="Hide Password" />
                    </span>
                </div>

                <button tabindex="0" type="submit" class="btn">Ganti Password</button>
            </form>
        </section>
    </main>
</body>
</html>

