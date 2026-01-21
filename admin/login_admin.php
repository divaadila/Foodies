<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - sehat_YUK</title>
    <style>
        body {
            background: #333;
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
            width: 400px; /* Increased width for the container */
        }
        h1 {
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
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
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            background-color: #007BFF;
            width: calc(100% - 40px);
        }
        .btn:hover{
            background-color: #007BFF;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 100px;
        }
    </style>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h1>Hi Welcome Back, Admin!</h1>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="submit" value="Login">
            <a href="add_admin.php" class="btn">Tambah Admin</a>
        </form>
        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'config.php';
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "SELECT * FROM admin WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $username;
                    echo "<script>showAlert('Login berhasil!');</script>";
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<script>showAlert('Password salah!');</script>";
                }
            } else {
                echo "<p class='error'>Username tidak ditemukan!</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
