<?php
$servername = "localhost";
$username = "root";  
$password = ""; 
$dbname = "medical"; 

$database = new mysqli($servername, $username, $password, $dbname);

if ($database->connect_error) {
    die("Errors : " . $database->connect_error);
}
?>

