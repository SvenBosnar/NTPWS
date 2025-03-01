<?php
// Pokreni sesiju samo ako nije već aktivna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Podaci za povezivanje s bazom podataka
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// Kreiranje konekcije
$conn = new mysqli($servername, $username, $password, $dbname);

// Provjera konekcije
if ($conn->connect_error) {
    die("Greška pri povezivanju s bazom: " . $conn->connect_error);
}


const ENCRYPTION_KEY = 'moja_tajna_sifra_enkripcije_12345'; 
