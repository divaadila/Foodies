<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

// Menghubungkan ke database
include 'config.php';

$errors = [];
$category = [
    'name' => '',
    'gambar' => ''
];

// Memeriksa apakah ada ID kategori di URL untuk mode edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty($_POST['name'])) {
        $errors[] = "Nama kategori tidak boleh kosong.";
    }

    // Proses upload gambar
    if (empty($_FILES["gambar"]["name"])) {
        if (isset($_GET['id'])) {
            $gambar = $category['gambar']; // Menggunakan gambar lama jika tidak ada gambar baru
        } else {
            $errors[] = "Gambar tidak boleh kosong.";
        }
    } else {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $targetFile = $targetDir . basename($_FILES["gambar"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] == UPLOAD_ERR_OK) {
            $check = getimagesize($_FILES["gambar"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $errors[] = "File is not an image.";
                $uploadOk = 0;
            }
        } else {
            $errors[] = "No file uploaded or there was an upload error.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $errors[] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                $gambar = $targetFile; // Path gambar yang disimpan
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Simpan data ke database
    if (empty($errors)) {
        $name = $_POST['name'];
        
        if (isset($_GET['id'])) {
            // Update kategori yang ada
            $sql = "UPDATE categories SET name=?, gambar=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $gambar, $id);
        } else {
            // Tambah kategori baru
            $sql = "INSERT INTO categories (name, gambar) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $name, $gambar);
        }

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .nav-links li:hover, .nav-links .nav-link.active{
            background-color: #c9c9c9;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .container .submit-container {
            display: flex;
            justify-content: center; /* Memusatkan tombol submit */
            width: 100%; /* Agar tombol submit menempati ruang penuh */
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%; /* Ensure full width */
            max-width: 600px; /* Set a maximum width for better appearance */
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: #28a745;
            text-align: center;
            color: white;
            border: none;
            padding: 12px 20px; /* Increased padding for a better button size */
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px; /* Increased font size for better visibility */
            transition: background-color 0.3s, transform 0.2s; /* Added transform for hover effect */
        }
        input[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.05); /* Slightly enlarge the button on hover */
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: #28a745;
        }
        .error {
            text-align: center;
            margin-top: 20px;
            color: red;
        }
        .back-button {
            display: block;
            margin: 20px auto;
            text-align: center;
            transition: background-color 0.3s, transform 0.2s;
        }
        .back-button a {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">FOODIES Admin</div>
        <hr style="background-color: black; height: 2px;">
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span> <span>></span></a></li>
            <li><a href="add_recipe.php"><i class="fas fa-utensils"></i><span>Recipe</span> <span>></span></a></li>
            <li><a href="add_category.php"><i class="fas fa-tags"></i><span>Category</span> <span>></span></a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
        <span>Hai, admin <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="logout_admin.php" style="text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="container">
        <h1><?php echo isset($_GET['id']) ? 'Edit Category' : 'Add Category'; ?></h1>
            <?php if (!empty($errors)) : ?>
                <div class="error">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <label for="name">Nama Kategori</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                
                <label for="gambar">Gambar Kategori</label>
                <input type="file" id="gambar" name="gambar" accept="image/*">
                <?php if (!empty($category['gambar'])): ?>
                    <img src="<?php echo htmlspecialchars($category['gambar']); ?>" alt="Category Image" style="max-width: 100px; margin-top: 10px;">
                <?php endif; ?>
                
                <div class="submit-container">
                    <input type="submit" value="<?php echo isset($_GET['id']) ? 'Update' : 'Add'; ?> Category">
                </div>
            </form>
            <div class="back-button">
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
