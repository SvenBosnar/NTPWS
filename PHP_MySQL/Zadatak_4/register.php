<?php
session_start();
require 'dbconfig.php';

$error_message = "";
$success_message = "";

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Limiting registration attempts
$max_attempts = 5;
$lockout_time = 15 * 60;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token!");
    }

    // Check for too many registration attempts
    if (isset($_SESSION['attempts']) && $_SESSION['attempts'] >= $max_attempts) {
        if (isset($_SESSION['last_attempt_time']) && (time() - $_SESSION['last_attempt_time']) < $lockout_time) {
            $error_message = "Too many registration attempts. Please try again in " . ceil(($lockout_time - (time() - $_SESSION['last_attempt_time'])) / 60) . " minutes.";
        } else {
            unset($_SESSION['attempts']);
            unset($_SESSION['last_attempt_time']);
        }
    } else {
        if ($conn_users) {
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $birth_date = trim($_POST['birth_date']);
            $country = trim($_POST['country']);

            // Generate username
            $username = generateUsername($first_name, $last_name, $conn_users);

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format!";
            } elseif (strtotime($birth_date) > time()) {
                $error_message = "Birth date cannot be in the future!";
            } else {
                // Check if the user already exists
                $stmt = $conn_users->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error_message = "A user with this email already exists!";
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $role = 'user';
                    $is_activated = 0;

                    // Insert user into the database
                    $stmt = $conn_users->prepare("INSERT INTO users (first_name, last_name, email, username, country, birth_date, password, role, is_activated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssi", $first_name, $last_name, $email, $username, $country, $birth_date, $hashed_password, $role, $is_activated);

                    if ($stmt->execute()) {
                        $success_message = "Registration successful! Your account is pending approval from the admin. <a href='login.php'>Log in here</a>";
                    } else {
                        $error_message = "Error during registration: " . $stmt->error;
                    }
                }
                $stmt->close();
            }
        } else {
            $error_message = "Error: Unable to connect to the database.";
        }
    }
    incrementRegistrationAttempts();
}

// Function to generate a unique username
function generateUsername($first_name, $last_name, $conn_users) {
    $base_username = strtolower(substr($first_name, 0, 1) . $last_name);
    $username = $base_username;
    $counter = 1;

    $stmt = $conn_users->prepare("SELECT id FROM users WHERE username = ?");
    do {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $username = $base_username . $counter;
            $counter++;
        } else {
            break;
        }
    } while (true);
    $stmt->close();
    return $username;
}

// Function to track registration attempts
function incrementRegistrationAttempts() {
    if (!isset($_SESSION['attempts'])) {
        $_SESSION['attempts'] = 0;
    }
    $_SESSION['attempts']++;
    $_SESSION['last_attempt_time'] = time();
}

// Fetch all countries from the 'countries' database
$countries = [];
if ($conn_countries) {
    $result = $conn_countries->query("SELECT country_name FROM countries");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $countries[] = $row['country_name'];
        }
        $result->free();
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Registration</h2>

    <?php if ($error_message): ?>
        <div class="message error">
            <?php echo $error_message; ?>
        </div>
    <?php elseif ($success_message): ?>
        <div class="message success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="birth_date">Birth Date:</label>
        <input type="date" id="birth_date" name="birth_date" required><br><br>

        <label for="country">Country:</label>
        <select name="country" id="country" required>
            <?php foreach ($countries as $country): ?>
                <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
