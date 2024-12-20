<?php
// Database configuration
$host = 'localhost';   // The host where MySQL is running
$user = 'root';        // Your MySQL username
$password = '';        // Your MySQL password (leave blank if no password)
$dbname = 'clothing_store'; // The name of your database

// Create a connection to the database
$conn = new mysqli($host, $user, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
