<?php 
require_once '../config.php'; 
session_start(); 

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /ISDepository/sign-in"); 
    exit; 
} else {
    $user_id = $_SESSION["user_id"]; 
}

// Listing removal logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['listing_id'])) {
    $listing_id = $_POST['listing_id'];
    
    $sql_remove = "DELETE FROM saved_listings WHERE `user_id` = ? AND listing_id = ?";
    $stmt_remove = mysqli_prepare($connection, $sql_remove);
    mysqli_stmt_bind_param($stmt_remove, "ss", $user_id, $listing_id);
    
    if (mysqli_stmt_execute($stmt_remove)) { 
      $sql_remove_interests = "DELETE FROM user_interests WHERE `user_id` = ? AND related_listing = ?";
      $stmt_remove_interests = mysqli_prepare($connection, $sql_remove_interests);
      mysqli_stmt_bind_param($stmt_remove_interests, "ss", $user_id, $listing_id);
      mysqli_stmt_execute($stmt_remove_interests);

        $_SESSION['message'] = "Listing removed successfully.";
    } else {
        $_SESSION['error'] = "Error removing listing: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt_remove);
    
    header("Location: " . $_SERVER['PHP_SELF']);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="../components/header-component/header-component.js"></script>
    <script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
    <!--AJAX Search--> 
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="/ISDepository/style.css">
    <link rel="stylesheet" href="homepage.css"> 
  </head>

  <body>

    <header-component></header-component>

    <main>
      <!-- Top alerts -->
      <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>
      
      <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- No welcome message; only on homepage -->

        <?php
        // Fetch saved listings
        $sql = "
            SELECT 
                listings.*, 
                conditions.condition_name AS condition_name, 
                listing_images.image_path AS first_image
            FROM 
                saved_listings
            JOIN 
                listings ON saved_listings.listing_id = listings.listing_id
            LEFT JOIN 
                conditions ON listings.condition_id = conditions.condition_id
            LEFT JOIN 
                (SELECT listing_id, MIN(img_id) AS min_image_id FROM listing_images GROUP BY listing_id) AS first_images
                ON listings.listing_id = first_images.listing_id
            LEFT JOIN 
                listing_images ON first_images.min_image_id = listing_images.img_id
            WHERE 
                saved_listings.user_id = ?
        ";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user_id); // Bind the user_id 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {

          echo '<h5 class="alert alert-primary" style="margin-left: 1%; margin-right: 1%;">'; 
            echo "Here are your listings, " . $_SESSION['username'] . "!"; 
          echo '</h5>'; 
          
          echo '<div class="container">';
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
                echo '</ul>';
                echo '<div class="card-body">'; 
                echo '<a href="/ISDepository/listing-details/?listing_id=' . $row['listing_id'] . '" class="btn btn-primary" data-toggle="tooltip">Details</a>';
                echo '<a class="btn btn-primary" href="#" role="button"><i class="bi-chat"></i></a>';
                
                // Remove listing, refers to the logic at the top 
                echo '<form method="post">';
                echo '<input type="hidden" name="listing_id" value="' . $row['listing_id'] . '">';
                echo '<button type="submit" class="btn btn-primary"><i class="bi bi-trash"></i></button>';
                echo '</form>';

                echo '</div>'; // End card actions
                echo '</div>'; // End card
                echo '</a>'; // End anchor
                echo '</div>'; // End col
            }
            echo '</div>';
        } else {
            echo '<h5 class="alert alert-danger" style="margin-left: 1%; margin-right: 1%;">'; 
            echo "You have no saved listings, " . $_SESSION['username'] . "!"; 
            echo '</h5>'; 
          
            echo '<div class="flex-container" style="justify-contents: center">'; 
            echo '<div class="alert alert-danger"><em>No saved listings found.</em></div>'; 
            echo '</div>'; 
        }

        mysqli_free_result($result);
        mysqli_close($connection);
        ?>
      </div>
    </main>

    <footer class="container">
      <p class="float-end"><a href="#">Back to top</a></p>
      <p>&copy; 2024 ISDepository &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>
  </body>
</html>