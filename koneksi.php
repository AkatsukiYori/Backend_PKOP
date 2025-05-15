<?php
$host = "localhost";       // or your server host
$user = "root";   // your DB username
$pass = "";   // your DB password
$db   = "PKOP";   // your DB name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
