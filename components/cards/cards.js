

class listingCard extends HTMLElement {
    
    constructor() {
      super();
      this.attachShadow({ mode: 'open' });
      this.shadowRoot.innerHTML = `
      <head> 
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      </head>

        <style>
            /* Add custom styles */
            .bd-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                font-size: 3.5rem;
                }
            }

            .b-example-divider {
                width: 100%;
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
            
            .btn-bd-primary {
                --bd-violet-bg: #712cf9;
                --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

                --bs-btn-font-weight: 600;
                --bs-btn-color: var(--bs-white);
                --bs-btn-bg: var(--bd-violet-bg);
                --bs-btn-border-color: var(--bd-violet-bg);
                --bs-btn-hover-color: var(--bs-white);
                --bs-btn-hover-bg: #6528e0;
                --bs-btn-hover-border-color: #6528e0;
                --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
                --bs-btn-active-color: var(--bs-btn-hover-color);
                --bs-btn-active-bg: #5a23c8;
                --bs-btn-active-border-color: #5a23c8;
            }

            .bd-mode-toggle {
                z-index: 1500;
            }

            .bd-mode-toggle .dropdown-menu .active .bi {
                display: block !important;
            }
            
            .btn-primary {
            color: #ffffff;
            background-color: #ee3d2e;
            border-color: #ffffff
            }

            .btn-primary:hover {
                color: #ffffff;
                background-color: #c93326;
                border-color: #ffffff;
            }

            .btn-primary:focus,
            .btn-primary.focus {
                box-shadow: #7d2017;
            }

            .btn-primary.disabled,
            .btn-primary:disabled {
                color: #212529;
                background-color: #7cc;
                border-color: #5bc2c2
            }

            .btn-primary:not(:disabled):not(.disabled):active,
            .btn-primary:not(:disabled):not(.disabled).active,
            .show>.btn-primary.dropdown-toggle {
                color: #212529;
                background-color: #9cdada;
                border-color: #2e7c7c
            }

            .btn-primary:not(:disabled):not(.disabled):active:focus,
            .btn-primary:not(:disabled):not(.disabled).active:focus,
            .show>.btn-primary.dropdown-toggle:focus {
                box-shadow: 0 0 0 .2rem rgba(91, 194, 194, 0.5)
            }

            .btn-outline-primary {
                color: #ffffff;
                background-color: transparent;
                background-image: none;
                border-color: #ee3d2e;
            }

            .btn-outline-primary:hover {
                color: #ffffff;
                background-color: #ee3d2e;
                border-color: #ffffff;
            }

            .btn-outline-primary:focus,
            .btn-outline-primary.focus {
                box-shadow: 0 0 0 .2rem rgba(119, 204, 204, 0.5)
            }

            .btn-outline-primary.disabled,
            .btn-outline-primary:disabled {
                color: #ee3d2e;
                background-color: transparent
            }

            .btn-outline-primary:not(:disabled):not(.disabled):active,
            .btn-outline-primary:not(:disabled):not(.disabled).active,
            .show>.btn-outline-primary.dropdown-toggle {
                color: #212529;
                background-color: #8ad3d3;
                border-color: #7cc
            }

            .btn-outline-primary:not(:disabled):not(.disabled):active:focus,
            .btn-outline-primary:not(:disabled):not(.disabled).active:focus,
            .show>.btn-outline-primary.dropdown-toggle:focus {
                box-shadow: 0 0 0 .2rem rgba(119, 204, 204, 0.5)
            }
        </style>
        <div class="card text-start" style="width:18rem;">
          <img src="" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title"></h5>
            <p class="card-text"></p>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"></li>
            <li class="list-group-item"></li>
          </ul>
          <div class="card-body">
            <a class="btn btn-primary" href="#" role="button">Make offer</a>
            <a class="btn btn-primary" href="#" role="button"><i class="bi-chat"></i></a>
            <a class="btn btn-primary" href="#" role="button"><i class="bi-bookmark"></i></a>
          </div>
        </div>
      `;
    }
  
    // Define properties for your component
    static get observedAttributes() {
      return ['image', 'title', 'text', 'price', 'condition'];
    }
  
    attributeChangedCallback(name, oldValue, newValue) {
      switch (name) {
        case 'image':
          this.shadowRoot.querySelector('img').src = newValue;
          break;
        case 'title':
          this.shadowRoot.querySelector('h5').textContent = newValue;
          break;
        case 'text':
          this.shadowRoot.querySelector('p.card-text').textContent = newValue;
          break;
        case 'price':
          this.shadowRoot.querySelector('ul li:nth-child(1)').textContent = `\$${newValue}`;
          break;
        case 'condition':
          this.shadowRoot.querySelector('ul li:nth-child(2)').textContent = newValue;
          break;
      }
    }
  }
  
  // Registering it
  customElements.define('listing-card', listingCard);