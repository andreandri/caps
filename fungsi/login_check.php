<?php
session_start();
include("../koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan username atau email
    $sql = "SELECT * FROM users WHERE (Username = ? OR Email = ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Periksa password (gunakan password_verify jika password di-hash)
        if ($password === $row['Password']) {  // atau `password_verify($password, $row['Password'])` jika di-hash
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['Email'] = $row['Email'];
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
            $_SESSION['loginMessage'] = "Password tidak cocok!";
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
