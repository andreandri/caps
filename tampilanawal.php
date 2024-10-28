<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tampilan Awal</title>

  <link rel="stylesheet" href="tampilanawal.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <h3 class="logo" tabindex="0">EasyBusTix</h3>
      <button id="menu" class="header__menu" aria-label="Menu Navigasi" tabindex="0">☰</button>
      <ul class="navbar-nav">
        <li><a href="login.php" class="link" tabindex="0">Login</a></li>
        <li><a href="register.php" class="link" tabindex="0">Registrasi</a></li>
        <li><a href="tampilanawal.php" class="link" tabindex="0">Home</a></li>
        <li><a href="login.php" class="link" tabindex="0">Profile</a></li>
        <li><a href="login.php" class="link" tabindex="0">History</a></li>
        <li><a href="about-us-awal.php" class="link" tabindex="0">About Us</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="bus">
      <article>
        <img src="img/bus.png" alt="">
        <p>"EasyBusTix - Pesan Mudah, Perjalanan Nyaman!"</p>
      </article> 

      <article>
        <h1>EasyBusTix</h1>
        <p>EasyBusTix adalah platform penjualan tiket bus online yang memudahkan Anda memesan tiket dengan cepat dan aman.</p>
      </article>
      </div>

    <aside class="busbus">
      <article class="buss">
        <h1>Bersama EasyBusTix Mempermudah Urusan Perjalananmu!</h1>
        <a href="pesantiket.php">Cek Tiket</a>
      </article>
            
      <article class="buss" id="populer">
        <h1>Rute</h1>
        <p>Palangka Raya - Sampit - Pangkalan Bun</p>
        <a href="jadwal.php">Cek Jadwal</a>
      </article>
    </aside>
  </main>
  
</body>
</html>