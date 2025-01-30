<?php 

  require_once '../config.php'; 

  session_start(); 

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /ISDepository/sign-in"); 
    exit; 
  } else {
    $user_id = $_SESSION["user_id"]; 
  }

?>

  <!DOCTYPE html>
  <html lang="en" data-bs-theme="auto">
    
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <title>ISDepository · My Listings</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" ></script>
      <script src="../components/header-component/header-component.js"></script>
      <script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
      <!--AJAX Search--> 
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <link rel="stylesheet" href="/ISDepository/style.css">
      <link rel="stylesheet" href="homepage.css"> 
  
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

    <!-- Products section -->
  
      <?php
        // Select all from listings to display on the page 
        // Using LEFT JOIN clause to join tables together in one query for convenience 
        $sql = " 
          SELECT 
              listings.*, 
              conditions.condition_name AS condition_name, 
              listing_images.image_path AS first_image
          FROM 
              listings
          LEFT JOIN 
              conditions 
              ON listings.condition_id = conditions.condition_id
          LEFT JOIN 
              (SELECT listing_id, MIN(img_id) AS min_image_id FROM listing_images GROUP BY listing_id) AS first_images
              ON listings.listing_id = first_images.listing_id
          LEFT JOIN 
              listing_images 
              ON first_images.min_image_id = listing_images.img_id
          WHERE 
              listings.seller_id = ?";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user_id); // Bind the user_id 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if the records exist
        if ($result && mysqli_num_rows($result) > 0) {

          echo '<h5 class="alert alert-primary" style="margin-left: 1%; margin-right: 1%;">'; 
          echo 'Here are your listings, ' . $_SESSION["username"] . '!'; 
          echo '</h5>';

          echo '<div class="container">'; // Start a container for the card layout

            echo '<div class="row">'; // Start a row for the card layout

            // Iterate while loop for each existing record 
            while ($row = mysqli_fetch_assoc($result)) {
                // Start an individual card for each individual listing 

                echo '<div class="col">'; 

                // Big anchor element that wraps around the whole card without changing its styling so that it can be clicked on, and not just the "view details" button. 
                // I am aware that this is also possible via Bootstrap's stretched-link class, that would render the other buttons unusable. 
                // For convenience and enhancing user experience. 
                  echo '<a href="/ISDepository/listing-details/?listing_id=' . $row['listing_id'] . '" data-toggle="tooltip" style="display: inline" title="View listing">';
                    
                  echo '<div class="card" style="width: 18rem">'; 

                      echo '<img class="card-img-top" style="width: 100%; height: 10vw; object-fit: cover;"
                      src="' . htmlspecialchars($row['first_image']) . '" alt="' . htmlspecialchars($row['title']) . '">'; 

                      echo '<div class="card-body">';
                        echo '<h5 class="card-title" style="color: #000000">' . htmlspecialchars($row['title']) . '</h5>'; // Title
                      echo '</div>'; 

                      echo '<ul class="list-group list-group-flush">'; 
                        echo '<li class="list-group-item">' . htmlspecialchars($row['condition_name']) . '</li>'; // Show condition from condition id
                        echo '<li class="list-group-item"><strong>Price: </strong>' . htmlspecialchars($row['price']) . '</li>'; // Price
                      echo '</ul>';

                      echo '<div class="card-body">'; 
                        echo '<a href="/ISDepository/listing-details/?listing_id=' . $row['listing_id'] . '" class="btn btn-primary" data-toggle="tooltip">Details</a>'; // View details 
                        echo '<a class="btn btn-primary" href="#" role="button"><i class="bi-chat"></i></a>';
                        echo '<a class="btn btn-primary" role="button" onclick="saveListing(' . $row['listing_id'] . ')"><i class="bi-bookmark"></i></a>';
                      echo '</div>'; // End card actions div

                  echo '</div>'; // End card div
                  echo '</a>'; // End anchor
                echo '</div>'; // End column
                // Apologies if the abundance of comments here look strange - it's for clearly separating the different divs. This will help extensibility. 
            }

            echo '</div>'; // End row
        } else {

          echo '<h5 class="alert alert-danger" style="margin-left: 1%; margin-right: 1%;">'; 
            echo "You haven't created any listings, " . $_SESSION['username'] . "!"; 
          echo '</h5>';

          echo '<div class="flex-container" style="justify-contents: center">'; 
            echo '<a href="/ISDepository/create-listing" class="alert alert-danger"><em>Create a new listing now!</em></div>'; 
          echo '</div>'; 
        }

        mysqli_free_result($result);
        mysqli_close($connection);
      
      ?>

    </div> <!-- /.container -->

    <!-- FOOTER -->
    <footer class="container">
      <p class="float-end"><a href="#">Back to top</a></p>
      <p>&copy; 2024 ISDepository &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>

  </main>
  
  