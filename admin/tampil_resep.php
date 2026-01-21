<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

include 'config.php';

$sql = "SELECT * FROM resep_db";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . htmlspecialchars($row["id"]) . " - Judul: " . htmlspecialchars($row["title"]) . " - Bahan: " . htmlspecialchars($row["ingredients"]) . " - Cara Pembuatan: " . htmlspecialchars($row["instructions"]) . " - Tanggal: " . htmlspecialchars($row["created_at"]) . "<br>";
    }
} else {
    echo "0 hasil";
}

$conn->close();
?>
