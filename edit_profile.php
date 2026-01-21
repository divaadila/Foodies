<?php
session_start();
include 'admin/config.php';

// Ambil kata kunci pencarian dari query string
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mencari resep berdasarkan judul atau deskripsi
$sql_search = "SELECT * FROM resep WHERE judul LIKE '%$search%' OR deskripsi LIKE '%$search%'";
$result_search = $conn->query($sql_search);

$search_results = [];
if ($result_search && $result_search->num_rows > 0) {
    while ($row = $result_search->fetch_assoc()) {
        $search_results[] = $row;
    }
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

$user_id = $_SESSION['user_id']; // Asumsikan Anda menyimpan user_id di sesi setelah login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses upload foto profil
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Tentukan lokasi penyimpanan
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $user_id . '.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update path gambar profil di database
            $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $dest_path, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Proses form lainnya (misalnya memperbarui nama, username, bio, dll.)
    // ...
}

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internasional Food Website</title>
    <link rel="stylesheet" href="css/edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!--box icons--> 
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <!--google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Upright:wght@300;400;500;600;700&family=Kavoon&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!--navigation-->
    <nav>
        <a href="#" class="logo"><i class='bx bxs-home-alt-2'></i>Foodies</a>
        <ul class="navlist">
            <li class="active"><a href="home.php">Home</a></li>
            <li class="link"><a href="about.php">About</a></li>
            <li class="link"><a href="all_recipes.php">Recipes</a></li>
        </ul>

        <div class="nav-search">
            <form action="search_results.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search" id="search-input" class="search-bar">
                <button type="submit" class="search-button">
                    <i class='bx bx-search' id="search-icon"></i>
                </button>
            </form>
        </div>
        <div class="nav-icons" id="profileContent">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-dropdown">
                    <div class="user-menu" id="user-menu">
                        <a href="profile.php" id="user-icon"><i class='bx bxs-user'></i></a>
                        <i class='bx bx-chevron-down icon-arrow' id="dropdown-icon"></i>
                    </div>
                    <div class="dropdown hidden" id="dropdown">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php" id="logout">Sign out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="#" class="login">Sign Up</a>
            <?php endif; ?>
        </div>              
    </nav>

    <section>
        <div class="edit-profile-form">
            <form action="process_edit_profile.php" method="POST" enctype="multipart/form-data" class="profile-edit-form">
                <div class="profile-header">
                    <div class="profile-img" id="profile-img-preview" onclick="document.getElementById('profile-picture-upload').click()">
                        <?php if (isset($user['profile_picture']) && !empty($user['profile_picture'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-img">
                        <?php else: ?>
                            <img src="Assets/default-profile.jpg" alt="Default Profile Picture" class="profile-pic">
                        <?php endif; ?>
                    </div>
                </div>
                <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*"><br>
                <div class="form-group">
                    <label for="full-name" class="profile-label">Name:</label>
                    <input type="text" id="full-name" name="full-name" class="profile-input" value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="username" class="profile-label">Username:</label>
                    <input type="text" id="username" name="username" class="profile-input" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email" class="profile-label">Email:</label>
                    <input type="email" id="email" name="email" class="profile-input" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio" class="profile-label">About you:</label>
                    <textarea id="bio" name="bio" rows="4" class="profile-textarea"><?php echo $user['bio']; ?></textarea>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="save-btn">Update</button>
                    <button type="button" class="cancel-btn" onclick="location.href='profile.php';">Cancel</button>
                </div>
            </form>
        </div>
    </section>

    <script src="js/edit.js"></script>
</body>
</html>