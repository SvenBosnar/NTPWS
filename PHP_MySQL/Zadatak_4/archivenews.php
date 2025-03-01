<?php
session_start();
require 'dbconfig.php';

// Provjera je li korisnik prijavljen
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

//provjera sadržaja sesije ako uloga nije postavljena
if (!isset($_SESSION['user_role'])) {
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";
    die("Uloga nije postavljena.");
}

// Provjera ispravnosti ID-a
if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    die("Neispravan ID članka.");
}
$news_id = (int) $_POST['id'];

// Dohvati članak iz baze
$sql = "SELECT user_id, is_archived FROM news WHERE id = ?";
$stmt = $conn_news->prepare($sql);
if (!$stmt) {
    die("Greška pri pripremi SQL upita.");
}
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Članak nije pronađen.");
}
$article = $result->fetch_assoc();
$stmt->close();

// Provjera ovlasti - admin, urednik ili autor
$allowed_roles = ['admin', 'editor'];
if (!in_array($_SESSION['user_role'], $allowed_roles) && $_SESSION['user_id'] != $article['user_id']) {
    die("Nemate dopuštenje za arhiviranje ovog članka.");
}

// Promjena statusa arhiviranja
$new_status = $article['is_archived'] ? 0 : 1;
$sql_update = "UPDATE news SET is_archived = ? WHERE id = ?";
$stmt_update = $conn_news->prepare($sql_update);
if (!$stmt_update) {
    die("Greška pri pripremi SQL upita.");
}
$stmt_update->bind_param("ii", $new_status, $news_id);
$stmt_update->execute();
$stmt_update->close();
$conn_news->close();

// Preusmjeri korisnika natrag na članak
header("Location: single_article.php?id=$news_id&menu=" . ($_GET['menu'] ?? 1));
exit;
?>