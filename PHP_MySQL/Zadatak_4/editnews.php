<?php
session_start(); // Pokrećemo sesiju ako već nije pokrenuta
require 'dbconfig.php'; // Koristi postojeću konfiguraciju baze

// Provjera postoji li 'id' u URL-u i je li broj
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "<p>Neispravan ID članka.</p>";
    exit;
}

$news_id = (int)$_GET['id'];

// SQL upit za dohvat članka prema ID-u
$sql = "SELECT * FROM news WHERE id = ?";
$stmt = $conn_news->prepare($sql);

if (!$stmt) {
    error_log("Greška pri pripremi upita: " . $conn_news->error);
    die("Greška u upitu.");
}

$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Članak nije pronađen.</p>";
    exit;
}

$article = $result->fetch_assoc();
$stmt->close();

// Provjera uloge korisnika - omogućiti editiranje samo ako je korisnik administrator, urednik ili autor
$can_edit = false;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'editor']) || $_SESSION['user_id'] == $article['user_id']) {
        $can_edit = true;
    }
}

// Provjera za 'menu' parametar u URL-u
$menu = isset($_GET['menu']) ? $_GET['menu'] : 1;

// Obrada POST zahtjeva za ažuriranje članka
if (isset($_POST['update'])) {
    $updated_content = $_POST['content'];
    // SQL upit za ažuriranje članka
    $update_sql = "UPDATE news SET content = ? WHERE id = ?";
    $update_stmt = $conn_news->prepare($update_sql);
    if ($update_stmt) {
        $update_stmt->bind_param("si", $updated_content, $news_id);
        $update_stmt->execute();
        // Preusmjeravanje na stranicu s novostima nakon uspješnog ažuriranja
        header("Location: novosti.php?menu=" . $menu);
        exit();
    } else {
        echo "<p>Greška pri ažuriranju članka.</p>";
    }
}
?>

<?php include 'header.php'; ?>

<div class="main-content">
    <h2><?php echo htmlspecialchars($article['title']); ?></h2>

    <?php
    // Dohvat prve slike povezane s člankom (ako postoji)
    $sql_images = "SELECT * FROM news_images WHERE news_id = ? LIMIT 1";
    $stmt_images = $conn_news->prepare($sql_images);
    $stmt_images->bind_param("i", $news_id);
    $stmt_images->execute();
    $result_images = $stmt_images->get_result();

    if ($result_images->num_rows > 0) {
        $image_row = $result_images->fetch_assoc();
        echo "<img src='" . htmlspecialchars($image_row['image_url']) . "' alt='Slika članka' class='responsive-image'>";
    }
    ?>

    <p><strong>Datum objave:</strong> <?php echo date("d. F Y", strtotime($article['created_date'])); ?></p>

    <?php if ($can_edit): ?>
        <form action="editnews.php?id=<?php echo $article['id']; ?>" method="POST">
            <textarea name="content" rows="10" cols="50"><?php echo htmlspecialchars($article['content']); ?></textarea>
            <button type="submit" name="update" class="edit-button button">Spremi promjene</button>
        </form>
    <?php else: ?>
        <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
    <?php endif; ?>

    <div class="action-buttons">
        <form action="editnews.php" method="GET" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <input type="hidden" name="menu" value="<?php echo $menu; ?>">
            <button type="submit" class="edit-button button">Uredi vijest</button>
        </form>
        <form action="archivenews.php?menu=<?php echo $menu; ?>" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <button type="submit" name="archive" class="archive-button button"> <?php echo $article['is_archived'] ? 'Poništi arhiviranje' : 'Arhiviraj'; ?> </button>
        </form>
        <form action="deletenews.php?menu=<?php echo $menu; ?>" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <button type="submit" name="delete" class="delete-button button" onclick="return confirm('Jeste li sigurni da želite obrisati ovu vijest?');">Obriši vijest</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<?php
$stmt_images->close();
$conn_news->close();
?>
