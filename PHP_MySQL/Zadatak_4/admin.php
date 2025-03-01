<?php
session_start();

// Provjera prijave i uloge
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");  // Ako nije prijavljen kao admin, preusmjeri ga na početnu
    exit();
}

// Uključi datoteku za povezivanje s bazom podataka
require_once 'dbconfig.php'; // Provjerite je li datoteka ispravno postavljena

// Provjeri je li veza s bazom 'users' uspješna
if ($conn_users->connect_error) {
    die("Veza s bazom nije uspjela: " . $conn_users->connect_error);
}


$error_message = "";
$success_message = "";

// Dohvat svih korisnika iz baze 'users'
$sql = "SELECT id, first_name, last_name, email, role, is_activated FROM users";
$result = $conn_users->query($sql); // Koristi $conn_users za bazu 'users'
if ($result === false) {
    die("Greška u SQL upitu: " . $conn_users->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ažuriranje uloge korisnika
    if (isset($_POST['user_id']) && isset($_POST['new_role'])) {
        $user_id = (int) $_POST['user_id'];
        $new_role = $_POST['new_role'];

        // Provjera je li nova uloga valjana
        if (in_array($new_role, ['user', 'editor', 'admin'])) {
            // Ažuriranje uloge korisnika
            $update_sql = "UPDATE users SET role = ? WHERE id = ?";
            $stmt = $conn_users->prepare($update_sql);
            $stmt->bind_param("si", $new_role, $user_id);

            if ($stmt->execute()) {
                $success_message = "Uloga korisnika uspješno ažurirana!";
            } else {
                $error_message = "Došlo je do pogreške prilikom ažuriranja uloge.";
            }
        } else {
            $error_message = "Neispravna rola.";
        }
    }

    // Aktivacija/deaktivacija korisnika
    if (isset($_POST['activate_user_id'])) {
        $user_id = (int) $_POST['activate_user_id'];
        $is_activated = isset($_POST['activate']) ? 1 : 0;

        // Ažuriranje statusa aktivacije korisnika
        $update_activation_sql = "UPDATE users SET is_activated = ? WHERE id = ?";
        $stmt = $conn_users->prepare($update_activation_sql);
        $stmt->bind_param("ii", $is_activated, $user_id);

        if ($stmt->execute()) {
            $success_message = $is_activated ? "Korisnik je aktiviran!" : "Korisnik je deaktiviran!";
        } else {
            $error_message = "Došlo je do pogreške prilikom ažuriranja statusa aktivacije.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administratorski panel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Administratorski panel</h1>

        <?php if (!empty($success_message)) { ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php } ?>

        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>

        <h2>Popis svih korisnika</h2>
        <table>
            <thead>
                <tr>
                    <th>Ime</th>
                    <th>Email</th>
                    <th>Trenutna uloga</th>
                    <th>Promijeni ulogu</th>
                    <th>Status aktivacije</th>
                    <th>Aktiviraj/Deaktiviraj</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td>
                            <form method="POST" action="admin.php">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="new_role">
                                    <option value="user" <?php echo ($row['role'] == 'user') ? 'selected' : ''; ?>>User
                                    </option>
                                    <option value="editor" <?php echo ($row['role'] == 'editor') ? 'selected' : ''; ?>>Editor
                                    </option>
                                    <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin
                                    </option>
                                </select>
                                <button type="submit">Spremi</button>
                            </form>
                        </td>
                        <td>
                            <?php echo $row['is_activated'] == 1 ? "Aktiviran" : "Deaktiviran"; ?>
                        </td>
                        <td>
                            <form method="POST" action="admin.php">
                                <input type="hidden" name="activate_user_id" value="<?php echo $row['id']; ?>">
                                <input type="checkbox" name="activate" value="1" <?php echo ($row['is_activated'] == 1) ? 'checked' : ''; ?>>
                                <button type="submit">Spremi</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>