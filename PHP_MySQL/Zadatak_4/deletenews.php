<?php
session_start();

require 'dbconfig.php';


echo "<pre>";
var_dump($_POST);
echo "</pre>";

// Provjera vrijednosti u $_POST
if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    die("Nevažeći ID vijesti.");
}

$news_id = intval($_POST['id']); // Osiguravanje sigurnog unosa




if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

// Funkcija za dohvat ID-a autora članka iz baze vijesti
function getArticleAuthorId($news_id, $conn_news)
{
    $sql = "SELECT user_id FROM news WHERE id = ?";
    $stmt = $conn_news->prepare($sql);
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    echo "ID autora vijesti: " . $user_id . "<br>";
    return $user_id;
}

// Provjera ima li korisnik potrebna prava za brisanje
if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] != 'admin' && $_SESSION['user_id'] != getArticleAuthorId($news_id, $conn_news))
) {
    die("Nemate prava za brisanje vijesti.");
}

// SQL upit za brisanje vijesti iz baze vijesti
$sql = "DELETE FROM news WHERE id = ?";
$stmt = $conn_news->prepare($sql);
$stmt->bind_param("i", $news_id);

if ($stmt->execute()) {
    echo "Vijest je uspješno obrisana!";
} else {
    echo "Greška: " . $stmt->error;
}

$stmt->close();
$conn_news->close();
$conn_users->close(); // Zatvaranje konekcije prema bazi korisnika (koja je već otvorena u dbconfig.php)

?>

<a href="novosti.php">Povratak na popis vijesti</a>