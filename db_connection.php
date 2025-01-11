<?php
// Database configuration
$servername = "localhost"; // Usually 'localhost' for XAMPP
$username = "root"; // Default username for XAMPP is 'root'
$password = ""; // No password for XAMPP
$dbname = "niva_tour_agency"; // Name of your database (create this in phpMyAdmin)

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//essential for establish a connection between PHP and MySQL database. witout this, we can't perform
 //operations like inserting, updating or retrieve data. 
?>

