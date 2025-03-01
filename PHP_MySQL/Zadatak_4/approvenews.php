<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Pristup zabranjen!");
}

$conn = new mysqli("localhost", "root", "", "news");
if ($conn->connect_error) {
    die("Povezivanje s bazom nije uspjelo: " . $conn->connect_error);
}

// Ako admin klikne "Approve", mijenja status na "approved"
if (isset($_POST['approve_id'])) {
    $stmt = $conn->prepare("UPDATE news SET status='approved' WHERE id=?");
    $stmt->bind_param("i", $_POST['approve_id']);
    $stmt->execute();
    $stmt->close();
}

// Prikaz svih neodobrenih novosti
$result = $conn->query("SELECT * FROM news WHERE status='pending'");

echo "<h2>Novosti na čekanju</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='approve_id' value='" . $row['id'] . "'>";
    echo "<button type='submit'>Odobri</button>";
    echo "</form>";
    echo "</div><hr>";
}

$conn->close();
?>