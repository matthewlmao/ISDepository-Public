<?php

$DB_SERVER = "127.0.0.1";
$DB_USERNAME  = "root";
$DB_PASSWORD = "";
$DB_DATABASE = "matthewx_ISDepository";

define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'matthewx_ISDepository');
 
/* Attempt to connect to db */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}