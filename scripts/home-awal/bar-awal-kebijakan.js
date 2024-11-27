class BarAwalKebijakanApp extends HTMLElement {
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
     .navbar{ 
        background-color: rgba(83, 144, 166, 0.8);

        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 1.5rem;
      }

      .link:hover{
        font-weight: bold;
      }

      .navlogo{
        display: flex;
      }

      .navlogo img{
        width: 5rem;
      }

      .navbar .logo{
        font-size: 1.5em;
      }

      .navbar .navbar-nav{
        list-style-type: none;
        padding-inline: 0;
        display: flex;
        font-size: 1.5em;

        gap: 3rem;
      }

      .navbar :focus{
        outline: 4px dashed #243642;
      }

      a {
        text-decoration: none;
        min-width: 44px;
        min-height: 44px;
        padding: 1rem 0;
      }

      .navbar , .logo, .link{
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
          top: 50px;
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
      }
    `;
  }

  render() {  
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <nav class="navbar">
        <div class="navlogo">
        <img src="img/EasyBusTix.png" alt="Logo EasyBusTix" >
        <h3 class="logo" tabindex="0">EasyBusTix</h3>
        </div>
        <button id="menu" class="header__menu" aria-label="Menu Navigasi" tabindex="0">☰</button>
        <ul class="navbar-nav">
          <li><a href="tampilanawal.php" class="link" tabindex="0">Home</a></li>
          <li><a href="#privacy" class="link" tabindex="0">Kebijakan Privasi</a></li>
          <li><a href="#syarat" class="link" tabindex="0">Syarat dan Ketentuan</a></li>
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

customElements.define("bar-awal-kebijakan-app", BarAwalKebijakanApp);
