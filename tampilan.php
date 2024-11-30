<?php
    include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home User</title>

  <script type="module" src="scripts/index.js"></script>
  <link rel="stylesheet" href="tampilan.css">
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>

  <main>
    <!-- Skeleton Loading -->
    <section class="skeleton">
      <div class="skeleton-content">
        <div class="skeleton-box large"></div>
        <aside>
          <div class="skeleton-box medium"></div>
          <div class="skeleton-box medium"></div>
        </aside>
      </div>
      <div class="skeleton-content">
        <div class="skeleton-box full"></div>
      </div>
    </section>


    <!-- Konten Asli -->
    <section class="real-content" style="display: none;">
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
    </section>
  </main>

  <footer>
    <footer-app></footer-app>
  </footer>
</body>
</html>
