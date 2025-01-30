<?php 

  function saveListing() { 

    require_once '../config.php';

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
              $_SESSION['message'] = "Listing saved successfully.";
          } else {
              $_SESSION['error'] = "Error saving listing: " . mysqli_error($connection);
          }
          mysqli_stmt_close($stmt_save_listing);
      }

    }
  