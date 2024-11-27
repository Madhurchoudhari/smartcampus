<?php
$servername = "localhost";
$username = "root";
$password = "Madhur10#";
$dbname = "smartcampus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>