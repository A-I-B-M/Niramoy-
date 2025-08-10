<?php
session_start();
include "../Connection.php";

if (!isset($_SESSION['hospital_id']) || !isset($_SESSION['hospital_name'])) {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ? AND hospital_id = ?");
    $stmt->bind_param("ii", $deleteId, $hospital_id);
    $stmt->execute();
    $_SESSION['success'] = "Blog deleted successfully.";
    header("Location: blogWrite.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $_SESSION['error'] = "Title and Content are required.";
        header("Location: blogWrite.php");
        exit();
    }

    if ($author === '') {
        $author = $hospital_name;
    }

    $uploadDir = "../uploads/blog_thumbnails/";
    $defaultThumbnail = "uploads/blog_thumbnails/default_thumbnail.jpg";
    $thumbnailUrl = $defaultThumbnail;

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['thumbnail']['tmp_name'];
        $fileName = basename($_FILES['thumbnail']['name']);
        $fileType = mime_content_type($fileTmpPath);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($fileType, $allowedTypes)) {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid('blog_') . '.' . $ext;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $thumbnailUrl = "uploads/blog_thumbnails/" . $newFileName;
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO blog_posts (title, author, content, thumbnail_url, hospital_id, created_at, views) VALUES (?, ?, ?, ?, ?, NOW(), 0)");
    $stmt->bind_param("ssssi", $title, $author, $content, $thumbnailUrl, $hospital_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Blog published successfully!";
    } else {
        $_SESSION['error'] = "Database error: " . $stmt->error;
    }

    header("Location: blogWrite.php");
    exit();
}

$blogs = [];
$stmt = $conn->prepare("SELECT id, title, created_at FROM blog_posts WHERE hospital_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $blogs[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Niramoy Dashboard</title>
  <link rel="stylesheet" href="blogWrite.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar bg-gradient text-white px-4 py-3 d-flex justify-content-between">
    <h2 id="h2tag" class="mb-0"><i class="fa-solid fa-hospital"></i> নিরাময়</h2>
    <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
  </nav>

  <div class="d-flex">
    <aside class="sidebar d-flex flex-column p-3">
      <h5 class="mb-4 fw-bold">Dashboard</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="doctorlist.php">Doctor List</a></li>
        <li class="nav-item"><a class="nav-link" href="patient.php">Patient List</a></li>
        <li class="nav-item"><a class="nav-link" href="appointmentDetails.php">Appointment Details</a></li>
        <li class="nav-item"><a class="nav-link" href="report.php">Report List</a></li>
        <li class="nav-item"><a class="nav-link" href="sampleRq.php">Sample Request</a></li>
        <li class="nav-item"><a class="nav-link active" href="blogWrite.php">Blog Write</a></li>
        <li class="nav-item"><a class="nav-link" href="addDoctor.php">Add Doctor</a></li>
        <li class="nav-item"><a class="nav-link" href="chatAdmin.php">Chat Admin</a></li>

      </ul>
    </aside>

    <main class="content flex-fill">
      <div class="blog-form-container">
        <h2>Write a New Blog</h2>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['success']).'</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['error']).'</div>';
            unset($_SESSION['error']);
        }
        ?>

        <?php if (!empty($blogs)): ?>
        <div class="mb-4">
          <h4>Your Existing Blogs</h4>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Title</th>
                <th>Date Posted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($blogs as $blog): ?>
              <tr>
                <td><?= htmlspecialchars($blog['title']) ?></td>
                <td><?= htmlspecialchars($blog['created_at']) ?></td>
                <td>
                  <a href="edit/editBlog.php?id=<?= $blog['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                  <a href="blogWrite.php?delete=<?= $blog['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="title">Blog Title</label>
            <input type="text" id="title" name="title" required>
          </div>

          <div class="form-group">
            <label for="author">Author Name</label>
            <input type="text" id="author" name="author" placeholder="Optional (will default to hospital name)">
          </div>

          <!-- <div class="form-group">
            <label for="thumbnail">Thumbnail Image</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
          </div> -->

          <div class="form-group">
            <label for="content">Blog Content</label>
            <textarea id="content" name="content" rows="10" required></textarea>
          </div>

          <button type="submit">Publish Blog</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
