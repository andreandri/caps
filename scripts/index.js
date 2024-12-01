import './loading.js';
import './home-user/bar.js';
import './home-user/footer.js';
import './home-awal/footer-awal.js';
import './home-awal/bar-awal.js';
import './home-awal/footer-awal-kebijakan.js';
import './home-awal/bar-awal-kebijakan.js';
import './home-user/bar-user.js';

document.addEventListener("DOMContentLoaded", () => {
  const loadingIndicator = document.querySelector("ind-loading");

  setTimeout(() => {
    if (loadingIndicator) {
      loadingIndicator.style.display = "none";
    }
  }, 1000); 
});