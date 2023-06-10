<?php
$host = "localhost";
$username = "your_mysql_username";
$password = "your_mysql_password";
$dbname = "your_mysql_database_name";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


