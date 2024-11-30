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
  <header>
    <bar-awal-app></bar-awal-app>
  </header>

  <main>
    <section id="home" class="hero">
      <div class="hero-text">
        <h1>Pesan Tiket Bus dengan Mudah dan Cepat</h1>
        <p>EasyBusTix membantu Anda menemukan tiket bus terbaik untuk perjalanan Anda. Aman, cepat, dan nyaman!</p>
        <a href="register.php" class="btn-primary">Daftar Sekarang</a>
        <a href="login.php" class="btn-secondary">Login</a>
      </div>
      <div class="hero-image">
        <img src="img/bus.png" alt="Gambar Bus">
      </div>
    </section>

    <section id="about" class="about-us">
      <div>
        <img src="img/EasyBusTix.png" alt="EasyBusTix Logo">
      </div>
      <div>
        <h2>Tentang EasyBusTix</h2>
        <p>
          EasyBusTix adalah platform pemesanan tiket bus online yang memudahkan Anda menemukan dan memesan tiket dengan cepat dan aman. Kami menyediakan berbagai pilihan rute dan operator bus di seluruh Indonesia, sehingga Anda dapat merencanakan perjalanan dengan lebih fleksibel.
        </p>
        <p>
          Dengan antarmuka yang ramah pengguna, proses pemesanan kami sangat sederhana dan efisien. Kami juga menawarkan berbagai metode pembayaran yang aman, mulai dari transfer bank, e-wallet, hingga kartu kredit. Selain itu, tim dukungan pelanggan kami siap membantu Anda 24/7 untuk memastikan pengalaman perjalanan Anda lancar.
        </p>
        <p>
          Nikmati fitur pencarian cepat, pelacakan bus real-time, informasi detail fasilitas bus, dan kemudahan pengelolaan tiket langsung melalui aplikasi kami. Terima kasih telah mempercayakan perjalanan Anda kepada EasyBusTix!
        </p>
      </div>
    </section>

    <section class="features">
      <h2>Kenapa Memilih EasyBusTix?</h2>
      <div class="features-grid">
        <div class="feature-item">
          <h3>Aman</h3>
          <p>Data dan transaksi Anda selalu terlindungi.</p>
        </div>
        <div class="feature-item">
          <h3>Cepat</h3>
          <p>Pesan tiket hanya dalam hitungan menit.</p>
        </div>
        <div class="feature-item">
          <h3>Nyaman</h3>
          <p>Antarmuka yang mudah digunakan kapan saja.</p>
        </div>
      </div>
    </section>

    <section id="team" class="team">
      <h2>Tim Pengembang Kami</h2>
        <div class="team-grid">
          <div class="team-member">
            <div class="image-container">
              <img src="img/amel.png" alt="Amelia Frenety Perdi">
              <div class="overlay">
                <a href="https://github.com/amel" target="_blank">
                  <img src="img/github.png" alt="GitHub">
                </a>
                <a href="https://ind-link.com/amel" target="_blank">
                  <img src="img/linkedin.png" alt="Personal Link">
                </a>
              </div>
            </div>
            <h3>Amelia Frenety Perdi</h3>
            <p>Developer</p>
          </div>
          <div class="team-member">
            <div class="image-container">
              <img src="img/andre.png" alt="Andre Andrianus">
              <div class="overlay">
                <a href="https://github.com/andre" target="_blank">
                  <img src="img/github.png" alt="GitHub">
                </a>
                <a href="https://ind-link.com/andre" target="_blank">
                  <img src="img/linkedin.png" alt="Personal Link">
                </a>
              </div>
            </div>
            <h3>Andre Andrianus</h3>
            <p>Developer</p>
          </div>
        </div>

        <div class="team-grid">
          <div class="team-member">
            <div class="image-container">
              <img src="img/imam.png" alt="Imam Syahrohim">
              <div class="overlay">
                <a href="https://github.com/amel" target="_blank">
                  <img src="img/github.png" alt="GitHub">
                </a>
                <a href="https://ind-link.com/amel" target="_blank">
                  <img src="img/linkedin.png" alt="Personal Link">
                </a>
              </div>
            </div>
            <h3>Imam Syahrohim</h3>
            <p>Developer</p>
          </div>
          <div class="team-member">
            <div class="image-container">
              <img src="img/oyong.png" alt="Natalio Valentino">
              <div class="overlay">
                <a href="https://github.com/andre" target="_blank">
                  <img src="img/github.png" alt="GitHub">
                </a>
                <a href="https://ind-link.com/andre" target="_blank">
                  <img src="img/linkedin.png" alt="Personal Link">
                </a>
              </div>
            </div>
            <h3>Natalio Valentino</h3>
            <p>Developer</p>
          </div>
        </div>
    </section>

    </main>

    <!-- Footer -->
    <footer>
      <footerawal-app></footerawal-app>
    </footer>
</body>
</html>

* {
    box-sizing: border-box;
  }
  
body {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
  background-color: #fff;
}
  
main {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  margin: 2.5rem;
}

header {
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.tampilsatu {
  display: flex;
  gap: 2rem;
}

.bus {
  display: flex;
  flex-direction: column;
  background-color: #B9E5E8;
  width: 65%;
  padding: 2rem 3rem 0;
  border-radius: 1rem;
  text-align: center;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.bus img {
  width: 50%;
}

.bus p {
  font-size: 1.3rem;
}

.bus h1 {
  font-size: 2rem;
}

aside {
  width: 35%;
}

.bussart {
  margin-bottom: 2rem;
}

.buss {
  background-color: #B9E5E8;
  border-radius: 1rem;

  padding: 1px 1rem 1.2rem;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.buss h1 {
  font-size: 20px;
  color: #2C7873;
}

.buss p {
  font-size: 16px;
  color: #555;
}

.buss a {
  display: block;
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #2C7873;
  color: #fff;
  border-radius: 1rem;
  text-decoration: none;
  font-weight: bold;
  text-align: center;
}

.buss a:hover {
  background-color: #35A29F;
}

.daftarbus {
  background-color: #B9E5E8;
  border-radius: 8px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.daftarbus h1 {
  text-align: center;
}

.tampildua {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  padding: 1rem;
  justify-items: center;
  align-items: center;
}

.tampildua article {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.tampildua img {
  width: 100%;
  max-width: 400px;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.tampildua img:hover {
  transform: scale(1.2);
}

.tampildua h1 {
  font-size: 1.5rem;
  color: #333;
  margin-top: 0.5rem;
}
