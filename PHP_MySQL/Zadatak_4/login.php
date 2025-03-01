<?php
session_start();
require 'dbconfig.php';

// Provjera konekcije s bazom korisnika
if (!isset($conn_users) || $conn_users->connect_error) {
    die("Greška: Konekcija s bazom korisnika nije uspostavljena. " . ($conn_users->connect_error ?? ""));
}

$error_message = "";
$success_message = "";

// CSRF zaštita
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Postavljanje parametara za ograničenje pokušaja
$max_attempts = 5; // maksimalan broj pokušaja prijave
$lockout_time = 900; // zaključavanje nakon 15 minuta (900 sekundi)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Provjera CSRF tokena
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Nevažeći CSRF token!");
    }

    // Provjera broja pokušaja prijave
    if (isset($_SESSION['attempts']) && $_SESSION['attempts'] >= $max_attempts) {
        if (isset($_SESSION['last_attempt_time']) && (time() - $_SESSION['last_attempt_time']) < $lockout_time) {
            $error_message = "Previše pokušaja prijave. Pokušajte ponovo za " . ceil(($lockout_time - (time() - $_SESSION['last_attempt_time'])) / 60) . " minuta.";
        } else {
            unset($_SESSION['attempts']);
            unset($_SESSION['last_attempt_time']);
        }
    } else {
        $email = $conn_users->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Ažurirani SQL upit za dohvat role i statusa aktivacije
        $sql = "SELECT id, first_name, last_name, password, role, is_activated FROM users WHERE email = ?";
        $stmt = $conn_users->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $first_name, $last_name, $hashed_password, $role, $is_activated);
            $stmt->fetch();

            // Provjera lozinke
            if (password_verify($password, $hashed_password)) {
                // Provjera je li korisnik aktiviran
                if ($is_activated == 0) {
                    $error_message = "Vaš račun još nije aktiviran od strane administratora.";
                    incrementLoginAttempts();
                } else {
                    unset($_SESSION['attempts']);
                    unset($_SESSION['last_attempt_time']);

                    // Pohrani podatke u sesiju
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $first_name . " " . $last_name;
                    $_SESSION['user_role'] = $role;
                    $_SESSION['logged_in'] = true;

                    $_SESSION['success_message'] = "Uspješno prijavljeni! Dobrodošli, $first_name $last_name.";

                    // Preusmjeri prema odgovarajućoj stranici
                    if ($_SESSION['user_role'] == 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                }
            } else {
                $error_message = "Neispravna lozinka!";
                incrementLoginAttempts();
            }
        } else {
            $error_message = "Korisnik s tim emailom ne postoji!";
            incrementLoginAttempts();
        }

        $stmt->close();
    }
}

function incrementLoginAttempts() {
    if (!isset($_SESSION['attempts'])) {
        $_SESSION['attempts'] = 0;
    }
    $_SESSION['attempts']++;
    $_SESSION['last_attempt_time'] = time();
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Početna</a>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
        <a href="admin.php">Administracija</a>
        <a href="logout.php">Odjavi se</a>
    <?php } else { ?>
        <a href="login.php">Prijava</a>
        <a href="register.php">Registracija</a>
    <?php } ?>
</div>

<h2>Prijava</h2>
<div class="form-container">
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Lozinka:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Prijavi se">
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
