<?php 
  require_once'config.php'; 

  session_start(); 

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header('Location: /ISDepository/home/');
    exit;
}

?> 

<!doctype html>
<html lang="en" data-bs-theme="auto">
  
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>ISDepository · Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/dist/css/bootstrap.min.css">
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="components/header-component/header-component.js"></script>
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
    <link rel="stylesheet" href="style.css">

  </head>

  <body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="check2" viewBox="0 0 16 16">
        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
      </symbol>
      <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
      </symbol>
      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
      </symbol>
      <symbol id="sun-fill" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
      </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle"> <!-- Light or dark mode toggle -->
      <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
              id="bd-theme"
              type="button"
              aria-expanded="false"
              data-bs-toggle="dropdown"
              aria-label="Toggle theme (auto)">
        <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Light
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Dark
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Auto
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
      </ul>
    </div>

  <header-component></header-component>

  <main>

    <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel"> <!--Carousel-->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="https://island.edu.hk/wp-content/uploads/2022/08/NAM_1372-1.jpg" class="img-fluid" max-width="100%" max-height="100%" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
          <div class="container">
            <div class="carousel-caption">
              <h1>Buy and sell. Cheap and fast. Start now.</h1>
              <p class="opacity-85">The new one-stop shop for second-hand academic supplies</p>
              <p><a class="btn btn-lg btn-primary" href="sign-in">Sign up now</a></p>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://island.edu.hk/wp-content/uploads/2023/08/IMG_3330-1.jpg" class="img-fluid" max-width="100%" max-height="100%" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
        <div class="container">
            <div class="carousel-caption">
              <h1>Exclusive for Island School students!</h1>
              <p class="opacity-85">Tailored to the needs of our tight-knit community.</p>
              <p><a class="btn btn-lg btn-primary" href="sign-in">Sign up now</a></p>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://island.edu.hk/wp-content/uploads/2023/08/IMG_3303-1.jpg" class="img-fluid" max-width="100%" max-height="100%" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
          <div class="container">
            <div class="carousel-caption">
              <h1>Start browsing now!</h1>
              <p class="opacity-85">Browse according to year group, subject, and more!</p>
              <p><a class="btn btn-lg btn-primary" href="sign-in">Browse</a></p>
            </div>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <div class="container sample-products"> <!-- Products section -->

      <div class="flex-container"> <!-- big container -->
        
        <script src="components/cards/listing-card.js" type="module"></script>
        
        <div class="flex-container"> <!-- big container -->
          <listing-card
            image="https://media.karousell.com/media/photos/products/2022/6/13/ib_math_aasl_1655130640_efc42469_progressive.jpg"
            title="IB Mathematics AASL Textbook"
            text="Price negotiable"
            price="250"
            condition="Brand new"
          ></listing-card>
        </div> <!-- card cont -->

        <div class="flex-container"> <!--card-->
          <listing-card
            image="https://media.karousell.com/media/photos/products/2022/6/13/ib_math_aasl_1655130640_efc42469_progressive.jpg"
            title="IB Mathematics AASL Textbook"
            text="Price negotiable"
            price="250"
            condition="Brand new"
          ></listing-card>
        </div> <!-- card cont -->

        <div class="flex-container"> <!--card-->
          <listing-card
            image="https://media.karousell.com/media/photos/products/2022/6/13/ib_math_aasl_1655130640_efc42469_progressive.jpg"
            title="IB Mathematics AASL Textbook"
            text="Price negotiable"
            price="250"
            condition="Brand new"
          ></listing-card>
        </div> <!-- card cont -->

    </div> <!-- /.container -->

    <!-- FOOTER -->
    <footer class="container">
      <p class="float-end"><a href="#">Back to top</a></p>
      <p>&copy; 2024 ISDepository &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>

  </main>

  <script> 

    // Fetching stuff for listing ^-^
    document.addEventListener('DOMContentLoaded', () => {
        const productList = document.getElementById('product-list');
    // Fetch listings from php 
        fetch('fetch-listing.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not okay (I promise)');   // My Chemical Romance reference. Sorry (not sorry). 
                }
                return response.json();
            })
            // Where the magic happens... to show fetched data in listing cards 
            .then(data => {
                data.forEach(listing => {
                    const card = document.createElement('listing-card');
                    card.setAttribute('image', listing.image_url);
                    card.setAttribute('title', listing.title);
                    card.setAttribute('text', listing.description);
                    card.setAttribute('price', listing.price);
                    card.setAttribute('condition', listing.condition_name);
                    card.setAttribute('listing-id', listing.listing_id);

                    productList.appendChild(card);
                });
            })
            .catch(error => console.error('Oops! Could not fetch listings because...', error));
    });

  </script>




<!--Header 

    <header data-bs-theme="dark">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">
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
                <button class="btn btn-primary me-md-2" type="button"><i class="bi-chat"></i></button>
                <button class="btn btn-primary" type="button"><i class="bi-bookmark"></i></button>
              </div>
            </div>
          </div>
        </nav>
      </header>

  -->