<?php
$host = "localhost";   // Change this if using a different server
$username = "root";    // Your database username
$password = "";        // Your database password (default is empty for XAMPP)
$database = "quality_management";  // Your database name

// Create Connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
