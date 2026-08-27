<?php
// Database Connection - ShajarDost
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'shajardost_db';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    // In production, show a friendly message instead of dying
    if (defined('ENV') && ENV === 'production') {
        die("We're experiencing technical difficulties. Please try again later.");
    } else {
        die("Connection failed: " . mysqli_connect_error());
    }
}

mysqli_set_charset($conn, "utf8mb4");
?>
