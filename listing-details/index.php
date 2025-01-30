<?php

  require_once '../config.php';

  session_start();

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /ISDepository/sign-in");
    exit;
  } else {
    $user_id = $_SESSION["user_id"];
  }

  $listing_id = $_GET['listing_id'];

  $seller = $title = $desc = $price = $condition = $tags = $created_at = ""; 
  $tags_array = array();

  // Check existence of id parameter before any processing 
  if(isset($_GET["listing_id"]) && !empty(trim($_GET["listing_id"]))){
      
      // Prepare a select statement
      $sql = "SELECT 
                  listings.*, 
                  users.username AS seller_username, /* uses username from users table as the seller name */
                  conditions.condition_name, 
                  GROUP_CONCAT(tags.tag_name SEPARATOR ',') AS tags /* using group_concat to get all tags associated with the listing */ 
              FROM 
                  listings
              LEFT JOIN 
                  users ON listings.seller_id = users.user_id /* using left join to get the seller name using their user id */ 
              LEFT JOIN 
                  conditions ON listings.condition_id = conditions.condition_id 
              LEFT JOIN 
                  listing_tags ON listings.listing_id = listing_tags.listing_id /* using left join to get all tags associated with the listing */
              LEFT JOIN 
                  tags ON listing_tags.tag_id = tags.tag_id
              WHERE 
                  listings.listing_id = ?
              GROUP BY 
                  listings.listing_id;
              ";  

      
      if($stmt = mysqli_prepare($connection, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_id);
          
          // Set parameters
          $param_id = trim($_GET["listing_id"]);
          
          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              $result = mysqli_stmt_get_result($stmt);
      
              if(mysqli_num_rows($result) == 1){

                  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                  
                  $seller = $row["seller_username"];
                  $title = $row["title"];
                  $desc = $row["description"];
                  $price = $row["price"];
                  $condition = $row["condition_name"];
                  $created_at = $row["created_at"];
                  $tags = $row["tags"];
                  $tags_array = array_map("trim", explode(", ", $tags));

              } else {
                $_SESSION['error'] = "Uh oh! Something went wrong.";
                header("location: /ISDepository");
                exit();
              }
              
          } else {
              $_SESSION['error'] = "Uh oh! Something went wrong.";
              header("location: /ISDepository"); 
              exit();
          }
      }
      
      // Close statement
      mysqli_stmt_close($stmt);
            
  } elseif (!isset($_GET['listing_id'])) {
      $_SESSION['error'] = "No listing selected.";
      header("location: /ISDepository/home");
    exit;
  }

  // Handle save listing 
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['listing_id'])) {
    $listing_id = $_POST['listing_id'];
    
    // Check if already saved
    $sql_check = "SELECT COUNT(*) FROM saved_listings WHERE `user_id` = ? AND listing_id = ?";
    $stmt_check = mysqli_prepare($connection, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ss", $user_id, $listing_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $count);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);

    if ($count > 0) {
        $_SESSION['error'] = "This listing has already been saved.";
    } else {
        $sql_save_listing = "INSERT INTO saved_listings (`user_id`, listing_id) VALUES (?, ?)";
        $stmt_save_listing = mysqli_prepare($connection, $sql_save_listing);
        mysqli_stmt_bind_param($stmt_save_listing, "ss", $user_id, $listing_id);
        
        if (mysqli_stmt_execute($stmt_save_listing)) {
            $_SESSION['message'] = "Listing saved successfully.
            <a href='/ISDepository/saved-listings'><i>View saved listings</i></a>";

            // Log listing's tags as user interest 
            $sql_interest = "INSERT INTO user_interests (`user_id`, tag_id, related_listing, interacted_at)
                                SELECT 
                                    ? AS `user_id`, 
                                    listing_tags.tag_id, 
                                    ? AS related_listing,
                                    current_timestamp() AS interacted_at
                                FROM 
                                    listing_tags
                                WHERE 
                                    listing_tags.listing_id = ?
                            ";
            $stmt_interest = mysqli_prepare($connection, $sql_interest);
            mysqli_stmt_bind_param($stmt_interest, "sss", $user_id, $listing_id, $listing_id);
            mysqli_stmt_execute($stmt_interest);
            mysqli_stmt_close($stmt_interest);

        } else {
            $_SESSION['error'] = "Error saving listing: " . mysqli_error($connection); 

        }
        mysqli_stmt_close($stmt_save_listing);
    }
    
    header("Location: /ISDepository/home"); 
    // Used to be $_SERVER['PHP_SELF'], but changed to home to prevent index.php extension. 
    // For extensibility - use .htaccess to remove file extensions, trailing slashes, etc. 
    exit;
}
?>


<!doctype html>
<html lang="en" data-bs-theme="auto">
  
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title> <?php echo $title; ?> </title>
    <link rel="stylesheet" href="listing-details.css">
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

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
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

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">
                      <b><?php echo $title; ?></b>
                    </h1>

                    <div class="form-group">
                        <label><b>Seller</b></label>
                        <a href="/ISDepository/profile/?seller=<?php echo $seller; ?>">
                          <p><?php echo $seller; ?></p>
                        </a>
                    </div>

                    <div class="form-group">
                        <label><b>Description</b></label>
                        <p><?php echo $desc; ?></p>
                    </div>                    
                    
                    <div class="form-group">
                        <label><b>Price</b></label>
                        <p><?php echo $price; ?></p>
                    </div>

                    <div class="form-group">
                        <label><b>Condition</b></label>
                        <p><?php echo $condition; ?></p>
                    </div>

                    <div class="form-group"> 
                        <label><b>Tags</b></label>
                          <?php foreach ($tags_array as $tag) { ?>
                            <p> <?php echo $tag; ?></p>
                          <?php } ?>
                        </div>

                    <div class="form-group">
                        <label><b>Date Created</b></label>
                        <p><?php echo $created_at; ?></p>
                    </div>

                    <div class="flex-container justify-content-lg-start">
                      <div class="row">
                        <div class="col">

                          <?php if ($_SESSION["username"] == $seller) { 
                            echo '<a href="/ISDepository/edit-listing/?listing_id=' . $listing_id . '" class="btn btn-primary">Edit</a>';
                          } else { 
                            echo '<form method="post">';
                            echo '<input type="hidden" name="listing_id" value="' . $row['listing_id'] . '">';
                            echo '<button type="submit" class="btn btn-primary">Save</button>';
                          echo '</form>';
                          } ?>
                        </div>

                        <div class="col">
                          <p><a href="/ISDepository/home" class="btn btn-primary">Back</a></p>
                        </div>

                      </div>
                    </div>

                </div>
            </div> 
        </div>
    </div>

  <!-- FOOTER -->
  <footer class="container">
    <p class="float-end"><a href="#">Back to top</a></p>
    <p>&copy; 2024 ISDepository &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
  </footer>

  </main>
  </body>
</html>
