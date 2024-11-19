<?php
session_start();
require '/config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validating user inputs 
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Preparing select statement from database 
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        // Execute statement 
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            // Check validity of username and password 
            if (mysqli_stmt_num_rows(statement: $stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    // Verify the password
                    if (password_verify($password, $hashed_password)) {
                        session_regenerate_id(); // Session fixation prevention 
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $username;
                        header(header: "Location: homepage.php"); // Redirect to homepage 
                        exit();
                    } else {
                        $error = "Invalid password.";
                    }
                }
            } else {
                $error = "No account found with that username.";
            }
        } else {
            $error = "Oh no! Something's not quite right. Try again later T-T";
        }
 
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>