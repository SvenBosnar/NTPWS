<?php
// Definiraj varijablu koja će kontrolirati koji sadržaj učitavamo
$menu = isset($_GET['menu']) ? $_GET['menu'] : 1; // Ako 'menu' nije definiran, učitaj početnu stranicu (menu=1)
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ovo je mjesto za opis.">
    <meta name="keywords" content="Ovo su ključne riječi.">
    <meta name="author" content="Sven Bosnar">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Croatia Rally</title>
</head>
<body>
    <!-- Navigacija -->
    <header class="site-header">
        <nav class="main-nav">
            <a href="?menu=1">Početna stranica</a>
            <a href="?menu=2">Novosti</a>
            <a href="?menu=3">O nama</a>
            <a href="?menu=4">Galerija</a>
            <a href="?menu=5">Kontakt</a>
        </nav>
    </header>

    <main>
        <section class="main-content">
            <?php
            // Učitavamo sadržaj na temelju varijable $menu
            if ($menu == 1) {
                include 'home.php';
            } elseif ($menu == 2) {
                include 'news.php';
            } elseif ($menu == 3) {
                include 'about.php';
            } elseif ($menu == 4) {
                include 'gallery.php';
            } elseif ($menu == 5) {
                include 'contact.php';
            } else {
                echo "<p>Stranica nije pronađena.</p>";
            }
            ?>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="social-icons">
                <a href="https://www.facebook.com/WRCcroatiaRally" target="_blank" aria-label="Facebook">
                    <img src="img/ikone/icons8-facebook.svg" alt="Facebook Icon" class="social-icon">
                </a>
                <a href="https://www.instagram.com/croatiarally/" target="_blank" aria-label="Instagram">
                    <img src="img/ikone/icons8-instagram.svg" alt="Instagram icon" class="social-icon">
                </a>
                <a href="https://x.com/croatia_rally" target="_blank" aria-label="X">
                    <img src="img/ikone/icons8-x.svg" alt="X icon" class="social-icon">
                </a>
                <p>&copy; 2024 Rally Croatia. Sva prava pridržana. Stranicu izradio Sven Bosnar</p>
                <a href="https://github.com/SvenBosnar/NTPWS" target="_blank" aria-label="GitHub">
                    <img src="img/ikone/icons8-github.svg" alt="GitHub Icon" class="social-icon">
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
