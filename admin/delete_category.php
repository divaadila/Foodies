<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query untuk menghapus kategori
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Category deleted successfully.";
    } else {
        echo "Error deleting category.";
    }

    $stmt->close();
    $conn->close();

    // Redirect kembali ke dashboard
    header("Location: dashboard.php");
    exit();
} else {
    echo "ID kategori tidak ditemukan.";
}
?>
