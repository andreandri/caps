class IndLoadingAwal extends HTMLElement {
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
      #loading-indicator {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        opacity: 1;
        transition: opacity 0.8s ease-in-out;
      }

      #loading-indicator img {
        width: 280px;
        margin-bottom: 8rem;
      }

      #loading-indicator h1 {
        font-size: 24px;
        font-weight: bold;
        color: #000000;
        margin: 0;
      }

      #loading-indicator p {
        font-size: 18px;
        color: #555555;
        margin-top: 8px;
      }

      #loading-indicator {
        animation: fade-in 0.8s ease-in-out;
      }
    `;
  }

  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
      ${this._style.outerHTML}
      <div id="loading-indicator">
        <img src="img/EasyBusTix.png" alt="EasyBusTix Logo" />
        <h1>Welcome To</h1>
        <p>EasyBusTix</p>
      </div>
    `;
  }
}

customElements.define("ind-loading-awal", IndLoadingAwal);
