class BarPesanApp extends HTMLElement {
  constructor() {
    super();

    this.attachShadow({ mode: "open" });
    this._style = document.createElement("style");
  }

  connectedCallback() {
    this.render();
    this.addEventListener();

    const navbar = this.shadowRoot.querySelector(".navbar");
    navbar.style.opacity = 0; 

    navbar.classList.add("skeleton");

    setTimeout(() => {
      navbar.classList.remove("skeleton"); 
      navbar.style.opacity = 1; 
    }, 800); 
  }

  updateStyle() {
    this._style.textContent = `
      .navbar { 
        background-color: #5390a6;
        display: flex;
        align-items: center;
        padding: 0.3rem 1rem;
        font-family: 'Poppins', sans-serif;
        transition: opacity 0.3s ease-in-out; 
      }

      .navbar.skeleton {
        position: relative;
        background: linear-gradient(90deg, #e0e0e0 25%, #fff 50%, #e0e0e0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
      }

      .navbar.skeleton::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
      }

      .link:hover {
        font-weight: bold;
      }

      .Kembali a img {
        width: 100%;
        height: 100%;
        width: 44px;
        height: 44px;
        object-fit: cover;
        cursor: pointer; 
        transition: transform 0.3s ease; 
      }

      .Kembali a:hover img {
        transform: scale(1.1);
      }

      .navlogo {
        margin-left: 1rem;
        display: flex;
        align-items: center;
      }

      .navlogo img {
        width: 4rem;
      }

      .navbar .logo {
        font-size: 1.4em;
      }

      .navbar :focus {
        outline: 1px solid #243642;
        border-radius: 0.4rem;
      }


      .navbar, .logo, .link {
        color: #021526;
      }

      @media screen and (max-width: 768px) {
        .navbar .logo {
          font-size: 1.2em;
        }
          
        .Kembali a img{
          width: 38px;
          height: 38px;
        }
      }

      @media screen and (max-width: 550px) {
        .navlogo img {
          width: 3rem;
        }

        .navbar .logo {
          font-size: 1em;
        }

        .navbar {
          padding: 0 0.5rem;
        }

        .Kembali a img{
          width: 30px;
          height: 30px;
        }
      }
    `;
  }

  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <nav class="navbar skeleton">
      <div class="Kembali">
          <a tabindex="0" href="pesantiket.php"><img src="img/back.png" alt=""></a>
        </div>
        <div class="navlogo">
          <img tabindex="0" src="img/EasyBusTix.png" alt="Logo EasyBusTix" >
          <h3 class="logo" tabindex="0">EasyBusTix</h3>
        </div>
      </nav>
    `;
  }

  addEventListener() {  
  }
}

customElements.define("bar-pesan-app", BarPesanApp);
