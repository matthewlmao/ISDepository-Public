<?php
session_start(); 
include("../config.php"); 

if(isset($_POST['submit'])){ 
  $email = $_POST['email']; 
  $password = $_POST['password']; 

  // Verify email validity 
  $verify_query = mysqli_query($con,"SELECT * from users where email='$email'"); // Check if it exists in the database 
  if(mysqli_num_rows($verify_query) == 0){ 
    echo "<div class='message'> 
      <p>This email is invalid, sorry T-T</p>
    </div> <br>"; 
    echo "<a href='javascript:self.history.back()'><button class='btn'>Take me back!</button></a>"; 
  }

  // If email is valid, verify password 
  else{
    $sql = mysqli_fetch_assoc($verify_query);   // Fetch password 
    $hashed_password = $user['password']; 

    if(password_verify($password, $hashed_password)) { 
      echo "<div class='message'>
        <p>Welcome back!</p>
      </div>"; 
      $_SESSION['user_email'] = $email;  // Storing email in session 
      header("/homepage.php");  // Redirect to homepage 
      exit(); 
    }

    else{ 
      echo"<div class='message'> 
        <p>Oops! Wrong password.</p>
      </div> <br>"; 
      echo"<a href='javascript:self.history.back()'><button class='btn'>Take me back!</button></a>"; 
    }
  }
}