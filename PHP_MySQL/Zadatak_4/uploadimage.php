<?php
session_start();
require 'dbconfig.php'; // Učitavanje konfiguracije baze

// Provjera dolazi li ispravan news_id putem POST-a
if (!isset($_POST['news_id']) || !is_numeric($_POST['news_id'])) {
    die("Greška: Nevažeći ID vijesti.");
}

$news_id = intval($_POST['news_id']); // Osiguranje da je to integer
$target_dir = "uploads/"; // Folder za slike


if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}


$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

foreach ($_FILES['images']['name'] as $key => $image_name) {
    $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if (!in_array($image_extension, $allowed_types)) {
        die("Greška: Dozvoljeni formati su JPG, JPEG, PNG, GIF.");
    }


    if ($_FILES['images']['size'][$key] > 5 * 1024 * 1024) {
        die("Greška: Datoteka je prevelika. Maksimalna veličina je 5MB.");
    }


    $check = getimagesize($_FILES['images']['tmp_name'][$key]);
    if ($check === false) {
        die("Greška: Datoteka nije slika.");
    }


    $unique_name = uniqid("img_", true) . "." . $image_extension;
    $target_file = $target_dir . $unique_name;


    if (!move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
        die("Greška: Nije moguće prenijeti datoteku.");
    }


    $max_width = 1920;
    $max_height = 1080;
    resizeImage($target_file, $image_extension, $max_width, $max_height);


    $stmt = $conn_news->prepare("INSERT INTO news_images (news_id, image_url, created_at) VALUES (?, ?, NOW())");
    if (!$stmt) {
        die("Greška pri pripremi SQL upita: " . $conn_news->error);
    }

    $stmt->bind_param("is", $news_id, $target_file);
    if ($stmt->execute()) {
        echo "Slika je uspješno uploadana!<br>";
    } else {
        echo "Greška pri spremanju slike u bazu: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

// Zatvori konekciju na news bazu **nakon** što je upit izvršen
$conn_news->close();


function resizeImage($file, $ext, $max_width, $max_height, $output_file = null)
{
    list($orig_width, $orig_height) = getimagesize($file);

    if ($orig_width <= $max_width && $orig_height <= $max_height) {
        return; // Nema potrebe za smanjenjem
    }

    $ratio = min($max_width / $orig_width, $max_height / $orig_height);
    $new_width = (int) ($orig_width * $ratio);
    $new_height = (int) ($orig_height * $ratio);

    $image_p = imagecreatetruecolor($new_width, $new_height);

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($file);
            break;
        case 'png':
            $image = imagecreatefrompng($file);
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);
            break;
        case 'gif':
            $image = imagecreatefromgif($file);
            break;
        default:
            return; // Ako format nije podržan, izađi
    }

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

    // Ako nema output file, prebrisati original
    if ($output_file === null) {
        $output_file = $file;
    }

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($image_p, $output_file, 85);
            break;
        case 'png':
            imagepng($image_p, $output_file, 8);
            break;
        case 'gif':
            imagegif($image_p, $output_file);
            break;
    }

    imagedestroy($image_p);
    imagedestroy($image);
}
?>