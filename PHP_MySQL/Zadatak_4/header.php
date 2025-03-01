<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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

    <header class="site-header">
        <nav class="main-nav">
            <a href="index.php?menu=1">Početna</a>
            <a href="index.php?menu=2">Novosti</a>
            <a href="index.php?menu=3">O nama</a>
            <a href="index.php?menu=4">Galerija</a>
            <a href="index.php?menu=5">Kontakt</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Odjava (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
            <?php else: ?>
                <a href="login.php">Prijava</a>
                <a href="register.php">Registracija</a>
            <?php endif; ?>
        </nav>
    </header>

</body>

</html>