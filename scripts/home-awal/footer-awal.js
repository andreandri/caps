class FooterAwalApp extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: "open" });
    this._style = document.createElement("style");
  }

  connectedCallback() {
    this.render();
  }

  updateStyle() {
    this._style.textContent = `
      .footer {
        background-color: rgba(83, 144, 166, 0.8);
        color: #fff;
        text-align: center;
        padding: 20px 0;
        font-family: Arial, sans-serif;
      }
  
      .footer-content {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 3rem;
      }
  
      .footer-content p{
        font-size: 14px;
      }
  
      .footer-content h4 {
        position: relative;
        text-decoration: none; 
        padding-bottom: 10px;
      }
  
      .footer-content h4::after {
        content: '';
        position: absolute;
        bottom: 0; 
        left: 0;
        width: 100%;
        height: 2px; 
        background-color: #000; 
      }
  
      .footer-logo {
        text-align: center;
      }
  
      .footer-logo img {
        width: 100px;
        height: auto;
      }
  
      .footer-logo p {
        margin: 5px 0;
        font-style: italic;
      }
  
      .social-media img {
        width: 30px;
        height: 30px;
        margin: 5px;
      }
  
      .contact, .manage {
        text-align: left;
      }
  
      .contact a, .manage a {
        color: #fff;
        text-decoration: none;
      }
  
      .contact a:hover, .manage a:hover {
        text-decoration: underline;
      }
  
      .footer-bottom {
        margin-top: 20px;
        font-size: 14px;
      }
  
      /* Mobile Responsive Design */
      @media (max-width: 768px) {
        .footer-content {
          flex-direction: column;
          align-items: center;
          gap: 1rem;
        }
  
        .footer-logo img {
          width: 80px;
        }
  
        .footer-content h4 {
          font-size: 16px;
        }
  
        .footer-content p {
          font-size: 12px;
        }
  
        .social-media img {
          width: 24px;
          height: 24px;
        }
  
        .contact, .manage {
          text-align: center;
        }
  
        .footer-bottom {
          font-size: 12px;
        }
      }
    `;
  }  
  
  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <footer class="footer">
      <div class="footer-content">
        <div class="footer-logo">
          <img src="img/EasyBusTix.png" alt="EasyBusTix Logo">
          <p>PEKAN MUDAH, PERJALANAN NYAMAN</p>
        </div>
        <div class="social-media">
          <h4>Sosial Media Kami:</h4>
          <a href="https://facebook.com/easybustix" target="_blank" aria-label="Facebook">
            <img src="img/fb.png" alt="Facebook">
          </a>
          <a href="https://twitter.com/easybustix" target="_blank" aria-label="Twitter">
            <img src="img/tw.png" alt="Twitter">
          </a>
          <a href="https://instagram.com/easybustix" target="_blank" aria-label="Instagram">
            <img src="img/ig.png" alt="Instagram">
          </a>
        </div>
        <div class="contact">
          <h4>Contact:</h4>
          <p>Email: <a href="mailto:support@easybustix.com">support@easybustix.com</a></p>
          <p>No kontak: <a href="https://wa.me/6281254986462" target="_blank" rel="noopener noreferrer">+62 812-5498-6462</a></p>
        </div>
        <div class="manage">
          <h4>Manage:</h4>
          <p><a href="kebijakanawal.php">Kebijakan Privasi</a></p>
          <p><a href="kebijakanawal.php">Syarat dan Ketentuan</a></p>
        </div>
        </div>
        <div class="footer-bottom">
          <p>© 2024 EasyBusTix. All rights reserved.</p>
        </div>
      </footer>
    `;
  }
}

customElements.define("footerawal-app", FooterAwalApp); 
