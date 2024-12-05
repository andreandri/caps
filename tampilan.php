<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home User</title>
  <link rel="icon" href="favicon.png" type="image/png">
  <script type="module" src="scripts/index.js"></script>
  <link rel="stylesheet" href="tampilan.css">
</head>

<body>
  <a href="#home" class="skip-link">Skip To Content</a>
  <header>
    <bar-app></bar-app>
  </header>

  <main>
    <ind-loading-main></ind-loading-main>
    <section class="tampilsatu">
      <div class="bus">
        <article>
          <img tabindex="0" src="img/bus.png" alt="">
          <p tabindex="0">"EasyBusTix - Pesan Mudah, Perjalanan Nyaman!"</p>
        </article>

        <article>
          <h1 tabindex="0">EasyBusTix</h1>
          <p tabindex="0">EasyBusTix adalah platform penjualan tiket bus online yang memudahkan Anda memesan tiket dengan cepat dan aman.</p>
        </article>
      </div>

      <aside>
        <div class="bussart">
          <article class="buss">
            <h1 id="home" tabindex="0">Bersama EasyBusTix Mempermudah Urusan Perjalananmu!</h1>
            <a tabindex="0" href="pesantiket.php">Pesan Tiket</a>
          </article>
        </div>

        <article class="buss" id="populer">
          <h1 tabindex="0">Rute Perjalanan Yang Tersedia :</h1>
          <p tabindex="0">Palangka Raya - Sampit</p>
          <p tabindex="0">Palangka Raya - Pangkalan Bun</p>
          <p tabindex="0">Sampit - Pangkalan Bun</p>
          <p tabindex="0">Sampit - Palangka Raya</p>
          <p tabindex="0">Pangkalan Bun - Sampit</p>
          <p tabindex="0">Pangkalan Bun - Palangka Raya</p>
          <a tabindex="0" href="jadwal.php">Cek Jadwal</a>
        </article>
      </aside>
    </section>

    <section class="daftarbus">
      <h1 tabindex="0">Daftar Tampilan Unit Bus yang Tersedia</h1>
      <div class="tampildua">
        <article>
          <img tabindex="0" src="img/bus2.png" alt="">
          <h1 tabindex="0">2 Unit</h1>
        </article>

        <article>
          <img tabindex="0" src="img/bus1.png" alt="">
          <h1 tabindex="0">2 Unit</h1>
        </article>

        <article>
          <img tabindex="0" src="img/bus.png" alt="">
          <h1 tabindex="0">2 Unit</h1>
        </article>
      </div>
    </section>
  </main>

  <footer>
    <footer-app></footer-app>
  </footer>
</body>

</html>