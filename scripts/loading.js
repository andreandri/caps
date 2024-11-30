class IndLoading extends HTMLElement {
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
    .loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;

      display: flex;
      justify-content: center;
      align-items: center;

      background-color: rgba(1, 2, 1, 0.8);
    }

    .loading {
      border: 0.8rem dotted #F5F5F5;
      border-radius: 50%;
      animation: loding 1s linear infinite;

      width: 4.5rem;
      height: 4.5rem;
    }

    @keyframes loding {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  `;
  }

  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
  ${this._style.outerHTML}
    <div class ="loader"> 
      <div class ="loading"> </div>
    </div>
  `;
  }
}

customElements.define("ind-loading", IndLoading);
