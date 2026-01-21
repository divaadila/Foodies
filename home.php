<?php
session_start();
include 'admin/config.php'; // Tambahkan koneksi ke database

// Ambil data resep dari database
$sql = "SELECT * FROM resep ORDER BY id DESC LIMIT 10";
$result_resep = $conn->query($sql);

$resep = [];
if ($result_resep && $result_resep->num_rows > 0) {
    while ($row = $result_resep->fetch_assoc()) {
        $resep[] = $row;
    }
}

// Ambil data kategori dari database
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

$categories = [];
if ($result_categories && $result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internasional Food Website</title>
    <link rel="stylesheet" href="css/style.css">
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
            <ul id="suggestions" class="suggestions hidden"></ul>
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

    <!--home-->
    <section class="home" id="home">
        <div class="home-text">
            <p>One World, One Cuisine</p>
            <img src="Assets/sendok.png" alt="sendok">
            <h1>Taste <span>The World</span><br> In Every Bite !</h1>
            <a href="all_recipes.php" class="btn">Explore Recipes<i class='bx bxs-right-arrow'></i></a>
        </div>

        <div class="home-img">
            <img src="Assets/home.png" alt="burger" class="home-img">
        </div>
    </section>

    <!--Category-->
    <section class="title-container">
        <div class="category-title">
            <h2>Category</h2>
        </div>
    </section>
    <section class="category" id="category">
        <div class="category-container">
            <button class="slider-button prev" id="prev">&#10094;</button>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="category-box" data-category="<?php echo htmlspecialchars($category['name']); ?>">
                        <a href="recipes.php?category=<?php echo urlencode($category['name']); ?>">
                            <img src="admin/<?php echo htmlspecialchars($category['gambar']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No categories available.</p>
            <?php endif; ?>
            <button class="slider-button next" id="next">&#10095;</button>
        </div>
    </section>

    <!--Recipe-->
    <section class="title-container">
        <div class="recipe-title">
            <h2>Recipes</h2>
            <a href="all_recipes.php"><p class="more-btn" style="align-content: center;">More ></p></a>
        </div>
    </section>
    <section class="recipe" id="recipe">
        <div class="recipe-container">
            <?php
            if (!empty($resep)) {
                // Output data of each row
                foreach ($resep as $row) {
                    echo '<div class="recipe-box">';
                    echo '<img src="admin/' . $row['gambar'] . '" alt="' . $row['judul'] . '">';
                    echo '<h3>' . $row['judul'] . '</h3>';
                    echo '<p>' . $row['deskripsi'] . '</p>';
                    echo '<a href="recipe.php?id=' . $row['id'] . '" class="recipe-btn">View Recipe</a>';
                    echo '</div>';
                }
            } else {
                echo 'No recipes found.';
            }
            $conn->close();
            ?>
        </div>
    </section>

    <!--Contact Us-->
    <section class="contact" id="contact">
        <div class="contact-text">
            <h2>Contact Us</h2>
            <p>Kami ingin mendengar dari Anda! Apakah Anda punya pertanyaan, saran, atau ingin berbagi resep favorit Anda dengan kami? Jangan ragu untuk menghubungi kami melalui informasi di bawah ini. Kami selalu bersemangat untuk mendengarkan cerita dan ide Anda tentang dunia kuliner.</p>
        </div>

        <div class="details">
            <div class="main-d">
                <i class='bx bxs-location-plus' ></i> Jl. Warga No.49 Jakarta 13860, Indonesia
            </div>

            <div class="main-d">
                <i class='bx bxs-phone' ></i> 081286973773
            </div>

            <div class="main-d">
                <a href="mailto:divaadila01@gmail.com"><i class='bx bxl-gmail' ></i> divaadila01@gmail.com</a>
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
    <script src="js/script.js"></script>
</body>
</html>
