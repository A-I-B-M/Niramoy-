<?php
include '../../Connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid blog ID.";
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../manageBlogs.php");
    exit();
} else {
    echo "Error deleting blog: " . $stmt->error;
}
?>
