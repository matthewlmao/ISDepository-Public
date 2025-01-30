const headerTemplate = document.createElement('template');

headerTemplate.innerHTML = `
  <style>
    /* Import all external styles */
    @import "https://cdn.jsdelivr.net/npm/@docsearch/css@3";
    @import "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css";
    @import "/ISDepository/assets/dist/css/bootstrap.min.css";
    @import "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css";
    @import "/ISDepository/style.css";

    /* Select2 specific styles that need to be included inside shadow DOM. It will break if this is not here. Lol. */
    .select2-container {
      z-index: 10000;
      width: 300px !important;
    }
    
    .select2-container--default .select2-selection--single {
      background-color: #fff;
      border: 1px solid #aaa;
      border-radius: 4px;
      height: 38px;
      display: flex;
      align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px;
    }

    /* Ensure the dropdown appears above other elements */
    .select2-dropdown {
      z-index: 10001;
    }
  </style>

  <header data-bs-theme="dark">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/ISDepository/">
          <img src="https://island.edu.hk/wp-content/uploads/2015/12/Island_School-.png" alt="Island School Logo" width="35px">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="position-absolute start-50 translate-middle-x">
            <a href="/ISDepository/search.php">
              <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"> 
            </a>
          </div>
          <div class="ms-auto d-flex gap-2">
            <a href="/ISDepository/messages"><button class="btn btn-primary" type="button"><i class="bi-chat"></i></button></a>
            <a href="/ISDepository/saved-listings"><button class="btn btn-primary" type="button"><i class="bi-bookmark"></i></button></a>
            <a href="/ISDepository/create-listing"><button class="btn btn-primary" type="button"><i class="bi bi-plus-circle"></i></button></a>
            <a href="/ISDepository/my-listings"><button class="btn btn-primary" type="button"><i class="bi bi-person-circle"></i></button></a>
            <a href="/ISDepository/components/logout.php"><button class="btn btn-primary" name="logout" onclick="return confirm('Are you sure you want to log out?');"><i class="bi bi-box-arrow-left"></i></button></a>
          </div>
        </div>
      </div>
    </nav>
  </header>
`;

class Header extends HTMLElement {
  constructor() {
    super();
    this._shadowRoot = this.attachShadow({ mode: 'open' });
    this._shadowRoot.appendChild(headerTemplate.content.cloneNode(true));
  }

  connectedCallback() {
    this.initializeSelect2();
  }

  initializeSelect2() {
    const selectElement = this._shadowRoot.querySelector('.js-example-basic-single');
    if (selectElement && window.jQuery && typeof window.jQuery().select2 === 'function') {
      window.jQuery(selectElement).select2({
        dropdownParent: document.body,
        width: '300px'
      });
    } else {
      console.error('Select element not found or jQuery/Select2 not loaded on the main page.');
    }
  }
}

customElements.define('header-component', Header);
