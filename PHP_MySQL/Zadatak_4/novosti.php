<?php
// Provjera je li sesija već započeta
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$show_archived = false; // Po defaultu sakriva arhivirane članke
$user_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if ($user_logged_in && isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'editor'])) {
    $show_archived = true; // Admini i editori vide arhivirane članke
}

// Provjera je li 'id' postavljen u URL-u
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $news_id = (int) $_GET['id'];  // Sanitizacija ID-a

    $conn = new mysqli("localhost", "root", "", "news");
    if ($conn->connect_error) {
        die("Povezivanje s bazom nije uspjelo: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM news WHERE id = ? AND (is_archived = 0 OR ? = 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $news_id, $show_archived);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();

    if (!$article) {
        die("<p class='error'>Članak nije pronađen ili je arhiviran.</p>");
    }

    $sql_images = "SELECT * FROM news_images WHERE news_id = ?";
    $stmt_images = $conn->prepare($sql_images);
    $stmt_images->bind_param("i", $news_id);
    $stmt_images->execute();
    $result_images = $stmt_images->get_result();
    $images = $result_images->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $stmt_images->close();
    $conn->close();
} else {
    $conn = new mysqli("localhost", "root", "", "news");
    if ($conn->connect_error) {
        die("Povezivanje s bazom nije uspjelo: " . $conn->connect_error);
    }

    $sql = "
        SELECT n.id, n.title, n.created_date, ni.image_url 
        FROM news n
        LEFT JOIN news_images ni ON n.id = ni.news_id
        WHERE n.is_archived = 0 OR ? = 1
        GROUP BY n.id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $show_archived);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novosti</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="main-content">
        <?php if (isset($article)): ?>
            <div class="article">
                <h2><?= htmlspecialchars($article['title']) ?></h2>
                <p class='date'><strong>Datum objave:</strong> <?= date("d. F Y", strtotime($article['created_date'])) ?>
                </p>
                <div class="content"><?= nl2br(htmlspecialchars($article['content'])) ?></div>

                <?php if (!empty($images)): ?>
                    <h3>Galerija slika:</h3>
                    <div class="gallery">
                        <?php foreach ($images as $image): ?>
                            <figure>
                                <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Slika članka" class="thumbnail">
                                <figcaption>Slika</figcaption>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Nema dostupnih slika za ovaj članak.</p>
                <?php endif; ?>

                <?php
                if (isset($_SESSION['user']['role'])) {
                    echo "<p><strong>Uloga korisnika:</strong> " . htmlspecialchars($_SESSION['user']['role']) . "</p>";
                } else {
                    echo "<p>Uloga korisnika nije definirana.</p>";
                }
                ?>
            </div>

        <?php else: ?>
            <h2>Popis članaka</h2>

            <?php if ($user_logged_in): ?>
                <button onclick="window.location.href='addnews.php'">
                    Dodaj novi članak
                </button>
            <?php endif; ?>

            <div class="articles-container">
                <?php foreach ($articles as $article): ?>
                    <div class="article-card">
                        <a href="single_article.php?id=<?= $article['id'] ?>">
                            <?php if (!empty($article['image_url'])): ?>
                                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Slika članka" class="thumbnail">
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($article['title']) ?></h3>
                        </a>
                        <p><strong>Datum objave:</strong> <?= date("d. F Y", strtotime($article['created_date'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>