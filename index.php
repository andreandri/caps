<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EasyBusTix - Home</title>
  <link rel="icon" href="favicon.png" type="image/png">
  <link rel="stylesheet" href="tampilanawal.css">
  <script type="module" src="scripts/index.js"></script>
</head>

<body>
  <ind-loading-awal></ind-loading-awal>

  <div id="main-content" style="display: none;">
    <a href="#hero" class="skip-link">Skip To Content</a>
    <header>
      <bar-awal-app></bar-awal-app>
    </header>

    <main>
      <ind-loading-main-awal></ind-loading-main-awal>
      <section id="home" class="hero">
        <div class="hero-text">
          <h1 tabindex="0">Pesan Tiket Bus dengan Mudah dan Cepat</h1>
          <p tabindex="0">EasyBusTix membantu Anda menemukan tiket bus terbaik untuk perjalanan Anda. Aman, cepat, dan nyaman!</p>
          <a tabindex="0" id="hero" href="register.php" class="btn-primary">Daftar Sekarang</a>
          <a tabindex="0" href="login.php" class="btn-secondary">Login</a>
        </div>
        <div class="hero-image">
          <img tabindex="0" src="img/bus.png" alt="Gambar Bus">
        </div>
      </section>

      <section id="about" class="about-us">
        <div>
          <img tabindex="0" src="img/EasyBusTix.png" alt="EasyBusTix Logo">
        </div>
        <div>
          <h2 tabindex="0">Tentang EasyBusTix</h2>
          <p tabindex="0">
            Easybustix adalah sebuah proyek aplikasi berbasis web yang bertujuan untuk menyediakan layanan pemesanan tiket bus secara online. Aplikasi ini dirancang untuk membantu pengguna, terutama di wilayah Kalimantan Tengah, agar penumpang dapat memesan tiket bus dengan cara yang lebih mudah, efisien, dan aman.
          </p>
          <p tabindex="0">
            Selain itu, Easybustix juga bertujuan mempermudah operator bus dalam mengelola jadwal, harga, dan ketersediaan kursi secara transparan, serta meningkatkan efisiensi dan kepercayaan dalam ekosistem transportasi publik. Dengan fitur-fitur unggulannya, Easybustix diharapkan menjadi solusi yang dapat mengurangi angka penipuan sekaligus meningkatkan kenyamanan pengguna dalam menggunakan transportasi bus.
          </p>
        </div>
      </section>

      <section class="features">
        <h2 tabindex="0">Kenapa Memilih EasyBusTix?</h2>
        <div class="features-grid">
          <div class="feature-item">
            <h3 tabindex="0">Aman</h3>
            <p tabindex="0">Data dan transaksi Anda selalu terlindungi.</p>
          </div>
          <div class="feature-item">
            <h3 tabindex="0">Cepat</h3>
            <p tabindex="0">Pesan tiket hanya dalam hitungan menit.</p>
          </div>
          <div class="feature-item">
            <h3 tabindex="0">Nyaman</h3>
            <p tabindex="0">Antarmuka yang mudah digunakan kapan saja.</p>
          </div>
        </div>
      </section>

      <section id="team" class="team">
        <h2 tabindex="0">Tim Pengembang Kami</h2>
        <div class="team-grid">
          <div class="team-member">
            <div class="image-container">
              <img tabindex="0" src="img/amel.jpg" alt="Foto Amelia Frenety Perdi">
              <div class="overlay">
                <a tabindex="0" href="https://github.com/ameliafrty28" target="_blank">
                  <img tabindex="0" src="img/github.png" alt="Gambar GitHub">
                </a>
                <a tabindex="0" href="https://www.linkedin.com/in/Amelia-FrenetyPerdi/" target="_blank">
                  <img tabindex="0" src="img/linkedin.png" alt="Gambar linkedin">
                </a>
              </div>
            </div>
            <h3 tabindex="0">Amelia Frenety Perdi</h3>
            <p tabindex="0">UI/UX Designer</p>
          </div>
          <div class="team-member">
            <div class="image-container">
              <img tabindex="0" src="img/andre.jpg" alt="Foto Andre Andrianus">
              <div class="overlay">
                <a tabindex="0" href="https://github.com/andreandri" target="_blank">
                  <img tabindex="0" src="img/github.png" alt="Gambar GitHub">
                </a>
                <a tabindex="0" href="https://www.linkedin.com/in/andre-andrianus02/" target="_blank">
                  <img tabindex="0" src="img/linkedin.png" alt="Gambar linkedin">
                </a>
              </div>
            </div>
            <h3 tabindex="0">Andre Andrianus</h3>
            <p tabindex="0">Back-end Developer</p>
          </div>
        </div>

        <div class="team-grid">
          <div class="team-member">
            <div class="image-container">
              <img tabindex="0" src="img/imam.jpg" alt="Foto Imam Syahrohim">
              <div class="overlay">
                <a tabindex="0" href="https://github.com/Malter17" target="_blank">
                  <img tabindex="0" src="img/github.png" alt="Gambar GitHub">
                </a>
                <a tabindex="0" href="https://www.linkedin.com/in/imam-syahrohim/" target="_blank">
                  <img tabindex="0" src="img/linkedin.png" alt="Gambar linkedin">
                </a>
              </div>
            </div>
            <h3 tabindex="0">Imam Syahrohim</h3>
            <p tabindex="0">Back-end Developer</p>
          </div>
          <div class="team-member">
            <div class="image-container">
              <img tabindex="0" src="img/oyong.jpg" alt="Foto Natalio Valentino">
              <div class="overlay">
                <a tabindex="0" href="https://github.com/Natalio2512" target="_blank">
                  <img tabindex="0" src="img/github.png" alt="Gambar GitHub">
                </a>
                <a tabindex="0" href="https://www.linkedin.com/in/natalio-valentino-a87818312/" target="_blank">
                  <img tabindex="0" src="img/linkedin.png" alt="Gambar linkedin">
                </a>
              </div>
            </div>
            <h3 tabindex="0">Natalio Valentino</h3>
            <p tabindex="0">Front-end Developer</p>
          </div>
        </div>
      </section>
    </main>

    <footer>
      <footerawal-app></footerawal-app>
    </footer>
  </div>
</body>

</html>