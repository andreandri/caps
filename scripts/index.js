import './loading-main-awal.js';
import './loading-awal.js';
import './loading-main.js';
import './loading-profil.js';
import './loading-admin.js';
import './home-user/bar.js';
import './home-user/bar-change.js';
import './home-user/bar-tiket.js';
import './home-user/bar-pesan.js';
import './home-user/footer.js';
import './home-awal/footer-awal.js';
import './home-awal/bar-awal.js';
import './home-awal/footer-awal-kebijakan.js';
import './home-awal/bar-awal-kebijakan.js';
import './home-user/bar-user.js';

document.addEventListener("DOMContentLoaded", () => {
  const loadingElement = document.querySelector("ind-loading-awal");
  const mainContent = document.getElementById("main-content");

  setTimeout(() => {
    if (loadingElement) {
      loadingElement.style.opacity = "0";
      setTimeout(() => {
        loadingElement.remove();
        if (mainContent) {
          mainContent.style.display = "block";
        }
      }, 400);
    }
  }, 1000);
});

document.addEventListener("DOMContentLoaded", () => {
  const loadingIndicator = document.querySelector("ind-loading-main-awal");
  const body = document.body;

  if (loadingIndicator) {
    body.classList.add("no-scroll");
  }

  const simulateDataLoad = new Promise((resolve) => {
    setTimeout(() => resolve(), 2700);
  });

  simulateDataLoad.then(() => {
    if (loadingIndicator) {
      loadingIndicator.style.display = "none";
      body.classList.remove("no-scroll");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const loadingIndicator = document.querySelector("ind-loading-main");
  const body = document.body;

  if (loadingIndicator) {
    body.classList.add("no-scroll");
  }

  const simulateDataLoad = new Promise((resolve) => {
    setTimeout(() => resolve(), 800);
  });

  simulateDataLoad.then(() => {
    if (loadingIndicator) {
      loadingIndicator.style.display = "none";
      body.classList.remove("no-scroll");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const loadingIndicator = document.querySelector("ind-loading-profil");

  const simulateDataLoad = new Promise((resolve) => {
    setTimeout(() => resolve(), 800);
  });

  simulateDataLoad.then(() => {
    if (loadingIndicator) {
      loadingIndicator.style.display = "none";
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const loadingIndicator = document.querySelector("ind-loading-admin");
  const body = document.body;

  if (loadingIndicator) {
    body.classList.add("no-scroll");
  }

  const simulateDataLoad = new Promise((resolve) => {
    setTimeout(() => resolve(), 800);
  });

  simulateDataLoad.then(() => {
    if (loadingIndicator) {
      loadingIndicator.style.display = "none";
      body.classList.remove("no-scroll");
    }
  });
});