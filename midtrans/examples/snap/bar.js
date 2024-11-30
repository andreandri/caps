class BarUserApp extends HTMLElement {
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
        outline: 1px solid #243642;
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
        
      }
    `;
  }

  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <nav class="navbar">
        <div class="navlogo">
        <img src="EasyBus.png" alt="Logo EasyBusTix" tabindex="0">
        <h3 class="logo" tabindex="0">EasyBusTix</h3>
      </nav>
    `;
  }

}

customElements.define("bar-user-app", BarUserApp);
