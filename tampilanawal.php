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
      <div class="navlogo">
        <img src="img/EasyBusTix.png" alt="Logo EasyBusTix" >
        <h3 class="logo" tabindex="0">EasyBusTix</h3>
        </div>
      <button id="menu" class="header__menu" aria-label="Menu Navigasi" tabindex="0">☰</button>
      <ul class="navbar-nav">
        <li><a href="login.php" class="link" tabindex="0">Login</a></li>
        <li><a href="tampilanawal.php" class="link" tabindex="0">Home</a></li>
        <li><a href="login.php" class="link" tabindex="0">Profile</a></li>
        <li><a href="login.php" class="link" tabindex="0">History</a></li>
        <li><a href="about-us-awal.php" class="link" tabindex="0">About Us</a></li>
      </ul>
    </nav>
  </header>

  <main>
        <section class="tampilsatu">
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
            
            <aside>
                <div class="bussart">
                    <article class="buss">
                        <h1>Bersama EasyBusTix Mempermudah Urusan Perjalananmu!</h1>
                        <a href="pesantiket.php">Cek Tiket</a>
                    </article>
                </div>
                
                    <article class="buss" id="populer">
                        <h1>Rute Perjalanan Yang Tersedia :</h1>
                        <p>Palangka Raya - Sampit</p>
                        <p>Palangka Raya - Pangkalan Bun</p>
                        <p>Sampit - Pangkalan Bun</p>
                        <p>Sampit - Palangka Raya</p>
                        <p>Pangkalan Bun - Sampit</p>
                        <p>Pangkalan Bun - Palangka Raya</p>
                        <a href="jadwal.php">Cek Jadwal</a>
                    </article>
            </aside>
        </section>

        <section class="daftarbus">
            <h1>Daftar Tampilan Unit Bus yang Tersedia</h1>
            <div class="tampildua">
                <article>
                    <img src="img/bus2.png" alt="">
                    <h1>2 Unit</h1>
                </article>

                <article>
                    <img src="img/bus1.png" alt="">
                    <h1>2 Unit</h1>
                </article>

                <article>
                    <img src="img/bus.png" alt="">
                    <h1>2 Unit</h1>
                </article>
            </div>
        </section>
    </main>

    <footer class="footer-container">
        <div class="footer-section">
          <h3>EasyBusTix</h3>
          <a href="#">Home</a>
          <a href="#">Profile</a>
          <a href="#">History</a>
          <a href="#">About Us</a>
        </div>
        <div class="footer-center">
          <div class="footer-img">
            <img src="img/EasyBusTix.png" alt="EasyBusTix Logo">
          </div>
          <p>© Copyright 2024. Made by Tim Capstone DB3-PS010</p>
        </div>
        <div class="footer-contact">
          <h3>Informasi Kontak</h3>
          <p>📞 +62 812 4567 8910</p>
          <p>✉️ <a href="mailto:capstoneteam@gmail.com">capstoneteam@gmail.com</a></p>
        </div>
      </footer>
  
</body>
</html>