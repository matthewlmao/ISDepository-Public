<?php

$DB_SERVER = "127.0.0.1";
$DB_USERNAME  = "root";
$DB_PASSWORD = "";
$DB_DATABASE = "isdepository";

define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'isdepository');
 
// Attempt to connect to db 
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection and output error if connection fails 
if ($connection === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
} 

require_once 'components/alerts.php';

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" ></script>
<script src="/ISDepository/components/header-component.js"></script>
<script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
<!--AJAX Search--> 
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="/ISDepository/style.css">
