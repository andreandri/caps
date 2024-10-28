<?php

session_start();

// Set default values
$username = "Not logged in";
$email = "Not logged in";

//cek apakah penggunba sudah login
if (isset($_SESSION['username']) && isset($_SESSION['email'])) {
    //mengembalikan nilai username dan password
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    //Kembasli ke menu login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script type="module" src="scripts/index.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <bar-app></bar-app>
    </header>
    <main>
        <section class="profile">
            <h2>Informasi Pribadi</h2>
            <div class="avatar"></div>
            <p>Username: <?php echo htmlspecialchars($username); ?> <a href="#">&#x270E;</a></p>
            <p>Email: <?php echo htmlspecialchars($email); ?> <a href="#">&#x270E;</a></p>
        </section>
        
        <section class="security">
            <h3>Keamanan</h3>
            <p><a href="#">&#x1F512; Ganti Password</a></p>
        </section>
        
        <section class="support">
            <h3>Bantuan</h3>
            <p><a href="#">&#x1F4AC; Pertanyaan</a></p>
            <p><a href="#">&#x1F4D3; Kebijakan Privasi</a></p>
        </section>

        <button class="logout">Logout</button>
    </main>
</body>
</html>
