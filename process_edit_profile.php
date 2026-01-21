<?php
session_start();

// Validasi sesi pengguna
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resep_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan user_id dari sesi
$user_id = $_SESSION['user_id'];

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $full_name = htmlspecialchars($_POST['full-name']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $bio = htmlspecialchars($_POST['bio']);
    
    // Menangani unggahan gambar
    $profile_picture_path = '';

    // Periksa apakah ada file gambar baru yang diunggah
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Periksa apakah file adalah gambar
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture_path = $target_file;
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file.";
                exit();
            }
        } else {
            echo "File bukan gambar.";
            exit();
        }
    } else {
        // Jika tidak ada file gambar baru diunggah, gunakan foto profil yang ada sebelumnya
        $sql_get_profile_picture = "SELECT profile_picture FROM users WHERE id = ?";
        $stmt_get_profile_picture = $conn->prepare($sql_get_profile_picture);
        $stmt_get_profile_picture->bind_param("i", $user_id);
        $stmt_get_profile_picture->execute();
        $stmt_get_profile_picture->store_result();

        // Bind result variables
        $stmt_get_profile_picture->bind_result($existing_profile_picture);
        $stmt_get_profile_picture->fetch();

        $profile_picture_path = $existing_profile_picture;

        $stmt_get_profile_picture->close();
    }

    // Update data profil pengguna di database
    $sql_update_profile = "UPDATE users SET name = ?, username = ?, email = ?, bio = ?, profile_picture = ? WHERE id = ?";
    $stmt_profile = $conn->prepare($sql_update_profile);
    $stmt_profile->bind_param("sssssi", $full_name, $username, $email, $bio, $profile_picture_path, $user_id);

    if ($stmt_profile->execute()) {
        // Redirect kembali ke halaman profil setelah berhasil
        header("Location: profile.php");
        exit();
    } else {
        // Redirect kembali ke halaman edit dengan pesan kesalahan jika terjadi masalah
        header("Location: edit_profile.php?error=1");
        exit();
    }

    $stmt_profile->close();
}

$conn->close();
?>
