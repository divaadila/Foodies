<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin Baru - sehat_YUK</title>
    <style>
        body {
            background-image: url('./img/buah.png'); /* Set the background image */
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .btn:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function showSuccessMessage() {
            alert("Admin berhasil ditambahkan!");
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h1>Tambah Admin Baru</h1>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="submit" value="Tambah Admin">
            <a href="login_admin.php" class="btn">Login</a>
        </form>

        

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'config.php';

            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                echo '<script type="text/javascript">',
                     'showSuccessMessage();',
                     '</script>';
                echo '<script type="text/javascript">',
                     'window.location.href = "login.php";',
                     '</script>';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
