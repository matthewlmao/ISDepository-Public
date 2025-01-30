<?php

  session_start();

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /ISDepository/sign-in"); 
    exit; 
  } 

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $user_code = $_POST['verification_code'];

      if (isset($_SESSION['verification_code']) && $user_code == $_SESSION['verification_code']) {
          echo "Email verified successfully!";
          // Clear the session
          unset($_SESSION['verification_code']);
      } else {
          echo "Invalid verification code.";
      }
  }

  
?>

<!DOCTYPE html>

    <form method="POST" action="$_SERVER['PHP_SELF']">
        <label for="verification_code">Enter Verification Code:</label>
        <input type="text" id="verification_code" name="verification_code" required>
        <button type="submit">Verify</button>
    </form>
