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
    #loading-indicator {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(1, 2, 1, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 10000;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #007bff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
  `;
  }

  render() {
    this.updateStyle();

    this.shadowRoot.innerHTML = `
  ${this._style.outerHTML}
    <div id="loading-indicator">
    <div class="spinner"></div>
  </div>
  
  `;
  }
}

customElements.define("ind-loading", IndLoading);
