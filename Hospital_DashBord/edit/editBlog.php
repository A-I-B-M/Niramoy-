<?php
session_start();
include "../../Connection.php";

if (!isset($_SESSION['hospital_id'])) {
    header("Location: ../../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$blog_id = intval($_GET['id'] ?? 0);

// Fetch the blog info
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ? AND hospital_id = ?");
$stmt->bind_param("ii", $blog_id, $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    echo "Blog not found or unauthorized access.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $content = trim($_POST['content']);

    $stmt = $conn->prepare("UPDATE blog_posts SET title = ?, author = ?, content = ? WHERE id = ? AND hospital_id = ?");
    $stmt->bind_param("sssii", $title, $author, $content, $blog_id, $hospital_id);
    $stmt->execute();

    $_SESSION['success'] = "Blog updated successfully!";
    header("Location: ../blogWrite.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Blog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6fa;
      font-family: 'Poppins', sans-serif;
      padding: 2rem;
    }
    .container {
      max-width: 600px;
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2e3192;
      margin-bottom: 1.5rem;
    }
    label {
      font-weight: 500;
    }
    .form-control {
      margin-bottom: 1rem;
    }
    .btn-primary {
      background: #2e3192;
      border: none;
    }
    .btn-secondary {
      margin-left: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Blog</h2>
    <form action="" method="post">
      <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Author</label>
        <input type="text" name="author" value="<?= htmlspecialchars($blog['author']) ?>" class="form-control">
      </div>
      <div class="mb-3">
        <label>Content</label>
        <textarea name="content" rows="6" class="form-control" required><?= htmlspecialchars($blog['content']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update Blog</button>
      <a href="../blogWrite.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</body>
</html>
