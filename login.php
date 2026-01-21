<?php
session_start();
$username = "root";$password = "";
try {
    $conn = new PDO("mysql:host=localhost;dbname=resep_db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->username) && isset($data->password)) {
            $username = $data->username;
            $password = $data->password;
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['profile_picture'] = $user['profile_picture']; // Simpan URL gambar profil dalam sesi
                    $response = array(
                        'status' => 'success',
                        'message' => 'Login berhasil!'
                    );
                    echo json_encode($response);
                    exit();
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Username atau password salah.'
                    );
                    echo json_encode($response);
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Username atau password salah.'
                );
                echo json_encode($response);
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Username atau password tidak diberikan.'
            );
            echo json_encode($response);
        }
    }
} catch(PDOException $e) {
    $response = array(
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $e->getMessage()
    );
    echo json_encode($response);
}
$conn = null;
?>
