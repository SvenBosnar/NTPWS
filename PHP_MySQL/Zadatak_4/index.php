<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Provjeri postoji li "menu" parametar u URL-u
$menu = isset($_GET['menu']) ? $_GET['menu'] : 1;


?>

<?php include 'header.php'; ?>

<main>
    <?php
    // Prikaz stranice na temelju vrijednosti iz $_GET['menu']
    if ($menu == 1) { 
        include 'pocetna.php'; 
    } elseif ($menu == 2) {
        include 'novosti.php';
    } elseif ($menu == 3) {
        include 'onama.php';
    } elseif ($menu == 4) {
        include 'galerija.php';
    } elseif ($menu == 5) {
        include 'kontakt.php';
    } else {
        echo "<p>Stranica nije pronađena.</p>";
    }
    ?>
</main>

<?php include 'footer.php'; ?>
