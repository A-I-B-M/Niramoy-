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
$result = mysqli_query($conn, "SELECT * FROM blog_posts WHERE id = $id");

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Blog not found.";
    exit();
}

$blog = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $content = trim($_POST['content']);

    $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, author = ?, content = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $author, $content, $id);

    if ($stmt->execute()) {
        header("Location: ../manageBlogs.php");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Blog</title>
    <link rel="stylesheet" href="../dashboard.css">
    <style>
        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 10px;
        }
        label {
            font-weight: 600;
        }
        input, textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
        }
        .back-btn {
            background: #6c757d;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Blog Post</h2>
    <form method="post">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required>

        <label>Author</label>
        <input type="text" name="author" value="<?= htmlspecialchars($blog['author']) ?>">

        <label>Content</label>
        <textarea name="content" rows="10" required><?= htmlspecialchars($blog['content']) ?></textarea>

        <button type="submit">Update Blog</button>
        <a href="../manageBlogs.php" class="back-btn" style="text-decoration:none; padding:10px 16px; color:white;">Back</a>
    </form>
</div>
</body>
</html>
