<?php
// admin/db_connect.php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "barangay_maruing";

// Create connection
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    // Fallback to localhost if 127.0.0.1 fails
    try {
        $conn = new mysqli("localhost", $username, $password, $dbname);
    } catch (mysqli_sql_exception $e2) {
        die("Database connection failed: " . $e2->getMessage());
    }
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper encoding
$conn->set_charset("utf8mb4");
?>
