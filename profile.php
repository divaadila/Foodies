<?php
session_start();
include 'admin/config.php'; // Tambahkan koneksi ke database

// Validasi sesi pengguna
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect ke halaman login jika tidak ada sesi pengguna
    exit();
}

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

// Sambungkan ke database
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

// Query untuk mengambil data pengguna dari database
$sql = "SELECT name, username, bio, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

// Mengikat hasil query ke variabel
$stmt->bind_result($user_name, $user_username, $user_bio, $user_profile_picture);

// Mendapatkan baris hasil
$stmt->fetch();

// Tutup statement dan koneksi database
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internasional Food Website</title>
    <link rel="stylesheet" href="css/profile.css">
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
        <div class="profile-container">
            <div class="profile-header">
                <a href="edit_profile.php" class="profile-pic">
                    <?php if (!empty($user_profile_picture)): ?>
                        <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="profile-img">
                    <?php else: ?>
                        <img src="Assets/default-profile.jpg" alt="Default Profile Picture" class="profile-pic">
                    <?php endif; ?>
                </a>
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($user_name); ?></h2>
                    <p><?php echo htmlspecialchars($user_username); ?></p>
                </div>
                <a href="edit_profile.php"><i class='bx bxs-pencil edit-icon'></i></a>
            </div><br>
            <p><?php echo htmlspecialchars($user_bio); ?></p>
        </div>
    </section>

    <script src="js/profile.js"></script>
</body>
</html>