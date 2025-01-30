<?php 
  session_start(); 

  // Check if user is already logged in 
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ 
    header('Location: /ISDepository/home/');
    echo "You are signed in!";
    exit; 
  }
  
  // Include config file
  require_once '../config.php'; 

  // Define and initialise variables 
  $email = $password = ""; 
  $email_err = $password_err = ""; 

  // Processing data 
  if($_SERVER["REQUEST_METHOD"] == "POST"){ 

    // Check if empty 
    if(empty(trim($_POST["email"]))){ 
      $email_err = "Please enter your email!"; 
    } else{ 
      $email = trim($_POST["email"]); 
    }

    if(empty(trim($_POST["password"]))){ 
      $password_err = "Please enter your password!"; 
    } else{ 
      $password = trim($_POST["password"]); 
    } 

    if(empty($_POST["terms"])){ 
      $terms_err = "Please agree to the terms and conditions!"; 
    } // Only allow users to proceed if they have agreed to the terms 

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
      // Prepare a select statement
      $sql = "SELECT `user_id`, username, email, hashed_password FROM users WHERE email = ?";
      
      if($stmt = mysqli_prepare($connection, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_email);

          // Set parameters 
          $param_email = $email; 
          
          // Attempt to execute the prepared statement 
          if(mysqli_stmt_execute($stmt)){
              // Store result
              mysqli_stmt_store_result($stmt);
              
              // If email exists, check password
              if(mysqli_stmt_num_rows($stmt) == 1){ 
                  // Bind result variables
                  mysqli_stmt_bind_result($stmt, $user_id, $username, $email, $hashed_password);
                  if(mysqli_stmt_fetch($stmt)){
                      if(password_verify($password, $hashed_password) && !empty($_POST["remember"])){
                          
                        // If password is correct, start a new session
                          session_start();
                          
                          // Store data in session variables
                          $_SESSION["loggedin"] = true; 
                          $_SESSION["user_id"] = $user_id; 
                          $_SESSION["email"] = $email; 
                          $_SESSION["username"] = $username; 
                          
                          
                          // Redirect user to homepage
                          header("location: /ISDepository/home/");
                          exit; 
                      } elseif(password_verify($password, $hashed_password) && empty($_POST["remember"])){ 

                        header("location: /ISDepository/home/");
                        exit;

                        // Don't remember user
                      }
                      else{
                          // Generic error msg for invalid pwd
                          $login_err = "Invalid email or password.";
                      }
                  }
              } else{
                  // Generic error msg for non-existent email 
                  $login_err = "Invalid email or password.";
              }
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
        }
    }
  
    // Close connection
    mysqli_close($connection);
  }

?>


<!doctype html>
<html lang="en" data-bs-theme="auto">
  
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>ISDepository · Sign-in</title>
    <link rel="stylesheet" href="sign-in.css">

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

      <a href="/ISDepository">
        <img class="mb-4" src="https://island.edu.hk/wp-content/uploads/2015/12/Island_School-.png" alt="Homepage" width="10%">
      </a>
      <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

      <div class="form-floating">
        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="email" placeholder="name@online.island.edu.hk">
        <label for="floatingInput">Your email...</label>
        <span class="invalid-feedback"><?php echo $email_err; ?></span>
      </div>

      <div class="form-floating">
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" placeholder="Password">
        <label for="floatingPassword">Your password...</label>
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
      </div>

      <div class="form-check text-start my-3">
        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault" name="remember">
        <label class="form-check-label" for="flexCheckDefault">
          Remember me
        </label>
        <span class="invalid-feedback"><?php echo $terms_err; ?></span>
      </div>

      <button id="sign-in-btn" class="btn btn-primary w-100 py-2" type="submit" value="Sign-in">Sign-in</button> 

      <a href="/ISDepository/register" class="btn btn-primary w-100 py-2">
        Register
      </a>

      <a href="/ISDepository/forget-password" class="text-center">
        <i>Forget password?</i>
      </a>

      <p class="mt-5 mb-3 text-body-secondary">&copy; 2024 ISDepository</p>

    </form>
  </main>
</div>

  </body>
</html>

