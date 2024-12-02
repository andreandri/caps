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
            echo "<script>alert('Foto Berhasil digunakan');</script>";
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('fileInput');
            const cameraButton = document.getElementById('cameraButton');
            const popup = document.getElementById('popup');
            const cancelButton = document.getElementById('cancelButton');
            const changeButton = document.getElementById('changeButton');
            const avatarPreview = document.getElementById('avatarPreview');
            const form = document.getElementById('profileForm');
            const loadingElement = document.createElement('ind-loading-profil');

            // Tambahkan custom element loading ke body tetapi sembunyikan
            document.body.appendChild(loadingElement);
            loadingElement.style.display = 'none';

            // Tampilkan file chooser saat tombol kamera ditekan
            cameraButton.addEventListener('click', () => {
                fileInput.click();
            });

            // Tampilkan preview gambar dan pop-up saat file dipilih
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const reader = new FileReader();

                    loadingElement.style.display = 'block'; // Tampilkan loading sebelum preview

                    reader.onload = function (event) {
                        setTimeout(() => {
                            avatarPreview.style.backgroundImage = `url(${event.target.result})`;
                            loadingElement.style.display = 'none'; // Sembunyikan loading
                            popup.style.display = 'block'; // Tampilkan pop-up
                        }, 1000); // Simulasi waktu loading
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Reload halaman dengan indikator loading jika tombol Batalkan ditekan
            cancelButton.addEventListener('click', () => {
                loadingElement.style.display = 'block'; // Tampilkan indikator loading
                setTimeout(() => {
                    window.location.reload(); // Reload halaman setelah loading
                }, 1000); // Simulasi waktu loading
            });

            // Kirim form jika tombol ganti foto ditekan
            changeButton.addEventListener('click', () => {
                popup.style.display = 'none';
                loadingElement.style.display = 'block'; // Tampilkan loading sebelum form dikirim

                setTimeout(() => {
                    alert('Foto Berhasil digunakan');
                    form.submit(); // Kirim form setelah loading
                }, 1000); // Simulasi waktu loading
            });
        });
    </script>
</head>
<body>
    <header>
        <bar-app></bar-app>
    </header>
    <main>
    <ind-loading-main></ind-loading-main>
        <section class="profile">
            <h2 tabindex="0">Informasi Pribadi</h2>
            <form id="profileForm" action="" method="post" enctype="multipart/form-data">
                <div class="foto">
                <div class="avatar-wrapper">
                    <div tabindex="0" class="avatar" id="avatarPreview" style="background-image: url('uploads/<?php echo htmlspecialchars($image); ?>'); place-self: center" alt="Foto Profile"></div>
                    <button tabindex="0" type="button" class="camera-button" id="cameraButton"><img src="img/kamera.png" alt="Ganti Foto Profile"></button>
                </div>
                </div>
                <input tabindex="0" type="file" id="fileInput" name="image" accept="image/*" style="display: none;">
                <div class="popup" id="popup" style="display: none;">
                  <div class="popup-content">
                    <p tabindex="0">Apakah Anda ingin mengganti foto?</p>
                    <button tabindex="0" type="button" id="changeButton">Ganti Foto</button>
                    <button tabindex="0" type="button" id="cancelButton">Batalkan</button>
                  </div>
                </div>
            </form>
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <div class="data">
              <div class="isi">
                <h3 tabindex="0">Username</h3>
                <p tabindex="0">&#x1F464; <?php echo htmlspecialchars($username); ?> <a tabindex="0" href="change_username.php">&#x270E;</a></p>
              </div>
              <div class="isi">
                <h3 tabindex="0">Email</h3>
                <p tabindex="0">&#x1F4E7; <?php echo htmlspecialchars($email); ?></p>
              </div>
            </div>
        </section>
        <section class="security">
            <h3 tabindex="0">Keamanan</h3>
            <p tabindex="0"><a tabindex="0" href="reset_password.php">&#x1F512; Ganti Password</a></p>
        </section>
        <section class="support">
            <h3 tabindex="0">Bantuan</h3>
            <p tabindex="0"><a tabindex="0" href="https://wa.me/6281254986462" target="_blank" rel="noopener noreferrer">&#x1F4AC; Pertanyaan</a></p>
            <p tabindex="0"><a tabindex="0" href="kebijakan.php">&#x1F4D3; Kebijakan Privasi</a></p>
        </section>
        <button tabindex="0" class="logout" onclick="window.location.href='fungsi/logout.php'">Logout</button>
    </main>

    <footer>
        <footer-app></footer-app>
    </footer>
</body>
</html>
