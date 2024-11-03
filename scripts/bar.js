class BarApp extends HTMLElement {
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
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background-color: #333;
        color: white;
        font-family: Arial, sans-serif;
      }

      .navbar img {
        height: 40px;
      }

      .logo {
        font-size: 1.5rem;
        margin-left: 1rem;
      }

      .header__menu {
        font-size: 1.5rem;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        display: none;
      }

      .navbar-nav {
        list-style: none;
        display: flex;
      }

      .navbar-nav li {
        margin-left: 1rem;
      }

      .navbar-nav .link {
        color: white;
        text-decoration: none;
        font-size: 1rem;
      }

      .navbar-nav .link:hover {
        text-decoration: underline;
      }

      @media (max-width: 768px) {
        .header__menu {
          display: block;
        }

        .navbar-nav {
          position: absolute;
          top: 100%;
          right: 0;
          background-color: #333;
          flex-direction: column;
          width: 100%;
          display: none;
        }

        .navbar-nav.open {
          display: flex;
        }

        .navbar-nav li {
          margin: 0;
          text-align: center;
          padding: 0.5rem 0;
        }
      }
    `;
  }

  render() {  
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <nav class="navbar">
        <img src="../img/EasyBusTix.png" alt="Logo EasyBusTix">
        <h3 class="logo" tabindex="0">EasyBusTix</h3>
        <button id="menu" class="header__menu" aria-label="Menu Navigasi" tabindex="0">☰</button>
        <ul class="navbar-nav">
          <li><a href="tampilan.php" class="link" tabindex="0">Home</a></li>
          <li><a href="profile.php" class="link" tabindex="0">Profile</a></li>
          <li><a href="#" class="link" tabindex="0">History</a></li>
          <li><a href="about-us.php" class="link" tabindex="0">About Us</a></li>
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

customElements.define("bar-app", BarApp);
