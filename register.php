<?php
// Menampilkan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mulai sesi
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resep_db"; // Nama database yang benar

$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Validasi input
    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password

    // Prepared statement untuk menghindari SQL injection
    $default_profile_picture = 'Assets/default-profile.jpg';
    $query = "INSERT INTO users (name, username, email, password, profile_picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $username, $email, $password, $default_profile_picture);

    if ($stmt->execute()) {
        // Mengarahkan ke login.php setelah registrasi berhasil
        header("Location: home.php");
        exit();
    } else {
        echo "Registrasi gagal. Silakan coba lagi.";
    }

    $stmt->close();
}

$conn->close();
?>
