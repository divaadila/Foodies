<?php
session_start();
include 'admin/config.php'; // Tambahkan koneksi ke database

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
// Pastikan untuk mengambil 'id' dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data resep dari database berdasarkan ID
    $sql = "SELECT * FROM resep WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipe = $result->fetch_assoc();

    // Cek apakah resep ditemukan
    if (!$recipe) {
        echo "Recipe not found.";
        exit();
    }

    $bahanList = array_filter(array_map('trim', explode("\n", $recipe['bahan'])));
    $langkahList = array_filter(array_map('trim', explode("\n", $recipe['langkah'])));

    $title = $recipe['judul'];
    $category = htmlspecialchars($recipe['kategori']); // Ambil kategori dari database

    // Simpan komentar
    if (isset($_POST['comment']) && isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $comment = $_POST['comment'];
        $stmt = $conn->prepare("INSERT INTO comments (username, recipe_title, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $title, $comment);
        $stmt->execute();
        $stmt->close();
    }

    // Ambil komentar dari database
    $comments = [];
    $stmt = $conn->prepare("SELECT c.*, u.profile_picture FROM comments c JOIN users u ON c.username = u.username WHERE c.recipe_title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "ID resep tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['judul']); ?> - Recipe</title>
    <link rel="stylesheet" href="css/recipe.css">
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
            <div class="popup hidden" id="popup">
                <div class="container" id="container">
                    <div class="form-container sign-up-container">
                        <form method="POST" action="register.php">
                            <h1 class="create">Create Account</h1>
                            <div class="infield">
                                <input type="text" name="name" placeholder="Name" required>
                                <label></label>
                            </div>
                            <div class="infield">
                                <input type="text" name="username" placeholder="Username" required>
                                <label></label>
                            </div>
                            <div class="infield">
                                <input type="email" name="email" placeholder="Email" required>
                                <label></label>
                            </div>
                            <div class="infield">
                                <input type="password" name="password" placeholder="Password" required>
                                <label></label>
                            </div>
                            <button type="submit" name="register">Sign Up</button>
                        </form>
                    </div>
                    <div class="form-container sign-in-container">
                        <form method="POST" id="form">
                            <h1 class="sign">Sign in</h1>
                            <div class="infield">
                                <input type="text" name="username" id="username" onchange="function_name()" placeholder="Username" required>
                                <label></label>
                            </div>
                            <div class="infield">
                                <input type="password" name="password" id="password" placeholder="Password" required>
                                <label></label>
                            </div>
                            <button type="submit"value="login">Sign In</button>
                        </form>
                    </div>
                    <div class="overlay-container" id="overlayCon">
                        <div class="overlay">
                            <div class="overlay-panel overlay-left">
                                <h1 class="overlay-text">Welcome Back!</h1>
                                <p class="overlay-p">To keep connected with us please login with your personal info</p>
                                <button>Sign In</button>
                            </div>
                            <div class="overlay-panel overlay-right">
                                <h1 class="overlay-text">Hello, Friend!</h1>
                                <p class="overlay-p">Enter your personal details and start journey with us</p>
                                <button>Sign Up</button>
                            </div>
                        </div>
                        <button id="overlayBtn"></button>
                    </div>
                </div>
            </div>
        </div>              
    </nav>

    <!--recipe-->
    <section class="recipe-container">
        <h1><?php echo htmlspecialchars($recipe['judul']); ?></h1>
        <br>
        <div class="recipe-flex-container">
        <img src="admin/<?php echo htmlspecialchars($recipe['gambar']); ?>" alt="<?php echo htmlspecialchars($recipe['judul']); ?>" class="recipe-img">
            <div class="ingredients-container">
                <h2>Ingredients</h2>
                <ul>
                    <?php foreach ($bahanList as $bahan): ?>
                        <li><?php echo htmlspecialchars($bahan); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="instructions-container">
            <h2>Instructions</h2>
            <ol>
                <?php foreach ($langkahList as $langkah): ?>
                    <li><?php echo htmlspecialchars($langkah); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>

        <!-- Comment Section -->
        <div class="comment-section">
        <h2>Comment</h2>
        <?php
        // Tetapkan nilai default untuk gambar profil
        $profilePicture = 'Assets/default-profile.jpg';

        if (isset($_SESSION['username'])) {
            // Jika pengguna sudah login, gunakan gambar profil dari sesi
            $profilePicture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'Assets/default-profile.jpg';
        ?>
            <div class="comment-form">
            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="profile-pic">
                <form method="POST" action="" class="comment-form-inner">
                    <input type="text" name="comment" placeholder="Beri komentar" id="comment-input" required>
                    <button type="submit" id="comment-btn">➤</button>
                </form>
            </div>
        <?php
            } else {
        ?>
            <div class="comment-form">
            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="profile-pic">
                <input type="text" placeholder="Beri komentar" id="comment-input" disabled>
                <button id="login-btn" onclick="alert('Harap login terlebih dahulu!')">Login untuk berkomentar</button>
            </div>
        <?php
            }
        ?>
            
            <!-- Tampilkan komentar -->
            <div class="comments">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <img src="<?php echo htmlspecialchars($comment['profile_picture']); ?>" alt="Profile Picture" class="profile-pic">
                        <div class="comment-info">
                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                            <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        </div>
                        <span style="margin-top: 9px;"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section> 

    <!--Scroll Top-->
    <a href="#" class="scroll">
        <i class='bx bx-up-arrow-alt'></i>
    </a>

    <footer>
        <p>© 2024 Foodies. All rights reserved.</p>
    </footer>
    <script src="js/recipe.js"></script>
</body>
</html>