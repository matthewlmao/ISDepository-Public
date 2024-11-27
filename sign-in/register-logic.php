<?php 
include('../config.php'); 

// Handle form submission 
if($DB_SERVER["request_method"] == "post"){

    // Get form data 
    $email = $_POST["username"];
    $password = $_POST["password"];

        // Validation of input 
        if(empty($email) or empty($password)){
            echo "Please fill in all fields.";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "Invalid email address! :(";
        }
        else{
            // Hash password 
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL Stmt to insert into db 
        $sql = "INSERT INTO users(username, email, password) VALUES(?, ?, ?);"; // ?s to prevent SQL injection 
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("sss", $email, $hashed_password);

        if($stmt->execute()){
            echo "Successfull registered! ^-^";
        }
        else{
            echo "Error!: "  . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
