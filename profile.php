<?php
session_start();
include 'koneksi.php';

// Ambil data pengguna dari session
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Ambil data gambar dari database
$query = "SELECT image FROM tb_users WHERE username = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$image = $userData['image'] ?? 'default-avatar.png'; // Jika tidak ada, gunakan avatar default

// Proses unggah foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetFolder = "uploads/";
    $imageName = basename($_FILES['image']['name']);
    $targetPath = $targetFolder . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        // Update path gambar di database
        $updateQuery = "UPDATE tb_users SET image = ? WHERE username = ?";
        $updateStmt = $koneksi->prepare($updateQuery);
        $updateStmt->bind_param("ss", $imageName, $username);
        if ($updateStmt->execute()) {
            $image = $imageName; // Update gambar pada halaman
            $_SESSION['message'] = "Foto profil berhasil diubah.";
        } else {
            $_SESSION['error'] = "Gagal menyimpan ke database.";
        }
    } else {
        $_SESSION['error'] = "Gagal mengunggah file.";
    }
}
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
            <form action="" method="post" enctype="multipart/form-data">
                <div class="avatar" style="background-image: url('uploads/<?php echo htmlspecialchars($image); ?>');"></div>
                <label for="image" class="edit-avatar">&#x270E;</label>
                <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                <button type="submit" name="upload">Unggah Foto</button>
            </form>
            <?php if (isset($_SESSION['message'])): ?>
                <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            <?php elseif (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <p>username: <?php echo htmlspecialchars($username); ?> <a href="change_username.php">&#x270E;</a></p>
            <p>email: <?php echo htmlspecialchars($email); ?></p>
        </section>
        <section class="security">
            <h3>Keamanan</h3>
            <p><a href="reset_password.php">&#x1F512; Ganti Password</a></p>
        </section>
        <section class="support">
            <h3>Bantuan</h3>
            <p><a href="https://wa.me/6281254986462" target="_blank" rel="noopener noreferrer">&#x1F4AC; Pertanyaan</a></p>
            <p><a href="kebijakan.php">&#x1F4D3; Kebijakan Privasi</a></p>
        </section>
        <button class="logout" onclick="window.location.href='fungsi/logout.php'">Logout</button>
    </main>
</body>
</html>
