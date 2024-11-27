<?php 
include_once'../config.php'; 

// Handle form submission 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])){

// Get form data 
    $email = $_POST["email"];
    $password = $_POST["password"];

        // Validation of input 
        if(empty($email) || empty($password)){
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
            echo "Successfully registered! ^-^";
        }
        else{
            echo "Error!: "  . $stmt->error;
        }

        $stmt->close(); 
    }
}

$conn->close();
