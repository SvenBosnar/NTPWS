<?php
session_start(); // Pokretanje sesije

// Provjera je li korisnik prijavljen i ima li dozvolu za dodavanje vijesti
if (!isset($_SESSION['logged_in']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'editor')) {
    die("Nemate dopuštenje za dodavanje vijesti.");
}

// Uključivanje baze podataka
require 'dbconfig.php';

$error_message = "";
$success_message = "";

// Provjera je li forma poslana
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_news'])) {
    // Sigurnosno filtriranje unosa
    $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars(trim($_POST['content']), ENT_QUOTES, 'UTF-8');
    $user_id = $_SESSION['user_id'];
    $created_date = date('Y-m-d H:i:s');
    $is_archived = 0;

    if (empty($title) || empty($content)) {
        $error_message = "Sva polja su obavezna!";
    } else {
        // Pripremljena SQL naredba za unos vijesti u bazu 'news'
        $sql = "INSERT INTO news (title, content, created_date, is_archived, user_id) VALUES (?, ?, ?, ?, ?)";

        // Korištenje $conn_news za povezivanje s bazom 'news'
        $stmt = $conn_news->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssii", $title, $content, $created_date, $is_archived, $user_id);
            if ($stmt->execute()) {
                $_SESSION['news_id'] = $stmt->insert_id;
                $success_message = "Nova vijest je uspješno dodana!";
            } else {
                $error_message = "Greška pri dodavanju vijesti: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Greška pri pripremi upita.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj vijest</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>Dodaj novu vijest</h2>

    <?php if (!empty($success_message)) {
        echo "<p class='message success'>$success_message</p>";
    } ?>
    <?php if (!empty($error_message)) {
        echo "<p class='message error'>$error_message</p>";
    } ?>

    <form method="POST" action="addnews.php">
        <label for="title">Naslov:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Sadržaj:</label>
        <textarea id="content" name="content" required></textarea><br><br>

        <button type="submit" name="submit_news">Dodaj vijest</button>
    </form>

    <?php
    // Ako je vijest uspješno dodana, prikaz forme za upload slike
    if (isset($_SESSION['news_id'])) {
        echo '<h3>Dodaj slike uz vijest</h3>';
        echo '<form action="uploadimage.php" method="post" enctype="multipart/form-data" class="file-upload">
            <input type="hidden" name="news_id" value="' . $_SESSION['news_id'] . '">
            <input type="file" name="images[]" multiple required>
            <button type="submit">Upload slike</button>
          </form>';
    }
    ?>

</body>

</html>