<?php
require 'dbconfig.php';

// Definicija funkcije za enkripciju
function encryptPassword($password) {
    $encryptionKey = 'moja_enkripcija'; // Ključ za enkripciju
    $iv = openssl_random_pseudo_bytes(16);
    
    $encryptedPassword = openssl_encrypt($password, 'AES-256-CBC', $encryptionKey, 0, $iv);

    return base64_encode($iv . $encryptedPassword); 
}

// Generiranje korisničkog imena
function generateUsername($first_name, $last_name, $conn) {
    $username = strtolower(substr($first_name, 0, 1) . $last_name); // Prvo slovo imena + prezime
    $username_check_sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($username_check_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Ako korisničko ime postoji, dodaj broj
    $counter = 1;
    while ($stmt->num_rows > 0) {
        $username = strtolower(substr($first_name, 0, 1) . $last_name . $counter);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $counter++;
    }

    return $username;
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $birth_date = $conn->real_escape_string($_POST['birth_date']);
    $country = $conn->real_escape_string($_POST['country']);

    // Generiranje korisničkog imena
    $username = generateUsername($first_name, $last_name, $conn);

    // Generiranje lozinke
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Provjera da rođendan nije u budućnosti
    if (strtotime($birth_date) > time()) {
        $error_message = "Rođendan ne može biti u budućnosti!";
    } else {
        // Provjera postoji li korisnik s istim emailom
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Korisnik s tim emailom već postoji!";
        } else {
            // Unos korisnika u bazu
            $sql = "INSERT INTO users (first_name, last_name, email, username, country, birth_date, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $first_name, $last_name, $email, $username, $country, $birth_date, $password);

            if ($stmt->execute()) {
                $success_message = "Registracija uspješna! <a href='login.php'>Prijavi se ovdje</a>";
            } else {
                $error_message = "Greška pri registraciji: " . $conn->error;
            }
        }
        $stmt->close();
    }
}

// Dohvati sve zemlje iz baze 'countries' tablice
$countries_sql = "SELECT country_name FROM countries.countries"; // Baza i tablica su 'countries'
$countries_result = $conn->query($countries_sql);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Registracija</h2>
<div class="form-container">
    <form method="post">
        <label for="first_name">Ime:</label>
        <input type="text" name="first_name" id="first_name" required>

        <label for="last_name">Prezime:</label>
        <input type="text" name="last_name" id="last_name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Lozinka:</label>
        <input type="password" name="password" id="password" required>

        <label for="birth_date">Datum rođenja:</label>
        <input type="date" name="birth_date" id="birth_date" required>

        <label for="country">Zemlja:</label>
        <select name="country" id="country" required>
            <option value="" disabled selected>Odaberi zemlju</option> <!-- Prva opcija 'Odaberi zemlju' -->
            <?php
            // Provjera ako ima rezultata iz baze
            if ($countries_result->num_rows > 0) {
                while ($row = $countries_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['country_name']) . "'>" . htmlspecialchars($row['country_name']) . "</option>";
                }
            } else {
                echo "<option value=''>Nema zemalja</option>";
            }
            ?>
        </select>

        <input type="submit" value="Registriraj se">
    </form>

    <?php if (!empty($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <?php if (!empty($success_message)) { ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php } ?>
</div>

</body>
</html>
