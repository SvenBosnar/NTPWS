<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Greška pri povezivanju s bazom: " . $conn->connect_error);
}

session_start();
?>
