<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EasyBusTix - Home</title>
  <link rel="stylesheet" href="tampilanawal.css">
  <script type="module" src="scripts/index.js"></script>
</head>
<body>
  <ind-loading-awal></ind-loading-awal>

  <div id="main-content" style="display: none;">
    <a href="#home" class="skip-link">Skip To Content</a>
    <header>
      <bar-awal-app></bar-awal-app>
    </header>

    <main>
    <ind-loading-main-awal ></ind-loading-main-awal>
      <section class="hero">
        <div class="hero-text">
          <h1 tabindex="0">Pesan Tiket Bus dengan Mudah dan Cepat</h1>
          <p tabindex="0">EasyBusTix membantu Anda menemukan tiket bus terbaik untuk perjalanan Anda. Aman, cepat, dan nyaman!</p>
          <a tabindex="0" id="home" href="register.php" class="btn-primary">Daftar Sekarang</a>
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
            EasyBusTix adalah platform pemesanan tiket bus online yang memudahkan Anda menemukan dan memesan tiket dengan cepat dan aman. Kami menyediakan berbagai pilihan rute dan operator bus di seluruh Indonesia, sehingga Anda dapat merencanakan perjalanan dengan lebih fleksibel.
          </p>
          <p tabindex="0">
            Dengan antarmuka yang ramah pengguna, proses pemesanan kami sangat sederhana dan efisien. Kami juga menawarkan berbagai metode pembayaran yang aman, mulai dari transfer bank, e-wallet, hingga kartu kredit. Selain itu, tim dukungan pelanggan kami siap membantu Anda 24/7 untuk memastikan pengalaman perjalanan Anda lancar.
          </p>
          <p tabindex="0">
            Nikmati fitur pencarian cepat, pelacakan bus real-time, informasi detail fasilitas bus, dan kemudahan pengelolaan tiket langsung melalui aplikasi kami. Terima kasih telah mempercayakan perjalanan Anda kepada EasyBusTix!
          </p tabindex="0">
        </div>
      </section>

      <section class="features">
        <h2 tabindex="0">Kenapa Memilih EasyBusTix?</h2>
        <div class="features-grid">
          <div class="feature-item">
            <h3 tabindex="0" >Aman</h3>
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
                <img tabindex="0" src="img/amel.jpg" alt="Amelia Frenety Perdi">
                <div class="overlay">
                  <a tabindex="0" href="https://github.com/ameliafrty28" target="_blank">
                    <img tabindex="0" src="img/github.png" alt="GitHub">
                  </a>
                  <a tabindex="0" href="https://www.linkedin.com/in/Amelia-FrenetyPerdi/" target="_blank">
                    <img tabindex="0" src="img/linkedin.png" alt="Personal Link">
                  </a>
                </div>
              </div>
              <h3 tabindex="0">Amelia Frenety Perdi</h3>
              <p tabindex="0">Developer</p>
            </div>
            <div class="team-member">
              <div class="image-container">
                <img tabindex="0" src="img/andre.png" alt="Andre Andrianus">
                <div class="overlay">
                  <a tabindex="0" href="https://github.com/andreandri" target="_blank">
                    <img tabindex="0" src="img/github.png" alt="GitHub">
                  </a>
                  <a tabindex="0" href="https://www.linkedin.com/in/andre-andrianus02/" target="_blank">
                    <img tabindex="0" src="img/linkedin.png" alt="Personal Link">
                  </a>
                </div>
              </div>
              <h3 tabindex="0">Andre Andrianus</h3>
              <p tabindex="0">Developer</p>
            </div>
          </div>

          <div class="team-grid">
            <div class="team-member">
              <div class="image-container">
                <img tabindex="0" src="img/imam.png" alt="Imam Syahrohim">
                <div class="overlay">
                  <a tabindex="0" href="https://github.com/Malter17" target="_blank">
                    <img tabindex="0" src="img/github.png" alt="GitHub">
                  </a>
                  <a tabindex="0" href="https://www.linkedin.com/in/imam-syahrohim/" target="_blank">
                    <img tabindex="0" src="img/linkedin.png" alt="Personal Link">
                  </a>
                </div>
              </div>
              <h3 tabindex="0">Imam Syahrohim</h3>
              <p tabindex="0">Developer</p>
            </div>
            <div class="team-member">
              <div class="image-container">
                <img tabindex="0" src="img/oyong.png" alt="Natalio Valentino">
                <div class="overlay">
                  <a tabindex="0" href="https://github.com/Natalio2512" target="_blank">
                    <img tabindex="0" src="img/github.png" alt="GitHub">
                  </a>
                  <a tabindex="0" href="https://www.linkedin.com/in/natalio-valentino-a87818312/" target="_blank">
                    <img tabindex="0" src="img/linkedin.png" alt="Personal Link">
                  </a>
                </div>
              </div>
              <h3 tabindex="0">Natalio Valentino</h3>
              <p tabindex="0">Developer</p>
            </div>
          </div>
      </section>

      </main>

      <!-- Footer -->
      <footer>
        <footerawal-app></footerawal-app>
      </footer>
    </div>
</body>
</html>