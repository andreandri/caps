import './loading.js';
import './home-user/bar.js';
import './home-user/footer.js';
import './home-awal/footer-awal.js';
import './home-awal/bar-awal.js';
import './home-awal/footer-awal-kebijakan.js';
import './home-awal/bar-awal-kebijakan.js';
import './home-user/bar-user.js';

document.addEventListener('DOMContentLoaded', async () => {
  const skeleton = document.querySelector('.skeleton');
  const content = document.querySelector('.real-content'); // Elemen asli

  // Tampilkan Skeleton
  skeleton.style.display = 'block';
  content.style.display = 'none';

  // Simulasi Fetch Data
  await new Promise((resolve) => setTimeout(resolve, 2000)); // Delay 2 detik

  // Sembunyikan Skeleton dan Tampilkan Konten Asli
  skeleton.style.display = 'none';
  content.style.display = 'block';
});
