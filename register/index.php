<?php

  session_start(); 

  require_once '../config.php';
 
  $email = $password = $confirm_password = ""; // Hold user input 
  $email_err = $password_err = $confirm_password_err = ""; // Hold potential errors
  
  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){

      // Validate email
      if(empty(trim($_POST["email"]))){
          $email_err = "Please enter an Island School email.";
      } elseif(!preg_match('/^[^\s@]+@online\.island\.edu\.hk$/', trim($_POST["email"]))){ // Check that email has Island School domain 
          $email_err = "Only Island School emails are allowed.";
      } else{
          // If no errors, prepare a select statement
          $sql = "SELECT `user_id` FROM users WHERE email = ?";
          
          if($stmt = mysqli_prepare($connection, $sql)){
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "s", $param_email);
              
              $param_email = trim($_POST["email"]);
              
              // Attempt to execute the prepared statement
              if (mysqli_stmt_execute($stmt)){
                  mysqli_stmt_store_result($stmt);
                  
                  if (mysqli_stmt_num_rows($stmt) == 1) {
                      $email_err = "This email is already taken.";

                  } else { // On email success  
                      $email = trim($_POST["email"]);
                      // Automatically generate username by removing email domain 
                      $domain = ['@online.island.edu.hk']; 
                      $fullStop = ['.']; 
                      // Remove domain and full stop from email to automatically generate username. 
                      $username = ucwords(str_replace('.', ' ', str_replace('@online.island.edu.hk', '', trim($_POST["email"]))));
                      // ucwords() capitalizes the first letter of each word in a string. 
                  }
              } else {
                  echo "Oops! Something went wrong. Please try again later.";
              }

              mysqli_stmt_close($stmt);
          }
      }
      
      // Validate password
      if(empty(trim($_POST["password"]))){
          $password_err = "Please enter a password."; 
      } elseif(strlen(trim($_POST["password"])) < 8){
          $password_err = "Password must have at least 8 characters.";
      } elseif(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[\d])(?=.*[^\w]).+$/', $_POST["password"])){
          $password_err = "Password must contain at least one upper case letter, lower case letter, number, and special character."; // Ensure password is secure by enforcing standard practice
      } else{
          $password = trim($_POST["password"]);
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);  

      }
      
      // Confirm password 
      if(empty(trim($_POST["confirm_password"]))){
          $confirm_password_err = "Please confirm password.";     
      } else{
          $confirm_password = trim($_POST["confirm_password"]);
          if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Passwords did not match.";
          }
      }
      
      // Check for any errors in user input before inserting into database 
      if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && !empty($_POST["terms"])){
          
          // Prepare an insert statement
          $sql = "INSERT INTO users (`user_id`, username, email, hashed_password, created_at) VALUES (UUID(), ?, ?, ?, current_timestamp())";
          
          if($stmt = mysqli_prepare($connection, $sql)){
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
              
              // Set parameters
              // Separates the user-input data and the query to defend database against SQL injections!! 
              $param_email = $email;
              $param_password = $hashed_password; 
              $param_username = $username; 
              
              if(mysqli_stmt_execute($stmt)){

                // Get the last inserted ID to set as user id 
                // Unfortunately, MySQLi does not have a built-in function to get the last inserted ID.
                // last_insert_id() only works for auto-increment columns T-T 
                $result_last_id = mysqli_query($connection, "SELECT `user_id` FROM users ORDER BY created_at DESC LIMIT 1");
                if ($result_last_id->num_rows > 0) {
                    $row = $result_last_id->fetch_assoc();
                    $user_id = $row['user_id'];
                }

                  session_start();
      
                  // Store data in session variables
                  $_SESSION["loggedin"] = true;
                  $_SESSION["user_id"] = $user_id;
                  $_SESSION["email"] = $email; 
                  $_SESSION["username"] = $username; 
                  $_SESSION["welcome"] = "Welcome to ISDepository, " . $username . "!";
                  
                  header("location: /ISDepository/home/");
                  exit; 

              } else{
                  echo "Oops! Something went wrong. Please try again later.";
              }

              mysqli_stmt_close($stmt);
          }
      }
      
      mysqli_close($connection);
    }

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
  
  <head><script src="/assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>ISDepository · Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="../components/header-component/header-component.js"></script>
    <script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="/ISDepository/style.css">

  </head>

  <body class="d-flex align-items-center py-4 bg-body-tertiary">
    
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

<div class="container"> <!--Div for sign-in form-->

  <main class="form-signin w-100 m-auto">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

      <!--Rest of the form--> 
      <div class="form-group">
        <a href="/ISDepository/">
          <img class="mb-4" src="https://island.edu.hk/wp-content/uploads/2015/12/Island_School-.png" alt="Homepage" width="10%">
        </a>
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
          <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="email" placeholder="name@online.island.edu.hk" name="email">
          <label for="floatingInput">Your email...</label>
          <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>

        <div class="form-floating">
          <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" id="password" placeholder="Password" name="password">
          <label for="floatingPassword">Your password...</label>
          <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>

        <div class="form-floating">
          <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" id="confirm_password" placeholder="Password" name="confirm_password">
          <label for="floatingPassword">Confirm password...</label>
          <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="form-check text-start my-3">
          <input class="form-check-input" type="checkbox" id="flexCheckDefault" name="terms">
          <label class="form-check-label" for="flexCheckDefault">
            I understand the terms and conditions 
          </label>
        </div>

        <button id="sign-in-btn" class="btn btn-primary w-100 py-2" type="submit" value="Register">Register</button> 

        <p>Already have an account? <a href="/ISDepository/sign-in/">Sign-in here</a>.</p>

        <p class="mt-5 mb-3 text-body-secondary">&copy; 2024 ISDepository</p>
      </div>
    </form>
  </main>
</div>
  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>



  </body>
</html>

