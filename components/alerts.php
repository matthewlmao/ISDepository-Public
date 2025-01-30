<?php function alerts() { 

  if(isset($_SESSION['success'])) {
  echo "<h5 class='alert alert-success margin-left: 1%; margin-right: 1%;'>"; 
      $_SESSION['success']; 
  echo "</h5>"; 
    unset($_SESSION['success']); 
  } elseif(isset($_SESSION['error'])) {
  echo '<h5 class="alert alert-danger margin-left: 1%; margin-right: 1%;">'; 
      $_SESSION['error']; 
  echo '</h5>'; 
    unset($_SESSION['error']); 
  } elseif(isset($_SESSION['welcome'])) {
  echo '<h5 class="alert alert-primary margin-left: 1%; margin-right: 1%;">'; 
      echo "Welcome back, " . $_SESSION['username'] . "!"; 
  echo '</h5>';
    unset($_SESSION['welcome']); 
  }

}
