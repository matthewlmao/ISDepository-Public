<?php 

  require_once '../config.php'; 

  session_start(); 

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
  } else {
    $user_id = $_SESSION["user_id"]; 
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["listing_id"])) {
    $listing_id = $_GET["listing_id"]; 

echo $listing_id; 
echo $user_id; 

    $sql = "DELETE FROM 
              listings 
            WHERE 
              listing_id = ? 
            AND 
              seller_id = ?
            ";
    if ($stmt = mysqli_prepare($connection, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $param_listing_id, $param_seller_id);

      $param_listing_id = $listing_id; 
      $param_seller_id = $user_id; 
      if (mysqli_stmt_execute($stmt)) { 
          if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['success'] = "Listing deleted successfully.";
            header("location: /ISDepository/");
            exit;
          } else {
            echo "Oops! Something went wrong. Please try again later.";
          } 
        }
    } else {
      echo "Oops! Something went wrong. Please try again later.";
    }
  } 

  mysqli_close($connection); 


?>

