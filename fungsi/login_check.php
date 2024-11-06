<?php
session_start();
include("../koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username_or_email'];
    $sandi = $_POST['sandi'];

    // Query untuk mencari pengguna berdasarkan username atau email
    $sql = "SELECT * FROM tb_users WHERE (username = ? OR Email = ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Periksa sandi (gunakan sandi_verify jika sandi di-hash)
        if ($sandi === $row['sandi']) {  // atau `sandi_verify($sandi, $row['sandi'])` jika di-hash
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row["role"];

            // Redirect berdasarkan role
            if ($row["role"] == "user") {
                header("Location: ../tampilan.php");
                exit();
            } elseif ($row["role"] == "admin") {
                header("Location: ../admintampilan.php");
                exit();
            }
        } else {
            $_SESSION['loginMessage'] = "sandi tidak cocok!";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['loginMessage'] = "Username atau email tidak ditemukan!";
        header("Location: ../login.php");
        exit();
    }
}
?>
