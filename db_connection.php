<?php
// Database configuration
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "niva_tour_agency"; 

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//essential for establish a connection between PHP and MySQL database. witout this, we can't perform
 //operations like inserting, updating or retrieve data. 
?>

