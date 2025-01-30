<?php
  // Initialize the session
  session_start();

  // If a session is detected, it will log the user out 
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ 

    // Unset all of the session variables
    session_unset(); 

    // Destroy current session.
    session_destroy();
    
    // Redirect to un-logged-in index.
    header("location: /ISDepository/");
    exit;
  } else{ 
    echo "You are not logged in."; 
  }