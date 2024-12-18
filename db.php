<?php

$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "farmfresh_mango";  
// Create a new MySQLi connection
$conn = new mysqli($serverName, $userName, $password, $dbName);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
} else {
    // echo "Connection successful!<br>";
}

?>
