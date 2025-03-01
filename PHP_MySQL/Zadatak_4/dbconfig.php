<?php
// Pokreni sesiju samo ako nije već aktivna
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Podaci za povezivanje s bazom podataka - Users
$servername = "localhost";
$username = "root";
$password = "";
$dbname_users = "users"; // Baza za korisnike

// Kreiranje konekcije za bazu 'users'
$conn_users = new mysqli($servername, $username, $password, $dbname_users);

// Provjera konekcije za bazu 'users'
if ($conn_users->connect_error) {
    die("Greška pri povezivanju s bazom 'users': " . $conn_users->connect_error);
}

// Podaci za povezivanje s bazom podataka - News
$dbname_news = "news"; // Baza za vijesti

// Kreiranje konekcije za bazu 'news'
$conn_news = new mysqli($servername, $username, $password, $dbname_news);

// Provjera konekcije za bazu 'news'
if ($conn_news->connect_error) {
    die("Greška pri povezivanju s bazom 'news': " . $conn_news->connect_error);
}

// Podaci za povezivanje s bazom podataka - Countries
$dbname_countries = "countries"; // Baza za zemlje

// Kreiranje konekcije za bazu 'countries'
$conn_countries = new mysqli($servername, $username, $password, $dbname_countries);

// Provjera konekcije za bazu 'countries'
if ($conn_countries->connect_error) {
    die("Greška pri povezivanju s bazom 'countries': " . $conn_countries->connect_error);
}

// Definiraj konstantu za enkripciju
const ENCRYPTION_KEY = 'moja_tajna_sifra_enkripcije_12345';

// Konekcija se zatvara u uploadimage.php nakon svih operacija
?>
