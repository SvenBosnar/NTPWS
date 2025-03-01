<?php
session_start();
require 'dbconfig.php';

$error_message = "";
$success_message = "";

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Nevažeći CSRF token!");
    }


    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

   
    $sql = "SELECT id, first_name, last_name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $last_name, $hashed_password);
        $stmt->fetch();

        // Provjeri lozinku
        if (password_verify($password, $hashed_password)) {
            // Spremi podatke u sesiju
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $first_name . " " . $last_name;
            $_SESSION['logged_in'] = true;

            // Postavi poruku o uspješnoj prijavi
            $_SESSION['success_message'] = "Uspješno prijavljeni! Dobrodošli, $first_name $last_name.";

            // Preusmjeri na početnu stranicu
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Neispravna lozinka!";
        }
    } else {
        $error_message = "Korisnik s tim emailom ne postoji!";
    }

    $stmt->close();
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

<!-- Navigacija -->
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
</div>

</body>
</html>
