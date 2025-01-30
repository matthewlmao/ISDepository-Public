const headerTemplate = document.createElement('template');

headerTemplate.innerHTML = `
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/ISDepository/assets/dist/css/bootstrap.min.css">
  <script src="/ISDepository/assets/dist/js/bootstrap.min.js"></script>
  <script src="/ISDepository/assets/dist/js/bootstrap.bundle.min.js" ></script>
  <script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
  <!--AJAX Search--> 
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    function showResult(str) {
      if (str.length==0) {
        document.getElementById("livesearch").innerHTML="";
        document.getElementById("livesearch").style.border="0px";
        return;
      }
      var xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          document.getElementById("livesearch").innerHTML=this.responseText;
          document.getElementById("livesearch").style.border="1px solid #A5ACB2";
        }
      }
      xmlhttp.open("GET","search/search-logic.php?q="+str,true);
      xmlhttp.send();
    }
  </script>
  <link rel="stylesheet" href="/ISDepository/style.css">

  <header data-bs-theme="dark">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/ISDepository/">
          <img src="https://upload.wikimedia.org/wikipedia/en/thumb/0/04/ESF_Island_School_%28Hong_Kong%29_Logo.png/180px-ESF_Island_School_%28Hong_Kong%29_Logo.png" alt="Island School Logo" width="35px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse" style="justify-content:center">
          <div class="flex-container" style="justify-content: center">
            <form>
              <input type="text" size="30" onkeyup="showResult(this.value)">
              <div id="livesearch"></div>
            </form>
          </div> 
          <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="float:right">
            <button class="btn btn-primary" type="button"><i class="bi-chat"></i></button>
            <button class="btn btn-primary" type="button"><i class="bi-bookmark"></i></button> 
            <a href="/ISDepository/components/logout.php">
              <button class="btn btn-primary" name="logout" onclick="return confirm('Are you sure you want to log out?');"><i class="bi bi-box-arrow-left"></i></button>
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>
`;

class Header extends HTMLElement {
  constructor() {
    super();
  }

  connectedCallback() {
    const shadowRoot = this.attachShadow({ mode: 'closed' });

    shadowRoot.appendChild(headerTemplate.content);
  }
}

customElements.define('header-component', Header);