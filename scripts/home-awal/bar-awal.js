class BarAwalApp extends HTMLElement {
  constructor() {
    super();

    this.attachShadow({ mode: "open" });
    this._style = document.createElement("style");
  }

  connectedCallback() {
    this.render();
    this.addEventListener();
  }

  updateStyle() {
    this._style.textContent = `
     .navbar { 
        background-color: #5390a6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.3rem 1rem;
        font-family: 'Poppins', sans-serif;
      }

      .link:hover {
        font-weight: bold;
      }

      .navlogo {
        display: flex;
        align-items: center;
      }

      .navlogo img {
        width: 4rem;
      }

      .navbar .logo {
        font-size: 1.4em;
        border-radius: 0.4rem;
      }

      .navbar .navbar-nav {
        list-style-type: none;
        padding-inline: 0;
        display: flex;
        font-size: 1.4em;
        gap: 1.5rem;
      }

      .navbar :focus {
        outline: 1px solid #243642;
      }

      a {
        text-decoration: none;
        min-width: 44px;
        min-height: 44px;
        padding: 1rem 0;
      }

      .navbar, .logo, .link {
        color: #021526;
      }

      .header__menu {
        font-size: 2rem;
        display: none;
        cursor: pointer;
        background: none;
        border: none;
        min-width: 44px;
        min-height: 44px;
        padding: 0.5rem 0.7rem;
      }

      @media screen and (max-width: 768px) {
        .navbar .navbar-nav {
          display: none;
          flex-direction: column;
          gap: 1rem;
          background-color: #fff;
          position: absolute;
          top: 43px;
          right: 0;
          width: 100%;
          padding: 1rem;
          text-align: center;
        }

        .navbar .navbar-nav.open {
          display: flex;
        }

        .header__menu {
          display: block;
        }

        .navbar .logo {
          font-size: 1.2em;
        }

        .navbar .navbar-nav {
          font-size: 1.2em;
        }
      }

      /* Additional styles for max-width: 550px */
      @media screen and (max-width: 550px) {
        .navlogo img {
          width: 3rem;
        }

        .navbar .logo {
          font-size: 1em;
        }

        .navbar .navbar-nav {
          font-size: 1em;
          gap: 1rem;
          top: 38px;
        }

        .header__menu {
          font-size: 1.8rem;
        }

        a {
          font-size: 0.9rem;
          padding: 0.8rem 0;
        }

        .navbar {
          padding: 0 0.5rem;
        }
      }
    `;
  }

  render() {  
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <nav class="navbar">
        <div class="navlogo">
        <img tabindex="0" src="img/EasyBusTix.png" alt="Logo EasyBusTix" >
        <h3 class="logo" tabindex="0">EasyBusTix</h3>
        </div>
        <button id="menu" class="header__menu" aria-label="Menu Navigasi" tabindex="0">☰</button>
        <ul class="navbar-nav">
          <li><a href="#home" class="link" tabindex="0">Home</a></li>
          <li><a href="#about" class="link" tabindex="0">About</a></li>
          <li><a href="#team" class="link" tabindex="0">Team</a></li>
        </ul>
      </nav>
    `;
  }

  addEventListener() {
    const menu = this.shadowRoot.querySelector("#menu");
    const navBar = this.shadowRoot.querySelector(".navbar-nav");

    menu.addEventListener("click", (event) => {
      navBar.classList.toggle("open");
      event.stopPropagation();
    });

    document.addEventListener("click", (event) => {
      if (!menu.contains(event.target) && !navBar.contains(event.target)) {
        navBar.classList.remove("open");
      }
    });
  }
}

customElements.define("bar-awal-app", BarAwalApp);
