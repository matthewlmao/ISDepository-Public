<?php 
  require_once '../config.php'; 
  require_once '../api/suggest/suggest-logic.php';
  session_start(); 

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: /ISDepository/sign-in"); 
      exit; 
  } else {
      $user_id = $_SESSION["user_id"]; 
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

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>ISDepository · Home</title>
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

  <!-- Dynamic top alert messages -->

  <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>
  
  <?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if(isset($_SESSION['welcome'])): ?>
    <div class="alert alert-success"><?= $_SESSION['welcome'] ?></div>
    <?php unset($_SESSION['welcome']); ?>
  <?php endif; ?>

      <!-- Products section -->
      <div class="container">
        <?php
        // Query to fetch listings joined with first image and condition name
        $decayLambda = 0.01; 

        $sql = "
            SELECT 
                listings.*,
                conditions.condition_name,
                listing_images.image_path AS first_image,
                COALESCE(basic_score, 0) AS basic_score,
                COALESCE(cosine_score, 0) AS cosine_score,
                COALESCE(tfidf_score, 0) AS tfidf_score,
                COALESCE(temporal_score, 0) AS temporal_score,
                COALESCE(basic_score, 0) + 
                COALESCE(cosine_score, 0) + 
                COALESCE(tfidf_score, 0) + 
                COALESCE(temporal_score, 0) AS total_score
            FROM listings
            LEFT JOIN conditions 
                ON listings.condition_id = conditions.condition_id
            LEFT JOIN (
                SELECT listing_id, MIN(img_id) AS min_image_id 
                FROM listing_images 
                GROUP BY listing_id
            ) AS first_images 
                ON listings.listing_id = first_images.listing_id
            LEFT JOIN listing_images 
                ON first_images.min_image_id = listing_images.img_id
            LEFT JOIN (
                -- Basic Dot Product Score
                SELECT lt.listing_id, COUNT(*) AS basic_score
                FROM listing_tags lt
                INNER JOIN user_interests ui 
                    ON lt.tag_id = ui.tag_id 
                    AND ui.user_id = ?
                GROUP BY lt.listing_id
            ) basic 
                ON listings.listing_id = basic.listing_id
            LEFT JOIN (
                -- Cosine Similarity Score
                SELECT lt.listing_id,
                      SUM(ui.weight * 1) /  -- tag_presence = 1 (since tag exists)
                      (SQRT(SUM(POW(ui.weight, 2))) * SQRT(COUNT(*))) AS cosine_score
                FROM listing_tags lt
                INNER JOIN (
                    SELECT tag_id, COUNT(*) AS weight
                    FROM user_interests
                    WHERE user_id = ?
                    GROUP BY tag_id
                ) ui 
                    ON lt.tag_id = ui.tag_id
                GROUP BY lt.listing_id
            ) cosine 
                ON listings.listing_id = cosine.listing_id
            LEFT JOIN (
                -- TF-IDF Score
                SELECT lt.listing_id, SUM(ui.weight * tfidf.idf) AS tfidf_score
                FROM listing_tags lt
                INNER JOIN (
                    SELECT tag_id, COUNT(*) AS weight
                    FROM user_interests
                    WHERE user_id = ?
                    GROUP BY tag_id
                ) ui 
                    ON lt.tag_id = ui.tag_id
                INNER JOIN (
                    SELECT tag_id, 
                          LOG((SELECT COUNT(*) FROM listings) / COUNT(DISTINCT listing_id)) AS idf
                    FROM listing_tags
                    GROUP BY tag_id
                ) tfidf 
                    ON lt.tag_id = tfidf.tag_id
                GROUP BY lt.listing_id
            ) tfidf 
                ON listings.listing_id = tfidf.listing_id
            LEFT JOIN (
                -- Temporal Decay Score
                SELECT lt.listing_id, 
                      SUM(EXP(-? * DATEDIFF(NOW(), ui.interacted_at))) AS temporal_score
                FROM listing_tags lt
                INNER JOIN user_interests ui 
                    ON lt.tag_id = ui.tag_id 
                    AND ui.user_id = ?
                GROUP BY lt.listing_id
            ) temporal 
                ON listings.listing_id = temporal.listing_id
            ORDER BY total_score DESC;
        ";
    
        $stmt_relevance = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt_relevance, "sssis", $user_id, $user_id, $user_id, $decayLambda, $user_id); 
        mysqli_stmt_execute($stmt_relevance);
        $result = mysqli_stmt_get_result($stmt_relevance);

        // Loop to dynamically generate cards 
        if ($result && mysqli_num_rows($result) > 0) {
            echo '<div class="row">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col">';
                echo '<a href="/ISDepository/listing-details/?listing_id=' . $row['listing_id'] . '" data-toggle="tooltip" style="display: inline" title="View listing">';
                  echo '<div class="card" style="width: 18rem">';
                    echo '<img class="card-img-top" style="width: 100%; height: 10vw; object-fit: cover;"
                          src="' . htmlspecialchars($row['first_image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                    echo '<div class="card-body">';
                      echo '<h5 class="card-title" style="color: #000000">' . htmlspecialchars($row['title']) . '</h5>';
                    echo '</div>';
                    echo '<ul class="list-group list-group-flush">'; 
                      echo '<li class="list-group-item">' . htmlspecialchars($row['condition_name']) . '</li>';
                      echo '<li class="list-group-item"><strong>HKD </strong>' . htmlspecialchars($row['price']) . '</li>';

                      // For troubleshooting 
                      // echo "Basic Score: " . $row['basic_score'] . "<br>";
                      // echo "Cosine Score: " . $row['cosine_score'] . "<br>";
                      // echo "TF-IDF Score: " . $row['tfidf_score'] . "<br>";
                      // echo "Temporal Score: " . $row['temporal_score'] . "<br>";
                      // echo "Total Score: " . $row['total_score'] . "<br>";

                    echo '</ul>';
                    echo '<div class="card-body">'; 
                      echo '<a href="/ISDepository/listing-details/?listing_id=' . $row['listing_id'] . '" class="btn btn-primary" data-toggle="tooltip">Details</a>';
                      echo '<a class="btn btn-primary" href="#" role="button"><i class="bi-chat"></i></a>';
                  
                    // Save listing form
                    echo '<form method="post">';
                      echo '<input type="hidden" name="listing_id" value="' . $row['listing_id'] . '">';
                      echo '<button type="submit" class="btn btn-primary"><i class="bi-bookmark"></i></button>';
                    echo '</form>';

                  echo '</div>'; // End card actions
                  echo '</div>'; // End card
                echo '</a>'; // End anchor
                echo '</div>'; // End col
            }
            echo '</div>'; // End row
        } else {
            echo '<div class="flex-container justify-content-center">'; 
              echo '<div class="alert alert-danger"><em>No records were found.</em></div>'; 
            echo '</div>'; 
        }

        mysqli_free_result($result);
        mysqli_close($connection);
        ?>
      </div>
    </main>

    <!-- Footer -->
    <footer class="container">
      <p class="float-end"><a href="#">Back to top</a></p>
      <p>&copy; 2024 ISDepository &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>
  </body>
</html>