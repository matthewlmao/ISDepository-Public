<?php 
  require_once 'config.php'; 

  session_start(); 

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /ISDepository/sign-in"); 
    exit; 
  } 

  $searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
  $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'relevance'; // Default ordering
  
  $sql = "SELECT 
            listings.*, 
            conditions.condition_name AS condition_name,
            listing_images.image_path AS first_image
          FROM listings
          LEFT JOIN conditions ON listings.condition_id = conditions.condition_id
          LEFT JOIN listing_images ON listings.listing_id = listing_images.listing_id 
          LEFT JOIN listing_tags ON listings.listing_id = listing_tags.listing_id 
          GROUP BY listings.listing_id";
  
  //Order the results if there is an order parameter
  switch ($orderBy) {
      case 'newest':
          $sql .= "ORDER BY listings.createdAt DESC";
          break;
      case 'oldest':
          $sql .= "ORDER BY listings.createdAt ASC";
          break;
      case 'price_asc':
          $sql .= "ORDER BY listings.price ASC";
          break;
      case 'price_desc':
          $sql .= "ORDER BY listings.price DESC";
          break;
      default: // Relevance is default
          $sql .= "ORDER BY relevance DESC";
          break;
  }
  $sql .= " LIMIT 50"; // Limiting results to improve sorting speed. However this will lead to some inaccuracies if the desired result is not in the first 50 results. 
  // While the limit is a necessary sacrifice, a "show more" button could be added to allow users to see more results to alleviate this issue. 
  
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("ss", $searchQuery, $searchQuery);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $products = array();
  while ($row = $result->fetch_assoc()) {
      $products[] = $row;
  }
  
  header('Content-Type: application/json');
  echo json_encode($products);
  
  $stmt->close();
  $connection->close(); 

?> 

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
  
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>ISDepository · Search</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/ISDepository/assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <script src="/ISDepository/assets/dist/js/bootstrap.min.js"></script>
    <script src="/ISDepository/assets/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
    <script src="/ISDepository/components/header-component/header-component.js"></script>
    <link rel="stylesheet" href="style.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/nextapps-de/flexsearch@0.7.41/dist/flexsearch.bundle.min.js"></script>
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

  <div class="flex-container w-auto" style="flex-direction: column;">

  <div class="search-container">
    <input class="form-control" type="text" id="searchInput" placeholder="Search listings...">
    <div id="suggestionsContainer" class="suggestions-dropdown"></div>
</div>

  <br>

  <div class="flex-container justify-content-end">
    <label for="orderBy" class="form-label">Order by: </label>
      <select id="orderBy" class="border border-secondary-subtle rounded">
          <option value="relevance">Relevance</option>
          <option value="newest">Newest</option>
          <option value="oldest">Oldest</option>
          <option value="price_asc">Price Ascending</option>
          <option value="price_desc">Price Descending</option>
      </select>
  </div>

      <br>

      <ul id="resultsList"></ul>
      <script src="https://cdn.jsdelivr.net/npm/flexsearch@0.7.31/dist/flexsearch.bundle.js"></script>
      <script src="/ISDepository/search-logic.js"></script>

  </div>

    <!-- FOOTER -->
    <footer class="container">
      <p class="float-end"><a href="#">Back to top</a></p>
      <p>&copy; 2024 ISDepository &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>

  </main>

