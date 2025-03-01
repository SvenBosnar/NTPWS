<?php
session_start(); // Pokrećemo sesiju ako već nije pokrenuta
require 'dbconfig.php'; // Koristi postojeću konfiguraciju baze

// Provjera postoji li 'id' u URL-u i je li broj
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "<p>Neispravan ID članka.</p>";
    exit;
}

$id = (int)$_GET['id']; // Samo koristimo 'id' bez prefiksa

// Provjera uloge korisnika - omogućiti prikaz arhiviranih članaka samo ako je korisnik admin/editor
$show_archived = true;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Provjera uloge i postavljanje vrijednosti $show_archived
    if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'editor')) {
        $show_archived = true; // Admini i urednici mogu vidjeti arhivirane i nearhivirane članke
    }
}

// SQL upit za dohvat članka prema ID-u
if ($show_archived) {
    // Admini i urednici mogu vidjeti sve članke (arhivirane i nearhivirane)
    $sql = "SELECT * FROM news WHERE id = ?";
} else {
    // Svi ostali korisnici mogu vidjeti samo nearhivirane članke
    $sql = "SELECT * FROM news WHERE id = ? AND is_archived = 0";
}

$stmt = $conn_news->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Članak nije pronađen ili je arhiviran.</p>";
    exit;
}

$article = $result->fetch_assoc();
$stmt->close();

// Provjera uloge korisnika - omogućiti editiranje samo ako je korisnik administrator, urednik ili autor
$can_edit = false;  // Početno postavimo na false

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Ako je korisnik prijavljen i ima odgovarajuću ulogu (admin/editor)
    if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'editor'])) {
        $can_edit = true;  // Admini i urednici mogu uređivati
    }
}

$menu = isset($_GET['menu']) ? $_GET['menu'] : 1;
?>

<?php include 'header.php'; ?>

<div class="main-content">
    <h2><?php echo htmlspecialchars($article['title']); ?></h2>

    <?php
    // Dohvat prve slike povezane s člankom (ako postoji)
    $sql_images = "SELECT * FROM news_images WHERE news_id = ? LIMIT 1";
    $stmt_images = $conn_news->prepare($sql_images);
    $stmt_images->bind_param("i", $id);
    $stmt_images->execute();
    $result_images = $stmt_images->get_result();

    if ($result_images->num_rows > 0) {
        $image_row = $result_images->fetch_assoc();
        echo "<img src='" . htmlspecialchars($image_row['image_url']) . "' alt='Slika članka' class='responsive-image'>";
    }
    ?>

    <p><strong>Datum objave:</strong> <?php echo date("d. F Y", strtotime($article['created_date'])); ?></p>
    <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

    <?php if ($can_edit == true): ?>
        <div class="action-buttons">
            <form action="editnews.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                <input type="hidden" name="menu" value="<?php echo $menu; ?>">
                <button type="submit" class="edit-button button">Uredi vijest</button>
            </form>
            <form action="archivenews.php?menu=<?php echo $menu; ?>" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                <button type="submit" name="archive" class="archive-button button">
                    <?php echo $article['is_archived'] ? 'Poništi arhiviranje' : 'Arhiviraj'; ?>
                </button>
            </form>
            <form action="deletenews.php?menu=<?php echo $menu; ?>" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                <button type="submit" name="delete" class="delete-button button" onclick="return confirm('Jeste li sigurni da želite obrisati ovu vijest?');">Obriši vijest</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<?php
$stmt_images->close();
$conn_news->close();
?>
