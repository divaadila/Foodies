<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

// Include the add_recipe2.php content
ob_start(); // Start output buffering 
$content = ob_get_clean(); // Get the content and clean the buffer

// Fetch recipes from the database
include 'config.php';
$sql = "SELECT * FROM resep";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <!-- Link to Font Awesome (be sure to replace with actual URL if using a local copy or different version) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">FOODIES Admin</div>
        <hr style="background-color: black; height: 2px;">
        <ul class="nav-links">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span> <span>></span></a></li>
            <li><a href="add_recipe.php"><i class="fas fa-utensils"></i><span>Recipe</span> <span>></span></a></li>
            <li><a href="add_category.php"><i class="fas fa-tags"></i><span>Category</span> <span>></span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
        <span>Hai, admin <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="logout_admin.php" style="text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="dashboard-content">
            <div class="card">
                <h3>Total Recipes</h3>
                <p><?php echo $result->num_rows; ?> Recipes</p>
            </div>
            <div class="card">
                <h3>Recent Activity</h3>
                <?php
                $sql_latest = "SELECT * FROM resep ORDER BY id DESC LIMIT 1";
                $result_latest = $conn->query($sql_latest);
                if ($result_latest->num_rows > 0) {
                    $row_latest = $result_latest->fetch_assoc();
                    echo "<p>Last recipe added: " . htmlspecialchars($row_latest['judul']) . "</p>";
                } else {
                    echo "<p>No recent activity.</p>";
                }
                ?>
            </div>
        </div><br>
        <h1 style="margin-left: 20px;">Recipes</h1><br>
        <div class="recipe-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='recipe-card'>";
                    echo "<img src='" . htmlspecialchars($row['gambar']) . "' alt='" . htmlspecialchars($row['judul']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['judul']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['deskripsi']) . "</p>";
                    echo "<div class='recipe-actions'>";
                    echo "<a href='add_recipe.php?id=" . $row['id'] . "' class='edit-btn'><i class='fa-solid fa-pen-to-square'></i> Edit</a>";
                    echo "<a href='delete_recipe.php?id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this recipe?\");'><i class='fa-solid fa-trash'></i> Delete</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No recipes found.</p>";
            }
            ?>
        </div><br>
        <h1 style="margin-left: 20px;">Categories</h1><br>
        <div class="category-container">
            <?php
            $sql_categories = "SELECT * FROM categories";
            $result_categories = $conn->query($sql_categories);

            if ($result_categories->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Gambar</th><th>Kategori</th><th>Aksi</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result_categories->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='" . htmlspecialchars($row['gambar']) . "' alt='" . htmlspecialchars($row['name']) . "' width='50' height='50'></td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>";
                    echo "<a href='add_category.php?id=" . $row['id'] . "' class='edit-btn'><i class='fa-solid fa-pen-to-square'></i>Edit</a> | ";
                    echo "<a href='delete_category.php?id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this category?\");'><i class='fa-solid fa-trash'></i>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No categories found.</p>";
            }
            ?>
        </div>

    </div>
</body>
</html>
