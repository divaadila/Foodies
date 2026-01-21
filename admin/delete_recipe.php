<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM resep WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Recipe deleted successfully.";
    } else {
        echo "Error deleting recipe.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid recipe ID.";
}

header("Location: dashboard.php");
exit();
?>
