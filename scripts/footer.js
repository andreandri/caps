class FooterApp extends HTMLElement {
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
      .footer-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: rgba(83, 144, 166, 0.8);
        color: white;
        padding: 20px;
      }
  
      .footer-section {
        display: flex;
        flex-direction: column;
        align-items: center;
      }
  
      .footer-section h3 {
        margin: 0 0 10px;
        font-size: 1.5rem;
        text-align: center;
      }
  
      .footer-section a {
        text-decoration: none;
        color: white;
        margin: 2px 0;
        text-align: center;
      }
  
      .footer-section a:hover {
        text-decoration: underline;
      }
  
      .footer-img img {
        display: block;
        margin: 10px auto;
        max-width: 150px;
      }
  
      .footer-center p {
        font-size: 0.9rem;
        margin: 5px 0;
        text-align: center;
      }
  
      .footer-contact h3 {
        margin: 0 0 10px;
        font-size: 1.5rem;
        text-align: center;
      }
  
      .footer-contact p {
        margin: 5px 0;
        font-size: 1rem;
        text-align: center;
      }
  
      .footer-contact a {
        color: white;
        text-decoration: none;
      }
  
      .footer-contact a:hover {
        text-decoration: underline;
      }
  
      /* Media Queries for Mobile */
      @media screen and (max-width: 768px) {
        .footer-container {
          flex-direction: column;
          gap: 20px;
          text-align: center;
        }
  
        .footer-section h3 {
          font-size: 1.2rem;
        }
  
        .footer-section a {
          font-size: 0.9rem;
        }
  
        .footer-img img {
          max-width: 100px;
        }
  
        .footer-contact h3 {
          font-size: 1.2rem;
        }
  
        .footer-contact p {
          font-size: 0.9rem;
        }
      }
    `;
  }
  
  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
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
          <p>✉️ <a href="mailto:easybustix@gmail.com">easybustix@gmail.com</a></p>
        </div>
      </footer>
    `;
  }
}

customElements.define("footer-app", FooterApp); 
